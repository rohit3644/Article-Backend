<?php

namespace App\Http\Controllers\Articles;

use App\Http\Controllers\Controller;

use App\Helpers\Response;
use Illuminate\Http\Request;
use App\Models\Article;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
// this class is used to delete articles
class DeleteArticleController extends Controller
{
    public function delete(Request $req)
    {
        // Begin Transaction
        DB::beginTransaction();
        try {
            $response = new Response();
            $article = Article::find($req->id);
            // delete the respective image file also
            File::delete('upload/images/' . $article->image_name);
            $article = Article::find($req->id)->delete();
            // Commit Transaction
            DB::commit();

            $msg = $response->response(200);
            return response()->json($msg);
        } catch (Exception $e) {
            // Rollback Transaction
            DB::rollback();
            $msg = $response->response(500);
            Log::error($e->getMessage());
            return response()->json($msg);
        }
    }
}
