<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\DataFilter;
use App\Helpers\Validations;
use Exception;
use App\Models\ArticleComment;

class EditCommentController extends Controller
{
    public function edit(Request $req)
    {
        $data_filter = new DataFilter();
        $newComment = $data_filter->check_input($req->newComment);

        $validate = new Validations();
        $validation_error = $validate
            ->update_comment_validate($newComment);

        if ($validation_error !== "") {
            return response()->json([
                "message" => $validation_error,
                "code" => 201,
            ]);
        }

        try {
            $comment = ArticleComment::find($req->id);
            $comment->comments = $newComment;
            $comment->save();
            return 1;
        } catch (Exception $e) {
            return -1;
        }
    }
}
