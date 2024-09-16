<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\File;
class BlogController extends Controller
{
    function index(){
        $blogs = Blog::where('status', true)->orderBy('id', 'desc')->paginate(2);

        return view('welcome',compact('blogs'));
    }
    function detail($id){
        $blog=Blog::find($id);
        $file = File::where('blog_id', $blog->id)->get();
        return view('detail',compact('blog','file'));
    }
}

