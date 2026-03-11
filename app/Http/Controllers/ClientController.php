<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private function valdiationRequest(Request $request)
    {
        $validate = new Client();

        return $request->validate([
            'first_name' => 'required|string|max:100',
            'middle_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'contact' => 'required|string|max:50',
            'term' => 'required|string|max:100',
            'monthly_payment' => 'required|decimal:2',
            'address' => 'required|string|max:500',
            'remarks' => 'nullable|string|max:500',
        ]);
    }


    public function index(Request $request)
    {
       $request = Client::get();
       return response()->json([
        'message' =>'Get data succesfully ',
        'data' => $request
       ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
         //
        $validate = $this->valdiationRequest($request);

        $layaway = new client();

        $layaway->first_name = $validate['first_name'];
        $layaway->middle_name = $validate['middle_name'];
        $layaway->last_name = $validate['last_name'];
        $layaway->address = $validate['address'];
        $layaway->contact = $validate['contact'];
        $layaway->term = $validate['term'];
        $layaway->monthly_payment = $validate['monthly_payment'];
        $layaway->remarks = $validate['remarks'];
        $layaway->save();
        try {     
            return response()->json([
                'message' => 'succesfully created',
                'data' => $layaway
            ], 201);
        } catch (\Exception $e){
            return response()->json([
                'message' => 'Failed to create data',
                'data' => $e
            ], 500);

        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $id)
    {
        //
        $client = Client::find($id);

        if(!$client){
            return response()->json(['message' => 'No data found'],  404);
        }else{
             return response()->json([
            'data'    => $client
        ], 200);
        }

       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $validate = $this->valdiationRequest($request, $id);

        $layaway = client::find($id);

        $validate = $request->validate([
            'first_name' => 'required|string|max:100',
            'middle_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'contact' => 'required|string|max:50',
            'term' => 'required|string|max:100',
            'monthly_payment' => 'required|decimal:2',
            'address' => 'required|string|max:500',
            'remarks' => 'nullable|string|max:500',
        ]);

        $layaway->first_name = $validate['first_name'];
        $layaway->middle_name = $validate['middle_name'];
        $layaway->last_name = $validate['last_name'];
        $layaway->address = $validate['address'];
        $layaway->contact = $validate['contact'];
        $layaway->term = $validate['term'];
        $layaway->monthly_payment = $validate['monthly_payment'];
        $layaway->remarks = $validate['remarks'];
        $layaway->save();

        try{
            return response()->json([
                'message' => 'Updated Succesfully',
                'data' => $layaway
            ], 200);
        }catch(\Exception $error){
            return response()->json([
                'message' => 'Failed to update client',
                'error' => $error->getMessage()
            ], 500);
        }
        

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $client = Client::find($id);
        if(!$client){
            return response()->json([
                'message' => 'Client not found'
            ], 404);
        }
        $client->delete();
        
        return response()->json([
            'message' => 'succesfully deleted',
        ], 200);
    }
}
