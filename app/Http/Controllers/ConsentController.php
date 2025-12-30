<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsentController extends Controller
{
// app/Http/Controllers/ConsentController.php

    public function store(Request $request)
    {
        $user = Auth::user();

        $user->update([
            'consent_given_at' => now(),
            'parent_consented' => $request->input('parentConsent') ?? null,
        ]);

        Auth::setUser($user->fresh());

        return response()->json(['success' => true]);
    }



}
