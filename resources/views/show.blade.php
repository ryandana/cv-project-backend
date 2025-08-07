@extends('layouts.app')

@section('content')
    <h1>{{ $blog->title }}</h1>
    <img src="{{ asset('storage/blogs/' . $blog->image) }}" alt="{{ $blog->title }}">
    <p>{{ $blog->excerpt }}</p>
    <div>{!! $blog->content !!}</div>
@endsection
