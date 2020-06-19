<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// article_comment table model with all relationship and mutators
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
