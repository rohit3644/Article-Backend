<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Article;
// users table model with all relationship and mutators
// for filtering the user input
class Users extends Model
{
    protected $perPage = 6;
    protected $table = 'users';

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = htmlspecialchars(stripslashes(trim($value)));
    }
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = htmlspecialchars(stripslashes(trim($value)));
    }
}
