@extends('layouts.app')
@section('title', 'สร้างคำร้อง')
@section('file')
@section('content')
    <h2 class="text text-center py-2">สร้างคำร้อง</h2>
    <form method="POST" action="/author/insert" enctype="multipart/form-data" >
        @csrf
        <div class="form-group">
            <label for="title">ชื่อคำร้อง</label>
            <input type="text" name="title" class="form-control">
        </div>
        @error('title')
            <div class="my-2">
                <span class="text-danger">{{$message}}</span>
            </div>
        @enderror
      
        <div class="form-group">
            <label for="content">เนื้อหาคำร้อง</label>
            <textarea name="content" cols="30" rows="5" class="form-control" id="content"></textarea>
        </div>

        @error('content')
            <div class="my-2">
                <span class="text-danger">{{$message}}</span>
            </div>
        @enderror
         
        <div class="form-group">
            <label for="file"></label>
            <input type="file" name="file[]"class="form-control-file" multiple>
        </div>
     
        <input type="submit" value="บันทึก" class="btn btn-primary my-3">
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
