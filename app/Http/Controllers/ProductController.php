<?php

namespace App\Http\Controllers;

use App\model\Attendance;
use App\model\Product;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Model
{

    public function validater()
    {
        return Validator::make(request()->all(), [
            'name' => 'required|string|min:1',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:0',
            'priceRentDay' => 'required|numeric|min:0',
            'priceRentHour' => 'required|numeric|min:0',
            'type' => 'nullable|string|min:2',
            'photo' => 'nullable|string',
        ]);
    }

    public function index(Request $request)
    {
        $Products = Product::orderBy('id', 'desc');
        if ($request->name) {
            $Products = $Products->where("name", 'LIKE', '%' . $request->name . '%');
        }
        $Products = $Products->paginate(10);
        return response()->json($Products, 200);
    }


    public function getAbsences(Request $request)
    {
        $Product =  Product::whereDoesntHave(
            'attendances',
            function ($q) use ($request) {
                $q->where('group_id', $request->get('group_id'))
                    ->where('date', $request->get('date'));
            }
        )->whereHas("ProductGroups", function ($q) use ($request) {
            $q->where("quotas", ">", 0)->where("group_id", $request->group_id);
        })->whereHas("groups", function ($q) use ($request) {
            $q->where('groups.id', $request->group_id);
        })->get();

        return BaseController::successData($Product, "تم جلب البيانات بنجاح");
    }

    public function getGroup()
    {
        $groups = Product::orderBy('id', 'desc')
            ->with(['groups.subj', 'groups.Client'])->paginate(10);
        return BaseController::successData($groups, "تم جلب البيانات بنجاح");
    }



    public function generate()
    {
        return response()->json($this->generateBarcodeNumber(), 200);
    }
    function generateBarcodeNumber()
    {
        $number = mt_rand(1000000000000, 9999999999999);
        if ($this->barcodeNumberExists($number)) {
            return $this->generateBarcodeNumber();
        }

        return $number;
    }

    function barcodeNumberExists($number)
    {
        return Product::where('barcode', $number)->exists();
    }

    public function getById(Request $request)
    {
        $user = Product::find($request->id);
        if ($user) {
            return BaseController::successData($user, "تم جلب البيانات بنجاح");
        }
        return BaseController::errorData($user, "السجل غير موجود");
    }


    public function store()
    {
        $validator = $this->validater();
        if ($validator->fails()) {
            return response()->json(['message' => $validator->getMessageBag(), 'data' => null], 400);
        }
        $user = Product::create($validator->validate());
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
        $Product = Product::findOrFail($request->id);
        $Product->update($request->all());
        if ($Product) {
            return response()->json(['message' => 'updated', 'data' =>  $Product], 200);
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }

    public  function deleteProduct(Request $request)
    {
        $Product = Product::destroy($request->id);
        return BaseController::successData($Product, "تمت العملية بنجاح");
    }


    //
}
