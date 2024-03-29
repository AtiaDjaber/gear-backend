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
            'montant' => 'required|numeric|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/',
            'pay' => 'required|numeric|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/',
            'rest' => 'required|numeric|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/',
            'remise' => 'nullable|numeric|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/',
            'remark' => 'nullable|string',
            'client_id' => 'required|exists:clients,id',
        ]);
    }

    public function index(Request $request)
    {
        $Factures = Facture::with(["sales.product", "client"]);

        if ($request->from) {
            $Factures = $Factures->where('created_at', ">=", $request->from);
        }
        if ($request->to) {
            $Factures = $Factures->where('created_at', "<=", $request->to);
        }
        if ($request->name) {
            $Factures = $Factures->whereHas("sales", function ($query) use ($request) {
                $query->where('sales.name', "LIKE", "%" . $request->name . "%");
            });
        }
        if ($request->type) {
            $Factures = $Factures->where('type', $request->type);
        }
        if ($request->client_id) {
            $Factures =   $Factures->where('client_id', $request->client_id);
        }
        // $Factures = $Factures->whereHas("sales", function ($query) use ($request) {
        //     $query->where('sales.type_table', $request->type);
        // });

        $Factures = $Factures->orderBy('id', 'desc')->paginate(10);
        return response()->json($Factures, 200);
    }



    public function getById(Request $request)
    {
        $Factures = Facture::with(["sales.product", "client"])->where('client_id', $request->client_id);

        if ($request->from) {
            $Factures = $Factures->where('created_at', ">=", $request->from);
        }
        if ($request->to) {
            $Factures = $Factures->where('created_at', "<=", $request->to);
        }
        $Factures = $Factures->orderBy('id', 'desc')->paginate(10);
        if ($Factures) {
            return response()->json($Factures, 200);
        }
        return BaseController::errorData($Factures, "السجل غير موجود");
    }

    public function  removeProductFromFactureAndReturnStock($facture_id)
    {
        if ($facture_id) {
            $listSales = Sale::where("facture_id", $facture_id)->get();
            foreach ($listSales as $sale) {
                Sale::destroy($sale->id);
                $product = Product::where('id', $sale->product_id)->first();
                $newQuotas =  $product->quantity + $sale->quantity;
                Product::where('id', $sale->product_id)->update(['quantity' => $newQuotas]);
            }
        }
    }

    public function store(Request $request)
    {
        $validator = $this->validater();
        if ($validator->fails())
            return response()->json(['message' => $validator->getMessageBag(), 'data' => "validation"], 400);

        try {
            DB::beginTransaction();

            $facture = Facture::updateOrCreate(
                ['id' => $request["id"]],
                [
                    "client_id" => $request["client_id"],
                    "montant" => $request["montant"],
                    "pay" => $request["pay"],
                    "rest" => $request["rest"],
                    "remise" => $request["remise"],
                    "remark" => $request["remark"],
                ]
            );

            $this->removeProductFromFactureAndReturnStock($request->id);

            foreach ($request->sales as $e) {
                // return response()->json(['message' => 'Created', 'data' => $e], 200);
                $id = null;
                if (array_key_exists('id', $e)) {
                    $id = $e["id"];
                }
                $sale = Sale::updateOrCreate(
                    ['id' => $id],
                    [
                        "name" => $e["name"],
                        "quantity" => $e["quantity"],
                        "total" => $e["total"],
                        "client_id" => $facture->client_id,
                        "product_id" => $e["product_id"],
                        "facture_id" => $facture->id,
                        "duration" => $e["duration"],
                        "priceRentHour" => $e["priceRentHour"],
                        "priceRentDay" => $e["priceRentDay"],
                        "type" => $e["type"]
                    ]
                );

                $product = Product::where('id', $e["product_id"])->first();
                $newQuotas =  $product->quantity - $e["quantity"];
                Product::where('id', $e["product_id"])->update(['quantity' => $newQuotas]);

                $data[] = $sale;
            }

            // $client = Client::find($request->client_id);
            // $client->update(["montant" => $client->montant + ($facture->montant - $oldMontant)]);
            $facture
                =
                Facture::with(["sales.product", "client"])->find($facture->id);
            DB::commit();

            return response()->json(['message' => 'Created', 'data' => $facture], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Transaction error facture', 'data' => $e], 500);
        }
    }

    public function closeFacture(Request $request)
    {
        // $validator = $this->validater();
        // if ($validator->fails()) {
        //     return response()->json(['message' => $validator->getMessageBag(), 'data' => null], 400);
        // }

        try {

            DB::beginTransaction();

            $facture = Facture::with('sales')->find($request->id);
            foreach ($facture->sales as $key => $sale) {
                if ($sale['returned'] == false) {
                    $product = Product::where('id', $sale['product_id'])->first();
                    $newQuotas =  $product->quantity + $sale['quantity'];
                    $product->update(['quantity' => $newQuotas,'returned' => true]);
                }
            }
            $facture->update(["type" => "history"]);
            $client = Client::find($facture->client_id);
            $client->update([
                "montant" => $client->montant + ($facture->rest)
            ]);

            DB::commit();

            return response()->json(['message' => 'Updated', 'data' => $client], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Transaction error facture', 'data' => $e], 500);
        }
    }

    public function openFacture(Request $request)
    {
        // $validator = $this->validater();
        // if ($validator->fails()) {
        //     return response()->json(['message' => $validator->getMessageBag(), 'data' => null], 400);
        // }

        try {
            DB::beginTransaction();

            $facture = Facture::find($request->id);
            $facture->update([
                "type" => "kira"
            ]);
            $client = Client::find($facture->client_id);
            $client->update([
                "montant" => $client->montant - ($facture->rest)
            ]);

            DB::commit();

            return response()->json(['message' => 'Created', 'data' => $client], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Transaction error facture', 'data' => $e], 500);
        }
    }

    public  function remove(Request $request)
    {
        DB::beginTransaction();
        try {
            $facture = Facture::find($request->id);
            if ($facture->type == "history") {
                $client = Client::find($facture->client_id);
                $client->update(["montant" => $client->montant - $facture->rest]);
            }
            $deletedFacture = Facture::destroy($request->id);

            $this->removeProductFromFactureAndReturnStock($request->id);

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
