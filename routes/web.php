<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {

    $SERVER_API_KEY ="AAAA9uZfwq0:APA91bHwqL0Jmv8V8X0oEhxPHkPde25jlgkWnvlwM0owdBNG_01vGl5u-PTB1jiP8fRTLxWEyfErlZHXkEFPi8E0t-CxMy5JHDTzUg4tWZtxHskNGB7hBCl3dtidk3QD7PHDACNN9xTJ";

    $token_1 = 'cQiV4r5wTxax2wZ2XU59U4:APA91bHlK1BlhQo3ar8A0wHp5i5J7_u2q3X8oAlHOncD8mYhKG1xUiULqS5Vq60RL4yCtxhoDnx6Hgu3IXePAzaOULICOgHISGO-faLe3NfR1tPdWWlTX66CGIGVW-gAlimu4agRmqqD';

    $data = [
        "registration_ids" => [
            $token_1
        ],

        "notification" => [

            "title" => 'Welcome',

            "body" => 'Description',

            "sound" => "default" // required for sound on ios

        ],

    ];

    $dataString = json_encode($data);

    $headers = [

        'Authorization: key=' . $SERVER_API_KEY,

        'Content-Type: application/json',

    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

    curl_setopt($ch, CURLOPT_POST, true);

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

    $response = curl_exec($ch);

    dd($response);
});
