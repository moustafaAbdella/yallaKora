<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\LeagueController;
use App\Http\Controllers\LivetvController;
use App\Http\Controllers\MatcheController;
use App\Http\Controllers\SeasonController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\PlayerSettingController;
use App\Http\Controllers\TrackAdsDetailsController;
use App\Http\Controllers\SubscriptionHistoryController;
use App\Http\Controllers\PaymentController as PaymentWeb;
use App\Http\Controllers\Api\PaymentController as PaymentApi;


use Maatwebsite\Excel\Facades\Excel;

use App\Exports\TrackAdsClass;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Reoptimized class loader:
Route::get('/optimize', function() {
    $exitCode = Artisan::call('optimize:clear');
    return '<h1>Reoptimized class loader</h1>';
});
//Clear Config cache:
Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1> ' . $exitCode ;
});

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    // return what you want
});

Route::get('/', function () {
    return redirect('/admin');
});

Auth::routes();                  

Route::get('/paypal/webhock', [PaymentApi::class, 'test']);
Route::get('/buy', [PaymentWeb::class, 'payment']);
Route::any('/checkout/api/paypal/order/create/', [PaymentWeb::class, 'makePayment']);
Route::any('/checkout/api/paypal/order/execute', [PaymentWeb::class, 'executePayment']);

Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {

    Route::get('/', [AdminController::class, 'index'])->name('admin');
    Route::get('/test', [AdminController::class, 'test']);
    Route::get('/get/dashboard/analysis', [AdminController::class, 'analysis']);

    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
    Route::post('/profile', [UserController::class, 'updateAdmin'])->name('profile.update');
    Route::post('/profile/password', [UserController::class, 'updatePasswordAdmin'])->name('profile.password');

    //teams
    Route::get('/teams', [AdminController::class, 'teams'])->name('teams');
    Route::get('/get/teams/data', [TeamController::class, 'data']);
    Route::get('/get/teams/count', [TeamController::class, 'count']);
    Route::get('/get/teams/search', [TeamController::class, 'search']);
    Route::post('/store/teams/image', [TeamController::class, 'uploadImage']);
    Route::delete('/remove/teams/image', [TeamController::class, 'removeImage']);
    Route::post('/store/teams', [TeamController::class, 'store']);
    Route::delete('/remove/teams/{team}', [TeamController::class, 'destroy']);
    Route::put('/update/teams/{team}', [TeamController::class, 'update']);
    Route::get('/update/teams/featured/{leaque}', [TeamController::class, 'changeFeatured']);

    //leagues
    Route::get('/leagues', [AdminController::class, 'leagues'])->name('leagues');
    Route::get('/get/leagues/data', [LeagueController::class, 'data']);
    Route::get('/get/leagues/count', [LeagueController::class, 'count']);
    Route::get('/get/leagues/search', [LeagueController::class, 'search']);
    Route::post('/store/leagues/image', [LeagueController::class, 'uploadImage']);
    Route::delete('/remove/leagues/image', [LeagueController::class, 'removeImage']);
    Route::post('/store/leagues', [LeagueController::class, 'store']);
    Route::delete('/remove/leagues/{leaque}', [LeagueController::class, 'destroy']);
    Route::put('/update/leagues/{leaque}', [LeagueController::class, 'update']);
    Route::get('/update/leagues/featured/{leaque}', [LeagueController::class, 'changeFeatured']);

    //seasons
    Route::get('/seasons', [AdminController::class, 'seasons'])->name('seasons');
    Route::get('/get/seasons/data', [SeasonController::class, 'data']);
    Route::get('/get/seasons/count', [SeasonController::class, 'count']);
    Route::get('/get/seasons/search', [SeasonController::class, 'search']);
    Route::post('/store/seasons/image', [SeasonController::class, 'uploadImage']);
    Route::delete('/remove/seasons/image', [SeasonController::class, 'removeImage']);
    Route::post('/store/seasons', [SeasonController::class, 'store']);
    Route::delete('/remove/seasons/{team}', [SeasonController::class, 'destroy']);
    Route::put('/update/seasons/{team}', [SeasonController::class, 'update']);

    //matches
    Route::get('/matches', [AdminController::class, 'matches'])->name('matches');
    Route::get('/get/matches/data', [MatcheController::class, 'data']);
    Route::get('/get/matches/videos/{matche}', [MatcheController::class, 'getVideos']);
    Route::get('/get/matches/count', [MatcheController::class, 'count']);
    Route::get('/get/matches/search', [MatcheController::class, 'search']);
    Route::post('/store/matches', [MatcheController::class, 'store']);
    Route::delete('/remove/matches/{matche}', [MatcheController::class, 'destroy']);
    Route::put('/update/matches/{matche}', [MatcheController::class, 'update']);
    Route::get('/update/matches/active/{matche}', [MatcheController::class, 'changeActive']);
    Route::delete('/remove/matches/videos/{matche}', [MatcheController::class, 'videoDestroy']);

    // notifications
    Route::get('/notifications', [AdminController::class, 'notifications'])->name('notifications');
    Route::post('/notifications', [NotificationsController::class, 'send'])->name('notifications.send');
    Route::get('/get/notifications/data', [NotificationsController::class, 'data']);
    Route::get('/get/notifications/search', [NotificationsController::class, 'search']);
    Route::post('/store/notifications', [NotificationsController::class, 'store']);
    Route::delete('/remove/notifications/{matche}', [NotificationsController::class, 'destroy']);
    Route::put('/update/notifications/{matche}', [NotificationsController::class, 'update']);

    //users
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/get/users/data', [UserController::class, 'data']);
    Route::get('/get/users/count', [UserController::class, 'count']);
    Route::get('/get/users/search', [UserController::class, 'search']);
    Route::post('/store/users', [UserController::class, 'store']);
    Route::delete('/remove/users/{user}', [UserController::class, 'destroy']);
    Route::put('/update/users/{user}', [UserController::class, 'update']);

    //subscriptions
    Route::get('/subscriptions', [AdminController::class, 'subscriptions'])->name('subscriptions');
    Route::get('/get/subscriptions/data', [SubscriptionController::class, 'data']);
    Route::get('/get/subscriptions/count', [SubscriptionController::class, 'count']);
    Route::post('/store/subscriptions', [SubscriptionController::class, 'store']);
    Route::delete('/remove/subscriptions/{subscription}', [SubscriptionController::class, 'destroy']);
    Route::put('/update/subscriptions/{subscription}', [SubscriptionController::class, 'update']);
    Route::get('/update/subscriptions/active/{subscription}', [SubscriptionController::class, 'changeActive']);

    //subscriptions history
    Route::get('/get/subscriptions/history/data', [SubscriptionHistoryController::class, 'data']);
    Route::post('/store/subscriptions/history', [SubscriptionHistoryController::class, 'store']);
    Route::delete('/remove/subscriptions/history/{subscription}', [SubscriptionHistoryController::class, 'destroy']);
    Route::put('/update/subscriptions/history/{subscription}', [SubscriptionHistoryController::class, 'update']);
    Route::get('/update/subscriptions/history/active/{subscription}', [SubscriptionHistoryController::class, 'changeActive']);

    //live tv
    Route::get('/livetv', [AdminController::class, 'livetv'])->name('livetv');
    Route::get('/get/livetvs/data', [LivetvController::class, 'data']);
    Route::get('/get/livetvs/search', [LivetvController::class, 'search']);
    Route::post('/store/livetvs/image', [LivetvController::class, 'uploadImage']);
    Route::delete('/remove/livetvs/image', [LivetvController::class, 'removeImage']);
    Route::post('/store/livetvs', [LivetvController::class, 'store']);
    Route::delete('/remove/livetvs/{livetvs}', [LivetvController::class, 'destroy']);
    Route::put('/update/livetvs/{livetvs}', [LivetvController::class, 'update']);
    Route::get('/update/livetvs/featured/{livetvs}', [LivetvController::class, 'changeFeatured']);
    Route::delete('/livetv/videos/destroy/{livetvvideo}', [LivetvController::class, 'videoDestroy']);
    Route::delete('/livetv/categories/destroy/{livetvgenre}', [LivetvController::class, 'destroyGenre']);

    //categories live tv
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
    Route::get('/categories/data', [CategorieController::class, 'index']);
    Route::get('/get/categories/data', [CategorieController::class, 'data']);
    Route::post('/categories/store', [CategorieController::class, 'store']);
    Route::get('/get/categories/search', [CategorieController::class, 'search']);
    Route::post('/store/categories/image', [CategorieController::class, 'uploadImage']);
    Route::delete('/remove/categories/image', [CategorieController::class, 'removeImage']);
    Route::get('/update/categories/featured/{genre}', [CategorieController::class, 'changeFeatured']);
    // Route::post('/categories/fetch', [CategorieController::class, 'fetch']);
    Route::delete('/categories/destroy/{genre}', [CategorieController::class, 'destroy']);
    Route::put('/categories/update/{genre}', [CategorieController::class, 'update']);

    //servers
    Route::get('/servers', [AdminController::class, 'servers'])->name('servers');
    Route::get('/servers/data', [ServerController::class, 'index']);
    Route::get('/get/servers/data', [ServerController::class, 'data']);
    Route::post('/servers/store', [ServerController::class, 'store']);
    Route::delete('/servers/destroy/{server}', [ServerController::class, 'destroy']);
    Route::put('/servers/update/{server}', [ServerController::class, 'update']);
    Route::get('/get/servers/search', [ServerController::class, 'search']);
    Route::get('/update/servers/{server}', [ServerController::class, 'changeEmbed']);

    //settinges
    Route::get('/settings', [AdminController::class, 'settings'])->name('settinges');
    Route::get('/settings/data', [SettingsController::class, 'index'])->name('settings.data');
    Route::put('/update/settings', [SettingsController::class, 'update']);

    //player settinges
    Route::get('/player/settings', [AdminController::class, 'settingsPlayer'])->name('player.settings');
    Route::get('/player/settings/data', [PlayerSettingController::class, 'index'])->name('player.settings.data');
    Route::put('/player/update/settings', [PlayerSettingController::class, 'update']);

    //ad track
    Route::get('/state/ad/track',[TrackAdsDetailsController::class, 'state']);
    Route::get('/ad/tracks',  [AdminController::class, 'adTrack'])->name('ad.tracks');
    Route::get('/ad/tracks/get',  [TrackAdsDetailsController::class, 'index']);
    Route::get('/ad/tracks/get/{track}',  [TrackAdsDetailsController::class, 'show']);
    Route::post('/ad/tracks/blocked/{id}',  [TrackAdsDetailsController::class, 'addBlocked']);


     
Route::get('export-track-ads', function () {
    return Excel::download(new TrackAdsClass, 'track_ads_details.xlsx');
});

    
});

