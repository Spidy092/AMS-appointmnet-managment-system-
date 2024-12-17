@extends('layouts.adminLayout')

@section('css')
<style>


.clinic-settings-form .form-input input, .form-input select {max-width: 300px !important;}
.form-input select {  border: 1px solid #b7b7b7;border-radius: 0 5px 5px 0;background-color: #fff;width: 100%;padding: 7px 10px;}
.clinic-settings-form  .form-input span:last-child { width: 100%;  max-width: 100px; text-align: center; background: #f9f9f9f9; color: #000;  border: 1px solid #ccc;}


.Clinic-staff   .form-input input {max-width: 300px;}
.Clinic-staff .form-input span:last-child { width: 100%;  max-width: 100px; text-align: center; background: #f9f9f9f9; color: #000;  border: 1px solid #ccc;}

#clinic-settings-form .flex-input-fields {max-width: 600px;}
.flex-input-fields .form-group{max-width: 100%;}
.Clinic-staff .form-input span{min-width: 40px; display: flex; align-items: center; justify-content: center;}



</style>
@endsection


@section('content')

<div class="main-heading">
    <h2> {{ isset($staff) ? "Edit" : "Add" }} Staff</h2>
</div>
<div class="form-card">
    <div class="content-div">

        <form id = "clinic-settings-form" action="{{ isset($staff) ? route('updateClinicStaff') : route('storeClinicStaff') }}" class="Clinic-staff" method="POST">
            @csrf
            <div class="flex-input-fields ">
                @isset($staff)
                    <input type="hidden" value="{{ $staff->user->id }}" name='staff_user_id'>
                    <input type="hidden" value="{{ $staff->id }}" name='staff_id'>
                    <input type="hidden" value="{{ $clinicId }}" name='clinic_id'>
                @endisset
                @if (!isset($staff))
                    <div class="form-group">
                        <label for="staff_clinic">Clinic Name</label>
                        <div class="form-input d-flex1">
                            <span><i class="fa-solid fa-id-card"></i></span> 
                                    <select name="clinic_id" id="staff_clinic">
                                        <option value="">Select Clinic Name</option>
                                        @foreach ($clinics as $clinic )
                                        <option value="{{ $clinic->id }}"> {{ $clinic->clinic_name }}</option>
                                        @endforeach
                                    </select>
                        </div>
                    </div>
                @endif
                <div class="form-group">
                    <label for="staff_name">Staff Name</label>
                    <div class="form-input d-flex1">
                       <span><i class="fa-regular fa-user"></i></span> <input class="form-control" type="name" id="staff_name" name="staff_name" value="{{ old('staff_name', isset($staff) ? $staff->user->name : '') }}"  >
                       
                    </div>
                    <div class="form-validation-error">
                        @error("staff_name")
                            *{{$message}}
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="staff_number">Mobile Number</label>
                    <div class="form-input d-flex1">
                       <span><i class="fa-solid fa-mobile"></i></span> <input class="form-control" type="name" id="staff_number" name="staff_number" value="{{ old('staff_number', isset($staff) ? $staff->user->phone_no : '') }}"  >
                       
                    </div>
                    <div class="form-validation-error">
                        @error("staff_number")
                            *{{$message}}
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="staff_email">Email Id</label>
                    <div class="form-input d-flex1">
                       <span><i class="fa-regular fa-envelope"></i></span> <input class="form-control" type="name" id="staff_email" name="staff_email" value="{{ old('staff_email', isset($staff) ? $staff->user->email : '') }}" > 
                    </div>
                    <div class="form-validation-error">
                        @error("staff_email")
                            *{{$message}}
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="staff_password">Password</label>
                    <div class="form-input d-flex1">
                       <span><i class="fa-solid fa-lock"></i></span> <input class="form-control" type="text" id="staff_password" name="staff_password" value="{{ old('staff_password') }}" >
                       
                    </div>
                    <div class="form-validation-error">
                        @error("staff_password")
                            *{{$message}}
                        @enderror
                    </div>
                </div>
                
                
    
            </div>
    
            
            
            <div class="button-appointment-drprofile">
                 <button>
                    <!-- <i class="fa-solid fa-rotate-right"></i> -->
                     Submit</button>
            </div>
        </form>
        @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    </div>
</div>

@endsection
