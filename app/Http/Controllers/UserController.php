<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->paginate(20);
        return view('users.index', compact('users'));
    }

    public function objects($username)
    {
        $user = User::where('username', $username)->firstOrFail();

        // Показываем только активные товары (по умолчанию)
        $products = $user->products()->with('images')->paginate(12);

        return view('users.products', compact('user', 'products'));
    }
}
