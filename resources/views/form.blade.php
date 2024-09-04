@extends('layouts.app')
@section('title', 'สร้างคำร้อง')
@section('content')
    <h2 class="text text-center py-2">สร้างคำร้อง</h2>
   
    <form id="dzImageUploadForm" method="POST" action="/author/insert" enctype="multipart/form-data" >
        @csrf
        <div class="form-group">
            <label for="title">ชื่อคำร้อง</label>
            <input type="text" name="title" class="form-control"id="title">
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
        @csrf
        <div class="position-relative">
            <div class="form-group mb-3">
                <div class="main-drag-area form-control p-0" id="dzDropzone"name="file[]">
                    <div class="dz-message rounded-3 text-muted opacity-75" id="dzPlaceholder">
                        <svg class="opacity-50 mb-3" width="50px" height="50px" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                            <path d="M41.636 8.404l1.017 7.237 17.579 4.71a5 5 0 0 1 3.587 5.914l-.051.21-6.73 25.114A5.002 5.002 0 0 1 53 55.233V56a5 5 0 0 1-4.783 4.995L48 61H16a5 5 0 0 1-4.995-4.783L11 56V44.013l-1.69.239a5 5 0 0 1-5.612-4.042l-.034-.214L.045 14.25a5 5 0 0 1 4.041-5.612l.215-.035 31.688-4.454a5 5 0 0 1 5.647 4.256zm-20.49 39.373l-.14.131L13 55.914V56a3 3 0 0 0 2.824 2.995L16 59h21.42L25.149 47.812a3 3 0 0 0-4.004-.035zm16.501-9.903l-.139.136-9.417 9.778L40.387 59H48a3 3 0 0 0 2.995-2.824L51 56v-9.561l-9.3-8.556a3 3 0 0 0-4.053-.009zM53 34.614V53.19a3.003 3.003 0 0 0 2.054-1.944l.052-.174 2.475-9.235L53 34.614zM48 27H31.991c-.283.031-.571.032-.862 0H16a3 3 0 0 0-2.995 2.824L13 30v23.084l6.592-6.59a5 5 0 0 1 6.722-.318l.182.159.117.105 9.455-9.817a5 5 0 0 1 6.802-.374l.184.162L51 43.721V30a3 3 0 0 0-2.824-2.995L48 27zm-37 5.548l-5.363 7.118.007.052a3 3 0 0 0 3.388 2.553L11 41.994v-9.446zM25.18 15.954l-.05.169-2.38 8.876h5.336a4 4 0 1 1 6.955 0L48 25.001a5 5 0 0 1 4.995 4.783L53 30v.88l5.284 8.331 3.552-13.253a3 3 0 0 0-1.953-3.624l-.169-.05L28.804 14a3 3 0 0 0-3.623 1.953zM21 31a4 4 0 1 1 0 8 4 4 0 0 1 0-8zm0 2a2 2 0 1 0 0 4 2 2 0 0 0 0-4zM36.443 6.11l-.175.019-31.69 4.453a3 3 0 0 0-2.572 3.214l.02.175 3.217 22.894 5.833-7.74a5.002 5.002 0 0 1 4.707-4.12L16 25h4.68l2.519-9.395a5 5 0 0 1 5.913-3.587l.21.051 11.232 3.01-.898-6.397a3 3 0 0 0-3.213-2.573zm-6.811 16.395a2 2 0 0 0 1.64 2.496h.593a2 2 0 1 0-2.233-2.496zM10 13a4 4 0 1 1 0 8 4 4 0 0 1 0-8zm0 2a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"></path>
                        </svg>
                        <span>Drag your images here to upload</span>
                    </div>
                    <div class="dz-previews-container" id="dzPreviews"></div>
                </div>
            </div>
        </div> 
       
        {{-- <div class="form-group">
            <label for="file"></label>
            <input type="file" name="file[]"class="form-control-file" multiple>
        </div>
      --}}
        <input type="submit" value="บันทึก" class="btn btn-primary my-3"id="dzSubmitButton">
        <a href="/author/blog" class="btn btn-success">คำร้องทั้งหมด</a>
    </form>
    {{-- id="dzSubmitButton" --}}
    <script id="dzLoadingOverlay" type="text/template">
        <div class="dz-loading-div">
            <div class="position-absolute w-100 h-100 start-0 top-0 d-flex align-items-center justify-content-center bg-white rounded-3 z-3">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </script>
    <script id="dzImageTemplate" type="text/template">
        <div class="dz-image-preview" data-id="">
            <div class="dz-image position-relative rounded-3 overflow-hidden h-100" >
                <img class="w-100 h-100 object-fit-cover" data-dz-thumbnail>
                <svg class="dz-remove-button m-2" type="button" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" />
                </svg>
            </div>
        </div>
    </script>    
    <script id="dzAdditionalTemplate" type="text/template">
        <div class="dz-additional-area text-muted position-relative form-control d-flex align-items-center justify-content-center">
            <svg class="dz-photo-icon opacity-75" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M15 8h.01" /><path d="M3 6a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v12a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3v-12z" /><path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l5 5" /><path d="M14 14l1 -1c.928 -.893 2.072 -.893 3 0l3 3" />
            </svg>
        </div>
    </script>
    
   
@endsection
