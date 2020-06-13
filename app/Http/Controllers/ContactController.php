<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use App\Mail\ContactFormMail;

use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use Exception;
use Illuminate\Contracts\Logging\Log;

class ContactController extends Controller
{
    public function contact(ContactRequest $req)
    {
        try {
            $response = new Response();
            $data = $req;
            Mail::to('test@test.com')->send(new ContactFormMail($data));

            $contact = new Contact;
            $contact->name = $req->name;
            $contact->email = $req->email;
            $contact->message = $req->message;
            $contact->save();
            $msg = $response->response(200);
            return response()->json($msg);
        } catch (Exception $e) {
            $msg = $response->response(500);
            $log = new Log();
            $log->error($msg["message"]);
            return response()->json($msg);
        }
    }
}
