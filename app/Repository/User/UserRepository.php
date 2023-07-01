<?php
declare(strict_types=1);

namespace App\Repository\User;

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserRepository
{
    public function postUserSignUp($id, $nick, $password)
    {
        $isUser = User::where('user_id', $id)->first();
        if ($isUser) {
            throw new \Exception('이미 존재하는 회원입니다.');
        } else {
            $user = new User;
            $user->user_id = $id;
            $user->user_nick = $nick;
            $user->user_password = Hash::make($password);
            $user->join_date = Carbon::now();
            $user->save();

            return User::find($user->user_idx);
        }
    }
}
