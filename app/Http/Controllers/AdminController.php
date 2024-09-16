<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Blog;
use App\Models\File;
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
                'title' => 'required',
                'content' => 'required',
        //         'file.*' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
            ],
            [
               'title.required' => 'กรุณาป้อนชื่อบทความของคุณ',
                'title.max' => 'ชื่อบทความไม่ควรเกิน 255 ตัวอักษร',
                'content.required' => 'กรุณาป้อนเนื้อหาบทความของคุณ',
        //         'file.*.required' => 'กรุณาเลือกไฟล์ที่ต้องการอัปโหลด',
        //         'file.*.mimes' => 'ไฟล์ต้องเป็นประเภท jpeg, png หรือ pdf',
        //         'file.*.max' => 'ขนาดไฟล์ต้องไม่เกิน 2MB',
            ]
        );
  $data = [
    'title' => $request->title,
    'content' => $request->content,
    'created_at' => Carbon::now()
  ];
  dd( $data);
        $blog = Blog::create($data);
    
        $filePaths =[];
        $files = $request->file('images');
        foreach ($files  as $index => $file) {
            $filePath = $file->store('file', 'public'); 
            $filePaths[] = basename($filePath); 
            File::create([
                'blog_id' => $blog->id,
                'images' => basename($filePath),
                'sort'=>  $index +1,
                'created_at' => Carbon::now()
            ]);
        }
        return ;
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
        $file = File::where('blog_id', $blog->id)->get();
        return view('edit',compact('blog','file'));
    }

    function update(Request $request,$id){
        $blog = Blog::find($id);
        $data=[
            'title' => $request->input('title'),
            'content' => $request->input('content'),
        ];
      
        $blog->update($data);
        $files = File::where('blog_id', $blog->id)->get();
        if (is_array($files)) {
            foreach ($files as $file) {
                Storage::disk('public')->delete('file/' . $file);
            }
        }
    // อัปโหลดไฟล์ใหม่
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $index => $file) {
            $filePath = $file->store('file', 'public'); 
            File::create([
                'blog_id' => $blog->id,
                'images' => basename($filePath),
                'sort' => $index +1
            ]);
        }
    }
        return redirect()->back();
    }

        function deleteFile(Request $request, $id, $file)
    {
        $blog = Blog::find($id);
       
        if (!$blog) {
            return response()->json(['error' => 'Blog not found.'], 404);
        }
        $files = File::where('blog_id', $blog->id) 
        ->where('images', $file)
        ->get();
        foreach ($files as $fileRecord) {
            if ($fileRecord->images === $file) {
                $fileRecord->forceDelete();
                // $files->delete(); soft delete
                Storage::disk('public')->delete('file/' . $fileRecord->images);
             
            return redirect()->back();
        }
        
}}}