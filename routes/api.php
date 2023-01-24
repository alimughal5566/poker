<?php

use App\Http\Controllers\BetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login_user',[BetController::class, 'loginUser'])->name('login.user');
Route::post('/store',[BetController::class, 'store'])->name('store');
Route::post('/update_bet',[BetController::class, 'updateBet'])->name('update.bet');
Route::get('/get_bet/{id}',[BetController::class, 'getBet'])->name('get.bet');
Route::get('/get_bet',[BetController::class, 'getAll'])->name('get.all');
Route::delete('/detele_all_bets',[BetController::class, 'deteleAllBets'])->name('detele.all.bets');


Route::post('/test',function (Request $request){
    return response()->json([$request->all(),$request->header('test'
    )]);
})->name('test');




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/send_user',[ClientController::class, 'sendUser'])->name('send.user');

Route::middleware('auth:sanctum')->group( function () {
    Route::post('/logout', [ClientController::class, 'signout'])->name('signout');
    Route::post('/create_room', [TransactionController::class, 'createRoom'])->name('createRoom');
    Route::post('/get_room', [TransactionController::class, 'getRoom'])->name('get.room');

});
