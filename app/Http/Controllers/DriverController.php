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
use Illuminate\Support\Facades\Validator;
use LaravelFCM\Facades\FCM as FacadesFCM;
use League\CommonMark\Block\Element\Document;

class DriverController extends Controller
{
    public function validater()
    {
        return Validator::make(request()->all(), [
            'tel' => 'required|string|min:10|max:16',
        ]);
    }

    public function validaterName()
    {
        return Validator::make(request()->all(), [
            'name' => 'required|string|min:4|max:25',
            'id' => 'required'
        ]);
    }

    public function store(Request $request)
    {
        $driverFound = Driver::where("tel", "=", $request->get("tel"))->first();

        if ($driverFound) {
            return response()->json(['message' => 'Found', 'data' => $driverFound], 200);
        }
        $validator = $this->validater();
        if ($validator->fails()) {
            return response()->json(['message' => $validator->getMessageBag(), 'data' => null], 400);
        }
        $driver = Driver::create($validator->validate());
        if ($driver) {
            return response()->json(['message' => 'Created', 'data' => $driver], 200);
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }


    public function index()
    {
        $drivers = Driver::all();
        // foreach ($drivers as $driver) {
        //     $this->sendNotification($driver["token"], "يوجد طلب جديد", " انقر هنا ");
        // }
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

    public function getById(Request $request)
    {

        $driver = Driver::find($request->id);
        if ($driver) {
            return BaseController::successData($driver, "تم جلب البيانات بنجاح");
        }
        return BaseController::errorData($driver, "السجل غير موجود");
    }


    public function updateToken(Request $request)
    {
        $driver = Driver::where("id", $request->id)->update(["token" => $request->token]);
        if ($driver) {
            return response()->json(['message' => 'updated', 'data' => $driver], 200);
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }

    public function avatar(Request $request)
    {
        if (!$request->hasFile('photo'))
            return response()->json(['upload_file_not_found'], 400);

        $file = $request->file('photo');

        if (!$file->isValid())
            return response()->json(['invalid_file_upload'], 400);

        $image = date('Y-m-d H:i:s') . $file->getClientOriginalName();
        $path = public_path() . '/uploads/profile/';
        $file->move($path, $image);

        $driver = Driver::where("id", $request->id)->update(["photo" => $image]);
        if ($driver) {
            return response()->json(['message' => 'updated', 'data' => $image], 200);
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }

    public function updateName(Request $request)
    {
        $validator = $this->validaterName();
        if ($validator->fails()) {
            return response()->json(['message' => $validator->getMessageBag(), 'data' => null], 400);
        }
        $driver = Driver::where("id", $request->id)->update(["name" => $request->name]);
        if ($driver) {
            return response()->json(['message' => 'updated', 'data' =>  $driver], 200);
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


        $downstreamResponse = FacadesFCM::sendTo($token, $option, $notification, $data);

        $downstreamResponse->numberSuccess();
    }
}
