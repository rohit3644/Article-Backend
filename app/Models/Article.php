<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Users;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'articles';
    public function user()
    {
        return $this->belongsTo(Users::class);
    }
    public function category()
    {
        return $this->belongsToMany(Category::class);
    }
}