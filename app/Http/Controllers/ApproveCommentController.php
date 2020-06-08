<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArticleComment;

class ApproveCommentController extends Controller
{
    public function approve(Request $req)
    {
        $comment = ArticleComment::find($req->id);
        $comment->is_approved = "Yes";
        $comment->save();
        return;
    }
}
