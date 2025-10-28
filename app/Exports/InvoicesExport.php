<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;

class InvoicesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Invoice::with('student.user', 'student.classRoom', 'createdBy');

        if ($this->request->filled('class_id')) {
            $query->whereHas('student', function ($q) {
                $q->where('rombel_id', $this->request->class_id);
            });
        }

        if ($this->request->filled('status')) {
            $query->where('status', $this->request->status);
        }

        if ($this->request->filled('month') && $this->request->filled('year')) {
            $query->whereMonth('due_date', $this->request->month)
                ->whereYear('due_date', $this->request->year);
        }

        if ($this->request->filled('month')) {
            $query->whereMonth('due_date', $this->request->month);
        }

        if ($this->request->filled('year')) {
            $query->whereYear('due_date', $this->request->year);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Nama Siswa',
            'Kelas',
            'Jumlah Tagihan',
            'Jatuh Tempo',
            'Status',
            'Dibuat Oleh',
            'Tanggal Dibuat',
        ];
    }

    public function map($invoice): array
    {
        return [
            $invoice->student->user->name,
            $invoice->student->classRoom->name ?? '-',
            'Rp ' . number_format($invoice->amount, 0, ',', '.'),
            $invoice->due_date->format('d/m/Y'),
            $this->getStatusLabel($invoice->status),
            $invoice->createdBy->name ?? '-',
            $invoice->created_at->format('d/m/Y H:i'),
        ];
    }

    private function getStatusLabel($status)
    {
        switch ($status) {
            case 'unpaid':
                return 'Belum Dibayar';
            case 'paid':
                return 'Sudah Dibayar';
            case 'partial':
                return 'Sebagian';
            default:
                return ucfirst($status);
        }
    }
}
