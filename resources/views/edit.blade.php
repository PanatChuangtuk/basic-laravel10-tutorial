@extends('layouts.app')
@section('title', 'แก้ไขคำร้อง')
@section('content')

    <h2 class="text text-center py-2">แก้ไขคำร้อง</h2>
    <form method="POST" action="{{route('update',$blog->id)}}"enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="title">ชื่อคำร้อง</label>
            <input type="text" name="title" class="form-control" value="{{$blog->title}}">
        </div>
        @error('title')
            <div class="my-2">
                <span class="text-danger">{{$message}}</span>
            </div>
        @enderror
        <div class="form-group">
            <label for="content">เนื้อหาคำร้อง</label>
            <textarea name="content"  cols="30" rows="5" class="form-control" id="content">{{$blog->content}}</textarea>
        </div>
        @error('content')
            <div class="my-2">
                <span class="text-danger">{{$message}}</span>
            </div>
        @enderror
        
        <div class="form-group">
            <input type="file" name="file[]"multiple class="form-control-file">
        </div>

        <div class="form-group">
            <h3>Uploaded Files:</h3>
            <ul>
                @php
                    $files = json_decode($blog->file, true);
                @endphp

                @if (!empty($files))
                    @foreach ($files as $file)
                        <li>
                            <a href="{{ asset('storage/uploads/' . $file) }}" target="_blank">{{ $file }}</a>
                        </li>
                    @endforeach
                @else
                    <li>No files uploaded</li>
                @endif
            </ul>
        </div>
        
        <input type="submit" value="อัปเดต" class="btn btn-primary my-3">
        <a href="/author/blog" class="btn btn-success">คำร้องทั้งหมด</a>
    </form>
    <script>
        ClassicEditor
            .create( document.querySelector('#content'))
            .catch( error => {
                console.error( error );
            });
    </script>  
@endsection
