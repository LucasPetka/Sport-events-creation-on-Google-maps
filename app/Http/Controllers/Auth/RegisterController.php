<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Str;
use Cookie;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/email/verify';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        do{
            $minutes = 720;
            $token = Str::random(60);
        }while(User::where('api_token', $token)->exists());

        do{
            $auth_id = Str::random(10);
        }while(User::where('auth_id', $auth_id)->exists());

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'avatar' => "blank-user-img.jpg",
            'liked_sports' => [],
            'password' => Hash::make($data['password']),
            'auth_id' => $auth_id,
            'api_token' => $token,
        ]);

        Cookie::queue(Cookie::make('api_token', $token, $minutes, null, null, false, false));

        $user->sendEmailVerificationNotification();

        return $user;

    }
}
