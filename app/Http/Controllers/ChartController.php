<?php

namespace App\Http\Controllers;

use App\model\Client;
use App\model\Expense;
use App\model\Facture;
use App\model\Product;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartController extends Model
{




    public function getClientsBenifits(Request $request)
    {
        $Factures = Facture::select(
            'Factures.ClientName',
            'Factures.Client_id',
            DB::raw("SUM(Factures.ClientBenefit) as total")
        )->whereBetween(
            'created_at',
            [$request->from, $request->to]
        )->where("isPresent", true)->groupBy('Client_id')
            ->orderBy("total", "desc")->get();

        if ($Factures) {
            return response()->json($Factures, 200);
        }
        return BaseController::errorData(null, "السجل غير موجود");
    }


    public function getYearMonthChart(Request $request)
    {
        $dataset = [];
        $user = Facture::select(
            "id",
            DB::raw("(sum(factures.montant-factures.remise)) as total"),
            DB::raw("(DATE_FORMAT(created_at, '%Y-%m')) as month_year")
        )->orderBy('created_at')->where("deleted_at", null)
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
            ->get();

        if ($user) {
            $dataset["data"] = $user->pluck("total");
            $dataset["labels"] = $user->pluck("month_year");
            return response()->json($dataset, 200);
        }
        return BaseController::errorData($user, "السجل غير موجود");
    }

    public function getSchoolBenifitPeriod(Request $request)
    {
        $data = Facture::select(
            DB::raw("(sum(Factures.montant-factures.remise)) as total")
        )->whereBetween(
            'created_at',
            [$request->from, $request->to]
        )->orderBy('created_at')->where("deleted_at", null)->first();

        if ($data) {
            return response()->json($data->total, 200);
        }
        return BaseController::errorData(null, "السجل غير موجود");
    }


    public function getClientBenifitById(Request $request)
    {
        $user = Facture::select(
            'Factures.group_id',
            'Factures.Client_id',
            'Factures.subjName',
            'Factures.groupName',
            DB::raw("SUM(Factures.Clientbenefit) as 'total'")
        )
            ->where('Factures.Client_id', $request->Client_id)
            ->whereBetween(
                'created_at',
                [$request->from, $request->to]
            )->groupBy(['group_id'])->get();

        if ($user) {
            return response()->json($user, 200);
        }
        return BaseController::errorData($user, "السجل غير موجود");
    }
    public function getInventoryAnalytic(Request $request)
    {
        $products = Product::select(
            DB::raw("SUM(products.price) as 'total'")
        )->first();
        if ($products) {
            return response()->json($products, 200);
        }
        return response()->json(null, 400);
    }

    public function getExpansesAnalytic(Request $request)
    {
        $Expenses = Expense::select(DB::raw("SUM(expenses.price) as 'total'"));

        if ($request->from) {
            $Expenses = $Expenses->where("created_at", ">=", $request->from);
        }
        if ($request->to) {
            $Expenses = $Expenses->where("created_at", "<=", $request->to);
        }

        $Expenses = $Expenses->first();
        if ($Expenses) {
            return response()->json($Expenses, 200);
        }
        return response()->json(null, 400);
    }

    public function getDuesClientsAnalytic(Request $request)
    {
        $products = Client::select(
            DB::raw("SUM(clients.montant) as 'total'")
        )->first();
        if ($products) {
            return response()->json($products, 200);
        }
        return response()->json(null, 400);
    }
}
