<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Article;

class Category extends Model
{
    protected $table = 'categories';
    public function article()
    {
        return $this->belongsToMany(Article::class);
    }
}
