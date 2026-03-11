<?php

namespace App\Http\Controllers;

use App\Models\payments;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    //
    private function valdiationRequest(Request $request)
    {
       

        return $request->validate([
            'client_id' => 'nullable|integer|exists:clients,id',
            'payment_amount' => 'required|decimal:2',
            'payment_date' => 'required|date',
            'payment_due' => 'required|date'
        ]);
    }

    public function create(Request $request)
    {
        $validated = $this->valdiationRequest($request);

        $payment = new payments();

        $payment->client_id = $validated['client_id'];
        $payment->payment_amount = $validated['payment_amount'];
        $payment->payment_date = $validated['payment_date'];
        $payment->payment_due = $validated['payment_due'];
        try {
            $payment->save();
            return response()->json([
                'message' => ' created successfully.',
                'data' => $payment
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        $payments = payments::all();

        return response()->json([
            'data' => $payments,
            'isSuccess' => true,
            'errorMessages' => []
        ], 200);
    }

    public function show(payments $id)
    {
        //
        $client = payments::find($id);

        if(!$client){
            return response()->json(['message' => 'No data found'],  404);
        }else{
             return response()->json([
            'data'    => $client
        ], 200);
        }

       
    }

    public function update (Request $request, $id)
    {
        $validate = $this->valdiationRequest($request, $id);
        $payment = payments::find($id);

         $validate = $request->validate([
            'payment_amount' => 'required|decimal:2',
            'payment_date' => 'required|date',
            'payment_due' => 'required|date'
        ]);
        
        $payment->payment_amount = $validate['payment_amount'];
        $payment->payment_date = $validate['required'];
        $payment->payment_due = $validate['payment_due'];

         try{
            return response()->json([
                'message' => 'Updated Succesfully',
                'data' => $payment
            ], 200);
        }catch(\Exception $error){
            return response()->json([
                'message' => 'Failed to update client',
                'error' => $error->getMessage()
            ], 500);
        }
    }

     public function destroy($id)
    {
        //
        $payment = payments::find($id);
        if(!$payment){
            return response()->json([
                'message' => 'Client not found'
            ], 404);
        }
        $payment->delete();
        
        return response()->json([
            'message' => 'succesfully deleted',
        ], 200);
    }
    
}
