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
        return Socialite::with($provider)->redirect();
    }

    //callback function for FACEBOOK
    public function callback($provider)
    {
        $getInfo = Socialite::with($provider)->user(); 
        $user = $this->createUser($getInfo,$provider); 
        auth()->login($user); 

        return redirect()->to('/home');
    }

    //create new user for sign in with facebook
    function createUser($getInfo,$provider){

        //Check if there is already user joined with facebook
        $user = User::where('provider_id', $getInfo->id)->first();

        //Check if there is already user joined with this email
        $userByEmail = User::where('email', $getInfo->email)->first();

        do{
            $minutes = 720;
            $token = Str::random(60);
        }while(User::where('api_token', $token)->exists());

        do{
            $auth_id = Str::random(10);
        }while(User::where('auth_id', $auth_id)->exists());


            if (!$user && !$userByEmail) { // if there is no user with facebook and no uses with same email create new user, else return user that already using that email
                $user = User::create([
                    'name'     => $getInfo->name,
                    'api_token' => $token,
                    'auth_id' => $auth_id,
                    'email'    => $getInfo->email,
                    'liked_sports' => json_encode([]),
                    'provider' => $provider,
                    'provider_id' => $getInfo->id,
                    'avatar' => $getInfo->avatar,
                ]);
            }
            else{                       
                if(!$userByEmail){ 

                    $user->api_token = $token;
                    Cookie::queue(Cookie::make('api_token', $token, $minutes, null, null, false, false));
                    $user->save();

                }else{

                    $userByEmail->api_token = $token;
                    Cookie::queue(Cookie::make('api_token', $token, $minutes, null, null, false, false));
                    $userByEmail->save();

                }

            }

        if(!$userByEmail){
            return $user;
        }else{
            return $userByEmail;
        }


    }

 }

?>
