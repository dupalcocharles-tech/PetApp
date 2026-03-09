<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClinicReport;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $query = ClinicReport::with(['clinic', 'owner', 'appointment'])->latest();

        if ($status) {
            $query->where('status', $status);
        }

        $reports = $query->paginate(15);

        return view('admin.reports', compact('reports', 'status'));
    }

    public function markReviewed($id)
    {
        $report = ClinicReport::findOrFail($id);
        $report->status = 'reviewed';
        $report->save();

        return back()->with('success', 'Report marked as reviewed.');
    }

    public function dismiss($id)
    {
        $report = ClinicReport::findOrFail($id);
        $report->status = 'dismissed';
        $report->save();

        return back()->with('success', 'Report dismissed.');
    }
}

