<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use AfricasTalking\SDK\AfricasTalking;
use App\Jobs\SendSMSMessages;
use App\Contact;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('message.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    //   private function sendSMS($contact, $message, $sms){
    //     return $sms->send([
    //         'to'      => $contact,
    //         'message' => $message
    //     ]);
    // }

    public function store(Request $request)
    {
      
      $contacts = Contact::where('id', '<', 4);
        
        $username = 'sandbox'; // use 'sandbox' for development in the test environment
        $apiKey   = '013cc9a74301e0f98fbcc495def5b92294627c2db03e428db32b0cb30782f83d'; // use your sandbox app API key for development in the test environment
        $AT = new AfricasTalking($username, $apiKey);
        // return "HH";


        // Get one of the services
        $sms = $AT->sms();
        // $result   = $sms->send([
        //     'to'      => '+254713624254',
        //     'message' => 'Hello World!'
        // ]);

        $message = $request->message;
       


        // Use the service
        foreach ($contacts as $contact) {

            try {
                // Thats it, hit send and we'll take care of the rest
                // $result = $sms->send($this->dispatch(new SendSMSMessages($member, $message))->delay(60));

                $result   = $sms->send([
                    'to'      => $contact->mobilenumber,
                    'message' => $message
                ]);
                echo json_decode($result);
            } catch (Exception $e) {
                echo "Error: ".$e.getMessage();
            }
            
            // $result   = $this->sendSMS($contact->mobilenumber, 'Hello World!', $sms);
            // dd($contact->mobilenumber);
        }

        print_r($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
