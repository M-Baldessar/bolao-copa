<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'nickname'     => 'nullable|string|max:30',
            'avatar_emoji' => 'nullable|string|max:10',
        ]);

        $validated['nickname']     = $validated['nickname'] ?: null;
        $validated['avatar_emoji'] = $validated['avatar_emoji'] ?: null;

        auth()->user()->update($validated);

        return back()->with('success', 'Perfil atualizado com sucesso!');
    }
}
