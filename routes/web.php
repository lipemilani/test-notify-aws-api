<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/send-notification', [NotificationController::class, 'sendNotification']);

