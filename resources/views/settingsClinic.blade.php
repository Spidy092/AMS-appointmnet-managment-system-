@extends('layouts.adminLayout')

@section('css')
<style>
    

.clinic-settings-form .form-input input {max-width: 300px;}
.clinic-settings-form  .form-input span:last-child { width: 100%;  max-width: 100px; text-align: center; background: #f9f9f9f9; color: #000;  border: 1px solid #ccc;}
#clinic-settings-form .flex-input-fields {max-width: 600px;}
.flex-input-fields .form-group{max-width: 100%;}




</style>
@section('content')
<div class="main-heading">
    <h2>Clinic Settings</h2>
</div>
<div class="form-card">

    <div class="clinic-settings-container">
        <!-- Clinic selection form -->
        <form method="GET" action="{{ route('clinic.settings.show') }}">
            <div class="form-group">
                <label for="clinic_id">Select Clinic</label>
                <select name="clinic_id" id="clinic_id" class="form-control" onchange="this.form.submit()">
                    <option value="">-- Select a Clinic --</option>
                    @foreach ($clinics as $clinic)
                        <option value="{{ $clinic->id }}" 
                            {{ request('clinic_id') == $clinic->id ? 'selected' : '' }}>
                            {{ $clinic->clinic_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>

        <!-- Clinic settings form (if a clinic is selected) -->
        @if($selectedClinic)
            <form method="POST" action="{{ route('clinic.settings.update') }}">
                @csrf
                <input type="hidden" name="clinic_id" value="{{ $selectedClinic->id }}">

                <!-- Time Slots input -->
                <div class="form-group">
                    <label for="time_slots">Time Slots</label>
                    <div class="form-input d-flex1">
                        <span><i class="fa-solid fa-business-time"></i></span>
                        <input class="form-control" type="number" id="time_slots" name="time_slots" 
                            value="{{ old('time_slots', $selectedClinic->clinicSetting ? $selectedClinic->clinicSetting->time_slot_minutes : '') }}" required>
                        <span class="unit ml-5">minutes</span>
                    </div>
                    <!-- Display validation error for time_slots -->
                    <div class="form-validation-error">
                        @error("time_slots")
                            *{{ $message }}
                        @enderror
                    </div>
                </div>

                <!-- Remainder To Patient input -->
                <div class="form-group">
                    <label for="remainder_to_patient">Remainder To Patient</label>
                    <div class="form-input">
                        <div class="remainder-sel-container">
                            <input type="radio" id="remainder_yes" name="remainder_to_patient" value="1" 
                                {{ old('remainder_to_patient', $selectedClinic->clinicSetting ? $selectedClinic->clinicSetting->remainder_to_patient : null) == 1 ? 'checked' : '' }}>
                            <label for="remainder_yes">Yes</label>
                        </div>
                        <div>
                             <input type="radio" id="remainder_no" name="remainder_to_patient" value="0" 
                                {{ old('remainder_to_patient', $selectedClinic->clinicSetting ? $selectedClinic->clinicSetting->remainder_to_patient : null) == 0 ? 'checked' : '' }}>
                            <label for="remainder_no">No</label>
                        </div>
                    </div>
                    <!-- Display validation error for remainder_to_patient -->
                    <div class="form-validation-error">
                        @error("remainder_to_patient")
                            *{{ $message }}
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Update</button>
            </form>

        @else
            <p>Please select a clinic to view and update settings.</p>
        @endif
    </div>
</div>
@endsection




@section('javaScript')

@endsection