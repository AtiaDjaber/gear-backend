<?php

namespace App\Http\Controllers;

use App\model\Expense;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Model
{

    public function validater()
    {
        return Validator::make(request()->all(), [
            'name' => 'required|string|min:1|max:100',
            'price' => 'required|min:0',
            'remarque' => 'nullable|string',
            'date' => 'required|date',
        ]);
    }

    public function index()
    {
        $Expenses = Expense::orderBy('id', 'desc')->paginate(10);
        return BaseController::successData($Expenses, "تم جلب البيانات بنجاح");
    }



    public function getById(Request $request)
    {
        $user = Expense::orderBy('id', 'desc')->find($request->id);
        if ($user) {
            return BaseController::successData($user, "تم جلب البيانات بنجاح");
        }
        return BaseController::errorData($user, "السجل غير موجود");
    }

    public function store()
    {
        $validator = $this->validater();
        if ($validator->fails())
            return response()->json(['message' => $validator->getMessageBag(), 'data' => null], 400);
        // //
        $user = Expense::create($validator->validate());
        if ($user)
            return response()->json(['message' => 'Created', 'data' => $user], 200);

        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }

    public function put(Request $request)
    {
        $validator = $this->validater();
        if ($validator->fails()) {
            return response()->json(['message' => $validator->getMessageBag(), 'data' => null], 400);
        }
        $Expense = Expense::findOrFail($request->id);
        $Expense->update($request->all());
        if ($Expense) {
            return response()->json(['message' => 'updated', 'data' =>  $Expense], 200);
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }

    public  function remove(Request $request)
    {
        $Expense = Expense::destroy($request->id);
        return BaseController::successData($Expense, "تمت العملية بنجاح");
    }




    public function getExpansesAnalytic(Request $request)
    {
        $Expenses = Expense::select(
            DB::raw("SUM(expenses.price) as 'total'")
        )->whereBetween(
            "date",
            [
                $request->from, $request->to
            ]
        )->first();
        if ($Expenses) {
            return response()->json($Expenses, 200);
        }
        return response()->json(null, 400);
    }


    //
}
