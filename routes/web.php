<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BlogController;
//นักอ่าน
Route::get('/',[BlogController::class,'index']);
Route::get('/',[BlogController::class,'indexFile']);
Route::get('/detail/{id}',[BlogController::class,'detail']);

//นักเขียน
Route::prefix('author')->group(function(){
    Route::get('/blog',[AdminController::class,'index'])->name('blog');
    // Route::get('/',[AdminController::class,'indexFile'])->name('file');
    Route::get('/create',[AdminController::class,'create']);
    Route::post('/insert',[AdminController::class,'insert']);
    Route::get('/delete/{id}',[AdminController::class,'delete'])->name('delete');
    Route::get('/change/{id}',[AdminController::class,'change'])->name('change');
    Route::get('/edit/{id}',[AdminController::class,'edit'])->name('edit');
    Route::post('/update/{id}',[AdminController::class,'update'])->name('update');
    // Route::delete('/file/delete/{id}/{file}', [AdminController::class, 'deleteFile'])->name('file.delete');
    Route::delete('/file/delete/{blog_id}/{file}', [AdminController::class, 'deleteFile'])->name('file.delete');
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
