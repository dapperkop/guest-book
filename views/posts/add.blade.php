@extends('layout')

@section('title', 'Add new post')

@section('content')
@isset($_GET['error'])
<h2 class="text-danger">{{ $_GET['message'] }}</h2>
@endisset
<form action="/posts" method="post">
    @isset($_GET['replyAt'])
    <input type="hidden" name="replyAt" value="{{ $_GET['replyAt'] }}" />
    @endisset
    <textarea name="content"></textarea>
    <button type="submit">Submit</button>
</form>
@endsection