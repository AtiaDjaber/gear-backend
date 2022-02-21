<?php

namespace App\Http\Controllers;

use App\model\Facture;
use App\model\Sale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FactureController extends Model
{

    public function validater()
    {
        return Validator::make(request()->all(), [
            'montant' => 'required|numeric|gt:0|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/',
            'pay' => 'required|numeric|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/',
            'rest' => 'required|numeric|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/',
            'remise' => 'nullable|numeric|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/',
            'remark' => 'nullable|string',
            'client_id' => 'required|exists:clients,id',
        ]);
    }

    public function index(Request $request)
    {
        $Factures = Facture::with("sales")->where('client_id', $request->client_id)
            ->orderBy('id', 'desc')->paginate(10);
        return response()->json($Factures, 200);
    }

    public function getById(Request $request)
    {
        $Factures = Facture::where('client_id', $request->client_id);

        if ($request->has('from') && $request->has('to')) {
            $Factures =   $Factures->whereBetween(
                'date',
                [$request->from, $request->to]
            );
        }
        $Factures = $Factures->orderBy('id', 'desc')->paginate(10);
        if ($Factures) {
            return response()->json($Factures, 200);
        }
        return BaseController::errorData($Factures, "السجل غير موجود");
    }


    public function store()
    {
        $validator = $this->validater();
        if ($validator->fails()) {
            return response()->json(['message' => $validator->getMessageBag(), 'data' => null], 400);
        }
        $user = Facture::create($validator->validate());


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
        $Facture = Facture::find($request->id);
        $Facture->update($request->all());
        if ($Facture) {
            return response()->json(['message' => 'updated', 'data' =>  $Facture], 200);
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }

    public  function remove(Request $request)
    {
        $Facture = Facture::destroy($request->id);
        return BaseController::successData($Facture, "تمت العملية بنجاح");
    }
}
