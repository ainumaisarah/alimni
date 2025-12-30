<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsentController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();
        $user->consent_given_at = now();
        $user->save();

        return response()->json(['success' => true]);
    }
}
