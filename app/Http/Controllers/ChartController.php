<?php

namespace App\Http\Controllers;

use App\model\Expense;
use App\model\Facture;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartController extends Model
{


    public function getClientsBenifitsChart(Request $request)
    {
        $dataset = [];
        $user = Facture::select(
            'factures.ClientName',
            'factures.Client_id',
            DB::raw("SUM(factures.ClientBenefit) as total")
        )->whereBetween(
            'created_at',
            [$request->from, $request->to]
        )->where("isPresent", true)
            ->groupBy('Client_id')->get();

        if ($user) {
            $dataset["data"] = $user->pluck("total");
            $dataset["Clients_ids"] = $user->pluck("Client_id");
            $dataset["labels"] = $user->pluck("ClientName");
            return response()->json($dataset, 200);
        }
        return BaseController::errorData($user, "السجل غير موجود");
    }


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
            DB::raw("(sum(factures.montant)) as total"),
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
            DB::raw("(sum(Factures.montant)) as total")
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
            )->where("isPresent", true)
            ->groupBy(['group_id'])->get();

        if ($user) {
            return response()->json($user, 200);
        }
        return BaseController::errorData($user, "السجل غير موجود");
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
}
