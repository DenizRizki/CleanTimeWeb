<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserProfileController extends Controller
{
 public function update(Request $request)
    {
        $user = $request->user(); 
        $customer = $user->customer; 
        
       $request->validate([
    'name'     => 'sometimes|required|string|min:3|max:255',
    'phone'    => 'sometimes|required|numeric|digits_between:10,13',
    'password' => 'sometimes|required|string|min:6',
]);

        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        if ($request->has('phone') && $customer) {
            $customer->update([
                'phone' => $request->phone 
            ]);
        }

        $latestPhone = $customer ? $customer->phone : '';

        return response()->json([
            'status'  => 'success',
            'message' => 'Perubahan profil berhasil disimpan.',
            'user'    => [
                'name'  => $user->name,
                'email' => $user->email,
                'phone' => $latestPhone, 
            ]
        ], 200);
    }
}