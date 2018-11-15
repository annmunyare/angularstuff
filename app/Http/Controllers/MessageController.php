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
 
        $batch_size = 700;
         
        $total_contacts = count($contacts);
         
        $total_batch = $total_contacts / $batch_size;
         
        $contacts = json_encode($contacts);
        $contacts = json_decode($contacts);
        // $contacts = Contact::remember(60)->get();
        // dd($contacts) ;
        $username = 'sandbox'; // use 'sandbox' for development in the test environment
        $apiKey = '013cc9a74301e0f98fbcc495def5b92294627c2db03e428db32b0cb30782f83d'; // use your sandbox app API key for development in the test environment
        $AT = new AfricasTalking($username, $apiKey);
         
        // Get one of the services
        $sms = $AT->sms();
        // dd( $application = $AT->application());
         
        $message = $request->sms;
         
        $mess = new Message();
        $mess->name = $message;
        $mess->save();
         
        $recipients = "";
        $batch_result = array();
         
        for ($m = 0; $m < $total_batch; $m++) {
            for ($k = 0; $k < ($m + $batch_size); $k++) {
            $recipients .= $contacts[$k]->mobilenumber . ',';
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
                
                //var_dump($result);
                } catch (Exception $e) {
                    echo "Error: " . $e . getMessage();
                }
            }

            $responsed = array();
            for ($m = 0; $m < count($batch_result); $m++) {
                var_dump($batch_result[0]);
                $array = json_encode($batch_result[$m]);
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
               $contact_id = 1;

               Status::create(
                array(
              'status'=>$status,
                'send_status_cost'=>$k,
                'bal_before_send' => 100,
                'message_id' =>$mess->id,
                'contact_id' =>$contact_id
                ));

                $responsed [] = $status ;
            }
        // $responsed = array();
        // Use the service
        // foreach ($contacts as $contact) {
        //     // return $contact;
        //     try {
        //         // Thats it, hit send and we'll take care of the rest
        //         // $result = $sms->send($this->dispatch(new SendSMSMessages($member, $message))->delay(60));
        //         $result   = $sms->send([
        //             'to'      => $contact->mobilenumber,
        //             'message' => $message
        //         ]);

        //        $array = json_encode($result, true);
        //        $js = json_decode($array);
        //        if($js->status == 'success')
        //        {
        //         $status=1;
        //        }
        //        elseif ($js->status == 'failed') {
        //         $status=2;
        //        }
        //        else{
        //         $status=3;
        //        }
               
               
        //        $data = $js->data;
        //        $rec = $data->SMSMessageData->Recipients;
        //        $number = $rec[0]->number;
        //        $cost =  $rec[0]->cost;
        //        $c = $rec[0]->cost;
        //         $len = strlen($c);
        //         $pos = strpos($c, 'S');
        //         $cost =  $rec[0]->cost;
        //         $k = substr($cost, $pos + 2, $len - $pos);
        //     //    echo $status;
        //     //    echo $number;
        //     //    echo $cost;
        //        $contact_id=$contact->id;

        //        Status::create(
        //         array(
        //       'status'=>$status,
        //         'send_status_cost'=>$k,
        //         'bal_before_send' => 100,
        //         'message_id' =>$mess->id,
        //         'contact_id' =>$contact_id
        //         ));

        //         // $response = Array('status'=>$status);

        //         // print_r($response);
              
        //     } catch (Exception $e) {
        //         echo "Error: ".$e.getMessage();
        //     }
            
        //     // $result   = $this->sendSMS($contact->mobilenumber, 'Hello World!', $sms);
        //     // dd($contact->mobilenumber);
        //     // print_r($result);
        //     $responsed [] = $status ;
        //     // print_r( response()->json($responsed, 200));
           
        // }
    
        return $responsed;
        
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
