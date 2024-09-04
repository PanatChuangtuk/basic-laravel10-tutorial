<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Blog;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function index(){
        $blogs=Blog::paginate(10);
        return view('blog',compact('blogs'));
    }
    function create(){
        return view('form');
    }

    function insert(Request $request){
        $request->validate(
            [
                'title'=>'required|max:50',
                'content'=>'required', 
                'file.*' => 'required|mimes:jpg,png,pdf|max:2048'
            ],
            [
                'title.required'=>'กรุณาป้อนชื่อบทความของคุณ',
                'title.max'=>'ชื่อบทความไม่ควรเกิน 50 ตัวอักษร',
                'content.required'=>'กรุณาป้อนเนื้อหาบทความของคุณ',
            ]
        );
        $filePaths = [];
        $files = $request->file('file');
        foreach ($files  as $file) {
            $filePath = $file->store('file', 'public'); 
            $filePaths[] = basename($filePath); 
        }
     
        $data=[
            'title'=>$request->title,
            'content'=>strip_tags($request->input('content')),
            'file'=>json_encode($filePaths),
            'created_at' => Carbon::now()
        ];
       
        Blog::create($data);
        return redirect('/author/blog');
    }

    function delete($id){
        Blog::find($id)->delete();
        return redirect()->back();
    }

    function change($id){
        $blog=Blog::find($id);
        $data=[
            'status'=>!$blog->status
        ];
        $blog=Blog::find($id)->update($data);
        return redirect()->back();
    }

    function edit($id){
        $blog=Blog::find($id);
        return view('edit',compact('blog'));
    }

    function update(Request $request,$id){
        $blog = Blog::find($id);
        $filePaths = [];
        $oldFilePaths = json_decode($blog->file, true)?: [];
        $request->validate(
            [
                'title'=>'required|max:50',
                'content'=>'required', 
                'file.*' => 'required|mimes:jpg,png,pdf|max:2048'
            ],
            [
                'title.required'=>'กรุณาป้อนชื่อบทความของคุณ',
                'title.max'=>'ชื่อบทความไม่ควรเกิน 50 ตัวอักษร',
                'content.required'=>'กรุณาป้อนเนื้อหาบทความของคุณ',
                'file.required' => 'กรุณาใส่รูปภาพ'
            ]
        );
      
        if (is_array($oldFilePaths)) {
            foreach ($oldFilePaths as $file) {
                Storage::disk('public')->delete('file/' . $file);
            }
        }
        
       foreach ($request->file('file') as $file) {
            $filePath = $file->store('file', 'public'); 
            $filePaths[] = basename($filePath); 
        }

        $data=[
            'title'=>$request->title,
            'content'=>strip_tags($request->input('content')),
            'file'=>json_encode($filePaths),
        ];
        $blog->update($data);
        return redirect('/author/blog');
    }
    
}
