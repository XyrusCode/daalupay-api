@extends('emails.layout')

@section('title', $post->title)

@section('content')
    <h1>{{ $post->title }}</h1>
    <p>{{ Str::limit($post->content, 150) }}</p>
    <p>
        <a href="{{ url('/blog/'.$post->id) }}" style="background: #007bff; color: #fff; padding: 10px 15px; text-decoration: none; border-radius: 5px;">
            Read More
        </a>
    </p>
@endsection
