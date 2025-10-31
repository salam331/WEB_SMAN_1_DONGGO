@extends('layouts.app')

@section('title', 'Input Absensi Siswa - SMAN 1 DONGGO')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Card Wrapper -->
                <div class="card border-0 shadow-sm rounded-3">
                    <div
                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3 px-4">
                        <h5 class="card-title mb-0 fw-semibold">
                            <i class="fas fa-calendar-check me-2"></i>
                            Input Absensi Siswa
                        </h5>
                        <a href="{{ route('teachers.attendances') }}" class="btn btn-light btn-sm fw-medium shadow-sm">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    </div>

                    <div class="card-body p-4">
                        <form id="attendanceForm" method="POST" action="{{ route('teachers.attendances.mark') }}">
                            @csrf

                            <!-- Form Filter -->
                            <div class="row g-4 mb-4">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-secondary">
                                        <i class="fas fa-users me-1 text-primary"></i> Kelas
                                    </label>
                                    <select class="form-select border-0 shadow-sm" name="class_id" id="classSelect"
                                        required>
                                        <option value="">Pilih Kelas</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-secondary">
                                        <i class="fas fa-book me-1 text-primary"></i> Mata Pelajaran
                                    </label>
                                    <select class="form-select border-0 shadow-sm" name="subject_id" id="subjectSelect"
                                        required disabled>
                                        <option value="">Pilih Mata Pelajaran</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-secondary">
                                        <i class="fas fa-calendar-day me-1 text-primary"></i> Tanggal
                                    </label>
                                    <input type="date" class="form-control border-0 shadow-sm" name="date"
                                        value="{{ now()->format('Y-m-d') }}" required>
                                </div>
                            </div>

                            <!-- Tombol Muat Siswa -->
                            <div id="loadStudentsBtn" class="mt-3 text-center" style="display: none;">
                                <button type="button" class="btn btn-outline-primary w-50 shadow-sm"
                                    id="loadStudentsButton">
                                    <i class="fas fa-users me-2"></i>Lanjutkan - Muat Daftar Siswa
                                </button>
                            </div>

                            <!-- Daftar Siswa -->
                            <div id="studentsList" class="mt-5" style="display: none;">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-list text-primary me-2 fs-5"></i>
                                    <h6 class="mb-0 fw-semibold text-secondary">Daftar Siswa Kelas <span id="className"
                                            class="text-dark"></span></h6>
                                </div>

                                <div class="table-responsive rounded-3 shadow-sm">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-primary text-center text-nowrap">
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="25%">Nama Siswa</th>
                                                <th width="15%">NIS</th>
                                                <th width="20%">Status Kehadiran</th>
                                                <th width="35%">Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody id="studentsTableBody" class="bg-white"></tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="d-flex justify-content-end gap-2 mt-5">
                                <button type="button" class="btn btn-outline-secondary shadow-sm px-4"
                                    onclick="window.history.back()">
                                    <i class="fas fa-times me-1"></i>Batal
                                </button>
                                <button type="submit" class="btn btn-primary shadow-sm px-4" id="submitBtn"
                                    style="display: none;">
                                    <i class="fas fa-save me-1"></i>Simpan Absensi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- End Card -->
            </div>
        </div>
    </div>

    <!-- Script Section -->
    <script>
        document.getElementById('classSelect').addEventListener('change', function () {
            const classId = this.value;
            const subjectSelect = document.getElementById('subjectSelect');
            const studentsList = document.getElementById('studentsList');
            const submitBtn = document.getElementById('submitBtn');

            if (classId) {
                fetch(`/teachers/classes/${classId}/subjects`)
                    .then(response => {
                        if (!response.ok) throw new Error('Gagal mengambil data');
                        return response.json();
                    })
                    .then(data => {
                        subjectSelect.innerHTML = '<option value="">Pilih Mata Pelajaran</option>';
                        data.forEach(subject => {
                            subjectSelect.innerHTML += `<option value="${subject.id}">${subject.name}</option>`;
                        });
                        subjectSelect.disabled = false;
                    })
                    .catch(() => {
                        alert('Terjadi kesalahan saat memuat mata pelajaran.');
                        subjectSelect.disabled = true;
                    });
            } else {
                subjectSelect.innerHTML = '<option value="">Pilih Mata Pelajaran</option>';
                subjectSelect.disabled = true;
                studentsList.style.display = 'none';
                submitBtn.style.display = 'none';
            }
        });

        document.getElementById('subjectSelect').addEventListener('change', function () {
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

        document.getElementById('loadStudentsButton').addEventListener('click', function () {
            const classId = document.getElementById('classSelect').value;
            const subjectId = document.getElementById('subjectSelect').value;
            const loadStudentsBtn = document.getElementById('loadStudentsBtn');
            const studentsList = document.getElementById('studentsList');
            const submitBtn = document.getElementById('submitBtn');

            if (classId && subjectId) {
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Memuat...';
                this.disabled = true;

                fetch(`/teachers/classes/${classId}/students`)
                    .then(response => {
                        if (!response.ok) throw new Error('Gagal memuat siswa');
                        return response.json();
                    })
                    .then(data => {
                        let html = '';
                        data.forEach((student, index) => {
                            html += `
                            <tr class="align-middle">
                                <td class="text-center">${index + 1}</td>
                                <td>
                                    <input type="hidden" name="students[${student.id}][student_id]" value="${student.id}">
                                    <span class="fw-semibold">${student.user.name}</span>
                                </td>
                                <td class="text-center">${student.nis}</td>
                                <td>
                                    <select class="form-select form-select-sm shadow-sm" name="students[${student.id}][status]" required>
                                        <option value="present">Hadir</option>
                                        <option value="late">Terlambat</option>
                                        <option value="absent">Tidak Hadir</option>
                                        <option value="excused">Izin</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm shadow-sm" name="students[${student.id}][remark]" placeholder="Opsional">
                                </td>
                            </tr>
                        `;
                        });
                        document.getElementById('studentsTableBody').innerHTML = html;
                        document.getElementById('className').textContent = document.querySelector('#classSelect option:checked').textContent;

                        loadStudentsBtn.style.display = 'none';
                        studentsList.style.display = 'block';
                        submitBtn.style.display = 'inline-block';
                    })
                    .catch(() => {
                        alert('Terjadi kesalahan saat memuat data siswa.');
                    })
                    .finally(() => {
                        this.innerHTML = '<i class="fas fa-users me-1"></i>Lanjutkan - Muat Daftar Siswa';
                        this.disabled = false;
                    });
            }
        });
    </script>
@endsection