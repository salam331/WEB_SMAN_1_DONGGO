<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LogsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $logs;

    public function __construct($logs)
    {
        $this->logs = $logs;
    }

    public function collection()
    {
        return $this->logs;
    }

    public function headings(): array
    {
        return [
            'Waktu',
            'User',
            'Role',
            'Aksi',
            'Model',
            'Model ID',
            'IP Address',
            'Deskripsi',
            'Nilai Lama',
            'Nilai Baru',
        ];
    }

    public function map($log): array
    {
        return [
            $log->created_at->format('Y-m-d H:i:s'),
            $log->user->name ?? 'N/A',
            $log->role ?? 'N/A',
            $log->action,
            $log->model,
            $log->model_id ?? '',
            $log->ip_address ?? '',
            $log->description ?? '',
            $log->old_values ? json_encode($log->old_values) : '',
            $log->new_values ? json_encode($log->new_values) : '',
        ];
    }
}
