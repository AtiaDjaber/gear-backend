<?php

namespace App\Http\Controllers;

use App\model\Client;
use App\model\Facture;
use App\model\Product;
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
        $Factures = Facture::with(["products", "client"]);
        if ($request->has('from') && $request->has('to')) {
            $Factures =   $Factures->whereBetween(
                'created_at',
                [$request->from, $request->to]
            );
        }
        if ($request->has('client_id')) {
            $Factures =   $Factures->where('client_id', $request->client_id);
        }
        $Factures = $Factures->orderBy('id', 'desc')->paginate(10);
        return response()->json($Factures, 200);
    }

    public function getById(Request $request)
    {
        $Factures = Facture::with("products")->where('client_id', $request->client_id);

        if ($request->has('from') && $request->has('to')) {
            $Factures =   $Factures->whereBetween(
                'created_at',
                [$request->from, $request->to]
            );
        }
        $Factures = $Factures->orderBy('id', 'desc')->paginate(10);
        if ($Factures) {
            return response()->json($Factures, 200);
        }
        return BaseController::errorData($Factures, "السجل غير موجود");
    }


    public function store(Request $request)
    {
        $validator = $this->validater();
        if ($validator->fails())
            return response()->json(['message' => $validator->getMessageBag(), 'data' => null], 400);

        DB::beginTransaction();

        try {

            $facture = Facture::create($validator->validate());
            $client = Client::find($request->client_id);
            $client->update(["montant" => $client->montant + $facture->rest]);
            DB::commit();

            return response()->json(['message' => 'Created', 'data' => $facture], 200);
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
        $Facture = Facture::find($request->id);
        $Facture->update($request->all());
        if ($Facture) {
            return response()->json(['message' => 'updated', 'data' =>  $Facture], 200);
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }

    public  function remove(Request $request)
    {
        DB::beginTransaction();
        try {
            $facture = Facture::find($request->id);
            $client = Client::find($facture->client_id);
            $client->update(["montant" => $client->montant - $facture->rest]);
            $deletedFacture = Facture::destroy($request->id);

            $listSales = Sale::where("facture_id", $request->id)->get();
            foreach ($listSales as $sale) {
                Sale::destroy($sale->id);

                $product = Product::where('id', $sale->product_id)->first();

                $newQuotas =  $product->quantity + $sale->quantity;
                Product::where('id', $sale->product_id)
                    ->update(['quantity' => $newQuotas]);
            }

            DB::commit();

            return BaseController::successData(["montant" => $client->montant, "id" => $request->id], "تمت العملية بنجاح");
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error ', 'data' => $e], 500);
        }
    }
}
