<?php

namespace App\Helpers;

// this class is used for creating associative array
// of article id and category id based on category selected
class MultipleInsert
{
    // this function returns associative array of article_id and category_id
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
