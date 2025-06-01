<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        return view('settings.edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
        ]);

        $user = auth()->user();
        $user->update($request->only('name', 'email'));

        return redirect()->route('settings.edit')->with('success', 'Profil mis à jour avec succès.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = auth()->user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('settings.edit')->with('success', 'Mot de passe modifié avec succès.');
    }

    public function updateTheme(Request $request)
    {
        $request->validate([
            'theme' => 'required|in:light,dark',
        ]);
    
        $user = auth()->user();
        $user->theme = $request->theme;
        $user->save();
    
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Thème mis à jour !'
            ]);
        }
    
        return back()->with('success', 'Thème mis à jour !');
    }
    


    public function destroy()
    {
        $user = auth()->user();

        // Optionnel : supprimer aussi ses livres et images associés
        $user->books()->each(function($book) {
            $book->delete();
        });

        auth()->logout();
        $user->delete();

        return redirect('/')->with('success', 'Ton compte a été supprimé.');
    }
}
