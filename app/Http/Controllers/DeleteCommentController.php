<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArticleComment;

class DeleteCommentController extends Controller
{
    public function delete(Request $req)
    {
        $comment = ArticleComment::find($req->id)->delete();
        return;
    }
}
