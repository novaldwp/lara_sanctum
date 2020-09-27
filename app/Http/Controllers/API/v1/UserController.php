<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function index()
    {
        $user   = request()->user();

        if ($user->tokencan('user::index'))
        {
            $users  = User::orderBy('created_at', 'DESC')->paginate(10);

            return response()->json(['status' => 1, 'data' => $users]);
        }

        return response()->json(['status' => 0, 'data' => 'Unauthorized']);
    }
}
