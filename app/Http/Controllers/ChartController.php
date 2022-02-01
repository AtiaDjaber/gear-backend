<?php

namespace App\Http\Controllers;

use App\model\Sale;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartController extends Model
{


    public function getClientsBenifitsChart(Request $request)
    {
        $dataset = [];
        $user = Sale::select(
            'Sales.ClientName',
            'Sales.Client_id',
            DB::raw("SUM(Sales.ClientBenefit) as total")
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
        $Sales = Sale::select(
            'Sales.ClientName',
            'Sales.Client_id',
            DB::raw("SUM(Sales.ClientBenefit) as total")
        )->whereBetween(
            'created_at',
            [$request->from, $request->to]
        )->where("isPresent", true)->groupBy('Client_id')
            ->orderBy("total", "desc")->get();

        if ($Sales) {
            return response()->json($Sales, 200);
        }
        return BaseController::errorData(null, "السجل غير موجود");
    }


    public function getSchoolBenifitChart(Request $request)
    {
        $dataset = [];
        $user = Sale::select(
            "id",
            DB::raw("(sum(Sales.schoolBenefit)) as total"),
            DB::raw("(DATE_FORMAT(created_at, '%Y-%m')) as month_year")
        )
            ->orderBy('created_at')
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
            // ->where("created_at", ">=", Carbon::now()->subYear())
            ->where("isPresent", true)
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
        $data = Sale::select(
            DB::raw("(sum(Sales.schoolBenefit)) as total")
        )->whereBetween(
            'created_at',
            [$request->from, $request->to]
        )->orderBy('created_at')->where("isPresent", true)->first();

        if ($data) {
            return response()->json($data->total, 200);
        }
        return BaseController::errorData(null, "السجل غير موجود");
    }


    public function getClientBenifitById(Request $request)
    {
        $user = Sale::select(
            'Sales.group_id',
            'Sales.Client_id',
            'Sales.subjName',
            'Sales.groupName',
            DB::raw("SUM(Sales.Clientbenefit) as 'total'")
        )
            ->where('Sales.Client_id', $request->Client_id)
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
}
