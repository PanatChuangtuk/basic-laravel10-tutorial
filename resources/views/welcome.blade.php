@extends('layouts.app')
@section('title','หน้าแรกของเว็บไซต์')
@section('content')
    <h2>บทความล่าสุด</h2>
    <hr>
    @foreach ($blogs as $item)
        <h2>{{$item->title}}</h2>
        <hr>
       
        {{-- @foreach($file as $image)
            <img src="{{ asset('file/file/' . $image->images) }}" alt="Image" />
        @endforeach --}}
        <p>{!!Str::limit($item->content,100)!!}</p>
        <a href="/detail/{{$item->id}}">อ่านเพิ่มเติม</a>
        <hr>
    @endforeach

    {{$blogs->links()}} 
@endsection

