<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Waiting;

class WaitingController extends Controller
{

   
    

     public function index()
    {
        $queues = Waiting::all();

        return response()->json([
            'data' => $queues,
            'isSuccess' => true,
            'errorMessages' => []
        ], 200);
    }

  public function show(Request $request, $id)
{
    $waiting = Waiting::find($id);

    if(!$waiting) {
        return response()->json(['message' => 'Not Found'], 404);
    }
    
    return response()->json([
        'message' => 'Success',
        'data' => $waiting
    ], 200);
}

    public function proceed (Request $request, $id)
    {
        $waiting = Waiting::find($id);

        if(!$waiting) {
            return response()->json(['message' => 'Not Found'], 404);
        }else{
             $validated = $request->validate([
            'status' => 'required|in:waiting,serving,done,cancelled'
        ]);

          $waiting->status = $validated['status'];
          $waiting->save();

          return response()->json(['message' => 'Updated succesfully'], 200);
        }
       
    }

    public function updateStatus($id)
    {
        $queue = Waiting::findOrFail($id);
        $queue->status = 'serving'; 
        $queue->save();

        return response()->json(['success' => true]);
    }

    public function cancel($id)
    {
        $queue = Waiting::findOrFail($id);
        $queue->status = 'cancelled'; 
        $queue->save();

        return response()->json(['success' => true]);
    }
}
