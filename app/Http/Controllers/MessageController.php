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
        $contacts = Contact::get();
        $batch_size = 1000;
        $total_contacts = count($contacts);
        $total_batch = $total_contacts / $batch_size;
        $contacts = json_encode($contacts);
        $contacts = json_decode($contacts);
        // use 'sandbox' for development in the test environment
        $username = 'sandbox';
        // use your sandbox app API key for development in the test environment
        $apiKey = 'ca5328cd241404e991d78a599f7ad9500925661e3cc4c3ab471b97c0598c21c2';
        $AT = new AfricasTalking($username, $apiKey);

        // Get one of the services
        $sms = $AT->sms();
        //    dd( $application = $AT->application());

        $message = $request->sms;

        $mess = new Message();
        $mess->name = $message;
        $mess->save();

        $recipients = "";
        $batch_result = array();
        $final_contact_per_batch = 0;

        $phone_and_id = array();
        $phones = array();
        $response_array = array();
        $response = array();

        for ($m = 0; $m < $total_batch; $m++) {
            //next maximum contact maximum index
            $max_contact_index = $batch_size * ($m + 1);
            //if this next maximum contact index has exceeded total number of contacts assign it to total_contacts
            $max_contact_index = $max_contact_index >= $total_contacts ? $total_contacts : $max_contact_index;
            for ($k = $final_contact_per_batch; $k < $max_contact_index; $k++) {
                $recipients .= $contacts[$k]->mobilenumber . ",";
                $phone_and_id['id'] = $contacts[$k]->id;
                $phone_and_id['phone'] = $contacts[$k]->mobilenumber;
                array_push($phones, $phone_and_id);
            }

            $enqueue = true;

            try {
                // Thats it, hit send and we'll take care of the rest
                $result = $sms->send([
                    'to' => $recipients,
                    'message' => $message,
                    'enqueue' => $enqueue,
                ]);

                array_push($batch_result, $result);

                $array = json_encode($result);
                $js = json_decode($array, true);

                $data = $js['data'];
                $recipients = $data['SMSMessageData']['Recipients'];

                // print_r($js->data->SMSMessageData->Recipients);
                foreach ($recipients as $recipient) {
                    $status = $recipient['status'];
                    $status = ($status == 'Success' ? 1 : ($status == 'Failed' ? 2 : 3));
                    $number = $recipient['number'];
                    $costStr = $recipient['cost'];
                    $cost = substr($costStr, strpos($costStr, 'S') + 2, strlen($costStr) - strpos($costStr, 'S'));

                    $response_array['status'] = $status;
                    $response_array['cost'] = $cost;
                    array_push($response, $response_array);

                    $contact_id = 1;

                    for ($id = 0; $id < count($phones); $id++) {
                        if ($phones[$id]['phone'] == $number) {
                            $contact_id = $phones[$id]['id'];
                            break;
                        }
                    }
                    set_time_limit(0);
                    // Status::create([
                    //     'status' => $status,
                    //     'send_status_cost' => $cost,
                    //     'bal_before_send' => 100,
                    //     'message_id' => $mess->id,
                    //     'contact_id' => $contact_id,
                    // ]);
                    // print_r($phones);
                    print_r($response);
                }

                $final_contact_per_batch = $batch_size * ($m + 1);
                $recipients = "";

            } catch (Exception $e) {
                echo "Error: " . $e . getMessage();
            }

        }
        // foreach ($batch_result as $batch) {

        //     // break;
        // }

        // return ($response);
        // return $responsed;

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
