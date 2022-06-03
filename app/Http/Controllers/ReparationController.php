<?php

namespace App\Http\Controllers;

use App\model\Client;
use App\model\Facture;
use App\model\Product;
use App\model\Reparation;
use App\model\Sale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReparationController extends Model
{

    public function validater()
    {
        return Validator::make(request()->all(), [
            'montant' => 'required|numeric|gt:0|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/',
            'remark' => 'nullable|string',
            'facture_id' => 'required|exists:factures,id',
            'product_id' => 'required|exists:products,id',
            'date' => 'required',
            'quantity' => 'required|numeric|gt:0|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/',
        ]);
    }

    public function index(Request $request)
    {
        $Reparations = Reparation::with(["product"]);
        if ($request->has('from') && $request->has('to')) {
            $Reparations =   $Reparations->whereBetween(
                'created_at',
                [$request->from, $request->to]
            );
        }
        if ($request->has('client_id')) {
            $Reparations = $Reparations->whereHas("facture", function ($query) use ($request) {
                $query->where('factures.client_id', $request->client_id);
            });
        }
        if ($request->has('facture_id')) {
            $Reparations = $Reparations->where('facture_id', $request->facture_id);
        }
        $Reparations = $Reparations->orderBy('id', 'desc')->paginate(10);
        return response()->json($Reparations, 200);
    }

    public function getById(Request $request)
    {
        $Reparations = Reparation::where('client_id', $request->client_id);

        if ($request->has('from') && $request->has('to')) {
            $Reparations =   $Reparations->whereBetween(
                'created_at',
                [$request->from, $request->to]
            );
        }
        $Reparations = $Reparations->orderBy('id', 'desc')->paginate(10);
        if ($Reparations) {
            return response()->json($Reparations, 200);
        }
        return BaseController::errorData($Reparations, "السجل غير موجود");
    }


    public function store(Request $request)
    {
        $validator = $this->validater();
        if ($validator->fails())
            return response()->json(['message' => $validator->getMessageBag(), 'data' => null], 400);

        DB::beginTransaction();

        try {

            $Reparation = Reparation::create($validator->validate());
            $client = Client::find($request->client_id);
            $client->update(["montant" => $client->montant + $Reparation->montant]);
            DB::commit();

            return response()->json(['message' => 'Created', 'data' => $Reparation, "montant" => $client], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error ', 'data' => $e], 500);
        }
    }

    public function put(Request $request)
    {
        $validator = $this->validater();
        if ($validator->fails()) {
            return response()->json(['message' => $validator->getMessageBag(), 'data' => null], 400);
        }
        $Reparation = Reparation::find($request->id);
        $Reparation->update($request->all());
        if ($Reparation) {
            return response()->json(['message' => 'updated', 'data' =>  $Reparation], 200);
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }

    public  function remove(Request $request)
    {
        DB::beginTransaction();
        try {
            $reparation = Reparation::find($request->id);
            $facture = Facture::find($reparation->facture_id);

            $client = Client::find($facture->client_id);
            $client->update(["montant" => $client->montant - $reparation->montant]);
            Reparation::destroy($request->id);

            DB::commit();

            return BaseController::successData([
                "montant" => $client->montant,
                "id" => $request->id
            ], "تمت العملية بنجاح");
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error ', 'data' => $e], 500);
        }
    }
}
