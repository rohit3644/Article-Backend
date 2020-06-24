<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;

use App\Helpers\Response;
use App\Mail\ContactFormMail;

use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
// this class is used for mailing the contact us message to the team
// and store the name, email and message in the the DB
class ContactController extends Controller
{
    public function contact(ContactRequest $req)
    {
        // Begin Transaction
        DB::beginTransaction();
        try {
            // mail the team
            $response = new Response();
            $data = $req;
            Mail::to('test@test.com')->send(new ContactFormMail($data));
            // store the data in DB
            $contact = new Contact;
            $contact->name = $req->name;
            $contact->email = $req->email;
            $contact->message = $req->message;
            $contact->save();
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
