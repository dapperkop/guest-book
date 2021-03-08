@extends('layout')

@section('title', 'List of posts')

@section('content')
@isset($_GET['success'])
<h2 class="text-success">Added successfully</h2>
@endisset
@isset($_GET['error'])
<h2 class="text-danger">{{ $_GET['message'] }}</h2>
@endisset
@forelse ($posts as $post)
<div id="post-{{ $post->id }}" class="row post">
    <div class="col-md-4">
        <div class="datetime created">
            <span>Created at {{ $post->createdAt->format('Y-m-d H:i:s') }}</span>
        </div>
        @if ($post->updatedAt)
        <div class="datetime updated">
            <span>Updated at {{ $post->updatedAt->format('Y-m-d H:i:s') }}</span>
        </div>
        @endif
        <ul class="list-inline">
            <li class="list-inline-item">
                <a href="/posts/edit/{{ $post->id }}">Edit</a>
            </li>
            <li class="list-inline-item">
                <a href="/posts/add?replyAt={{ $post->id }}">Reply</a>
            </li>
        </ul>
    </div>
    <div class="col-md-8">
        @if ($post->replyAt)
        <div class="reply">
            <span>Reply at</span>
            <a href="/posts#post-{{ $post->replyAt }}">#{{ $post->replyAt }}</a>
        </div>
        @endif
        <div class="content">
            <span>Content:</span>
            {!! nl2br(htmlspecialchars($post->content)) !!}
        </div>
    </div>
</div>
@empty
<span>Posts not found</span>
@endforelse
@endsection