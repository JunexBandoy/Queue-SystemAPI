<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Queues;
use App\Repositories\QueueRepository;

class QueuesController extends Controller
{
    protected QueueRepository $queuesRepo;

    public function __construct(QueueRepository $queuesRepo)
    {
        $this->queuesRepo = $queuesRepo;
    }

    // Store new queue
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name'      => 'required|string|max:100',
                'middle_initial'  => 'required|string|max:1',
                'last_name'       => 'required|string|max:100',
                'contact_number'  => 'required|string|max:20',
                'service_id'      => 'required',
                'priority'        => 'required|in:senior,pwd,regular',
                'queue_date'      => 'nullable|date',
            ]);

            $queue = Queues::create($validated);

            return response()->json($queue, 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function index()
    {
        $queues = Queues::all();

        return response()->json([
            'data' => $queues,
            'isSuccess' => true,
            'errorMessages' => []
        ], 200);
    }

    // Global waiting
    public function waiting(Request $request)
    {
        $items = $this->queuesRepo->getWaitingQueues();

        return response()->json([
            'data' => $items,
            'isSuccess' => true,
            'errorMessages' => []
        ], 200);
    }

    // Waiting for the authenticated user's section only
   
    public function waitingForMySection(Request $request)
    {      
        $sectionId = (int) $request->user()->section_id;
        if (!$sectionId) {
            return response()->json(['message' => 'User has no section assigned.'], 422);
        }

        $items = $this->queuesRepo->getWaitingQueuesForSection($sectionId);

        return response()->json([
            'data' => $items,
            'isSuccess' => true,
            'errorMessages' => []
        ], 200);
    }

     public function servingForMySection(Request $request)
    {
        $sectionId = (int) $request->user()->section_id;
        if (!$sectionId) {
            return response()->json(['message' => 'User has no section assigned.'], 422);
        }

        $items = $this->queuesRepo->getServingQueuesForSection($sectionId);

        return response()->json([
            'data' => $items,
            'isSuccess' => true,
            'errorMessages' => []
        ], 200);
    }

    public function updateStatus($id)
    {
        $queue = Queues::findOrFail($id);
        $queue->status = 'serving'; 
        $queue->save();

        return response()->json(['success' => true]);
    }

    public function cancel($id)
    {
        $queue = Queues::findOrFail($id);
        $queue->status = 'cancelled'; 
        $queue->save();

        return response()->json(['success' => true]);
    }

    public function done($id)
    {
        $queue = Queues::findOrFail($id);
        $queue->status = 'done'; 
        $queue->save();

        return response()->json(['success' => true]);
    }

    public function transfer(Request $request, $id)
{
    $validated = $request->validate([
        'service_id' => 'required'
    ]);

    $queue = Queues::findOrFail($id);
    $queue->service_id = $validated['service_id']; // ✔ correct
    $queue->status = 'waiting';
    $queue->save();

    return response()->json(['success' => true]);
}

}