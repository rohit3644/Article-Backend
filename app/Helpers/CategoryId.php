<?php

namespace App\Helpers;

class CategoryId
{
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
