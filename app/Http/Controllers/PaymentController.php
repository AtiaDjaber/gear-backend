<?php

namespace App\Http\Controllers;

use App\model\Payment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Model
{

    public function validater()
    {
        return Validator::make(request()->all(), [
            'price' => 'required|numeric|gt:0|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/',
            'date' => 'required|date',
            'client_id' => 'required|exists:clients,id',
        ]);
    }

    public function index(Request $request)
    {
        $Payments = Payment::where('client_id', $request->client_id)
            ->orderBy('id', 'desc')->paginate(10);
        return response()->json($Payments, 200);
    }

    public function getById(Request $request)
    {
        $payments = Payment::where('client_id', $request->client_id);
        if ($request->has('from') && $request->has('to')) {
            $payments =   $payments->whereBetween(
                'date',
                [$request->from, $request->to]
            );
        }
        $payments = $payments->orderBy('id', 'desc')->paginate(10);
        if ($payments) {
            return response()->json($payments, 200);
        }
        return BaseController::errorData($payments, "السجل غير موجود");
    }


    public function store()
    {

        $validator = $this->validater();
        if ($validator->fails()) {
            return response()->json(['message' => $validator->getMessageBag(), 'data' => null], 400);
        }
        $user = Payment::create($validator->validate());
        if ($user) {
            return response()->json(['message' => 'Created', 'data' => $user], 200);
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }

    public function put(Request $request)
    {
        $validator = $this->validater();
        if ($validator->fails()) {
            return response()->json(['message' => $validator->getMessageBag(), 'data' => null], 400);
        }
        $Payment = Payment::find($request->id);
        $Payment->update($request->all());
        if ($Payment) {
            return response()->json(['message' => 'updated', 'data' =>  $Payment], 200);
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }

    public  function remove(Request $request)
    {
        $Payment = Payment::destroy($request->id);
        return BaseController::successData($Payment, "تمت العملية بنجاح");
    }
}
