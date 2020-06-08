<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;
use App\Helpers\DataFilter;
use App\Helpers\Validations;

use App\Models\Contact;

class ContactController extends Controller
{
    public function contact(Request $req)
    {
        $data_filter = new DataFilter();
        $email = $data_filter->check_input($req->email);
        $name = $data_filter->check_input($req->name);
        $message = $data_filter->check_input($req->message);

        $validate = new Validations();
        $validation_error = $validate
            ->contact_validate($email, $name, $message);
        if ($validation_error !== "") {
            return response()->json([
                "message" => $validation_error,
                "code" => 201,
            ]);
        }
        $data = $req;
        Mail::to('test@test.com')->send(new ContactFormMail($data));

        $contact = new Contact;
        $contact->name = $name;
        $contact->email = $email;
        $contact->message = $message;
        $contact->save();
        return response()->json([
            "message" => "We have successfully received your message",
            "code" => 200,
        ]);
    }
}
