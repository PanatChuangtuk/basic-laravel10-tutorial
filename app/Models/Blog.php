<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Blog extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $fillable=[
        'title',
        'content',
        'status',
      
    ];
    protected $dates = ['deleted_at'];
    
    public function files()
    {
        return $this->hasMany(File::class);
    }
}

?>
