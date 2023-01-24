<?php

namespace App\Http\Controllers;

use App\Models\Bet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

//use Illuminate\Support\Facades\Request;

class BetController extends Controller
{
    public function store(Request $request){
        $bet=Bet::create([
            "status"=>$request->status,
            "winner"=>$request->winner,
        ]);
        return response()->json(['bet'=>$bet],200);
    }

    public function updateBet(Request $request){

        $bet = Bet::find($request->id);
        $bet->fill([
         'status' =>$request->status,
         'winner' => $request->winner,
            ]);
        $bet->save();
        return response()->json(['bet'=>$bet],200);
    }
    public function getBet($id){
        $bet=Bet::where('id',$id)->first();
        return response()->json(['bet'=>$bet],200);
    }
public function deteleAllBets(){
    $bet=Bet::truncate();
    return response()->json(['bet'=>$bet],200);
}

    public function getall(){
        $bet=Bet::where('winner','!=',null)->get();

        return response()->json(['bet'=>$bet],200);
    }
    public function Show(){

        $Allbet=Bet::where('user_id',Auth::id())->get();
    }


    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]);

            if($validateUser->fails()){
                return response()->json([
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }


            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken,
                'user' =>$user
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

}
