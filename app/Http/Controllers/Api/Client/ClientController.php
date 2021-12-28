<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientStoreRequest;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $users = User::select('*')
            ->search(request()->search)
            ->get();

        return response()->json([
            'ok' => true,
            'data' => $users,
            'message' => 'success',
        ]);

    }

    public function store(ClientStoreRequest $request)
    {
        $user = User::create($request->all());

        return response()->json([
            'ok'    => true,
            'data'  => $user,
            'message' => 'success'
        ]);
    }

}