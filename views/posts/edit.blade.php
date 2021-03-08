@extends('layout')

@section('title', 'Edit post #' . $post->id)

@section('content')
@isset($_GET['error'])
<h2 class="text-danger">{{ $_GET['message'] }}</h2>
@endisset
<form action="/posts/{{ $post->id }}" method="post">
    <textarea name="content">{!! htmlspecialchars($post->content) !!}</textarea>
    <button type="submit">Submit</button>
</form>
@endsection