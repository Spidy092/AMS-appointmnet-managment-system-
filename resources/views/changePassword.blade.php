@extends('layouts.adminLayout')

@section('css')
    <style>
        .button-appointment-drprofile {position: relative;display: inline-block;margin-top: 20px;}
        .form-input {position: relative}
        .text-danger{font-size: 13px;color: rgb(218 51 68);position: absolute; top: 37px; left: 50px}
    </style>

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
<div class="form-card">
    <div class="heading-div">
        <h2> Change Password</h2>
    </div>
    <div class="main-inner">
        <form id = "clinic-settings-form" name="valid_cont" class="content-div Clinic-staff" id="send" method="POST" action="{{ route('updatePassword')}}" >
            @csrf

            <div class="flex-input-fields">
            </div>
            <div class="form-group ">
                <label for="OldPassword">Old Password*</label>
                <div class="form-input d-flex1">
                    <span class="icon"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" id="OldPassword" class="form-control"  placeholder="Old Password" name="OldPassword" maxlength="50" value="{{old('OldPassword')}}">
                    <div class="text-danger">
                        @error('OldPassword')
                            * {{$message}}
                        @enderror
                    </div>
                 </div>
                {{-- <input type="password" id="OldPassword" class="form-control" placeholder="Old Password" name="OldPassword" maxlength="50" value="{{old('OldPassword')}}"> --}}
            </div>


            <div class="form-group ">
                <label for="NewPassword">New Password*</label>
                <div class="form-input d-flex1">
                    <span class="icon"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" id="password" class="form-control password" placeholder="New Password" name="NewPassword" maxlength="50" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters">
                    <ul class="helper-text">
                        <li class="length">Must be at least 8 characters long.</li>
                        <li class="lowercase">Must contain a lowercase letter.</li>
                        <li class="uppercase">Must contain an uppercase letter.</li>
                        <li class="number">Must contain a number.</li>
                        <li class="special">Must contain a special character.</li>
                    </ul>
                    <div class="text-danger">
                        @error('NewPassword')
                            * {{$message}}
                        @enderror
                    </div>
                </div>
                {{-- <input type="password" id="NewPassword" class="form-control password" placeholder="New Password" name="NewPassword" maxlength="50" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"> --}}

            </div>
            <div class="form-group ">
                <label for="ConfirmPassword">New Password*</label>
                <div class="form-input d-flex1">
                    <span class="icon"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" id="ConfirmPassword" class="form-control"  placeholder="Confirm Password*" name="ConfirmPassword" maxlength="50" >
                    <div class="text-danger">
                        @error('ConfirmPassword')
                            * {{$message}}
                        @enderror
                    </div>
                 </div>
                {{-- <input type="password" id="ConfirmPassword" class="form-control"  placeholder="Confirm Password*" name="ConfirmPassword" maxlength="50" > --}}
            </div>
            <div class="button-input button-appointment-drprofile">
                <button class="btn-block" name="txtsubmit">Update</button>
            </div>
        </form>
    </div>
 </div>
 <script src="{{ asset('public/assets/js/passwordValidation.js') }}"></script>
@stop
