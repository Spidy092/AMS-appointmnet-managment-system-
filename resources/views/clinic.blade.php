@extends('layouts.adminLayout')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"
        integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


@endsection



@section('content')

    @isset($clinic)
        <dialog id = "add-clinic-doctor-dialog">
            <div class="heading-div timing-dialog-header-div">
                <h2>Add Doctors</h2>
                <span onclick="handleAddDoctorDialogBtn('close')"> <img src="{{ asset('public/assets/images/icons/close.png') }}"
                        alt="close icon">
                </span>
            </div>
            <form action="{{ route('doctorClinicMapping') }}" method="POST" id="add-clinic-doctor-form">
                @csrf
                <input type="hidden" value="{{ $clinic['id'] }}" name="clinic">
                <div class="form-group" id="doc-sel2">
                    <label for="clinic_doctor">Select Doctor</label>
                    <select name="doctor" id="clinic_doctor" class="form-control" 
                        onchange="handleSelectDoctor(this, '{{ route('search.specializations') }}', '{{ csrf_token() }}', '{{ $clinic['id'] }}')">
                    </select>
                    <div class="form-validation-error" id="doctor-error"></div>
                </div>
            
                <p id="specializations-error-message" style="display: none"></p>
            
                <div class="form-group" id="spe-sel2" style="visibility:hidden;">
                    <label for="clinic_specialization">Select Specializations</label>
                    <select name="specializations[]" id="clinic_specialization" class="form-control" multiple>
                    </select>
                    <div class="form-validation-error" id="specializations-error"></div>
                </div>
            
                <div>
                    <button type="submit" id="submit-button">Submit</button>
                </div>
            </form>
            
        </dialog>
        <dialog id = "update-clinic-doctor-dialog">
            <div class="heading-div timing-dialog-header-div">
                <h2>Add Doctors</h2>
                <span onclick="handleDialog('close', 'update-clinic-doctor-dialog' )"> <img src="{{ asset('public/assets/images/icons/close.png') }}"
                        alt="close icon">
                </span>
            </div>
            <form action="{{ route('updateDoctorClinicMapping') }}" method="POST" id="update-clinic-doctor-form" onsubmit="return handleUpdateClinicDoctorMappingForm(this)">
                @csrf
                <input type="hidden" value="{{ $clinic['id'] }}" name="clinic">
                <input type="hidden" name="doctor" value="" id = "doctor-ClinicDoctorMaping">
                <div class="form-group" id="doc-sel2">
                    <label for="update_clinic_doctor">Select Doctor</label>
                    <select id="update_clinic_doctor" class="form-control">
                    </select>
                    <div class="form-validation-error" id="update-doctor-error"></div>
                </div>
            
                <p id="specializations-error-message" style="display: none"></p>
            
                <div class="form-group" id="update-spe-sel2" >
                    <label for="update_clinic_specialization">Select Specializations</label>
                    <select name="specializations[]" id="update_clinic_specialization" class="form-control" multiple>
                    </select>
                    <div class="form-validation-error" id="update-specializations-error"></div>
                </div>
            
                <div>
                    <button type="submit" id="update-submit-button">Submit</button>
                </div>
            </form>
            
        </dialog>
    @endisset
    @isset($clinic)
        <dialog id = "clinic-timing-dialog">
            <div class="timing-dialog-header-div heading-div">
                <h2>Clinic Timings</h2>
                <span onclick="handleChangeTimeDialogBtn('close')"> <img
                        src="{{ asset('public/assets/images/icons/close.png') }}" alt="close icon"></span>
            </div>



            <form action="{{ route('updateClinicTimings') }}" name = "clinicTimingForm"
                onsubmit="return handleOnClinicTimingForm('{{ csrf_token() }}', '{{ $clinic['id'] }}')" method="POST"
                id = "clinicTimingForm">

                <table class="clinic-timings">
                    <thead>
                        <tr>
                            <th>Practice is open</th>
                            <th colspan="2">Morning Session</th>
                            <th colspan="2">Evening Session</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th>From</th>
                            <th>To</th>
                            <th>From</th>
                            <th>To</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $Weeks = [
                                'sun' => 'Sunday',
                                'mon' => 'Monday',
                                'tue' => 'Tuesday',
                                'wed' => 'Wednesday',
                                'thu' => 'Thursday',
                                'fri' => 'Friday',
                                'sat' => 'Saturday',
                            ];
                            $formatTime = function ($time) {
                                return $time ? date('h:i a', strtotime($time)) : '';
                            };
                        @endphp

                        @foreach ($Weeks as $shortDay => $fullDay)
                            @php
                                $timing = collect($clinic['clinic_timings'])->firstWhere('day', $shortDay);

                                $isChecked = $timing ? 'checked' : '';
                                $isVisible = $timing ? 'visible' : 'hidden';
                            @endphp

                            <tr>
                                <td class="day-name">
                                    <input type="checkbox" id="clinic-timing-{{ $shortDay }}"
                                        name="clinic-timing-{{ $shortDay }}" {{ $isChecked }}
                                        onchange="toggleVisibility('{{ $shortDay }}')">
                                    {{ $fullDay }}
                                </td>
                                <td><input type="text" class="time-pickable {{ $shortDay }}-time"
                                        style="visibility: {{ $isVisible }};"
                                        value="{{ $formatTime($timing['morning_from'] ?? '') }}" readonly></td>
                                <td><input type="text" class="time-pickable {{ $shortDay }}-time"
                                        style="visibility: {{ $isVisible }};"
                                        value="{{ $formatTime($timing['morning_to'] ?? '') }}" readonly></td>
                                <td><input type="text" class="time-pickable {{ $shortDay }}-time"
                                        style="visibility: {{ $isVisible }};"
                                        value="{{ $formatTime($timing['evening_from'] ?? '') }}" readonly></td>
                                <td><input type="text" class="time-pickable {{ $shortDay }}-time"
                                        style="visibility: {{ $isVisible }};"
                                        value="{{ $formatTime($timing['evening_to'] ?? '') }}" readonly></td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>

                <div class="timing-save-button">
                    <button id = "clinicTimingsFormBtn">Save</button>
                </div>
            </form>


        </dialog>
    @endisset

    <div class="main-heading">
        <h2>
            @if (isset($clinic))
                Update Clinic - {{$clinic['clinic_name']}}
            @else
                Add New Clinic
            @endif
        </h2>

    </div>

    @isset($clinic)
        <div class="add-clinic-top">
            <div class="add-clinics-main form-card">

                <div class="heading-div">
                    <h2>Clinic Images</h2>

                </div>
                <div class="add-clinics" id = "logo-image-container">
                    <div class="add-clinic">
                        @if ($clinic['logo_url'] == null)
                            <img src="{{ asset('public/assets/images/clinic/icons/hospital.png') }}" alt="clinic-img">
                        @else
                            <img src="{{ asset('public/' . $clinic['logo_url']) }}" alt="Image">
                        @endif
                        <p>Add Clinic Logo</p>

                        <a class="hover-text" href="#ex6"
                            onclick="handleClinicAddImgDialog('open', 'clinic-logo-dialog')">Add/Delete images</a>


                    </div>
                    @if (
                        $clinic['clinic_image1_thumb'] ||
                            $clinic['clinic_image2_thumb'] ||
                            $clinic['clinic_image3_thumb'] ||
                            $clinic['clinic_image4_thumb'] ||
                            $clinic['clinic_image5_thumb']
                    )
                        <div class="scroll" id = "addCarousel">
                            <div class=" owl-carousel  add-clinic" id = "clinic-images">

                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($clinic["clinic_image{$i}_thumb"])
                                        <img src="{{ asset("public/{$clinic["clinic_image{$i}_thumb"]}") }}"
                                            alt="clinic-img">
                                    @endif
                                @endfor

                            </div>
                            <a class="hover-text" href="#ex7"
                                onclick="handleClinicAddImgDialog('open', 'clinic-image-dialog')">Add/Delete images</a>
                        </div>
                    @else
                        <div class="add-clinic">
                            <img src="{{ asset('public/assets/images/clinic/icons/photo.png') }}" alt="clinic-img">
                            <p>Add Clinic Photos</p>
                            <a class="hover-text" href="#ex7"
                                onclick="handleClinicAddImgDialog('open', 'clinic-image-dialog')">Add/Delete images</a>

                        </div>
                    @endif

                </div>



            </div>

            <div class="add-clinics-main form-card">
                <div class="heading-div specialization-heading">
                    <h2>Services</h2>
                    <button id="save-button"
                        onclick="handleSpecializationSave('{{ csrf_token() }}', '{{ $clinic['id'] }}')">Save</button>
                </div>
                <form id="specialization-form" method="POST" action="{{ route('saveClinicSpecializations') }}">
                    <ul class="scrollable-list">
                        @foreach ($specializations as $specialization)
                            <li>
                                <label>
                                    <input type="checkbox" id="specialization-{{ $specialization->id }}"
                                        class="parent-checkbox" value="{{ $specialization->id }}"
                                        data-target="#child-list-{{ $specialization->id }}"
                                        @if (in_array($specialization->id, $selectedSpecializations)) checked @endif>
                                    {{ $specialization->specialization_name }}
                                </label>
                                @if ($specialization->children->count() > 0)
                                    <ul id="child-list-{{ $specialization->id }}" class="child-scrollable-list"
                                        style="display: none;">
                                        @foreach ($specialization->children as $child)
                                            <li>
                                                <label>
                                                    <input type="checkbox" id="child-{{ $child->id }}"
                                                        class="child-checkbox" value="{{ $child->id }}"
                                                        data-parent="specialization-{{ $specialization->id }}"
                                                        @if (in_array($child->id, $selectedSpecializations)) checked @endif>
                                                    {{ $child->specialization_name }}
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </form>
            </div>

        </div>
    @endisset

    <div class="clinic-profile form-card">
        <div class="heading-div">
            <h2>Clinic Details</h2>


        </div>
        <div class="clinic-profile-card">
            <form class="content-div"
                action="{{ isset($clinic) ? route('updateClinicFunction', ['id' => $clinic['id']]) : route('addClinicFunction') }}"
                method="POST">
                @csrf
                <div class="flex-input-fields d-flex1">
                    <div class="form-group">
                        <label for="clinicName">Clinic Name</label>
                        <div class="form-input d-flex1">
                            <span><i class="fa-solid fa-hospital"></i></span>
                            <input class="form-control" type="text" id="clinicName" name="clinic_name"
                                value="{{ isset($clinic) ? $clinic['clinic_name'] : old('clinic_name') }}">
                        </div>
                        <div class="form-validation-error">
                            @error('clinic_name')
                                *{{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="clinicTagline">Clinic Tagline</label>
                        <div class="form-input d-flex1">
                            <span><i class="fa-solid fa-quote-left"></i></span>
                            <input class="form-control" type="text" id="clinicTagline" name="clinic_tag_line"
                                value="{{ isset($clinic) ? $clinic['clinic_tag_line'] : old('clinic_tag_line') }}">
                        </div>
                        <div class="form-validation-error">
                            @error('clinic_tag_line')
                                *{{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="contactNumber1">Contact Number 1</label>
                        <div class="form-input d-flex1">
                            <span><i class="fa-solid fa-phone"></i></span>
                            <input class="form-control" type="text" id="contactNumber1" name="contact_no_1"
                                value="{{ isset($clinic) ? $clinic['contact_no_1'] : old('contact_no_1') }}">
                        </div>
                        <div class="form-validation-error">
                            @error('contact_no_1')
                                *{{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="contactNumber2">Contact Number 2</label>
                        <div class="form-input d-flex1">
                            <span><i class="fa-solid fa-phone"></i></span>
                            <input class="form-control" type="text" id="contactNumber2" name="contact_no_2"
                                value="{{ isset($clinic) ? $clinic['contact_no_2'] : old('contact_no_2') }}">
                        </div>
                        <div class="form-validation-error">
                            @error('contact_no_2')
                                *{{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div class="form-group con-all">
                        <label for="gstin">GSTIN</label>
                        <div class="form-input d-flex1">
                            <span><i class="fa-solid fa-id-card"></i></span>
                            <input class="form-control" type="text" id="gstin" name="gstin"
                                value="{{ isset($clinic) ? $clinic['gstin'] : old('gstin') }}">
                        </div>
                        <div class="form-validation-error">
                            @error('gstin')
                                *{{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div class="form-group con-all">
                        <label for="aboutClinic">About Clinic</label>
                        <div class="form-input d-flex1">
                            <span><i class="fa-solid fa-info-circle"></i></span>
                            <input class="form-control" type="text" id="aboutClinic" name="about_clinic"
                                value="{{ isset($clinic) ? $clinic['about_clinic'] : old('about_clinic') }}">
                        </div>
                        <div class="form-validation-error">
                            @error('about_clinic')
                                *{{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="webAddress">Web Address</label>
                        <div class="form-input d-flex1">
                            <span><i class="fa-solid fa-globe"></i></span>
                            <input class="form-control" type="text" id="webAddress" name="web_address"
                                value="{{ isset($clinic) ? $clinic['web_address'] : old('web_address') }}">
                        </div>
                        <div class="form-validation-error">
                            @error('web_address')
                                *{{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <div class="form-input d-flex1">
                            <span><i class="fa-solid fa-map-marker-alt"></i></span>
                            <input class="form-control" type="text" id="address" name="address"
                                value="{{ isset($clinic) ? $clinic['address'] : old('address') }}">
                        </div>
                        <div class="form-validation-error">
                            @error('address')
                                *{{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="pincode">Pincode</label>
                        <div class="form-input d-flex1">
                            <span><i class="fa-solid fa-location-arrow"></i></span>
                            <input class="form-control" type="text" id="pincode" name="pincode"
                                value="{{ isset($clinic) ? $clinic['pincode'] : old('pincode') }}">
                        </div>
                        <div class="form-validation-error">
                            @error('pincode')
                                *{{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="country">Country</label>
                        <div class="form-input d-flex1">
                            <span><i class="fa-solid fa-flag"></i></span>
                            <input class="form-control" type="text" id="country" name="country"
                                value="{{ isset($clinic) ? $clinic['country'] : old('country') }}">
                        </div>
                        <div class="form-validation-error">
                            @error('country')
                                *{{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="state">State</label>
                        <div class="form-input d-flex1">
                            <span><i class="fa-solid fa-map"></i></span>
                            <input class="form-control" type="text" id="state" name="state"
                                value="{{ isset($clinic) ? $clinic['state'] : old('state') }}">
                        </div>
                        <div class="form-validation-error">
                            @error('state')
                                *{{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="district">City</label>
                        <div class="form-input d-flex1">
                            <span><i class="fa-solid fa-city"></i></span>
                            <input class="form-control" type="text" id="district" name="district"
                                value="{{ isset($clinic) ? $clinic['district'] : old('district') }}">
                        </div>
                        <div class="form-validation-error">
                            @error('district')
                                *{{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="locality">Locality</label>
                        <div class="form-input d-flex1">
                            <span><i class="fa-solid fa-building"></i></span>
                            <input class="form-control" type="text" id="locality" name="locality"
                                value="{{ isset($clinic) ? $clinic['locality'] : old('locality') }}">
                        </div>
                        <div class="form-validation-error">
                            @error('locality')
                                *{{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="fees-settings">
                            <label>Fees </label>
                            <div class="form-input d-flex1" style="gap: 5px;">
                                <input class="form-control" type="radio" id="radio-nofees" name="fees_based_on"
                                    value="nofee"
                                    {{ isset($clinic) ? ($clinic['fees_based_on'] == 'nofee' ? 'checked' : '') : (old('fees_based_on') == 'nofee' ? 'checked' : '') }}>
                                <label for="radio-nofees" id = "no-fees-lable">No Fees </label>
                                <input class="form-control" type="radio" id="radio-doctor" name="fees_based_on"
                                    value="specificationBased"
                                    {{ isset($clinic) ? ($clinic['fees_based_on'] == 'specificationBased' ? 'checked' : '') : (old('fees_based_on') == 'specificationBased' ? 'checked' : '') }}>
                                <label for="radio-doctor">Doctor Based</label>
                                <input class="form-control" type="radio" id="radio-clinic-based" name="fees_based_on"
                                    value="clinicBased"
                                    {{ isset($clinic) ? ($clinic['fees_based_on'] == 'clinicBased' ? 'checked' : '') : (old('fees_based_on') == 'clinicBased' ? 'checked' : '') }}>

                                <label for="radio-clinic-based" id = "clinic-based-lable">Clinic Based</label>
                            </div>
                        </div>
                        <div class="form-validation-error">
                            @error('fees_based_on')
                                *{{ $message }}
                            @enderror
                        </div>

                    </div>
                    <div class="form-group" id="consultancy-fees"
                        style="{{ isset($clinic) ? ($clinic['fees_based_on'] == 'clinicBased' ? '' : 'display:none') : (old('fees_based_on') == 'clinicBased' ? '' : 'display:none') }}">

                        <label for="consultation_fee">Consultancy Fees</label>
                        <div class="form-input d-flex1">
                            <span><i class="fa-solid fa-money-bill"></i></span> <input class="form-control"
                                type="text" id="consultation_fee" name="consultation_fee"
                                value="{{ isset($clinic) ? $clinic['consultation_fee'] : old('consultation_fee') }}">
                            <span class="unit ml-5">INR</span>
                        </div>
                        <div class="form-validation-error">
                            @error('consultation_fee')
                                *{{ $message }}
                            @enderror
                        </div>

                    </div>
                </div>

                <div class="button-clinic">
                    @if (!isset($clinic))
                        <button type='reset'><span><i class='fa-solid fa-rotate'></i></span> Reset</button>
                    @endif
                    <button><span><i class="fa-solid fa-check"></i></span>
                        {{ isset($clinic) ? 'Update' : 'Submit' }}
                    </button>
                </div>
            </form>



            <div class="clilnic-location">
                <form class="content-div">
                    <div class="appointment-input-fields">
                        <div class="form-group">
                            <label for="map_location_input">Google Map Location</label>
                            <div class="form-input ">
                                <input class="form-control" type="text" id="map_location_input"
                                    name="map_location_input" placeholder="KG Halli , Ashoka layout">
                            </div>
                        </div>
                    </div>
                </form>
                <div class="map-wrapper">

                    <a class="map"
                        href="https://www.google.com/maps/place/AVR+Electronics+Private+Limited/@13.0251726,77.5837499,17z"
                        target="_blank">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3887.1648418820782!2d77.5837499742553!3d13.025172613703138!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bae17b8682daabb%3A0xb68352818c7e11cc!2sAVR%20Electronics%20Private%20Limited!5e0!3m2!1sen!2sin!4v1725614125231!5m2!1sen!2sin"
                            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </a>

                </div>

            </div>

        </div>
    </div>



    @isset($clinic)
        <div class="form-card">
            <div class="clinic-open-heading heading-div">
                <h2>Clinic Open Hours</h2>
                <span class="button-clinic">
                    <button type="button" onclick="handleChangeTimeDialogBtn('open')"><span><i
                                class="fa-regular fa-clock"></i></span><span>Change Timings</span></button>
                </span>
            </div>


            <div class="ac-c-timings">
                <table>
                    <thead>
                        <th>Day</th>
                        <th>Morning Timings</th>
                        <th>Evening Timings</th>
                    </thead>
                    <tbody>

                        @php
                            $Weeks = [
                                'sun' => 'Sunday',
                                'mon' => 'Monday',
                                'tue' => 'Tuesday',
                                'wed' => 'Wednesday',
                                'thu' => 'Thursday',
                                'fri' => 'Friday',
                                'sat' => 'Saturday',
                            ];
                        @endphp

                        @foreach ($Weeks as $shortDay => $fullDay)
                            @php
                                // Find the timing for the current day
                                $timing = collect($clinic['clinic_timings'])->firstWhere('day', $shortDay);

                                // Helper function to convert 24-hour format to AM/PM
                                $formatTime = function ($time) {
                                    return $time ? date('h:i A', strtotime($time)) : null;
                                };
                            @endphp

                            <tr>
                                <td>{{ $fullDay }}</td>
                                @if ($timing)
                                    @if (!$formatTime($timing['morning_from']) == null)
                                        <td>{{ $formatTime($timing['morning_from']) }} -
                                            {{ $formatTime($timing['morning_to']) }}</td>
                                    @else
                                        <td>Closed</td>
                                    @endif
                                    @if (!$formatTime($timing['evening_from']) == null)
                                        <td>{{ $formatTime($timing['evening_from']) }} -
                                            {{ $formatTime($timing['evening_to']) }}</td>
                                    @else
                                        <td>Closed</td>
                                    @endif
                                @else
                                    <td>Closed</td>
                                    <td>Closed</td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="form-card">
            <div class="clinic-open-heading heading-div">
                <h2>clinic Docotrs</h2>
                <span class="button-clinic">
                    <button type="button" onclick="handleAddDoctorDialogBtn('open')">Add</button>
                </span>
            </div>


            <div class="clinic-doctors-cards" id = "clinic-doctors-container">

                @foreach ($clinicDoctors as $clinicDoctor)
                <div class="clinic-doctors-card" id = "clinicDoctor-{{$clinicDoctor->doctor->id}}" >
                    <span><img src='{{ asset('public/assets/images/doctor/doctor.jpg') }}'></span>
                    <div class="clinic-doctors-name">
                        <p>Dr. {{$clinicDoctor->doctor->user->name}}</p>
                        <p>{{$clinicDoctor->doctor->user->email}}</p>
                    </div>
                    <div class="edit-clinic-doctor-btns">
                        <button class="del-clinic-doctor-btn" type="button" value="{{$clinicDoctor->doctor->id}}" onclick="handleEditClinicDoctor(this, '{{$clinic['id']}}', '{{route('getClinicDoctorData')}}', '{{csrf_token()}}')"><i class="fa-solid fa-pen-to-square"></i></button>
                        <button class="del-clinic-doctor-btn" type="button" value="{{$clinicDoctor->doctor->id}}" onclick="handleDeleteclinicDoctor(this, '{{$clinic['id']}}', '{{route('destroyDoctorClinicMapping')}}', '{{csrf_token()}}')"><i class="fa-solid fa-trash"></i></button>

                    </div>
                </div>
                @endforeach

                @if ($clinicDoctors->count() < 1)
                  <p id="no-doctor-msg">No doctor is added. <span onclick="handleAddDoctorDialogBtn('open')">click here</span> to add</p>
                @endif
               
           
            </div>
        </div>
    @endisset


    @isset($clinic)
        <dialog id = "clinic-logo-dialog">
            <div class="upload-popup">
                <div class="heading-div">
                    <h2>Upload Clinic Logo</h2>
                    <span class="close-modal" onclick="handleClinicAddImgDialog('close', 'clinic-logo-dialog')">&times;</span>
                </div>
                <form action="{{ route('uploadClinicLogo') }}" method="POST" enctype="multipart/form-data"
                    id = "upload-logo-form">
                    @csrf
                    <input type="hidden" name="clinic_id" value="{{ $clinic['id'] }}">
                    <div class="upload-details">
                        <input type="file" id="logo-img-input" class="file-input" name = "logo_image">
                        <button class="upload-btn" id = "logoSubmitbtn"><i
                                class="fa-solid fa-cloud-arrow-up"></i>Upload</button>
                    </div>
                </form>
            </div>
        </dialog>

        <dialog id = "clinic-image-dialog">
            <div class="upload-popup">
                <div class="heading-div">
                    <h2>Upload Clinic Logo</h2>
                    <span class="close-modal"
                        onclick="handleClinicAddImgDialog('close', 'clinic-image-dialog')">&times;</span>
                </div>

                <form action="{{ route('uploadClinicImages') }}" method="POST" enctype="multipart/form-data"
                    id = "upload-images-form">
                    @csrf
                    <input type="hidden" name="clinic_id" value="{{ $clinic['id'] }}">
                    <div class="upload-details">
                        <input type="file" id="clinic-images-input" class="file-input" multiple name = "clinic_images">
                        <button class="upload-btn" id = "imagesSubmitbtn"><i
                                class="fa-solid fa-cloud-arrow-up"></i>Upload</button>
                    </div>
                </form>


                <div class="uploaded-images">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($clinic["clinic_image{$i}_thumb"])
                            <div class="image-item" id = "{{ "clinic_image{$i}" }}">
                                <img src='{{ asset("public/{$clinic["clinic_image{$i}_thumb"]}") }}' alt="Uploaded dd Image">
                                <div class="delete-icon"
                                    onclick='handleDeleteClinicImage("{{ "clinic_image{$i}" }}","{{ "clinic_image{$i}_thumb" }}", "{{ $clinic['id'] }}", "{{ route('destroyclinicImage') }}", "{{ csrf_token() }}")'>
                                    <i class="fa-solid fa-trash"></i>
                                </div>
                            </div>
                        @endif
                    @endfor

                </div>

            </div>
        </dialog>
    @endisset






@endsection



@section('javaScript')

<script src="{{ asset('/public/assets/js/dialog.js') }}"></script>

    @isset($clinic) {{-- doctor clinic maping  --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
            integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="{{ asset('public/assets/js/doctorClinicMapping.js') }}"></script>
        <script>
            $('#clinic_specialization').select2({
                placeholder: "Select Specializations",
                allowClear: true,
                multiple: true,
                width: '100%',
                dropdownParent: $('#spe-sel2')
            });
            $('#update_clinic_specialization').select2({
                placeholder: "Select Specializations",
                allowClear: true,
                multiple: true,
                width: '100%',
                dropdownParent: $('#update-spe-sel2')
            });
            $('#clinic_doctor').select2({
            allowClear: true,
            multiple: false,
            width: '100%',
            dropdownParent: $('#doc-sel2'),
            placeholder: '-- Select Doctor --',
            ajax: {
                url: '{{ route('search.doctors') }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        term: params.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            minimumInputLength: 1,
            templateResult: formatDoctorOption,
            templateSelection: formatDoctorSelection 
            });
        </script>
    @endisset
    


    @isset($clinic)
        <script src="{{ asset('/public/assets/js/mf-clinic-timings.js') }}"></script>
        <script src="{{ asset('/public/assets/js/addClinicDoctor.js') }}"></script>
    @endisset


    @isset($clinic)
        <script>
            function handleClinicAddImgDialog(btn, id) {
                console.log("click working");
                const clinicLogodialog = document.getElementById(id);
                if (btn == "open") {
                    clinicLogodialog.showModal();
                } else if (btn == "close") {
                    clinicLogodialog.close();
                }

            }
        </script>
    @endisset


    @isset($clinic)
        {{-- ===== image submit through ajax js  ====== --}}
        <script src="{{ asset('public/assets/js/storeClinicLogo.js') }}"></script>
        <script src="{{ asset('public/assets/js/storeClinicImages.js') }}"></script>

        {{-- ===== image deletion through ajax js  ====== --}}
        <script src="{{ asset('public/assets/js/deleteClinicImages.js') }}"></script>

        {{-- ===== specialization toggling and saving through ajax js  ====== --}}
        <script src="{{ asset('public/assets/js/saveSpecialization.js') }}"></script>
    @endisset


    <!-- consultancy fees input settings  -->
    <script>
        const clinicRadio = document.getElementById('radio-clinic-based');
        const docRadio = document.getElementById('radio-doctor');
        const nofeeRadio = document.getElementById('radio-nofees');

        const feesInput = document.getElementById('consultancy-fees');

        function handleRadioBtn(radioBtn) {



            if (radioBtn.value == "clinicBased") {
                feesInput.style.display = "block";
            } else {
                feesInput.style.display = "none";
            }
        }

        clinicRadio.addEventListener('change', () => {
            handleRadioBtn(clinicRadio);
        });
        docRadio.addEventListener('change', () => {
            handleRadioBtn(docRadio);
        });
        nofeeRadio.addEventListener('change', () => {
            handleRadioBtn(nofeeRadio);
        });
    </script>
    {{-- 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css"> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.js"></script>

    <script>
        $('.owl-carousel.add-clinic').owlCarousel({
            loop: true,
            margin: 10,
            nav: true,
            autoplay: true,
            autoplayTimeout: 3000,

            responsive: {
                0: {
                    items: 1
                },
                400: {
                    items: 1
                },
                490: {
                    items: 1
                },
                576: {
                    items: 1
                },
                776: {
                    items: 1
                },
                800: {
                    items: 1
                },
                1000: {
                    items: 1
                },
                1400: {
                    items: 1
                }
            }
        })
    </script>
@endsection
