<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Article;

class Users extends Model
{
    protected $perPage = 6;
    protected $table = 'users';

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
