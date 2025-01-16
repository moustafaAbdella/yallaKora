<?php

use App\Http\Controllers\Api\CategorieController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\TrackAdsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Auth\UserController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\CodeCheckController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\LeagueController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\MatcheController;
use App\Http\Controllers\Api\LiveTvController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\PlanController;


// use Maatwebsite\Excel\Facades\Excel;

// use App\Exports\TrackAdsClass;



// Route::get('export-track-ads', function () {
//     return Excel::download(new TrackAdsClass, 'track_ads_details.xlsx');
// });
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
Route::get('/image/comp-logo/{filename}', [App\Http\Controllers\LeagueController::class, 'getImg']);
Route::get('/image/team-logo/{filename}', [App\Http\Controllers\TeamController::class, 'getImg']);
Route::get('/image/livetv/{filename}', [App\Http\Controllers\LivetvController::class, 'getImg']);


Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);
Route::get('VirefyLink/get', [SettingsController::class, 'virefyUrl']);
//decrypter
Route::middleware('decrypter')->group(function () {
    // competitions
    Route::get('competitions/get/data', [LeagueController::class, 'data']);
    Route::get('competitions/get/search', [LeagueController::class, 'search']);
    // teams
    Route::get('teams/get/data', [TeamController::class, 'data']);
    Route::get('teams/get/search', [TeamController::class, 'search']);
    // matches
    Route::get('matches/get/data', [MatcheController::class, 'data']);
    //live tv
    Route::get('get/categories', [ CategorieController::class, 'getCategories' ]);
    Route::get('get/tvs', [ CategorieController::class, 'getLiveTv' ]);

    // searche
    Route::get('get/search', [ SearchController::class, 'search' ]);

    // settings
    Route::get('settings/get/data', [SettingsController::class, 'data']);
    
    Route::get('player/config', [SettingsController::class, 'config']);
    Route::get('servers/get/data', [ SettingsController::class, 'servers' ]);
    //track ads
    Route::get('get/myIp',[TrackAdsController::class,'myIp']);
    Route::post('get/link',[TrackAdsController::class,'store']);
    Route::post('set/link',[TrackAdsController::class,'update']);
    // create device
    Route::post('device', [SettingsController::class, 'device']);

    // plan
    Route::get('plans/get/data', [PlanController::class, 'data']);

    //auth
    Route::post('password/email',  ForgotPasswordController::class);
    Route::post('password/code/check', CodeCheckController::class);
    Route::post('password/reset', ResetPasswordController::class);
});

Route::middleware(['auth:api','decrypter'])->group(function () {
    Route::get('user', [UserController::class, 'userInfo']);
    Route::post('update/user', [UserController::class, 'updateUser']);
    Route::post('update/user/password', [UserController::class, 'updatePassUser']);
    Route::post('remove/user', [UserController::class, 'removeUser']);
    Route::get('logout', [UserController::class, 'logout']);

    // plan
    Route::get('plans/get/my', [PlanController::class, 'my']);
    Route::controller(PaymentController::class)->group(function () {
        Route::group([ 'prefix' => 'payment-mobile' ], function () {
            Route::post('/', 'payment')->name('payment-mobile');
        });
    });

    // competitions
    Route::post('competitions/set/favorite', [LeagueController::class, 'setFavorite']);
    Route::post('competitions/remove/favorite', [LeagueController::class, 'removeFavorite']);
    Route::get('competitions/get/favorite', [LeagueController::class, 'getFavorite']);

    // teams
    Route::post('teams/set/favorite', [TeamController::class, 'setFavorite']);
    Route::post('teams/remove/favorite', [TeamController::class, 'removeFavorite']);
    Route::get('teams/get/favorite', [TeamController::class, 'getFavorite']);

    // //remove Account
    // Route::post('remove/user/email',  ForgotPasswordController::class);
    // Route::post('remove/user/code/check', CodeCheckController::class);
    // Route::post('remove/user/reset', ResetPasswordController::class);
});
Route::controller(PaymentController::class)->group(function () {
    Route::group([ 'prefix' => 'payment-mobile' ], function () {
        Route::get('/verify', 'verify')->name('verify-payment');
        Route::get('/error', 'errorPayment')->name('error-payment');
    });
});

