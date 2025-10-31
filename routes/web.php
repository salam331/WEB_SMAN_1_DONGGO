<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\LogController;

// Public Routes
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/announcements', [PublicController::class, 'announcements'])->name('public.announcements');
Route::get('/gallery', [PublicController::class, 'gallery'])->name('public.gallery');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::post('/contact', [PublicController::class, 'storeContactMessage'])->name('contact.store');
// Route::get('/announcement/{id}', [PublicController::class, 'showAnnouncement'])->name('public.announcement.show');
Route::get('/announcement/detail/{id}', [PublicController::class, 'getAnnouncementDetail'])
    ->name('public.announcement.detail');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard redirect based on role
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('guru')) {
            return redirect()->route('teacher.dashboard');
        } elseif ($user->hasRole('siswa')) {
            return redirect()->route('student.dashboard');
        } elseif ($user->hasRole('orang_tua')) {
            return redirect()->route('parent.dashboard');
        }
        return redirect('/');
    })->name('dashboard');

    // NOTE: debug route removed. Use tinker or dedicated admin utilities to inspect roles.

    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/public-dashboard', function () {
            return redirect()->route('home');
        })->name('public.dashboard');

        // User Management
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');

        // Teacher Management
        Route::get('/teachers', [AdminController::class, 'teachers'])->name('teachers');
        Route::get('/teachers/create', [AdminController::class, 'createTeacher'])->name('teachers.create');
        Route::post('/teachers', [AdminController::class, 'storeTeacher'])->name('teachers.store');
        Route::get('/teachers/{teacher}/edit', [AdminController::class, 'editTeacher'])->name('teachers.edit');
        Route::put('/teachers/{teacher}', [AdminController::class, 'updateTeacher'])->name('teachers.update');
        Route::delete('/teachers/{teacher}', [AdminController::class, 'destroyTeacher'])->name('teachers.destroy');

        // Student Management - resource handled by StudentController
        Route::get('/students', [StudentController::class, 'index'])->name('students');
        Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
        Route::post('/students', [StudentController::class, 'store'])->name('students.store');
        Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
        Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
        Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');

        // Class Management - resource handled by ClassRoomController
        Route::get('/classes', [ClassRoomController::class, 'index'])->name('classes');
        Route::get('/classes/create', [ClassRoomController::class, 'create'])->name('classes.create');
        Route::post('/classes', [ClassRoomController::class, 'store'])->name('classes.store');
        Route::get('/classes/{classRoom}/edit', [ClassRoomController::class, 'edit'])->name('classes.edit');
        Route::put('/classes/{classRoom}', [ClassRoomController::class, 'update'])->name('classes.update');
        Route::delete('/classes/{classRoom}', [ClassRoomController::class, 'destroy'])->name('classes.destroy');

        // Subject Management
        Route::resource('/subjects', SubjectController::class)->names('subjects');

        // Schedule Management
        Route::get('/schedules', [AdminController::class, 'schedulesIndex'])->name('schedules.index');
        Route::get('/schedules/create', [AdminController::class, 'create'])->name('schedules.create');
        Route::post('/schedules', [AdminController::class, 'store'])->name('schedules.store');
        Route::get('/schedules/{schedule}', [AdminController::class, 'show'])->name('schedules.show');
        Route::get('/schedules/{schedule}/edit', [AdminController::class, 'edit'])->name('schedules.edit');
        Route::put('/schedules/{schedule}', [AdminController::class, 'update'])->name('schedules.update');
        Route::delete('/schedules/{schedule}', [AdminController::class, 'destroy'])->name('schedules.destroy');

        // Announcement Management
        Route::get('/announcements', [AdminController::class, 'announcements'])->name('announcements');
        Route::get('/announcements/create', [AdminController::class, 'createAnnouncement'])->name('announcements.create');
        Route::post('/announcements', [AdminController::class, 'storeAnnouncement'])->name('announcements.store');
        Route::get('/announcements/{announcement}/edit', [AdminController::class, 'editAnnouncement'])->name('announcements.edit');
        Route::put('/announcements/{announcement}', [AdminController::class, 'updateAnnouncement'])->name('announcements.update');
        Route::delete('/announcements/{announcement}', [AdminController::class, 'destroyAnnouncement'])->name('announcements.destroy');

        // Material Management
        Route::resource('/materials', MaterialController::class)->names('materials');
        Route::get('/materials/{material}/download', [MaterialController::class, 'download'])->name('materials.download');

        // Attendance Management
        Route::get('/attendances', [AdminController::class, 'attendancesIndex'])->name('attendances.index');
        Route::get('/attendances/create', [AdminController::class, 'createAttendance'])->name('attendances.create');
        Route::post('/attendances', [AdminController::class, 'storeAttendance'])->name('attendances.store');
        Route::get('/attendances/{attendance}/show', [AdminController::class, 'showAttendance'])->name('attendances.show');
        Route::get('/attendances/{attendance}/edit', [AdminController::class, 'editAttendance'])->name('attendances.edit');
        Route::put('/attendances/{attendance}', [AdminController::class, 'updateAttendance'])->name('attendances.update');
        Route::delete('/attendances/{attendance}', [AdminController::class, 'destroyAttendance'])->name('attendances.destroy');
        Route::get('/attendances/summary', [AdminController::class, 'attendanceSummary'])->name('attendances.summary');
        Route::get('/attendances/export', [AdminController::class, 'exportAttendances'])->name('attendances.export');
        Route::get('/attendances/report', [AdminController::class, 'attendanceReport'])->name('attendances.report');

        // Invoice Management
        Route::get('/invoices', [AdminController::class, 'invoices'])->name('invoices');
        Route::get('/invoices/create', [AdminController::class, 'createInvoice'])->name('invoices.create');
        Route::post('/invoices', [AdminController::class, 'storeInvoice'])->name('invoices.store');
        Route::get('/invoices/{invoice}/edit', [AdminController::class, 'editInvoice'])->name('invoices.edit');
        Route::put('/invoices/{invoice}', [AdminController::class, 'updateInvoice'])->name('invoices.update');
        Route::delete('/invoices/{invoice}', [AdminController::class, 'destroyInvoice'])->name('invoices.destroy');
        Route::put('/invoices/{invoice}/mark-paid', [AdminController::class, 'markInvoicePaid'])->name('invoices.markPaid');
        Route::get('/invoices/export', [AdminController::class, 'exportInvoices'])->name('invoices.export');
        Route::get('/invoices/report', [AdminController::class, 'invoiceReport'])->name('invoices.report');

        // Gallery Management
        Route::resource('/galleries', \App\Http\Controllers\Admin\GalleryController::class)->names('galleries');

        // School Profile Management
        Route::resource('/school-profiles', \App\Http\Controllers\Admin\SchoolProfileController::class)->names('school-profiles');

        // Parent Management
        Route::resource('/parents', \App\Http\Controllers\Admin\ParentController::class)->names('parents');

        // Contact Messages Management
        Route::resource('/contact-messages', \App\Http\Controllers\Admin\ContactMessageController::class)->names('contact-messages');
        Route::post('/contact-messages/{contactMessage}/approve', [\App\Http\Controllers\Admin\ContactMessageController::class, 'approve'])->name('contact-messages.approve');
        Route::post('/contact-messages/{contactMessage}/reject', [\App\Http\Controllers\Admin\ContactMessageController::class, 'reject'])->name('contact-messages.reject');

        // Logs
        Route::get('/logs', [AdminController::class, 'logs'])->name('logs');
        Route::get('/logs/dashboard', [LogController::class, 'dashboard'])->name('logs.dashboard');
        Route::resource('/logs', LogController::class)->names('logs');
    });

    // Teacher Routes
    Route::middleware(['role:guru'])->prefix('teachers')->name('teachers.')->group(function () {
        Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');

        // Subject Management
        Route::resource('/subjects', \App\Http\Controllers\Teacher\SubjectController::class)->names('subjects');

        // Material Management
        Route::resource('/materials', \App\Http\Controllers\Teacher\MaterialController::class)->names('materials');
        Route::get('/materials/{material}/download', [\App\Http\Controllers\Teacher\MaterialController::class, 'download'])->name('materials.download');

        // Exam Management
        Route::resource('/exams', \App\Http\Controllers\Teacher\ExamController::class)->names('exams');
        Route::get('/exams/{exam}/input-grades', [\App\Http\Controllers\Teacher\ExamController::class, 'inputGrades'])->name('exams.input_grades');
        Route::post('/exams/{exam}/store-grades', [\App\Http\Controllers\Teacher\ExamController::class, 'storeGrades'])->name('exams.store_grades');

        // Class Management
        Route::get('/classes', [TeacherController::class, 'classes'])->name('classes');
        Route::get('/classes/{classId}', [TeacherController::class, 'classDetail'])->name('classes.detail');
        

        // Schedule Management
        Route::get('/schedules', [TeacherController::class, 'schedules'])->name('schedules');

        // Attendance Management
        Route::get('/attendances', [TeacherController::class, 'attendances'])->name('attendances');
        Route::get('/attendances/create', [TeacherController::class, 'createAttendance'])->name('attendances.create');
        Route::post('/attendances/mark', [TeacherController::class, 'markAttendance'])->name('attendances.mark');
        Route::get('/attendances/{scheduleId}/{date}', [TeacherController::class, 'showAttendance'])->name('attendances.show');
        Route::get('/attendances/{scheduleId}/{date}/edit', [TeacherController::class, 'editAttendance'])->name('attendances.edit');
        Route::put('/attendances/{scheduleId}/{date}', [TeacherController::class, 'updateAttendance'])->name('attendances.update');
        Route::delete('/attendances/{scheduleId}/{date}', [TeacherController::class, 'destroyAttendance'])->name('attendances.destroy');

        // Additional routes for attendance modal
        Route::get('/classes/{classId}/subjects', [TeacherController::class, 'getSubjectsForClass'])->name('classes.subjects');
        Route::get('/classes/{classId}/students', [TeacherController::class, 'getStudentsForClass'])->name('classes.students');

        // Grade Management
        Route::get('/grades', [TeacherController::class, 'grades'])->name('grades');
        Route::get('/grades/input/{examId}', [TeacherController::class, 'inputGrades'])->name('grades.input');
        Route::post('/grades/input/{examId}', [TeacherController::class, 'storeGrades'])->name('grades.store');

        // Announcement Management
        Route::get('/announcements', [TeacherController::class, 'announcements'])->name('announcements');
        Route::get('/announcements/create', [TeacherController::class, 'createAnnouncement'])->name('announcements.create');
        Route::post('/announcements', [TeacherController::class, 'storeAnnouncement'])->name('announcements.store');
        Route::get('/announcements/{announcement}/edit', [TeacherController::class, 'editAnnouncement'])->name('announcements.edit');
        Route::put('/announcements/{announcement}', [TeacherController::class, 'updateAnnouncement'])->name('announcements.update');
        Route::delete('/announcements/{announcement}', [TeacherController::class, 'destroyAnnouncement'])->name('announcements.destroy');
        Route::post('/announcements/{announcement}/publish', [TeacherController::class, 'publishAnnouncement'])->name('announcements.publish');

        // // Messages
        // Route::get('/messages', [TeacherController::class, 'messages'])->name('messages');
        // Route::post('/messages', [TeacherController::class, 'storeMessage'])->name('messages.store');
        // Route::get('/messages/{message}', [TeacherController::class, 'showMessage'])->name('messages.show');
        // Route::get('/messages/{message}/edit', [TeacherController::class, 'editMessage'])->name('messages.edit');
        // Route::put('/messages/{message}', [TeacherController::class, 'updateMessage'])->name('messages.update');
        // Route::delete('/messages/{message}', [TeacherController::class, 'destroyMessage'])->name('messages.destroy');

        // Notifications
        Route::get('/notifications', [TeacherController::class, 'notifications'])->name('notifications');
    });

    // Student Routes
    Route::middleware(['role:siswa'])->prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');

        // Schedule
        Route::get('/schedule', [StudentController::class, 'schedule'])->name('schedule');

        // Attendance
        Route::get('/attendance', [StudentController::class, 'attendance'])->name('attendance');

        // Grades
        Route::get('/grades', [StudentController::class, 'grades'])->name('grades');

        // Materials
        Route::get('/materials', [StudentController::class, 'materials'])->name('materials');

        // Announcements
        Route::get('/announcements', [StudentController::class, 'announcements'])->name('announcements');

        // Invoices
        Route::get('/invoices', [StudentController::class, 'invoices'])->name('invoices');

        // Library
        Route::get('/library', [StudentController::class, 'library'])->name('library');
        Route::post('/library/borrow', [StudentController::class, 'borrowBook'])->name('library.borrow');
        Route::post('/library/return/{borrowId}', [StudentController::class, 'returnBook'])->name('library.return');

        // Gallery
        Route::get('/gallery', [StudentController::class, 'gallery'])->name('gallery');

        // Messages
        Route::get('/messages', [StudentController::class, 'messages'])->name('messages');
        Route::post('/messages/send', [StudentController::class, 'sendMessage'])->name('messages.send');

        // Notifications
        Route::get('/notifications', [StudentController::class, 'notifications'])->name('notifications');
    });

    // Parent Routes
    Route::middleware(['role:orang_tua'])->prefix('parent')->name('parent.')->group(function () {
        Route::get('/dashboard', [ParentController::class, 'dashboard'])->name('dashboard');

        // Child Detail
        Route::get('/child/{childId}', [ParentController::class, 'childDetail'])->name('child.detail');

        // Child Attendance
        Route::get('/child/{childId}/attendance', [ParentController::class, 'childAttendance'])->name('child.attendance');

        // Child Grades
        Route::get('/child/{childId}/grades', [ParentController::class, 'childGrades'])->name('child.grades');

        // Child Invoices
        Route::get('/child/{childId}/invoices', [ParentController::class, 'childInvoices'])->name('child.invoices');

        // Announcements
        Route::get('/announcements', [ParentController::class, 'announcements'])->name('announcements');

        // Messages
        Route::get('/messages', [ParentController::class, 'messages'])->name('messages');
        Route::post('/messages/send', [ParentController::class, 'sendMessage'])->name('messages.send');

        // Notifications
        Route::get('/notifications', [ParentController::class, 'notifications'])->name('notifications');
    });
});
