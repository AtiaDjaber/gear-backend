<?php

namespace App\Http\Controllers;

use App\Http\Controllers\DriversController as DriversController;
use App\Http\Controllers\BaseController as BaseController;
use App\model\Offer;
use App\model\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;




class OfferController extends Controller
{
    public function checkFields()
    {
        return Validator::make(request()->all(), [

            'driver_id' => 'required|exists:drivers,id',
            'orders_id' => 'required|exists:orders,id',
            'price' => 'min:1',

        ]);
    }

    public function store(Request $request)
    {
        $validator = $this->checkFields();
        if ($validator->fails()) {
            return response()->json(['message' => $validator->getMessageBag(), 'data' => null], 400);
        }
        $offer = Offer::create($validator->validate());
        if ($offer) {
            $this->getUserByOrdersId($request->orders_id, $request->price); //
            return response()->json(['message' => 'Created', 'data' => $offer], 200);
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }

    public function getUserByOrdersId($order_id, $price)
    {
        $orders = Orders::leftJoin('users', 'orders.user_id', 'users.id')
            ->leftJoin('drivers', 'orders.driver_id', 'drivers.id')
            ->select(
                'orders.*',
                'users.id as user_id',
                'users.tel as telClient',
                'users.token',
                'users.name as userName',
                'drivers.name as driverName'
            )->where("Orders.id", "=", $order_id)->get();
        foreach ($orders as $order) {
            DriverController::sendNotification($order["token"], "لديك عرض توصيل جديد", $order["driverName"] . " | " . $price . " DA ");
        }
        // $orders->driverName . "المبلغ DA " . $price
    }

    public function index(Request $request)
    {
        $offers = Offer::leftJoin('drivers', 'offer.driver_id', 'drivers.id')
            ->select(
                'offer.*',
                'drivers.id as driver_id',
                'drivers.name as driverName',
                'drivers.rate as driverRate',
                'drivers.tel as driverTel'
            )->where('orders_id', '=', $request->orders_id)->get();
        return BaseController::successData($offers, "تم جلب البيانات بنجاح");
    }
}
