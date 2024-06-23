<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\WeatherController;
use App\Http\Controllers\TranslationController;
use App\Http\Controllers\GeocodingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

// Route for the root endpoint
$router->get('/', function () use ($router) {
    return $router->app->version();
    // Returns the version of the Lumen application.
});

// Weather APIs
$router->group(['prefix' => 'weather', 'middleware' => ['auth', 'logUserActivity']], function () use ($router) {
    $router->get('/current', ['uses' => 'WeatherController@getCurrentWeather']);
    // Endpoint for getting current weather data

    $router->get('/forecast', ['uses' => 'WeatherController@getForecast']);
    // Endpoint for getting weather forecast data

    $router->get('/history', ['uses' => 'WeatherController@getHistory']);
    // Endpoint for getting weather history data
});

// Translation APIs
$router->group(['prefix' => 'translation', 'middleware' => ['auth', 'logUserActivity']], function () use ($router) {
    $router->post('/detect', ['uses' => 'TranslationController@detect']);
    // Endpoint for language detection

    $router->get('/languages', ['uses' => 'TranslationController@getLanguages']);
    // Endpoint for retrieving supported languages

    $router->post('/translate', ['uses' => 'TranslationController@translate']);
    // Endpoint for translation
});

// Geocoding APIs
$router->group(['prefix' => 'geocoding', 'middleware' => ['auth', 'logUserActivity']], function () use ($router) {
    $router->get('/by-lat-lng', ['uses' => 'GeocodingController@getByLatLng']);
    // Endpoint for geocoding by latitude and longitude

    $router->get('/by-place-id', ['uses' => 'GeocodingController@getByPlaceId']);
    // Endpoint for geocoding by place ID

    $router->get('/by-address', ['uses' => 'GeocodingController@getByAddress']);
    // Endpoint for geocoding by address
});

// CRUD operations for users
$router->get('/users', 'UserController@index');
$router->post('/users', 'UserController@store');
$router->get('/users/{id}', 'UserController@show');
$router->put('/users/{id}', 'UserController@update');
$router->delete('/users/{id}', 'UserController@destroy');

// User registration and login endpoints
$router->post('register', 'AuthController@register');
$router->post('login', 'AuthController@login');
