<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator  = Validator::make($request->all(), [ // buat validasi
            'name'  => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:3'
        ]);

        if ($validator->fails()) // jika validasi gagal
        {
            return response()->json([
                'error' => $validator->errors() // maka tampilkan error
            ], 422);
        }

        $data   = [ // masukkan request ke variable array
            'name'  => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role'  => $request->role
        ];

        $user   = $request->user(); // dapatkan informasi user yang sedang login

        if ($user->tokenCan('user:create')) // cek kondisi apakah user mempunyai hak untuk create
        {
            $users  = User::create($data); // jika iya maka create user baru

            return response()->json([ // tampilkan pesan
                'status' => 1,
                'message' => 'Created user successfully'
            ], 200);
        }

        return response()->json([ // jika tidak maka tampilkan pesan ini
            'status' => 0,
            'data' => 'Unauthorized'
        ]);
    }

    public function login(Request $request)
    {
        $this->validate($request, [ // melakukan validasi
            'email' => 'required|string|exists:users,email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first(); // cek apakah email tersedia di users
        if (!Hash::check($request->password, $user->password)) // cek apakah request password sama dengan di users
        {
            return response()->json([ // jika tidak maka tampilkan pesan ini
                'status' => 0,
                'data' => 'Wrong password, please try again!'
            ]);
        }

        $role = ($user->role == 'admin') ? ['user:index', 'user:create']:['user:index']; // jika role admin maka diberi hak akses index dan create

        return response()->json([ // tampilkan informasi user yang sedang login dan token
            'status' => 1,
            'data'  => $user->createToken($user->name, $role) // insert nama user dan hak akses
        ]);
    }

    public function logout()
    {
        $user = request()->user(); // mendapatkan informasi user yang login

        if (request()->token_id) // jika request mempunyai token id
        {
            $user->tokens()->where('id', request()->token_id)->delete(); // hapus token berdasarkan token id

            return response()->json([ // tampilan pesan
                'status'    => 1,
                'message'   => 'Log out successfully'
            ]);
        }

        $user->tokens()->delete(); // delete token user
        return response()->json([ // tampilkan pesan
            'status'    => 1,
            'message'   => 'Log out successfully',
        ]);
    }
}
