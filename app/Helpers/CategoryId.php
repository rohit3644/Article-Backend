<?php

namespace App\Helpers;
// this class is used to get the catgeory id of selected catgeory
class CategoryId
{
    // returns the array of id as one article can have many categories
    public function get_id($selected_category, $category_data)
    {
        $id = [];
        foreach ($category_data as $data) {
            if (in_array($data->category, $selected_category)) {
                array_push($id, $data->id);
            }
        }

        return $id;
    }
}
