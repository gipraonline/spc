<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FieldLog;
use App\Models\Admin;

class AdminFieldLogController extends Controller
{
    /**
     * Display all field logs with filters.
     */
    public function index(Request $request)
    {
         $fieldLogs = FieldLog::with(['admin', 'tasks'])
        ->latest('work_date')
        ->latest('check_in_time')
        ->paginate(15);

    return view('admin.admin-log.index', compact('fieldLogs'));

    }

    /**
     * Display a single field log.
     */
    public function show(FieldLog $fieldLog)
    {
        $fieldLog->load(['admin', 'tasks']);

    return view('admin.admin-log.show', compact('fieldLog'));
    }

   
}