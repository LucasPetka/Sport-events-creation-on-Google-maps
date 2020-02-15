<?php
 namespace App\Http\Controllers;

 use Illuminate\Http\Request;
 use Validator,Redirect,Response,File;
 use Socialite;
 use App\User;
 use Illuminate\Support\Str;
 use Cookie;

 class SocialController extends Controller
 {

    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        $getInfo = Socialite::driver($provider)->user(); 
        $user = $this->createUser($getInfo,$provider); 
        auth()->login($user); 

        return redirect()->to('/home');
    }

    function createUser($getInfo,$provider){

        $user = User::where('provider_id', $getInfo->id)->first();

        do{
            $minutes = 720;
            $token = Str::random(60);
        }while(User::where('api_token', $token)->exists());

        do{
            $auth_id = Str::random(10);
        }while(User::where('auth_id', $auth_id)->exists());


            if (!$user) {
                $user = User::create([
                    'name'     => $getInfo->name,
                    'api_token' => $token,
                    'auth_id' => $auth_id,
                    'email'    => $getInfo->email,
                    'provider' => $provider,
                    'provider_id' => $getInfo->id,
                    'avatar' => $getInfo->avatar,
                ]);
            }
            else{
                $minutes = 720;
                do{
                    $token = Str::random(60);
                    $user->api_token = $token;
        
                 }while(User::where('api_token', $user->api_token)->exists());
                 
                Cookie::queue(Cookie::make('api_token', $token, $minutes, null, null, false, false));
                $user->save();
            }


        return $user;
    }

 }

?>
