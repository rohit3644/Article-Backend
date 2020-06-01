<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use Exception;
use Illuminate\Support\Facades\Hash;

use App\Helpers\DataFilter;
use App\Helpers\Validations;

class RegistrationController extends Controller
{
    public function register(Request $req)
    {

        $data_filter = new DataFilter();
        $name = $data_filter->check_input($req->name);
        $email = $data_filter->check_input($req->email);
        $password = $data_filter->check_input($req->password);
        $mobile = $data_filter->check_input($req->mobile);

        $validate = new Validations();
        $validation_error = $validate->register_validate($name, $email, $password, $mobile);
        if ($validation_error !== "") {
            return response()->json([
                "message" => $validation_error,
                "code" => 201,
            ]);
        }

        $result = 0;
        try {
            $user = new Users;
            $user->name = $name;
            $user->email = $email;
            $user->password = Hash::make($password);
            $user->mobile_no = $mobile;
            $user->is_admin = "No";
            $user->save();
            $result = 1;
        } catch (Exception $e) {
            $result = -1;
        } finally {
            return $result;
        }
    }
}
