@extends('layouts.app')
@section('title')
    {{$blog->title}}
@endsection
@section('content')
    <h2>{{$blog->title}}</h2>
    <hr>
    {!!$blog->content!!}
    <hr>
    @foreach($file as $image)
        <img class="w-100 h-100 object-fit-cover" src="{{ asset('file/file/' . $image->images) }}" alt="Image" />
    @endforeach
@endsection
