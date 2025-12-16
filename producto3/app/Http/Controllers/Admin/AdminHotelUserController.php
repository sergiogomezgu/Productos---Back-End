<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotelUser;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminHotelUserController extends Controller
{
    public function index()
    {
        $users = HotelUser::with('hotel')->get();
        return view('admin.hotel_users.index', compact('users'));
    }

    public function create()
    {
        $hotels = Hotel::all();
        return view('admin.hotel_users.create', compact('hotels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:hotel_users,email',
            'password' => 'required|min:6',
        ]);

        HotelUser::create([
            'hotel_id' => $request->hotel_id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.hotel_users.index')
            ->with('success', 'Usuario de hotel creado correctamente');
    }

    public function edit(HotelUser $user)
    {
        $hotels = Hotel::all();
        return view('admin.hotel_users.edit', compact('user', 'hotels'));
    }

    public function update(Request $request, HotelUser $user)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:hotel_users,email,{$user->id}",
            'password' => 'nullable|min:6',
        ]);

        $data = [
            'hotel_id' => $request->hotel_id,
            'name' => $request->name,
            'email' => $request->email,
        ];

        // Solo actualizar contraseña si el usuario la cambia
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.hotel_users.index')
            ->with('success', 'Usuario actualizado correctamente');
    }

    public function destroy(HotelUser $user)
    {
        $user->delete();

        return redirect()->route('admin.hotel_users.index')
            ->with('success', 'Usuario eliminado correctamente');
    }
}
