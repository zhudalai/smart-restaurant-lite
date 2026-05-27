<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DailyReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DailyReportController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = DailyReport::orderByDesc('report_date');

        if ($request->has('from')) {
            $query->where('report_date', '>=', $request->input('from'));
        }
        if ($request->has('to')) {
            $query->where('report_date', '<=', $request->input('to'));
        }

        $reports = $query->paginate(30);

        return response()->json($reports);
    }

    public function show(int $id): JsonResponse
    {
        $report = DailyReport::findOrFail($id);
        return response()->json(['data' => $report]);
    }

    public function latest(): JsonResponse
    {
        $report = DailyReport::orderByDesc('report_date')->first();

        if (!$report) {
            return response()->json(['message' => 'No reports generated yet'], 404);
        }

        return response()->json(['data' => $report]);
    }

    public function generate(Request $request): JsonResponse
    {
        $date = $request->input('date', now()->subDay()->format('Y-m-d'));

        $cmd = 'php ' . base_path('artisan') . " report:daily {$date}";
        $output = shell_exec("cd " . base_path() . " && {$cmd} 2>&1");

        $report = DailyReport::where('report_date', $date)->first();

        return response()->json([
            'message' => 'Report generated',
            'output' => $output,
            'data' => $report,
        ]);
    }
}
