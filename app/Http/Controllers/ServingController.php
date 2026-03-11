<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Serving;

class ServingController extends Controller
{
     public function index()
    {
        $queues = Serving::all();

        return response()->json([
            'data' => $queues,
            'isSuccess' => true,
            'errorMessages' => []
        ], 200);
    }

    public function updateStatus($id)
{
    $queue = Serving::findOrFail($id);
    $queue->status = 'done'; 
    $queue->save();

    return response()->json(['success' => true]);
}

}
