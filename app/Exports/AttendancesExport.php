<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;

class AttendancesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Attendance::with('student.user', 'student.classRoom', 'recordedBy');

        if ($this->request->filled('class_id')) {
            $query->whereHas('student', function ($q) {
                $q->where('rombel_id', $this->request->class_id);
            });
        }

        if ($this->request->filled('start_date')) {
            $query->where('date', '>=', $this->request->start_date);
        }

        if ($this->request->filled('end_date')) {
            $query->where('date', '<=', $this->request->end_date);
        }

        if ($this->request->filled('status')) {
            $query->where('status', $this->request->status);
        }

        return $query->orderBy('date', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Nama Siswa',
            'Kelas',
            'Tanggal',
            'Status',
            'Keterangan',
            'Dicatat Oleh',
            'Waktu Pencatatan',
        ];
    }

    public function map($attendance): array
    {
        return [
            $attendance->student->user->name,
            $attendance->student->classRoom->name ?? '-',
            $attendance->date->format('d/m/Y'),
            $this->getStatusLabel($attendance->status),
            $attendance->remark ?? '-',
            $attendance->recordedBy->name ?? '-',
            $attendance->created_at->format('d/m/Y H:i'),
        ];
    }

    private function getStatusLabel($status)
    {
        switch ($status) {
            case 'present':
                return 'Hadir';
            case 'absent':
                return 'Tidak Hadir';
            case 'late':
                return 'Terlambat';
            case 'excused':
                return 'Izin';
            default:
                return ucfirst($status);
        }
    }
}
