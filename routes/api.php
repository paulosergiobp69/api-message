<?php
use Illuminate\Support\Facades\Route;


Route::resource('rules', 'App\Http\Controllers\Api\RegulationApiController');

Route::resource('messages', 'App\Http\Controllers\Api\MessageApiController');





