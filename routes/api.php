<?php

use Illuminate\Http\Request;
use Webpatser\Countries\CountriesFacade as Countries;
use App\Genre;
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

$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {

    /*
     |--------------------------------------------------------------------------
     | Nomenclators
     |--------------------------------------------------------------------------
     */
    $api->get('/countries', ['as' => 'countries.index', 'uses' => function () {
        return Countries::all()->toArray();
    }]);

    $api->get('/genres', ['as' => 'genres.index', 'uses' => function () {
        return Genre::all()->toArray();
    }]);

    /*
     |--------------------------------------------------------------------------
     | Films
     |--------------------------------------------------------------------------
     */
    $api->get('/films', ['as' => 'films.index', 'uses' => 'App\Http\Controllers\FilmController@index']);

    $api->post('/films', ['as' => 'films.store', 'uses' => 'App\Http\Controllers\FilmController@store']);

    $api->put('/films/{id}', ['as' => 'films.update', 'uses' => 'App\Http\Controllers\FilmController@update']);

    $api->delete('/films/{id}', ['as' => 'films.destroy', 'uses' => 'App\Http\Controllers\FilmController@destroy']);

    $api->get('/films/{slug}', ['as' => 'films.slug', 'uses' => 'App\Http\Controllers\FilmController@slug']);

    /*
     |--------------------------------------------------------------------------
     | Users
     |--------------------------------------------------------------------------
     */
    $api->post('/register', ['as' => 'users.register', 'uses' => 'App\Http\Controllers\UserController@register']);

    $api->post('/login', ['as' => 'users.login', 'uses' => 'App\Http\Controllers\UserController@login']);

    $api->post('/logout', ['as' => 'users.logout', 'uses' => 'App\Http\Controllers\UserController@logout']);

});

