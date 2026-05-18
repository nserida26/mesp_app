<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;

class AuditLogController extends Controller
{
    public function index()
    {
        return view('admin.audit-logs.index', [
            'logs' => Activity::latest()->paginate(30),
        ]);
    }
}
