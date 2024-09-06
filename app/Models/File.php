<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class File extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ['images', 'sort','blog_id'];
    protected $dates = ['deleted_at'];
   
    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
}
