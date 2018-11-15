<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use AfricasTalking\SDK\AfricasTalking;
use App\Jobs\SendSMSMessages;
use App\Contact;
use App\Status;
use App\Message;
use Response;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        // return view('message.index');
        $contacts = Contact::where('id', '<', 4)->get();
        // dd($contacts) ;
        $username = 'sandbox'; // use 'sandbox' for development in the test environment
        $apiKey   = '013cc9a74301e0f98fbcc495def5b92294627c2db03e428db32b0cb30782f83d'; // use your sandbox app API key for development in the test environment
        $AT = new AfricasTalking($username, $apiKey);
       
        // Get one of the services
        $sms = $AT->sms();
    //    dd( $application = $AT->application());
   

        $message = $request->sms; 

        $mess = new Message();
        $mess->name = $message;
        $mess->save();
        
        // Message::create(
        //     array(
        //   'name'=>$message,
        
        //     ));
        //     $message_id=
        $response = Array();
        // Use the service
        foreach ($contacts as $contact) {
            // return $contact;
            try {
                // Thats it, hit send and we'll take care of the rest
                // $result = $sms->send($this->dispatch(new SendSMSMessages($member, $message))->delay(60));
                $result   = $sms->send([
                    'to'      => $contact->mobilenumber,
                    'message' => $message
                ]);
                
                
          
               $array = json_encode($result, true);
               $js = json_decode($array);
               if($js->status == 'success')
               {
                $status=1;
               }
               elseif ($js->status == 'failed') {
                $status=2;
               }
               else{
                $status=3;
               }
               
               
               $data = $js->data;
               $rec = $data->SMSMessageData->Recipients;
               $number = $rec[0]->number;
               $cost =  $rec[0]->cost;
               $c = $rec[0]->cost;
                $len = strlen($c);
                $pos = strpos($c, 'S');
                $cost =  $rec[0]->cost;
                $k = substr($cost, $pos + 2, $len - $pos);
            //    echo $status;
            //    echo $number;
            //    echo $cost;
               $contact_id=$contact->id;

               Status::create(
                array(
              'status'=>$status,
                'send_status_cost'=>$k,
                'bal_before_send' => 100,
                'message_id' =>$mess->id,
                'contact_id' =>$contact_id
                ));

                $response = Array('status'=>$status);

                // print_r($response);
              
            } catch (Exception $e) {
                echo "Error: ".$e.getMessage();
            }
            
            // $result   = $this->sendSMS($contact->mobilenumber, 'Hello World!', $sms);
            // dd($contact->mobilenumber);
            // print_r($result);
            $responsed = [
                'status' =>$status
            ];
           
        }
        return response()->json($responsed, 200);

        
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
