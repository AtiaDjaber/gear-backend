<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController as BaseController;
use App\Driver;
use App\model\Orders;
use Illuminate\Http\Request;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

class DriverController extends Controller
{
    public function index()
    {

        $drivers = Driver::all();
        return BaseController::successData($drivers, "تم جلب البيانات بنجاح");
    }




    public function rateDriver(Request $request)
    {


        $order = Orders::where("id", $request->order_id)->update([
            "rate" => $request->rate,
        ]);
        if ($order) {

            $driverData = Driver::findOrFail($request->driver_id);
            $lastRate = $driverData->rate;
            $numberRate = $driverData->number_rates;
            $newRate = (($lastRate * $numberRate) + $request->rate) /  ($numberRate + 1);

            $driver = Driver::where("id", $request->driver_id)->update([
                "rate" => $newRate,
                "number_rates" => $numberRate + 1,
            ]);

            if ($driver) {
                return response()->json(['message' => 'updated', 'rate' =>  $newRate], 200);
            }
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }

    public static function sendNotification($token, $title, $body)
    {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);

        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($body)
            ->setSound('default');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['a_data' => 'my_data']);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();


        $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);

        $downstreamResponse->numberSuccess();


        // $SERVER_API_KEY =
        //     "AAAA7giRrx0:APA91bFOZ2ZzFBF1TF2E0Y4veEQyuQyXgPd8EJVX_L_ixi0k6D49CiwHZ0lYcFBiwoM8PvmPisNG5QtVhT79-PWAAq--iuXYQFYc1bKnt6Vy44OBxRrXgqptHbguWcTSVZcoVnUBJF9g";

        // $data = [

        //     "registration_ids" => [
        //         $token
        //     ],

        //     "notification" => [
        //         "title" => $title,
        //         "body" => $body,
        //         "sound" => "default" // required for sound on ios

        //     ],

        // ];

        // $dataString = json_encode($data);

        // $headers = [
        //     'Authorization: key=' . $SERVER_API_KEY,
        //     'Content-Type: application/json',
        // ];

        // $ch = curl_init();

        // curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        // $response = curl_exec($ch);
    }
}
