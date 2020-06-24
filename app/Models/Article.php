<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Users;
use Illuminate\Database\Eloquent\Model;
// articles table model with all relationship to different tables
// and mutators for filtering the data before storing in database
class Article extends Model
{
    protected $perPage = 6;
    protected $table = 'articles';
    public function user()
    {
        return $this->belongsTo(Users::class);
    }
    public function category()
    {
        return $this->belongsToMany(Category::class);
    }
    public function comments()
    {
        return $this->hasMany(ArticleComment::class);
    }
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = htmlspecialchars(stripslashes(trim($value)));
    }
    public function setContentAttribute($value)
    {
        $this->attributes['content'] = htmlspecialchars(stripslashes(trim($value)));
    }
    public function setAuthorNameAttribute($value)
    {
        $this->attributes['author_name'] = htmlspecialchars(stripslashes(trim($value)));
    }
}
