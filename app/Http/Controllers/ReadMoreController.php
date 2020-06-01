<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ReadMoreController extends Controller
{
    public function getId(Request $req)
    {
        $data = Article::find($req->selectedId);
        $categories = $data->category;
        return $categories;
    }
}
