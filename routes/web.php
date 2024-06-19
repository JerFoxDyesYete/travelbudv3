<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\WeatherController;
use App\Http\Controllers\TranslationController;
use App\Http\Controllers\GeocodingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// Weather APIs
$router->group(['prefix' => 'weather', 'middleware' => ['auth', 'logUserActivity']], function () use ($router) {
    $router->get('/current', ['uses' => 'WeatherController@getCurrentWeather']);
    $router->get('/forecast', ['uses' => 'WeatherController@getForecast']);
    $router->get('/history', ['uses' => 'WeatherController@getHistory']);
});

// Translation APIs
$router->group(['prefix' => 'translation', 'middleware' => ['auth', 'logUserActivity']], function () use ($router) {
    $router->post('/detect', ['uses' => 'TranslationController@detect']);
    $router->get('/languages', ['uses' => 'TranslationController@getLanguages']);
    $router->post('/translate', ['uses' => 'TranslationController@translate']);
});

// Geocoding APIs
$router->group(['prefix' => 'geocoding', 'middleware' => ['auth', 'logUserActivity']], function () use ($router) {
    $router->get('/by-lat-lng', ['uses' => 'GeocodingController@getByLatLng']);
    $router->get('/by-place-id', ['uses' => 'GeocodingController@getByPlaceId']);
    $router->get('/by-address', ['uses' => 'GeocodingController@getByAddress']);
});


//crud
$router->get('/users', 'UserController@index');
$router->post('/users', 'UserController@store');
$router->get('/users/{id}', 'UserController@show');
$router->put('/users/{id}', 'UserController@update');
$router->delete('/users/{id}', 'UserController@destroy');


//registration
$router->post('register', 'AuthController@register');
$router->post('login', 'AuthController@login');
