<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin'); // Hanya admin bisa akses semua logs
    }

    public function dashboard()
    {
        // Statistics for dashboard
        $stats = [
            'total_logs_today' => Log::whereDate('created_at', today())->count(),
            'total_logs_week' => Log::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'total_logs_month' => Log::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'most_active_users' => Log::with('user')
                ->selectRaw('user_id, COUNT(*) as count')
                ->whereNotNull('user_id')
                ->groupBy('user_id')
                ->orderBy('count', 'desc')
                ->limit(5)
                ->get(),
            'action_distribution' => Log::selectRaw('action, COUNT(*) as count')
                ->groupBy('action')
                ->orderBy('count', 'desc')
                ->get(),
            'role_distribution' => Log::with('user')
                ->selectRaw('roles.name as role, COUNT(logs.id) as count')
                ->join('users', 'logs.user_id', '=', 'users.id')
                ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('model_has_roles.model_type', 'App\\Models\\User')
                ->groupBy('roles.name')
                ->orderBy('count', 'desc')
                ->get()
                ->map(function ($item) {
                    $item->role = $item->role;
                    return $item;
                }),
        ];

        return view('admin.logs.dashboard', compact('stats'));
    }

    public function index(Request $request)
    {
        $query = Log::with('user');

        // Filtering
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('model')) {
            $query->where('model', $request->model);
        }
        if ($request->filled('user_name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user_name . '%');
            });
        }
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [$request->from, $request->to]);
        }

        // Export functionality
        if ($request->has('export')) {
            return $this->exportLogs($request);
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.logs.index', compact('logs'));
    }

    public function show(Log $log)
    {
        return view('admin.logs.show', compact('log'));
    }

    public function destroy(Log $log)
    {
        $log->delete();
        return redirect()->route('admin.logs.index')->with('success', 'Log berhasil dihapus.');
    }

    private function exportLogs(Request $request)
    {
        $query = Log::with('user');

        // Apply same filters as index
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('model')) {
            $query->where('model', $request->model);
        }
        if ($request->filled('user_name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user_name . '%');
            });
        }
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [$request->from, $request->to]);
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        $format = $request->get('export');

        switch ($format) {
            case 'pdf':
                return $this->exportToPdf($logs);
            case 'excel':
                return $this->exportToExcel($logs);
            case 'csv':
                return $this->exportToCsv($logs);
            default:
                abort(400, 'Format export tidak didukung');
        }
    }

    private function exportToPdf($logs)
    {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.logs.export_pdf', compact('logs'));
        return $pdf->download('logs_' . now()->format('Y-m-d_H-i-s') . '.pdf');
    }

    private function exportToExcel($logs)
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\LogsExport($logs), 'logs_' . now()->format('Y-m-d_H-i-s') . '.xlsx');
    }

    private function exportToCsv($logs)
    {
        $filename = 'logs_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');

            // Header
            fputcsv($file, ['Waktu', 'User', 'Role', 'Aksi', 'Model', 'Model ID', 'IP Address', 'Deskripsi']);

            // Data
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user->name ?? 'N/A',
                    $log->role ?? 'N/A',
                    $log->action,
                    $log->model,
                    $log->model_id ?? '',
                    $log->ip_address ?? '',
                    $log->description ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
