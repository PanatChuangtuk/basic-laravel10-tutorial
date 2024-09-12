@extends('layouts.app')
@section('title', 'สร้างคำร้อง')
@section('content')
    <h2 class="text text-center py-2">สร้างคำร้อง</h2>

    <form id="dzDropzone" method="POST" action="/author/insert" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="title">ชื่อคำร้อง</label>
            <input type="text" name="title" class="form-control" id="title">
        </div>

        @error('title')
            <div class="my-2">
                <span class="text-danger">{{ $message }}</span>
            </div>
        @enderror

        <div class="form-group">
            <label for="content">เนื้อหาคำร้อง</label>
            <textarea name="content" cols="30" rows="5" id="content"></textarea>

        </div>

        @error('content')
            <div class="my-2">
                <span class="text-danger">{{ $message }}</span>
            </div>
        @enderror

        <div class="position-relative">
            <div class="form-group mb-3">
                <div class="main-drag-area form-control p-0"  >
                    <div class="dz-message rounded-3 text-muted opacity-75" id="dzPlaceholder">
                        <svg class="opacity-50 mb-3" width="50px" height="50px" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                            <!-- SVG Path -->
                        </svg>
                        <span>Drag your images here to upload</span>
                    </div>
                    <div class="dz-previews-container" id="dzPreviews"></div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="button-group">
                <button id="submitF" type="submit" class="btn btn-custom">บันทึก</button>
                <a href="/author/blog" class="btn btn-success">คำร้องทั้งหมด</a>
            </div>
        </div>
    </form>

    <script id="dzLoadingOverlay" type="text/template">
        <div class="dz-loading-div">
            <div class="position-absolute w-100 h-100 start-0 top-0 d-flex align-items-center justify-content-center bg-white rounded-3 z-3">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loding...</span>
                </div>
            </div>
        </div>
    </script>

    <script id="dzImageTemplate" type="text/template">
        <div class="dz-image-preview" data-id="">
            <div class="dz-image position-relative rounded-3 overflow-hidden h-100">
                <img class="w-100 h-100 object-fit-cover" data-dz-thumbnail>
                <svg class="dz-remove-button m-2" type="button" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12"/><path d="M6 6l12 12"/>
                </svg>
            </div>
        </div>
    </script>

    <script id="dzAdditionalTemplate" type="text/template">
        <div class="dz-additional-area text-muted position-relative form-control d-flex align-items-center justify-content-center">
            <svg class="dz-photo-icon opacity-75" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M15 8h.01"/><path d="M3 6a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v12a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3v-12z"/><path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l5 5"/><path d="M14 14l1 -1c.928 -.893 2.072 -.893 3 0l3 3"/>
            </svg>
        </div>
    </script>

   

@endsection
   