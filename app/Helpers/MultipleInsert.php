<?php

namespace App\Helpers;

class MultipleInsert
{
    public function multiple_insert($category_id_array, $article_id)
    {
        $data = [];
        foreach ($category_id_array as $id) {
            $associativeArray = [];
            $associativeArray['article_id'] = $article_id;
            $associativeArray['category_id'] = $id;
            array_push($data, $associativeArray);
        }

        return $data;
    }
}
