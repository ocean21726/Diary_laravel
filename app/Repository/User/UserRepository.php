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

    public function getUserIdCheck($id)
    {
        $idCheck = User::where('user_id', $id)->first();
        if ($idCheck) {
            return false;
        } else {
            return true;
        }
    }

    public function postUserLogin($id, $password)
    {
        $user = User::where('user_id', $id)->first();

        if (!$user) {
            throw new \Exception('존재하지 않는 회원입니다.');
        } else {
            if (Hash::check($password, $user->user_password)) {
                return $user;
            } else {
                throw new \Exception('비밀번호 오류');
            }
        }
    }

    public function getUserFind($userId, $searchId)
    {
        $user = User::where('user_id', $searchId)->whereNotIn('user_id', [$userId])->first();
        if ($user) {
            return [$user, true];
        } else {
            return [null, false];
        }
    }
}
