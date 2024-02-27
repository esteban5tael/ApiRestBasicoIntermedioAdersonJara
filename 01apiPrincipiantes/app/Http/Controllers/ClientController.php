<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientStoreRequest;
use App\Http\Requests\ClientUpdateRequest;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Client::query()
            ->orderBy('id', 'desc')
            ->get();


        return response()->json([
            'succes' => true,
            'data' => $data
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClientStoreRequest $request)
    {
        $client = Client::create($request->validated());

        return response()->json([
            'success' => true,
            'data' => $client
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        return response()->json([
            'succes' => true,
            'data' => $client
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClientUpdateRequest $request, Client $client)
    {
        $client->update($request->validated());


        return response()->json([
            'success' => true,
            'data' => $client,
            'message' => 'Client Updated Successfully'
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(Client $client)
    public function destroy($id)
    {
        $client = Client::find($id);

        if (is_null($client)) {
            return response()->json([
                'success' => false,
                'message' => 'Client NOT Deleted'
            ], 404);
        }

        $client->delete();


        return response()->json([
            'success' => true,
            'data' => $client,
            'message' => 'Client Deleted Successfully'
        ], 201);
    }
}
