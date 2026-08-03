<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Audit Logs
     */
    public function index(Request $request)
    {
        $query = AuditLog::with('user');

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('record_name', 'ILIKE', "%{$search}%")
                    ->orWhere('description', 'ILIKE', "%{$search}%")
                    ->orWhere('module', 'ILIKE', "%{$search}%")
                    ->orWhere('action', 'ILIKE', "%{$search}%")
                    ->orWhere('ip_address', 'ILIKE', "%{$search}%");

            });
        }

        /*
        |--------------------------------------------------------------------------
        | User
        |--------------------------------------------------------------------------
        */

        if ($request->filled('user')) {

            $query->where('user_id', $request->user);
        }

        /*
        |--------------------------------------------------------------------------
        | Module
        |--------------------------------------------------------------------------
        */

        if ($request->filled('module')) {

            $query->where('module', $request->module);
        }

        /*
        |--------------------------------------------------------------------------
        | Action
        |--------------------------------------------------------------------------
        */

        if ($request->filled('action')) {

            $query->where('action', $request->action);
        }

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where('status', $request->status);
        }

        /*
        |--------------------------------------------------------------------------
        | Date Range
        |--------------------------------------------------------------------------
        */

        if ($request->filled('from')) {

            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {

            $query->whereDate('created_at', '<=', $request->to);
        }

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $stats = [

            'total' => AuditLog::count(),

            'today' => AuditLog::whereDate(
                'created_at',
                today()
            )->count(),

            'success' => AuditLog::where(
                'status',
                'success'
            )->count(),

            'failed' => AuditLog::where(
                'status',
                'failed'
            )->count(),

            'warning' => AuditLog::where(
                'status',
                'warning'
            )->count(),

        ];

        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */

        $users = User::orderBy('name')->get();

        $modules = AuditLog::select('module')
            ->distinct()
            ->orderBy('module')
            ->pluck('module');

        $actions = AuditLog::select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        /*
        |--------------------------------------------------------------------------
        | Data
        |--------------------------------------------------------------------------
        */

        $logs = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view(
            'Admin.audit_logs.index',
            compact(
                'logs',
                'stats',
                'users',
                'modules',
                'actions'
            )
        );
    }

    /**
     * Show Detail
     */
    public function show(AuditLog $auditLog)
    {
        $auditLog->load('user');

        return response()->json([
            'success' => true,
            'data' => $auditLog
        ]);
    }

    /**
     * Delete
     */
    public function destroy(AuditLog $auditLog)
    {
        $auditLog->delete();

        return back()->with(
            'success',
            'Audit log deleted successfully.'
        );
    }

    /**
     * Bulk Delete
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array'],
        ]);

        AuditLog::whereIn(
            'id',
            $request->ids
        )->delete();

        return back()->with(
            'success',
            'Selected logs deleted successfully.'
        );
    }
}