<?php

namespace App\Http\Controllers;

use App\model\Product;
use App\model\Sale;
use App\model\Subj;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SaleController extends Model
{

    public function validater()
    {
        return Validator::make(request()->all(), [
            'product_id.*' => 'required|exists:products,id',
            'facture_id.*' => 'required|exists:factures,id',
            'client_id.*' => 'required|exists:clients,id',
            'name.*' => 'required|string|min:2',
            'quantity.*' => 'required|numeric',
            'price.*' => 'required|numeric|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/',
            'priceRentHour.*' => 'required|numeric|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/',
            'priceRentDay.*' => 'required|numeric|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/',
            'type.*' => 'required|string'
        ]);
    }

    public function index(Request $request)
    {
        $Sales = Sale::orderBy('id', 'desc');
        if ($request->name) {
            $Sales = $Sales->where(function ($q) use ($request) {
                $q->orWhere('name', 'LIKE', '%' . $request->name . '%');
            });
        }
        if ($request->client_id != null)
            $Sales = $Sales->where('client_id', '=', $request->client_id);
        if ($request->product_id != null)
            $Sales = $Sales->where('product_id', '=', $request->product_id);
        if ($request->product_id != null)
            $Sales = $Sales->where('facture_id', '=', $request->facture_id);
        if ($request->from != null)
            $Sales = $Sales->where('date', '>=', $request->from);
        if ($request->to != null)
            $Sales = $Sales->where('date', '<=', $request->to);

        $Sales = $Sales->paginate(10);
        return response()->json($Sales, 200);
    }

    public function store(Request $request)
    {
        $validator = $this->validater();
        if ($validator->fails()) {
            return response()->json(['message' => $validator->getMessageBag(), 'data' => "validation error"], 400);
        }
        $data = [];

        try {
            DB::beginTransaction();

            foreach ($request->all() as $e) {
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
                        "client_id" => $e["client_id"],
                        "product_id" => $e["product_id"],
                        "facture_id" => $e["facture_id"],
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
            DB::commit();

            return response()->json(['message' => 'Created', 'data' => $data], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Transaction error sales', 'data' => $e], 500);
        }
    }

    public function put(Request $request)
    {
        $validator = $this->validater();
        if ($validator->fails()) {
            return response()->json(['message' => $validator->getMessageBag(), 'data' => null], 400);
        }
        $Sale = Sale::findOrFail($request->id);
        $Sale->update($request->all());
        if ($Sale) {
            return response()->json(['message' => 'updated', 'data' =>  $Sale], 200);
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }


    public function statusSale(Request $request)
    {

        try {

            DB::beginTransaction();

            $sale = Sale::find($request->id);
            if ($sale->status == false) {
                $sale->update(["returned" => true]);

                $product = Product::find($sale->product_id);
                $newQuotas =  $product->quantity + $sale->quantity;
                Product::where('id', $sale->product_id)->update(['quantity' => $newQuotas]);
            }
            DB::commit();

            return response()->json(['message' => 'Updated', 'data' => $sale], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Transaction error facture', 'data' => $e], 500);
        }
    }


    public function remove(Request $request)
    {
        DB::beginTransaction();

        try {
            $Sale =  Sale::findOrFail($request->id);
            $Sale->delete();

            $product = Product::find($Sale->product_id);
            $newQuotas =  $product->quantity + $Sale->quantity;
            Product::where('id', $Sale->product_id)->update(['quantity' => $newQuotas]);

            DB::commit();
            return BaseController::successData($Sale, "تمت العملية بنجاح");
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error ', 'data' => $e], 500);
        }
    }
    //
}
