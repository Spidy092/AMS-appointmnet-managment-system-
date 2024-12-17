@extends('layouts.adminLayout')



@section('content')



<dialog id="calendar-dialog" class="form-card">

    <div class="heading-div calendar-dialog-heading">
        <h2><span><img src="{{ asset('public/assets/images/calendar/user.png') }}" alt="User icon"></span>patientname</h2>
        <span onclick="handleCalenderDialog('close')"><img src="{{ asset('public/assets/images/icons/close.png') }}" alt="Close icon"></span>
    </div>
    <div class="calendar-dialog-content">
        <p title="Crown & Bridge."><span><img src="{{ asset('public/assets/images/calendar/heart.png') }}" alt="Treatment plan"></span>Treatment Plan: <span >Crown & Bridge long name</span></p>
        <p title="14-11-2024 9:52 AM (30 minutes)"><span><img src="{{ asset('public/assets/images/calendar/time-left.png') }}" alt="time icon"></span><span >14-11-2024 9:52 AM (30 minutes)</span></p>
        <p title="Scheduled"><span><img src="{{ asset('public/assets/images/calendar/updates.png') }}" alt="status icon"></span>Status:<span >Scheduled</span></p>
        <p title="Doctor"><span><img src="{{ asset('public/assets/images/calendar/stethoscope.png') }}" alt="status icon"></span>Assigned To:<span >Doctor name</span></p>
    </div>
    <div class="calendar-dialog-footer">
        <div style="display: flex;  align-items: center; justify-content: center; gap: 10px;">
            <button><span><img src="{{ asset('public/assets/images/calendar/close.png') }}" alt="Close icon"></span></button>
            <button><span><img src="{{ asset('public/assets/images/calendar/edit.png') }}" alt="edit icon"></span></button>
        </div>
        <button>Engage Now</button>
    </div>

</dialog>

<div class="main-heading">
    <h2>Add Appointment</h2>
</div>
<div class="form-card calendar-page">
    
    <div id="calendar"></div>
</div>
<!-- Appointment Popup -->
<div class="d-flex">
    <div id="appointment_popup" class="popup-container">
            <div class="heading-div">
                <h2>Appointment Details</h2>
                <span id="cancel_appointment" class="close"><img src="{{ asset('public/assets/images/icons/close.png') }}" alt="close"></span>
            </div>
            <form id="appointment_form" class="popup-form-section" method="POST" action="{{ route('storeAppointment') }}" onsubmit="return  handleAppointmentForm(this)">
                @csrf
                <div class="popup-form-group">
                    <label class="popup-form-label" for="patient_name">Patient Name</label>
                    <div class="form-input">
                        <span><i class="fa-solid fa-user"></i></span>
                        <input class="popup-form-control" type="text" id="patient_name" name="patient_name" >
                        <div class="form-validation-error" id="error-patient_name"></div>
                    </div>
                </div>
            
                <div class="popup-form-group grid1">
                    <label class="popup-form-label" for="contact_number">Contact Number</label>
                    <div class="form-input">
                        <span><i class="fa-solid fa-phone"></i></span>
                        <input class="popup-form-control" type="tel" id="contact_number" name="contact_number" >
                        <div class="form-validation-error" id="error-contact_number"></div>
                    </div>
                </div>
            
                <div class="popup-form-group">
                    <label class="popup-form-label" for="email_address">Email ID</label>
                    <div class="form-input">
                        <span><i class="fa-regular fa-envelope"></i></span>
                        <input class="popup-form-control" type="email" id="email_address" name="email_address" >
                        <div class="form-validation-error" id="error-email_address"></div>
                    </div>
                </div>
            
                {{-- <div class="popup-form-group">
                    <label class="popup-form-label" for="consultation_reason">Consultation For</label>
                    <div class="form-input">
                        <span><i class="fa-solid fa-tag"></i></span>
                        <input class="popup-form-control" type="text" id="consultation_reason" name="consultation_reason" required>
                    </div>
                </div> --}}
            
                <div class="popup-form-group">
                    <label class="popup-form-label" for="appointment_date">Scheduled Date</label>
                    <div class="form-input">
                        <span><i class="fa-regular fa-calendar-days"></i></span>
                        <input class="popup-form-control" type="date" id="appointment_date" name="appointment_date" readonly>
                        <div class="form-validation-error" id="error-appointment_date"></div>
                    </div>
                </div>
            
              
            
                {{-- <div class="popup-form-group">
                    <label class="popup-form-label">To Time</label>
                    <div class="form-input">
                        <span><i class="fa-regular fa-clock"></i></span>
                        <input class="popup-form-control" type="time" id="end_time" name="end_time" readonly>
                    </div>
                </div> --}}
            
                <div class="popup-form-group">
                    <label class="popup-form-label" for="sel_clinic">Clinic/Hospital</label>
                    <div class="form-input">
                        <span><i class="fa-solid fa-stethoscope"></i></span>
                        <select name="sel_clinic" id="sel_clinic" class="popup-form-control" onchange="handleGetDoctor(this, '{{ route('appointmentGetDoctors') }}', '{{ csrf_token() }} ')">
                            <option value="">Select Clinic</option>
                            @foreach($clinics as $clinic)
                                <option value="{{ $clinic->id }}">{{ $clinic->clinic_name }}</option>
                            @endforeach
                        </select>
                        <div class="form-validation-error" id="error-sel_clinic"></div>
                    </div>
                </div>
                <div class="popup-form-group">
                    <label class="popup-form-label" for="sel_doctor">Doctor/Consultation</label>
                    <div class="form-input">
                        <span><i class="fa-solid fa-stethoscope"></i></span>
                        <select name="sel_doctor" id="sel_doctor" class="popup-form-control" onchange="handleGetDoctorAvailableTime(this, '{{ route('getDoctorAvailableTimings') }}', '{{ csrf_token() }}')">
                            <option value="">Select Doctor</option>
                        </select>
                        <div class="form-validation-error" id="error-sel_doctor"></div>
                    </div>
                </div>
            
                <div class="popup-form-group">
                    <label class="popup-form-label" for="durationSelect">Duration (minutes)</label>
                    <div class="form-input">
                        <span><i class="fa-regular fa-clock"></i></span>
                        <select class="popup-form-control" id="durationSelect" disabled>
                        </select>
                        <input type="hidden" name="appointment_duration" id="appointment_duration">
                        <div class="form-validation-error" id="error-appointment_duration"></div>
                    </div>
                </div>
                <div class="popup-form-group">
                    <label class="popup-form-label" for="start_time">From Time</label>
                    <div class="form-input">
                        <span><i class="fa-regular fa-clock"></i></span>
                        <input class="popup-form-control" type="time" id="start_time" name="start_time" >
                        <div class="form-validation-error" id="error-start_time"></div>
                    </div>
                </div>
            
                
                <div class="button-appointment-drprofile">
                    <button id="save_appointment">Save</button>
                </div>
            </form>
            
        {{-- <div class="popup-button-group">
            <button type="button" class="popup-btn popup-btn-cancel" id="cancel_appointment">
                <span><i class="fa-solid fa-xmark"></i></span>Cancel
            </button>
            <button type="submit" class="popup-btn" id="save_appointment">
                <span><i class="fa-solid fa-check"></i></span>Save
            </button>
        </div> --}}
    </div>
</div>

@endsection

@section('javaScript')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>

<script src="{{asset('public/assets/js/scriptCall.js')}}"></script>

<script src="{{ asset('public/assets/js/appointmentBooking.js') }}"></script>

@endsection