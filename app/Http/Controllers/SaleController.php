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
            'priceRent.*' => 'required|numeric|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/',
            'type.*' => 'required|string',

            // 'date' => 'required|date'
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
        // foreach ($request->all() as $sale) {
        $validator = $this->validater();
        if ($validator->fails()) {
            return response()->json(['message' => $validator->getMessageBag(), 'data' => null], 400);
        }
        $data = [];
        // foreach ($request->all() as $e) {


        //     $sale = Sale::create(
        //         [
        //             "name" => $e["name"],
        //             "quantity" => $e["quantity"],
        //             "price" => $e["price"],
        //             "client_id" => $e["client_id"],
        //             "product_id" => $e["product_id"],
        //             "facture_id" => $e["facture_id"],
        //             "priceRent" => $e["priceRent"], "type" => $e["type"],
        //         ]
        //     );
        //     $data[] = $sale;
        //     if ($sale) {

        //         // $product = Product::where('id', $sale->product_id)->first();

        //         // $newQuotas =  $product->quantity - $sale->quantity;
        //         // Product::where('id', $sale->product_id)
        //         //     ->update(['quantity' => $newQuotas]);
        //     }
        // }
        // return response()->json(['message' => 'Created', 'data' => $data], 200);

        DB::beginTransaction();
        try {
            foreach ($request->all() as $e) {


                $sale = Sale::create(
                    [
                        "name" => $e["name"],
                        "quantity" => $e["quantity"],
                        "price" => $e["price"],
                        "client_id" => $e["client_id"],
                        "product_id" => $e["product_id"],
                        "facture_id" => $e["facture_id"],
                        "priceRent" => $e["priceRent"],
                        "type" => $e["type"],
                    ]
                );

                $product = Product::where('id', $sale->product_id)->first();

                $newQuotas =  $product->quantity - $sale->quantity;
                Product::where('id', $sale->product_id)
                    ->update(['quantity' => $newQuotas]);

                $data[] = $sale;
            }
            DB::commit();

            return response()->json(['message' => 'Created', 'data' => $data], 200);
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
        $Sale = Sale::findOrFail($request->id);
        $Sale->update($request->all());
        if ($Sale) {
            return response()->json(['message' => 'updated', 'data' =>  $Sale], 200);
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }

    public function remove(Request $request)
    {
        DB::beginTransaction();

        try {
            $Sale =  Sale::findOrFail($request->id);
            $Sale->delete();

            $ProductGroup = Product::where('id', $Sale->Product_id)->first();

            $newQuotas =  $ProductGroup->quantity + 1;
            Product::where('id', $Sale->Product_id)->update(['quantity' => $newQuotas]);


            DB::commit();
            return BaseController::successData($Sale, "تمت العملية بنجاح");
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error ', 'data' => $e], 500);
        }
    }
    //
}
