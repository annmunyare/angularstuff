<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Flow;
use Response;


class FlowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    
    // public function getstate(Request $request)
    // {
    //     $client = new Client();
    //     $response = $client->request('GET', 'https://nanyukiafann-stuff.azurewebsites.net/api/v1/flows', [
    //         'headers' => 
    //         [
    //             'Accept' => 'application/json',
    //             'Content-type' => 'application/json'
    //         ]
    //     ]);
    //     $body = $response->getBody();
    //     $content =$body->getContents();
    //     $arr = json_decode($content,TRUE);
    //     //    return $content;
    //        dd($arr);
    //     // return $arr['groupName'];
    //     foreach($arr as $obj )
    //     {
            
    //         // return $obj;
            
         
    //      }
     

       
     
        
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
   
     $flow = new Flow($request->all());

     $flow->save();
     
     return Response::json($flow);
    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     //
    //      return $request;
    // }

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
