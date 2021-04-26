<?php

namespace App\Http\Controllers;

use App\Providers\RouteServiceProvider;
use App\Rules\IsUserOwner;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function update(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'id' => ['required', new IsUserOwner()],
            'name' => "required|string|max:255|unique:users,name,$user->id",
            'email' => "required|string|email|max:255|unique:users,email,$user->id",
        ]);

        $user->update($validated);

        return redirect(RouteServiceProvider::HOME);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => ['required', new IsUserOwner()],
        ]);
        auth()->user()->delete();
        auth()->logout();

        return redirect(RouteServiceProvider::HOME);
    }
}
