<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class TransactionController extends Controller{
    private $clientURL="http://poker.sportsbetsasia.com";
    private $adminURL="http://gamezone.sportsbetsasia.com";
    private $client_id="98411558-7690-4ba4-8255-1f374b84a84a";
    private $client_secret="S4wLT7FJfpAegyroLtHHMIBe2KXfJZQ28svpzqZH";
    public function sendUser(Request $request){
            if (Auth::guard('api')->check()){
                $access_token=$request->access_token;

                $response = Http::withHeaders([
                    'Accept'=>"application/json",
                    'Authorization'=>"Bearer " . $access_token
                ])->get($this->adminURL.'/api/user');
                return $response->json();
            }{
                return '{need login}';
        }
    }

    public function createRoom(Request $request){
        if (Auth::guard('api')->check()){
            $access_token=$request->access_token;

            $response = Http::withHeaders([
                'Accept'=>"application/json",
                'Authorization'=>"Bearer " . $access_token
            ])->post($this->adminURL."/api/session/create",
                [
                    "status" => $request->status,
                    "winner" => $request->winner,
                    "betting_name" => $request->betting_name,
                    "is_private" => $request->is_private,
                    "betting_level" => $request->betting_level,
                ]);
            return $response;
dd($response);
        }{
            return '{need login}';
        }
    }

    public function getRoom(Request $request){
        if (Auth::guard('api')->check()){
            $access_token=$request->access_token;

            $response = Http::withHeaders([
                'Accept'=>"application/json",
                'Authorization'=>"Bearer " . $access_token
            ])->get($this->adminURL."/api/session/get_single/".$request->room_id);
            return $response;
        }{
            return '{need login}';
        }
    }




}
