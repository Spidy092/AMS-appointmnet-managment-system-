<?php

use App\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\DisableBackBtn;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\DoctorsController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\SpecializationController;
use App\Http\Controllers\ClinicSettingsController;
use App\Http\Controllers\CommunicationController;
use App\Http\Controllers\StaffController;
use App\Models\ClinicDetail;
use App\Http\Controllers\DoctorClinicController;

Route::get('/', [LoginController::class,'home'])->name('home');

Route::get('/unauthorized', function() {
    return view('errors.unauthorized');
})->name('unauthorized');


Route::post('getPlaceByPincode', [ClinicController::class, 'getPlaceByPincode'])->name('getPlaceByPincode');


Route::middleware(['guest'])->group(function () {
    Route::get('/login', [LoginController::class,'loginView'])->name('login');
    // Route::get('/register', [RegistrationController::class, 'registerView']);
    Route::get('/forgotPassword', [ForgotPasswordController::class, 'forgotPasswordView']);
    // Route::post('/registerUser', [RegistrationController::class, 'register']);
    Route::post('/loginUser', [LoginController::class, 'authenticate']);
    Route::post('/forgot-password-send-email', [ForgotPasswordController::class, 'forgotPasswordEmailFunction']);
    Route::get('/resetPassword/{token}', [ForgotPasswordController::class, 'resetPasswordEmail']);
    Route::post('/resetPasswordFunc', [ForgotPasswordController::class, 'resetPasswordFunc']);

    Route::get('/addUser', [UserController::class, 'showAddUserForm'])->name('showAddUserForm');
    Route::post('/add-user', [UserController::class, 'addUserFunction'])->name('addUserFunction');
});

Route::middleware(['auth', DisableBackBtn::class])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'dashboardView'])->name('dashboard');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/check-session', [LoginController::class, 'checkSession']);

    Route::get('/changePassword', [UserController::class, 'changepass'])->name('changePassword');
    Route::post('/updatePassword', [UserController::class, 'updatePassword'])->name('updatePassword');
    Route::get('/profile', [UserController::class, 'profileView']);
    Route::post('/updateProfile', [UserController::class, 'updateProfile']);

    Route::get('services', [ServicesController::class, 'viewServices']);
    Route::post('getServicesData', [ServicesController::class, 'getServicesData'])->name('getServicesData');
    Route::post('addService', [ServicesController::class, 'addService']);
    Route::post('getServiceDetails', [ServicesController::class, 'getServiceDetails']);
    Route::post('editService', [ServicesController::class, 'editService']);
    Route::post('serviceActionFunc', [ServicesController::class, 'serviceActionFunc']);
    Route::post('serviceDeleteFunction', [ServicesController::class, 'serviceDeleteFunction']);
    Route::post('serviceStatusFunc', [ServicesController::class, 'serviceStatusFunc']);

    Route::get('/edit-user/{encryptedId}', [UserController::class, 'editUser']);
    Route::post('/update-user/{encryptedId}', [UserController::class, 'editUserFunc']);
    Route::get('/manageUser', [UserController::class, 'manageUser']);
    Route::post('/user-data', [UserController::class, 'getUserData']);
    Route::post('/userActionFunc', [UserController::class, 'userActionFunc']);
    Route::get('/editUser/{id}', [UserController::class, 'editUser']);
    Route::post('/userDeleteFunc', [UserController::class, 'userDeleteFunc']);


    Route::get('/add-clinic', [ClinicController::class, 'showAddClinicForm'])->name('showAddClinicForm');
    Route::post('/add-clinic', [ClinicController::class, 'addClinicFunction'])->name('addClinicFunction');
    Route::get('/update-clinic/{clinic}', [ClinicController::class,'showUpdateClinicForm'])->name('showUpdateClinicForm');
    Route::post('getClinicsData', [ClinicController::class, 'getClinicsData'])->name('getClinicsData');
    Route::post('clinicActionFunc', [ClinicController::class, 'clinicActionFunc'])->name('clinicActionFunc');
    Route::post('clinicDeleteFunction', [ClinicController::class, 'clinicDeleteFunction'])->name('clinicDeleteFunction');
    Route::get('manageClinics', [ClinicController::class, 'manageClinicView'])->name('manageClinicView');


    Route::get('/search-doctors', [DoctorClinicController::class, 'searchDoctors'])->name('search.doctors'); // for select2 search doctors through ajax
    Route::post('/search-specializations', [DoctorClinicController::class, 'getSpecializationsClinicDoctor'])->name('search.specializations');
    Route::post('/store-clinic-doctor-mapping', [DoctorClinicController::class, 'storeDoctorClinicMapping'])->name('doctorClinicMapping'); 
    Route::post('/destroy-clinic-doctor-mapping', [DoctorClinicController::class, 'destroyDoctorClinicMapping'])->name('destroyDoctorClinicMapping'); 
    Route::post('/get-clinic-doctor-relation', [DoctorClinicController::class, 'getClinicDoctorData'])->name('getClinicDoctorData'); 
    Route::post('/update-clinic-doctor-mapping', [DoctorClinicController::class, 'updateDoctorClinicMapping'])->name('updateDoctorClinicMapping'); 



    Route::get('addDoctor', [DoctorsController::class, 'showAddDoctorForm'])->name('showAddDoctorForm');
    Route::get('manageDoctor', [DoctorsController::class, 'manageDoctorView'])->name('manageDoctorView');
    Route::get('editDoctor', [DoctorsController::class, 'showeditDoctorForm'])->name('showeditDoctorForm');
    Route::post('addDoctorFunction', [DoctorsController::class, 'addDoctorFunction'])->name('addDoctorFunction');
    Route::post('getDoctorsData', [DoctorsController::class, 'getDoctorsData'])->name('getDoctorsData');
    Route::post('doctorActionFunc', [DoctorsController::class, 'doctorActionFunc'])->name('doctorActionFunc');
    Route::post('doctorDeleteFunction', [DoctorsController::class, 'doctorDeleteFunction'])->name('doctorDeleteFunction');
    Route::get('editDoctor/{id}', [DoctorsController::class, 'editDoctor'])->name('editDoctor');
    Route::put('editDoctor/{id}', [DoctorsController::class, 'updateDoctor'])->name('updateDoctor');


    Route::get('/appointment-booking', [AppointmentController::class, 'index'])->name('appointmentBooking');
    Route::post('/appointment/get-doctors', [AppointmentController::class, 'getDoctorsAndDuration'])->name('appointmentGetDoctors');
    Route::post('/appointment/add-appointment', [AppointmentController::class, 'storeAppointment'])->name('storeAppointment');
    Route::post('/appointment/available-time', [AppointmentController::class, 'getDoctorAvailableTimings'])->name('getDoctorAvailableTimings');




});



