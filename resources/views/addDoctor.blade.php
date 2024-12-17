@extends('layouts.adminLayout')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('content')
<div class="main-heading">
    <h2>{{ isset($doctor) ? 'Edit' : 'Add New' }} Doctor</h2>
</div>
<div class="form-card" style="padding: 0">
    <div class="heading-div">
        <h2>Doctor Details</h2>
    </div>
    <div class="content-div">

        <form id="doctor-profile-form"  method="POST"  action="{{ isset($doctor) ? route('updateDoctor', $doctor->id) : route('addDoctorFunction') }}">
            @csrf
            @if(isset($doctor))
                @method('PUT')

                @php
                $nameParts = explode(' ', $doctor->user->name, 2);
                $firstName = $nameParts[0];
                $lastName = $nameParts[1] ?? '';
            @endphp

            @endif

            <div class="flex-input-fields d-flex1">
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <div class="form-input d-flex1">
                        <span><i class="fa-solid fa-user"></i></span>
                        <input class="form-control" type="text" id="first_name" name="first_name" maxlength="50" value="{{ old('first_name', $firstName ?? '') }}">
                    </div>
                    <div class="form-validation-error">
                        @error('first_name')
                            * {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <div class="form-input d-flex1">
                        <span><i class="fa-solid fa-user"></i></span>
                        <input class="form-control" type="text" id="last_name" name="last_name" value="{{ old('last_name', $lastName ?? '') }}">
                    </div>
                    <div class="form-validation-error">
                        @error('last_name')
                            * {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email ID</label>
                    <div class="form-input d-flex1">
                        <span><i class="fa-regular fa-envelope"></i></span>
                        <input class="form-control" type="email" id="email" name="email" value="{{ old('email', $doctor->user->email ?? '') }}">
                    </div>
                    <div class="form-validation-error">
                        @error('email')
                            * {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="phone">Mobile No</label>
                    <div class="form-input d-flex1">
                        <span><i class="fa-solid fa-phone"></i></span>
                        <input class="form-control" type="text" id="phone" name="phone" value="{{ old('phone', $doctor->user->phone_no ?? '') }}">
                    </div>
                    <div class="form-validation-error">
                        @error('phone')
                            * {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="form-input d-flex1">
                        <span><i class="fa-solid fa-lock"></i></span>
                        <input class="form-control" type="text" id="password" name="doctor_password" value="{{ old('doctor_password') }}">
                    </div>
                    <div class="form-validation-error">
                        @error('doctor_password')
                            * {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="gender">Gender</label>
                    <div class="form-input">
                        <!-- Male -->
                        <input type="radio" id="male" name="gender" value="M" {{ old('gender', $doctor->gender ?? '') == 'M' ? 'checked' : '' }}>
                        <label for="male">Male</label>

                        <!-- Female -->
                        <input type="radio" id="female" name="gender" value="F" {{ old('gender', $doctor->gender ?? '') == 'F' ? 'checked' : '' }}>
                        <label class="ml-5" for="female">Female</label>

                        <!-- Other -->
                        <input type="radio" id="other" name="gender" value="O" {{ old('gender', $doctor->gender ?? '') == 'O' ? 'checked' : '' }}>
                        <label class="ml-5" for="other">Other</label>

                        <div class="form-validation-error">
                            @error('gender')
                                * {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <label for="dob">Date of Birth</label>
                    <div class="form-input">
                        <span><i class="fa-regular fa-calendar-days"></i></span>
                        <input class="form-control" type="date" id="dob" name="dob" value="{{ old('dob', $doctor->dob ?? '') }}">
                        <div class="form-validation-error">
                            @error('dob')
                                * {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group con-all">
                    <label for="alt_email">Alternative Email ID</label>
                    <div class="form-input d-flex1">
                        <span><i class="fa-regular fa-envelope"></i></span>
                        <input class="form-control" type="email" id="alt_email" name="alt_email" value="{{ old('alt_email', $doctor->alt_email ?? '') }}">
                        <div class="form-validation-error">
                            @error('alt_email')
                                * {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group con-all">
                    <label for="alt_phone">Alternative Contact</label>
                    <div class="form-input d-flex1">
                        <span><i class="fa-solid fa-phone"></i></span>
                        <input class="form-control" type="text" id="alt_phone" name="alt_phone" value="{{ old('alt_phone', $doctor->alt_phone ?? '') }}">
                        <div class="form-validation-error">
                            @error('alt_phone')
                                * {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="about_me">About Doctor</label>
                    <div class="form-input d-flex1">
                        <span><i class="fa-solid fa-user"></i></span>
                        <input class="form-control" type="text" id="about_me" name="about_me" value="{{ old('about_me', $doctor->about_me ?? '') }}">
                        <div class="form-validation-error">
                            @error('about_me')
                                * {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="pan_number">Pan Number</label>
                    <div class="form-input d-flex1">
                        <span><i class="fa-solid fa-id-card"></i></span>
                        <input class="form-control" type="text" id="pan_number" name="pan_number" value="{{ old('pan_number', $doctor->pan_number ?? '') }}">
                        <div class="form-validation-error">
                            @error('pan_number')
                                * {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <div class="form-input d-flex1">
                        <span><i class="fa-solid fa-location-pin"></i></span>
                        <input type="text" class="form-control" id="address" name="address" maxlength="60" value="{{ old('address', $doctor->address ?? '') }}">
                        <div class="form-validation-error">
                            @error( 'address')
                            * {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="pincode">Pin Code</label>
                    <div class="form-input d-flex1">
                        <span><i class="fa-solid fa-map-pin"></i></span>
                        <input class="form-control" type="text" id="pincode" name="pincode" maxlength="6" value="{{ old('pincode', $doctor->pincode ?? '') }}">
                        <div class="form-validation-error">
                            @error('pincode')
                            * {{ $message }}
                        @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="district">District</label>
                    <div class="form-input d-flex1">
                        <span><i class="fa-solid fa-building"></i></span>
                        <input class="form-control" type="text" id="district" name="district" readonly value="{{ old('district', $doctor->district ?? '') }}">
                        <div class="form-validation-error">
                            @error('district')
                            * {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="state">State</label>
                    <div class="form-input d-flex1">
                        <span><i class="fa-solid fa-mountain-city"></i></span>
                        <input class="form-control" type="text" id="state" name="state" readonly value="{{ old('state', $doctor->state ?? '') }}">
                        <div class="form-validation-error">
                            @error('state')
                            * {{ $message }}
                        @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="country">Country</label>
                    <div class="form-input d-flex1">
                        <span><i class="fa-solid fa-globe"></i></span>
                        <input class="form-control" type="text" id="country" name="country" readonly value="{{ old('country', $doctor->country ?? '') }}">
                        <div class="form-validation-error">
                            @error('country')
                                * {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="locality">Locality</label>
                    <div class="form-input d-flex1">
                        <span><i class="fa-solid fa-location-crosshairs"></i></span>
                        <select class="form-control" name="locality" id="localitySelect">
                            <option value="" selected disabled>Select Locality</option>
                            @isset($doctor)
                            <option value="{{$doctor->locality}}" selected disabled>{{ $doctor->locality }}</option>
                            @endisset
                        </select>
                        <div class="form-validation-error">
                            @error('locality')
                                * {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>



                <div class="form-group">
                    <label for="locality">Specialization</label>
                    <div class="d-flex1">
                        <select class="form-control" id="SpecializationSelect" name="specializations[]" multiple="multiple">
                          
                        
                            @foreach($specializations as $specialization)
                                <option value="{{ $specialization->id }}" 
                                    {{ isset($doctor) ? (in_array($specialization->id, $doctorSpecializations) ? 'selected' : '' ) : "" }}>
                                    {{ $specialization->specialization_name }}
                                </option>
                            @endforeach
                        </select>
                        
                        
                        <div class="form-validation-error">
                            @error('specializations')
                                * {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
                


            </div>
        </form>

        {{-- @if($errors->any())
            {!! implode('', $errors->all('<div>:message</div>')) !!}
        @endif --}}

        <div class="button-appointment-drprofile">
            <button type="submit" form="doctor-profile-form"> {{ isset($doctor) ? "Update" : "Add" }} Profile</button>
        </div>
    </div>
</div>

@endsection

@section("javaScript")
<script>
    const pincodeInput = document.getElementById("pincode");

    pincodeInput.addEventListener("input", function () {
        if (pincodeInput.value.length === 6) {
            if (/^\d+$/.test(pincodeInput.value)) {
                getPlace(pincodeInput.value);
            } else {
                alert("Pincode should contain only numbers.");
            }
        }
    });

    function getPlace(pinCode) {
        $.ajax({
            url: "{{ route('getPlaceByPincode') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                pincode: pinCode
            },
            success: function (response) {
                if (response.error) {
                    alert(response.message);
                    resetFields();
                } else {
                    // $("#txtCity").val(response.city);
                    $("#district").val(response.district);
                    $("#state").val(response.state);
                    $("#country").val(response.country);

                    // Populate locality dropdown
                    const localitySelect = document.getElementById("localitySelect");
                    localitySelect.innerHTML = '<option value="" selected disabled>Select Locality</option>';
                    response.locality.forEach(function (element) {
                        const option = document.createElement("option");
                        option.text = element;
                        option.value = element;
                        localitySelect.appendChild(option);
                    });
                }
            }
        });
    }

    function resetFields() {
        // $("#txtCity").val("");
        $("#district").val("");
        $("#state").val("");
        $("#country").val("");
        const localitySelect = document.getElementById("localitySelect");
        localitySelect.innerHTML = '<option value="" selected disabled>Select Locality</option>';
    }
</script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(document).ready(function() {
        $('#SpecializationSelect').select2();
    });
</script>
@endsection
