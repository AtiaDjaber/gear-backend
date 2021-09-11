<?php

namespace App\Http\Controllers;

use App\Driver;
use App\model\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\DriverController as DriverController;

class OrdersController extends Controller
{
    public function checkFields()
    {
        return Validator::make(request()->all(), [
            'fromLatitude' => 'required',
            'fromLongutide' => 'required',
            'toLatitude' => 'required',
            'toLongutide' => 'required',
            'user_id' => 'required|exists:users,id',
            'status' => 'required',
            'photo' => 'required',
            'addressFrom' => 'nullable',
            'addressTo' => 'nullable',
            "typeTransport" => 'required',
            'description' => 'nullable',
            'distance' => 'required',
            'duration' => 'required',
        ]);
    }

    public function index()
    {
        $orders = Orders::leftJoin('users', 'orders.user_id', 'users.id')
            ->leftJoin('drivers', 'orders.driver_id', 'drivers.id')
            ->select(
                'orders.*',
                'drivers.id as driver_id',
                'drivers.name as driverName',
                'drivers.tel as driverTel',
                'users.id as user_id',
                'users.tel as telClient',
                'users.email',
                'users.name as userName'

            )->orderBy('orders.id', 'desc')->paginate(30);
        return BaseController::successData($orders, "تم جلب البيانات بنجاح");
    }

    public function store()
    {
        $validator = $this->checkFields();
        if ($validator->fails()) {
            return response()->json(['message' => $validator->getMessageBag(), 'data' => null], 400);
        }
        $order = Orders::create($validator->validate());
        if ($order) {
            $drivers = Driver::all();
            foreach ($drivers as $driver) {
                DriverController::sendNotification($driver["token"], "يوجد طلب جديد", " انقر هنا ");
            }

            return response()->json(['message' => 'Created', 'data' => $order], 200);
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }
    public function getOrdersByUserId($user_id)
    {
        $orders = Orders::leftJoin('users', 'orders.user_id', 'users.id')
            ->leftJoin('drivers', 'orders.driver_id', 'drivers.id')
            ->select(
                'orders.*',
                'drivers.id as driver_id',
                'drivers.name as driverName',
                'drivers.tel as driverTel',
                'users.id as user_id',
                'users.tel as telClient',
                'users.email',
                'users.name as userName'

            )->where("user_id", "=", $user_id)->paginate(50);
        return BaseController::successData($orders, "تم جلب البيانات بنجاح");
    }

    public function getOrdersByDriverId($driver_id)
    {
        $orders = Orders::leftJoin('users', 'orders.user_id', 'users.id')
            ->leftJoin('drivers', 'orders.driver_id', 'drivers.id')
            ->select(
                'orders.*',
                'drivers.id as driver_id',
                'drivers.name as driverName',
                'drivers.tel as driverTel',
                'users.id as user_id',
                'users.tel as telClient',
                'users.email',
                'users.name as userName'

            )->where("driver_id", "=", $driver_id)->paginate(50);
        return BaseController::successData($orders, "تم جلب البيانات بنجاح");
    }

    public function update(Request $request)
    {

        $orders = Orders::where("id", $request->id)->update([
            "price" => $request->price,
            "driver_id" => $request->driver_id,
            "status" => $request->status
        ]);

        if ($orders) {
            $driver = Driver::findOrFail($request->driver_id);
            $body =  " الطلب رقم " . $request->id;
            $title = "تمت الموافقة على عرضك";
            DriverController::sendNotification($driver["token"], $title, $body);

            return response()->json(['message' => 'updated', 'data' => $orders], 200);
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }
    //  'cQiV4r5wTxax2wZ2XU59U4:APA91bHlK1BlhQo3ar8A0wHp5i5J7_u2q3X8oAlHOncD8mYhKG1xUiULqS5Vq60RL4yCtxhoDnx6Hgu3IXePAzaOULICOgHISGO-faLe3NfR1tPdWWlTX66CGIGVW-gAlimu4agRmqqD';

}
