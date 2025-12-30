<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'parentConsent' => 'nullable|boolean',
        ]);

        $user = Auth::user();

        $user->update([
            'consent_given_at' => now(),
            'parent_consented' => $request->input('parentConsent') ?? null,
        ]);

        // Log the consent action
        activity()
            ->causedBy($user)
            ->withProperties([
                'parent_consented' => $request->input('parentConsent') ?? null,
            ])
            ->log('Student gave PDPA consent');

        return response()->json(['success' => true]);
    }
}
