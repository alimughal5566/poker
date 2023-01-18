<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class TransactionController extends Controller{
    private $clientURL="https://poker.sportsbetsasia.com";
    private $adminURL="https://gamezone.sportsbetsasia.com";
    private $client_id="983e6cf8-dc50-4d1f-8cf7-8fa52a0c983b";
    private $client_secret="qdQaedBtrs6xXI7Lsg4HXrWl6XTfGE8WHkm0YegL";
    public function sendUser(Request $request){
            if (Auth::guard('api')->check()){
                $access_token=$request->header('access_token');

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
//        dd(Auth::guard('api')->check());
        if (Auth::guard('api')->check()){
            $access_token=$request->header('access_token');

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

        }{
            return '{need login}';
        }
    }

    public function getRoom(Request $request){
        if (Auth::guard('api')->check()){
            $access_token=$request->header('access_token');

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
