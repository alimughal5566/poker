<?php

namespace App\Http\Controllers;

use App\Models\User;
use http\Exception\InvalidArgumentException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use Laravel\Sanctum\PersonalAccessToken;


class ClientController extends Controller
{
    /**
     * @var string
     * Client ID: 98428cc5-98fc-4f9c-bebe-74fc99739f35
     * Client secret: knZl6c9tu48LnVPcYdgk3KepOhydPBlvfA9KuTcp
     */
    private $clientURL="https://card.s888-live.com";
    private $adminURL="https://s888-live.com";
    private $client_id="98428cc5-98fc-4f9c-bebe-74fc99739f35";
    private $client_secret="knZl6c9tu48LnVPcYdgk3KepOhydPBlvfA9KuTcp";
    public function authCallback (Request $request){

    $request->session()->put("state",$state=Str::random(40));

    $query=http_build_query([
        "client_id"=>$this->client_id,
        "redirect_uri"=>$this->clientURL."/callback",
        "response_type"=>"code",
        "scope"=>"",
        "state"=>$state,
    ]);
    return redirect( $this->adminURL."/oauth/authorize?".$query);

    }
    public function tokenGenerate  (Request $request)
    {
        $state = $request->session()->pull('state');
        throw_unless(strlen($state) > 0 && $state = $request->state, InvalidArgumentException::class);
        $response = Http::asForm()->post($this->adminURL."/oauth/token",
            [
                "grant_type" => "authorization_code",
                "client_id" => $this->client_id,
                "client_secret" => $this->client_secret,
                "redirect_uri" => $this->clientURL."/callback",
                "code" => $request->code
            ]);

        $request->session()->put($response->json());
        return redirect('/authuser');
    }
    public function getUser(Request $request){
        $access_token=$request->session()->get('access_token');

        $response = Http::withHeaders([
            'Accept'=>"application/json",
            'Authorization'=>"Bearer " . $access_token
        ])->get($this->adminURL.'/api/user');
         $useArray=$response->json();
        try{
           $email=$useArray['email'];
        }catch (\Throwable $th){
            return redirect("login")->withError("Failed to get Login Information ! Try again");
        }
        $user =User::where("email",$email)->first();
        if(!$user){
            $user=new User;
            $user->name=$useArray['name'];
            $user->email=$useArray['email'];
            $user->email_verified_at=$useArray['email_verified_at'];
            $user->save();
        }
        $token = $user->createToken('my-app-token')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token,
            'access_token' => $access_token
        ];
        return view('game_view',compact('response'));
    }
    public function sendUserAgainstAccess(Request $request){
        $access_token=$request->session()->get('access_token');

        $response = Http::withHeaders([
            'Accept'=>"application/json",
            'Authorization'=>"Bearer " . $access_token
        ])->get($this->adminURL.'/api/user');
        $useArray=$response->json();
        try{
            $email=$useArray['email'];
        }catch (\Throwable $th){
            return redirect("login")->withError("Failed to get Login Information ! Try again");
        }
        $user =User::where("email",$email)->first();
        if(!$user){
            $user=new User;
            $user->name=$useArray['name'];
            $user->email=$useArray['email'];
            $user->email_verified_at=$useArray['email_verified_at'];
            $user->save();
        }
        $token = $user->createToken('my-app-token')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token,
            'access_token' => $access_token
        ];
//        dd($response);
        return view('game_view',compact('response'));
    }
        public function sendUser(Request $request){
            if (Auth::guard('api')->check()){
                $access_token=$request->access_token;
            $response = Http::withHeaders([
                'Accept'=>"application/json",
                'Authorization'=>"Bearer " . $access_token
            ])->get($this->adminURL.'/api/user');
                    $data=new \stdClass();
                $data->name=$response['name'];
                $data->email=$response['email'];
                $data->id=$response['id'];

            return $data;
        }
    }


        public function signout(Request $request){
          // Get bearer token from the request
            $accessToken = $request->bearerToken();

            // Get access token from database
            $token = PersonalAccessToken::findToken($accessToken);

            // Revoke token
            $token->delete();
            return response()->json(['status'=>'logout']);
        }

}