// Route::get('settingsStaffAccess', [ClinicController::class, 'settingsStaffAccessForm'])->name('settingsStaffAccessForm');
Route::get('settingsSpecialization', [ClinicController::class, 'settingsSpecializationView'])->name('settingsSpecialization');
Route::get('appointmentList', [ClinicController::class,'appointmentListView'])->name('appointmentList');
Route::get('appointmentReport', [ClinicController::class,'appointmentReportForm'])->name('appointmentReport');
Route::get('clinicReport', [ClinicController::class,'clinicReportForm'])->name('clinicReport');


// Route::get('/specializations/create', [SpecializationController::class, 'create']);
Route::get('specializations', [SpecializationController::class, 'viewServices'])->name('specializations');
Route::post('/specializations/store', [SpecializationController::class, 'store'])->name('store_specialization');
Route::post('getSpecializationData', [SpecializationController::class, 'getSpecializationData'])->name('getSpecializationData');
Route::post('specializationDeleteFunction', [SpecializationController::class, 'specializationDeleteFunction']);
Route::post('specializationStatusFunc', [SpecializationController::class, 'specializationStatusFunc']);
Route::post('specializationActionFunc', [SpecializationController::class, 'specializationActionFunc']);


Route::post('/clinic/update/{id}', [ClinicController::class, 'updateClinicFunction'])->name('updateClinicFunction');
Route::post('upload-clinic-logo', [ClinicController::class,'uploadClinicLogo'])->name('uploadClinicLogo');
Route::post('upload-clinic-images', [ClinicController::class,'uploadClinicImages'])->name('uploadClinicImages');
Route::post('delete-clinic-images', [ClinicController::class,'destroyclinicImage'])->name('destroyclinicImage');
Route::post('update-clinic-timings', [ClinicController::class,'updateClinicTimings'])->name('updateClinicTimings');
Route::post('update-clinic-specialization', [ClinicController::class,'saveClinicSpecializations'])->name('saveClinicSpecializations');
// Route::post('getClinicsData', [DoctorsController::class, 'getClinicsData'])->name('getClinicsData');



Route::get('/clinic/settings', [ClinicSettingsController::class, 'show'])->name('clinic.settings.show');
Route::post('/clinic/settings/update', [ClinicSettingsController::class, 'update'])->name('clinic.settings.update');






Route::post('/clinic/communication-settings/update', [CommunicationController::class, 'updateContacts'])->name('updateCommunicationContacts');
Route::post('/clinic/communication-settings/email-settings', [CommunicationController::class, 'updateEmailSettings'])->name('updateCommunicationEmailSetiings');
Route::post('/clinic/communication-settings/sms-settings', [CommunicationController::class, 'updateSmsSettings'])->name('updateCommunicationSmsSetiings');

Route::get('/clinic/communication-settings', [CommunicationController::class, 'show'])->name('clinic.communication.show');




Route::get('/clinic/staff/create', [StaffController::class, 'createStaff'])->name('createClinicStaff');
Route::post('/clinic/staff/store', [StaffController::class, 'storeStaff'])->name('storeClinicStaff');
Route::get('/clinic/{clinic_id}/staff/{staff_id}/edit', [StaffController::class, 'editStaff'])->name('editClinicStaff');
Route::post('/clinic/staff/update', [StaffController::class, 'updateStaff'])->name('updateClinicStaff');


Route::get('/clinic/staff-access-create', [StaffController::class, 'createStaffAccess'])->name('createClinicStaffAccess');
Route::post('/clinic/staff-access/store', [StaffController::class, 'storeStaffAccess'])->name('storeClinicStaffAccess');
















// Route::middleware(['auth'])->group(function () {
    // Display the doctor timings form
    Route::get('/doctor/{doctorId}/timings', [DoctorClinicController::class, 'showForm'])->name('doctor.timings.form');

    // Store doctor timings for a specific clinic
    Route::post('doctor/timings/update', [DoctorClinicController::class, 'updateDoctorTimings'])->name('updateDoctorTimings');

// });












// waste urls

// Route::post('/save-clinic-schedule', [ClinicController::class, 'saveSchedule']);
// Route::get('/clinic-schedule', function () {
//     return view('clinic-schedule');
// });

