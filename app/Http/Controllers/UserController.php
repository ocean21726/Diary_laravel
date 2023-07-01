<?php

namespace App\Http\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use App\Repository\User\UserRepository;

class UserController extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    protected function jwt(User $user)
    {
        $payload = [
            'iss' => "diary-jwt", // Issuer of the token
            'sub' => $user->user_idx, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + 31556926 // Expiration time
        ];
        // As you can see we are passing `JWT_SECRET` as the second parameter that will
        // be used to decode the token in the future.
        return JWT::encode($payload, env('JWT_SECRET'));
    }

    public function postUserSignUp()
    {
        $id = request()->id;
        $nick = request()->nick;
        $password = request()->password;

        try {
            $user = \DB::transaction(function () use ($id, $nick, $password) {
                return $this->userRepository->postUserSignUp($id, $nick, $password);
            });
            \DB::commit();
            $token = $this->jwt($user);
            $userData = [
                'user_idx' => $user->user_idx,
                'user_id' => $user->user_id,
                'user_nick' => $user->user_nick
            ];
            return response()->object(['user' => $userData, 'token' => $token]);
        } catch (\Exception $e) {
            \Log::info('UserSignUpError: '.$e->getMessage());
            \DB::rollBack();
            return response()->fail('회원가입 실패');
        }
    }

    public function getUserIdCheck()
    {
        $id = request()->id;
        $result = $this->userRepository->getUserIdCheck($id);

        if ($result) {
            return response()->success('사용할 수 있는 ID입니다.');
        } else {
            return response()->fail('사용할 수 없는 ID입니다.');
        }
    }

    public function postUserLogin()
    {
        $id = request()->id;
        $password = request()->password;

        try {
            $user = \DB::transaction(function () use ($id, $password) {
                return $this->userRepository->postUserLogin($id, $password);
            });
            \DB::commit();
            $token = $this->jwt($user);
            $userData = [
                'user_idx' => $user->user_idx,
                'user_id' => $user->user_id,
                'user_nick' => $user->user_nick
            ];
            return response()->object(['user' => $userData, 'token' => $token]);
        } catch (\Exception $e) {
            \Log::info('UserLoginError: '.$e->getMessage());
            \DB::rollBack();
            return response()->fail('로그인 실패');
        }
    }
}
