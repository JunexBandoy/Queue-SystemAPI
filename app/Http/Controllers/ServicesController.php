<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Services;

class ServicesController extends Controller
{
    private function validationRequest(Request $request)
    {
       

        return $request->validate([
            'id' => 'nullable|integer|exists:clients,id',
            'service_code' => 'required|varchar:5',
            'service_name' => 'required|varchar:100',
            'is_active' => 'required|integer:1'
        ]);
    }

    public function create(Request $request)
    {
        $validated = $this->validationRequest($request);

        $services = new Services();
        $services->client_id   = $validated['client_id'] ?? null;
        $services->service_code= $validated['service_code'];
        $services->service_name= $validated['service_name'];
        $services->is_active   = $validated['is_active'];

        try {
            $services->save();
            return response()->json([
                'message' => ' created successfully.',
                'data' => $services
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
        $payments = Services::all();

        return response()->json([
            'data' => $payments,
            'isSuccess' => true,
            'errorMessages' => []
        ], 200);
    }
}
