<?php

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/csv', function() {
    $table = User::all();
    $output='';
    foreach ($table as $row) {
        $output.=  implode(",",$row->toArray());
    }
    $headers = array(
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="registerd_users.csv"',
    );
  
    return Response::make(rtrim($output, "\n"), 200, $headers);
  });
