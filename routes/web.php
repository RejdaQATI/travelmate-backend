<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

Route::get('/sendgrid-api-test', function () {
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ta_cle_api_sendgrid',
        'Content-Type' => 'application/json',
    ])->post('https://api.sendgrid.com/v3/mail/send', [
        'personalizations' => [[
            'to' => [['email' => 'qati.rejda@gmail.com']],
            'subject' => 'Test d\'envoi via SendGrid API',
        ]],
        'from' => ['email' => 'qati.rejda@gmail.com', 'name' => 'Travel Mate'],
        'content' => [[
            'type' => 'text/plain',
            'value' => 'Ceci est un test d\'envoi d\'email via l\'API SendGrid.',
        ]],
    ]);

    return $response->status(); // Cela retournera le statut de la requête (200 si tout va bien)
});


Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
