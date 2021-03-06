<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// article_comment table model with all relationship and mutators
// for filtering the data before storing in database
class ArticleComment extends Model
{
    protected $table = 'article_comment';
    public function article()
    {
        return $this->belongsTo(Article::class);
    }
    public function setCommentsAttribute($value)
    {
        $this->attributes['comments'] = htmlspecialchars(stripslashes(trim($value)));
    }
}
