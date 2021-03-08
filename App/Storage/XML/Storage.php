<?php

namespace App\Storage\XML;

use App\Models\Post;
use App\Storage\IStorage;
use App\Storage\XML\Exceptions\EndDocumentWriterException;
use App\Storage\XML\Exceptions\OpenReaderException;
use App\Storage\XML\Exceptions\OpenURIWriterException;
use App\Storage\XML\Exceptions\StartDocumentWriterException;
use DateTime;
use XMLReader;
use XMLWriter;

class Storage implements IStorage
{
    private string $file = ROOT_DIR . '/posts.xml';
    private ?XMLReader $xmlReader = NULL;
    private ?XMLWriter $xmlWriter = NULL;

    public function __construct()
    {
        $this->xmlReader = new XMLReader;

        if (!$this->openReader()) {
            throw new OpenReaderException;
        }

        $this->xmlWriter = new XMLWriter;
    }

    private function resetReader(): bool
    {
        return $this->xmlReader->close() && $this->openReader();
    }

    private function openReader(): bool
    {
        return $this->xmlReader->open($this->file, 'UTF-8');
    }

    private function setParserPropertyReader(): bool
    {
        return $this->xmlReader->setParserProperty(XMLReader::VALIDATE, TRUE);
    }

    private function isValidReader(): bool
    {
        return $this->xmlReader->isValid();
    }

    private function openURIWriter(): bool
    {
        return $this->xmlWriter->openUri($this->file);
    }

    private function startDocumentWriter(): bool
    {
        return $this->xmlWriter->startDocument('1.0', 'UTF-8');
    }

    private function endDocumentWriter(): bool
    {
        return $this->xmlWriter->endDocument();
    }

    private function flushWriter(): int
    {
        return $this->xmlWriter->flush();
    }

    private function addAttributeWriter(string $attribute, string $value): bool
    {
        if (!$this->xmlWriter->startAttribute($attribute)) {
            return false;
        }

        if (!$this->xmlWriter->text($value)) {
            return false;
        }

        if (!$this->xmlWriter->endAttribute()) {
            return false;
        }

        return true;
    }

    private function addPostWriter(Post $post): bool
    {
        if (!$this->xmlWriter->startElement('Post')) {
            return false;
        }

        if (!$this->addAttributeWriter('id', $post->id)) {
            return false;
        }

        if ($post->replyAt) {
            if (!$this->addAttributeWriter('replyAt', $post->replyAt)) {
                return false;
            }
        }

        if (!$this->addAttributeWriter('createdAt', $post->createdAt->format(DateTime::ATOM))) {
            return false;
        }

        if ($post->updatedAt) {
            if (!$this->addAttributeWriter('updatedAt', $post->updatedAt->format(DateTime::ATOM))) {
                return false;
            }
        }

        if (!$this->xmlWriter->text($post->content)) {
            return false;
        }

        if (!$this->xmlWriter->endElement()) {
            return false;
        }

        return true;
    }

    private function saveAll(array $posts): bool
    {
        if (!$this->openURIWriter()) {
            throw new OpenURIWriterException;
        }

        if (!$this->startDocumentWriter()) {
            throw new StartDocumentWriterException;
        }

        if (!$this->xmlWriter->startElement('Posts')) {
            return false;
        }

        foreach ($posts as $post) {
            if (!$this->addPostWriter($post)) {
                return false;
            }
        }

        if (!$this->xmlWriter->endDocument()) {
            return false;
        }

        if (!$this->endDocumentWriter()) {
            throw new EndDocumentWriterException;
        }

        $this->flushWriter();

        return true;
    }

    private function getAll(): array
    {
        $posts = [];

        while (@$this->xmlReader->read()) {
            if ($this->xmlReader->nodeType === XMLReader::ELEMENT && $this->xmlReader->name === 'Post') {
                $post = new Post;

                $updatedAt = $this->xmlReader->getAttribute('updatedAt');

                $post->id = $this->xmlReader->getAttribute('id');
                $post->replyAt = $this->xmlReader->getAttribute('replyAt');
                $post->createdAt = DateTime::createFromFormat(DateTime::ATOM, $this->xmlReader->getAttribute('createdAt'));
                $post->updatedAt = is_null($updatedAt) ? $updatedAt : DateTime::createFromFormat(DateTime::ATOM, $updatedAt);
            }

            if ($this->xmlReader->nodeType === XMLReader::TEXT) {
                $post->content = $this->xmlReader->value;
            }

            if ($this->xmlReader->nodeType === XMLReader::END_ELEMENT && $this->xmlReader->name === 'Post') {
                $posts[] = $post;
            }
        }

        $this->resetReader();

        return $posts;
    }

    public function getAllPosts(): array
    {
        return $this->getAll();
    }

    public function addPost(Post $post): bool
    {
        $post->id = uniqid();

        $posts = $this->getAllPosts();
        $posts[] = $post;

        return $this->saveAll($posts);
    }

    public function editPost(Post $post): bool
    {
        $posts = $this->getAllPosts();

        foreach ($posts as $p) {
            if ($p->id === $post->id) {
                $p->updatedAt = $post->updatedAt;
                $p->content = $post->content;
            }
        }

        return $this->saveAll($posts);
    }
}
