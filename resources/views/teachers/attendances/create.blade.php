@extends('layouts.app')

@section('title', 'Input Absensi Siswa - SMAN 1 DONGGO')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-check text-primary me-2"></i>
                        Input Absensi Siswa
                    </h5>
                    <a href="{{ route('teachers.attendances') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form id="attendanceForm" method="POST" action="{{ route('teachers.attendances.mark') }}">
                        @csrf
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Kelas</label>
                                <select class="form-select" name="class_id" id="classSelect" required>
                                    <option value="">Pilih Kelas</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Mata Pelajaran</label>
                                <select class="form-select" name="subject_id" id="subjectSelect" required disabled>
                                    <option value="">Pilih Mata Pelajaran</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tanggal</label>
                                <input type="date" class="form-control" name="date" value="{{ now()->format('Y-m-d') }}" required>
                            </div>
                        </div>

                        <div id="loadStudentsBtn" class="mt-3" style="display: none;">
                            <button type="button" class="btn btn-outline-primary w-100" id="loadStudentsButton">
                                <i class="fas fa-users me-1"></i>Lanjutkan - Muat Daftar Siswa
                            </button>
                        </div>

                        <div id="studentsList" class="mt-4" style="display: none;">
                            <h6 class="mb-3">
                                <i class="fas fa-users me-2"></i>
                                Daftar Siswa Kelas <span id="className"></span>
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="25%">Nama Siswa</th>
                                            <th width="15%">NIS</th>
                                            <th width="20%">Status Kehadiran</th>
                                            <th width="35%">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody id="studentsTableBody">
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                                <i class="fas fa-times me-1"></i>Batal
                            </button>
                            <button type="submit" class="btn btn-primary" id="submitBtn" style="display: none;">
                                <i class="fas fa-save me-1"></i>Simpan Absensi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('classSelect').addEventListener('change', function() {
    const classId = this.value;
    const subjectSelect = document.getElementById('subjectSelect');
    const studentsList = document.getElementById('studentsList');
    const submitBtn = document.getElementById('submitBtn');

    if (classId) {
        // Fetch subjects for this class
        fetch(`/teachers/classes/${classId}/subjects`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                subjectSelect.innerHTML = '<option value="">Pilih Mata Pelajaran</option>';
                data.forEach(subject => {
                    subjectSelect.innerHTML += `<option value="${subject.id}">${subject.name}</option>`;
                });
                subjectSelect.disabled = false;
            })
            .catch(error => {
                console.error('Error fetching subjects:', error);
                alert('Terjadi kesalahan saat memuat mata pelajaran. Silakan coba lagi.');
                subjectSelect.innerHTML = '<option value="">Pilih Mata Pelajaran</option>';
                subjectSelect.disabled = true;
            });
    } else {
        subjectSelect.innerHTML = '<option value="">Pilih Mata Pelajaran</option>';
        subjectSelect.disabled = true;
        studentsList.style.display = 'none';
        submitBtn.style.display = 'none';
    }
});

document.getElementById('subjectSelect').addEventListener('change', function() {
    const classId = document.getElementById('classSelect').value;
    const subjectId = this.value;
    const loadStudentsBtn = document.getElementById('loadStudentsBtn');
    const studentsList = document.getElementById('studentsList');
    const submitBtn = document.getElementById('submitBtn');

    if (classId && subjectId) {
        loadStudentsBtn.style.display = 'block';
        studentsList.style.display = 'none';
        submitBtn.style.display = 'none';
    } else {
        loadStudentsBtn.style.display = 'none';
        studentsList.style.display = 'none';
        submitBtn.style.display = 'none';
    }
});

document.getElementById('loadStudentsButton').addEventListener('click', function() {
    const classId = document.getElementById('classSelect').value;
    const subjectId = document.getElementById('subjectSelect').value;
    const loadStudentsBtn = document.getElementById('loadStudentsBtn');
    const studentsList = document.getElementById('studentsList');
    const submitBtn = document.getElementById('submitBtn');

    if (classId && subjectId) {
        // Show loading state
        this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Memuat...';
        this.disabled = true;

        // Fetch students for this class
        fetch(`/teachers/classes/${classId}/students`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                let html = '';
                data.forEach((student, index) => {
                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>
                                <input type="hidden" name="students[${student.id}][student_id]" value="${student.id}">
                                ${student.user.name}
                            </td>
                            <td>${student.nis}</td>
                            <td>
                                <select class="form-select form-select-sm" name="students[${student.id}][status]" required>
                                    <option value="present">Hadir</option>
                                    <option value="late">Terlambat</option>
                                    <option value="absent">Tidak Hadir</option>
                                    <option value="excused">Izin</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm" name="students[${student.id}][remark]" placeholder="Opsional">
                            </td>
                        </tr>
                    `;
                });
                document.getElementById('studentsTableBody').innerHTML = html;

                // Update class name
                const className = document.querySelector('#classSelect option:checked').textContent;
                document.getElementById('className').textContent = className;

                loadStudentsBtn.style.display = 'none';
                studentsList.style.display = 'block';
                submitBtn.style.display = 'inline-block';
            })
            .catch(error => {
                console.error('Error fetching students:', error);
                alert('Terjadi kesalahan saat memuat data siswa. Silakan coba lagi.');
                // Reset button state
                this.innerHTML = '<i class="fas fa-users me-1"></i>Lanjutkan - Muat Daftar Siswa';
                this.disabled = false;
            });
    }
});
</script>
@endsection
