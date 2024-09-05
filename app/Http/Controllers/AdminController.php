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
        // $request->validate(
        //     [
        //         'title' => 'required|string|max:255',
        //         'content' => 'required|string',
        //         'file.*' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
        //     ],
        //     [
        //        'title.required' => 'กรุณาป้อนชื่อบทความของคุณ',
        //         'title.max' => 'ชื่อบทความไม่ควรเกิน 255 ตัวอักษร',
        //         'content.required' => 'กรุณาป้อนเนื้อหาบทความของคุณ',
        //         'file.*.required' => 'กรุณาเลือกไฟล์ที่ต้องการอัปโหลด',
        //         'file.*.mimes' => 'ไฟล์ต้องเป็นประเภท jpeg, png หรือ pdf',
        //         'file.*.max' => 'ขนาดไฟล์ต้องไม่เกิน 2MB',
        //     ]
        // );
        $filePaths =[];
        $files = $request->file('file');
        foreach ($files  as $file) {
            $filePath = $file->store('file', 'public'); 
            $filePaths[] = basename($filePath); 
        }
     
        $data=[
            'title'=>$request->input('title'),
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
        $oldFilePaths = json_decode($blog->file, true)?: [];
      
        if (is_array($oldFilePaths)) {
            foreach ($oldFilePaths as $file) {
                Storage::disk('public')->delete('file/' . $file);
            }
        }
        if ($request->hasFile('file')) {
       foreach ($request->file('file') as $file) {
            $filePath = $file->store('file', 'public'); 
            $oldFilePaths[] = basename($filePath); 
        }}

        $data=[
            'title'=>$request->title,
            'content'=>strip_tags($request->input('content')),
            'file'=>json_encode($oldFilePaths ),
        ];
        $blog->update($data);
        return redirect('/author/blog');
    }
        function deleteFile(Request $request, $id, $file)
    {
        $blog = Blog::find($id);
        if (!$blog) {
            return response()->json(['error' => 'Blog not found.'], 404);
        }

        $files = json_decode($blog->file, true);
        if (($key = array_search($file, $files)) !== false) {
            unset($files[$key]);
            $blog->file = json_encode(array_values($files));
            $blog->save();
            
            Storage::disk('public')->delete('file/' . $file);

            return redirect()->back();
        }
        
}
}