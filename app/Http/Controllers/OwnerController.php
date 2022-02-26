<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Models\Owner;
use Illuminate\Support\Facades\Hash;


class OwnerController extends Controller
{
    public function index()
    {
        $items = Owner::all();
        return response()->json(['data' => $items]);
    }

    public function store(RegisterRequest $request)
    {
        $input = $request->validated();

        $item = Owner::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'type_id' => 2
        ]);

        return response()->json(['data' => $item], 200);
    }
}
