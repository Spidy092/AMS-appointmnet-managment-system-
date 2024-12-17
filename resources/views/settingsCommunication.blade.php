@extends('layouts.adminLayout')

@section('content')
    <div class="main-heading">
        <h2>Communication Settings</h2>
    </div>
    <div class="communication-settings-main form-card">
        <div class="clinic-settings-container">
            <!-- Clinic selection form -->
            <form method="GET" action="{{ route('clinic.communication.show') }}">
                <div class="form-group">
                    <label for="clinic_id">Select Clinic</label>
                    <select name="clinic_id" id="clinic_id" class="form-control" onchange="this.form.submit()">
                        <option value="">-- Select a Clinic --</option>
                        @foreach ($clinics as $clinic)
                            <option value="{{ $clinic->id }}" {{ request('clinic_id') == $clinic->id ? 'selected' : '' }}>
                                {{ $clinic->clinic_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>

        </div>
        @if ($selectedClinic)
            <!-- tabination starts -->
            <div class="gsmk-tabination">
                <div class="tab-wrapper">
                    <ul class="tabs">
                        <li class="tab-link active" data-tab="1"><span><img
                                    src="{{ asset('public/assets/images/icons/general-settings.png') }}"
                                    alt="general settings"></span>
                            <p>General Settings</p>
                        </li>
                        <li class="tab-link" data-tab="2"><span><img
                                    src="{{ asset('public/assets/images/icons/email-settings.png') }}"
                                    alt="Email settings"></span>
                            <p>Email Settings</p>
                        </li>
                        <li class="tab-link" data-tab="3"><span><img
                                    src="{{ asset('public/assets/images/icons/sms-settings.png') }}"
                                    alt="sms settings"></span>
                            <p>SMS Settings</p>
                        </li>
                    </ul>
                </div>

                <div class="content-wrapper">
                    <div id="tab-1" class="tab-content active">
                        <div class="tab-in-content">
                            <!-- <h3 class="form-h3">General Settings</h3> -->

                            <form action="{{ route('updateCommunicationContacts') }}" method="POST"
                                id = "communication-contacts-form"
                                onsubmit="return handleCommunicationContactForm('communication-contacts-form','{{ csrf_token() }}', {{ $selectedClinic->id }})">

                                <div class="general-settings-forms">
                                    <div class="general-settings-form">
                                        <label>Email Id</label>
                                        <p>Forward Patient Email Replies To Your Mail</p>
                                        <div class="form-input d-flex1">
                                            <span><i class="fa-regular fa-envelope"></i></span>
                                            <input class="form-control" type="email" name="communication_email"
                                                value = '{{ $selectedClinic->communication_email }}'>
                                        </div>
                                    </div>
                                    <div class="general-settings-form">
                                        <label>Contact No</label>
                                        <p>Forward patient SMS replies to your Mobile</p>
                                        <div class="form-input d-flex1">
                                            <span><i class="fa-solid fa-phone"></i></span>
                                            <input class="form-control" type="text" name="communication_contact_number"
                                                value="{{ $selectedClinic->communication_contact_number }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="general-settings-save">
                                    <button>Save Settings</button>
                                </div>

                            </form>

                        </div>
                    </div>
                    <div id="tab-2" class="tab-content">
                        <div class="tab-in-content tab2">
                            <div class="note">
                                <span><i class="fa-solid fa-circle-info"></i></span>
                                <p>Use @{{ DATE }}, @{{ TIME }}, @{{ CLINIC }},
                                    @{{ PATIENT }}, @{{ DENTIST }}, @{{ CLINICNUMBER }},
                                    @{{ CLINIC ADDRESS }} in the clinic email template. </p>
                            </div>

                            <form action="{{ route('updateCommunicationEmailSetiings') }}" id = "communication-email-form"
                                onsubmit="return handleCommunicationContactForm('communication-email-form','{{ csrf_token() }}', {{ $selectedClinic->id }})">
                                @foreach (['confirmation', 'cancellation', 'remainder'] as $event)
                                    @php
                                        // Find the email setting for the current event
                                        $emailSetting = $emailSettings->firstWhere('event', $event);
                                    @endphp

                                    @if ($emailSetting)
                                        <div class="email-checkbox-setting">
                                            <span class="form__checkbox">
                                                <input type="checkbox" id="{{ $emailSetting->event }}"
                                                    name="{{ $emailSetting->event }}_enabled" value="1"
                                                    {{ $emailSetting->is_enabled ? 'checked' : '' }}
                                                    onchange="toggleEmailInput('{{ $emailSetting->event }}', '{{ $emailSetting->event }}-msg-input')">
                                                <label for="{{ $emailSetting->event }}">Appointment
                                                    {{ $emailSetting->event }} Email</label>
                                            </span>
                                        </div>
                                        <div class="general-settings-forms"
                                            style="display: {{ $emailSetting->is_enabled ? 'block' : 'none' }};"
                                            id="{{ $emailSetting->event }}-msg-input">
                                            <div class="form-group">
                                                <label>Email Subject</label>
                                                <div class="form-input d-flex1">
                                                    <span><i class="fa-regular fa-envelope"></i></span>
                                                    <input class="form__input" name="{{ $emailSetting->event }}_subject"
                                                        maxlength="35" value="{{ $emailSetting->subject }}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Email Text</label>
                                                <div class="form-input d-flex1">
                                                    <span><i class="fa-regular fa-envelope"></i></span>
                                                    <textarea class="form_input" name="{{ $emailSetting->event }}_body" rows="4" cols="50">{{ $emailSetting->body }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <!-- Default settings when email setting does not exist -->
                                        @if ($event == 'confirmation')
                                            <div class="email-checkbox-setting">
                                                <span class="form__checkbox">
                                                    <input type="checkbox" id="appointment" name="confirmation_enabled"
                                                        value="1"
                                                        onchange="toggleEmailInput('appointment', 'confirm-msg-input')">
                                                    <label for="appointment">Appointment Confirmation Email</label>
                                                </span>
                                            </div>
                                            <div class="general-settings-forms" style="display: none;"
                                                id="confirm-msg-input">
                                                <div class="form-group">
                                                    <label>Email Subject</label>
                                                    <div class="form-input d-flex1">
                                                        <span><i class="fa-regular fa-envelope"></i></span>
                                                        <input class="form__input" name="confirmation_subject"
                                                            maxlength="35"
                                                            value="Dental Appointment at @{{ CLINIC }}">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Email Text</label>
                                                    <div class="form-input d-flex1">
                                                        <span><i class="fa-regular fa-envelope"></i></span>
                                                        <textarea class="form_input" name="confirmation_body" rows="4" cols="50">Hi @{{ PATIENT }}, Your appointment at @{{ CLINIC }} with Dr. @{{ DENTIST }} has been scheduled for @{{ DATE }} at @{{ TIME }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif ($event == 'cancellation')
                                            <div class="email-checkbox-setting">
                                                <span class="form__checkbox">
                                                    <input type="checkbox" id="appointment-cancle"
                                                        name="cancellation_enabled" value="1"
                                                        onchange="toggleEmailInput('appointment-cancle', 'cancle-msg-input')">
                                                    <label for="appointment-cancle">Appointment Cancellation Email</label>
                                                </span>
                                            </div>
                                            <div class="general-settings-forms" style="display: none;"
                                                id="cancle-msg-input">
                                                <div class="form-group">
                                                    <label>Email Subject</label>
                                                    <div class="form-input d-flex1">
                                                        <span><i class="fa-regular fa-envelope"></i></span>
                                                        <input class="form__input" name="cancellation_subject"
                                                            maxlength="35"
                                                            value="Cancelled: Dental Appointment at @{{ CLINIC }}">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Email Text</label>
                                                    <div class="form-input d-flex1">
                                                        <span><i class="fa-regular fa-envelope"></i></span>
                                                        <textarea class="form_input" name="cancellation_body" rows="4" cols="50">Hi @{{ PATIENT }}, Your appointment at @{{ CLINIC }} with Dr. @{{ DENTIST }} on @{{ DATE }} at @{{ TIME }} has been cancelled.</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif ($event == 'remainder')
                                            <div class="email-checkbox-setting">
                                                <span class="form__checkbox">
                                                    <input type="checkbox" id="appointment-rem" name="remainder_enabled"
                                                        value="1"
                                                        onchange="toggleEmailInput('appointment-rem', 'remainder-msg-input')">
                                                    <label for="appointment-rem">Appointment Remainder Email</label>
                                                </span>
                                            </div>
                                            <div class="general-settings-forms" style="display: none;"
                                                id="remainder-msg-input">
                                                <div class="form-group">
                                                    <label>Email Subject</label>
                                                    <div class="form-input d-flex1">
                                                        <span><i class="fa-regular fa-envelope"></i></span>
                                                        <input class="form-control" type="name"
                                                            name="remainder_subject" value="Dental Appointment Remainder!">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Email Text</label>
                                                    <div class="form-input d-flex1">
                                                        <span><i class="fa-regular fa-envelope"></i></span>
                                                        <textarea class="form_input" name="remainder_body" rows="4" cols="50">Your appointment has been scheduled at @{{ CLINIC }} from @{{ DATE }} at @{{ TIME }}, by @{{ DENTIST }}. This is just a remainder for your confirmed appointment.</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @endforeach



                                <div class="general-settings-save">
                                    <button>
                                        <!-- <img src="./images/icons/bookmark.png" alt="bookmark"> -->
                                        Save Settings</button>
                                </div>

                            </form>

                        </div>
                    </div>
                    <div id="tab-3" class="tab-content">
                        <div class="tab-in-content">
                            <!-- <h3 class="form-h3">SMS Settings</h3> -->
                            <form action="{{ route('updateCommunicationSmsSetiings') }}" id = "communication-sms-form"
                                onsubmit="return handleCommunicationContactForm('communication-sms-form','{{ csrf_token() }}', {{ $selectedClinic->id }})">
                                <div class="sms-settings-accordian-main">
                                    @php
$events = ['confirmation', 'cancellation', 'remainder'];
@endphp

@foreach ($events as $event)
    @php
        $smsSetting = $smsSettings->firstWhere('event', $event) ?? null;
    @endphp
    <div class="set">
        <div class="sms-checkbox-setting">
            <span class="form__checkbox">
                <input type="checkbox" id="sms-{{ $event }}-checkbox" 
                    name="{{ $event }}_enabled" 
                    value="1"
                    {{ $smsSetting && $smsSetting->is_enabled ? 'checked' : '' }}
                    onchange="toggleSmsContent('sms-{{ $event }}-checkbox', 'sms-{{ $event }}-content')">
                <label for="sms-{{ $event }}-checkbox">
                    Appointment {{ ucfirst($event) }} SMS
                </label>
            </span>
        </div>

        <div id="sms-{{ $event }}-content"
            style="display: {{ $smsSetting && $smsSetting->is_enabled ? 'block' : 'none' }}">
            <div class="sms-content">
                <p>
                    <span id="sms-{{ $event }}-patient-span"
                        style="display: {{ $smsSetting && $smsSetting->include_patient_name ? 'inline' : 'none' }}">
                        hi @{{ PATIENT }}, <br>
                    </span>
                    your appointment {{ $event == 'cancellation' ? 'at' : ($event == 'remainder' ? 'is scheduled at' : 'is confirmed') }}
                    <span id="sms-{{ $event }}-clinic-span"
                        style="display: {{ $smsSetting && $smsSetting->include_clinic_name ? 'inline' : 'none' }}">
                        at @{{ CLINIC }}
                    </span>
                    on @{{ DATE }} at @{{ TIME }}. <br>
                    <span id="sms-{{ $event }}-contact-span"
                        style="display: {{ $smsSetting && $smsSetting->include_contact_number ? 'inline' : 'none' }}">
                        For more info call @{{ CLINICNUMBER }}. <br>
                    </span>
                    Powered by Fractional Therapy
                </p>

                <div class="sms-inputs">
                    <div class="input-container">
                        <span class="form__checkbox">
                            <input type="checkbox"
                                id="sms-{{ $event }}-patient-checkbox" value="1"
                                name="{{ $event }}_include_patient"
                                {{ $smsSetting && $smsSetting->include_patient_name ? 'checked' : '' }}
                                onchange="toggleSpan('sms-{{ $event }}-patient-checkbox', 'sms-{{ $event }}-patient-span')">
                            <label for="sms-{{ $event }}-patient-checkbox">Patient Name</label>
                        </span>
                    </div>
                    <div class="input-container">
                        <span class="form__checkbox">
                            <input type="checkbox"
                                id="sms-{{ $event }}-clinic-checkbox" value="1"
                                name="{{ $event }}_include_clinic"
                                {{ $smsSetting && $smsSetting->include_clinic_name ? 'checked' : '' }}
                                onchange="toggleSpan('sms-{{ $event }}-clinic-checkbox', 'sms-{{ $event }}-clinic-span')">
                            <label for="sms-{{ $event }}-clinic-checkbox">Clinic Name</label>
                        </span>
                    </div>
                    <div class="input-container">
                        <span class="form__checkbox">
                            <input type="checkbox"
                                id="sms-{{ $event }}-number-checkbox" value="1"
                                name="{{ $event }}_include_contact"
                                {{ $smsSetting && $smsSetting->include_contact_number ? 'checked' : '' }}
                                onchange="toggleSpan('sms-{{ $event }}-number-checkbox', 'sms-{{ $event }}-contact-span')">
                            <label for="sms-{{ $event }}-number-checkbox">Contact Number</label>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach


                                    <!-- Default settings if no settings are available -->
                                    @if ($smsSettings->isEmpty())
                                        <!-- Add default settings for confirmation, cancellation, and remainder SMS -->
                                        <!-- Similar structure as above with default values -->
                                    @endif
                                </div>

                                <div class="general-settings-save">
                                    <button>
                                        <!-- <img src="./images/icons/bookmark.png" alt="bookmark"> -->
                                        Save Settings</button>
                                </div>
                            </form>






                        </div>
                    </div>

                </div>
            </div>
            <!-- tabination ends -->
        @else
            <p>Please select a clinic to view and update settings.</p>
        @endif

    </div>
@endsection

@section('javaScript')
    <script src="{{ asset('public/assets/js/communicationSettings.js') }}"></script>


    <!-- tabination script -->
    <script>
        $('.tab-link').click(function() {
            var tabID = $(this).attr('data-tab');
            $(this).addClass('active').siblings().removeClass('active');
            $('#tab-' + tabID).addClass('active').siblings().removeClass('active');
        });
    </script>
    <!-- tabination script -->

    {{-- <!-- sms accordian starts-->
<script>
    $(document).ready(function(){
        $(".set > a").on("click", function(){
            if ($(this).hasClass("active")) {
                $(this).removeClass("active");
                $(this).siblings(".content").slideUp(200);
            } else {
                $(".set > a").removeClass("active");
                $(".content").slideUp(200);
                
                $(this).addClass("active");
                $(this).siblings(".content").slideDown(200);
            }
        });
    });
</script>
<!-- sms accordian starts--> --}}


    <!-- message inputs show and not show scripts -->
    <script>
        // Function to toggle the visibility of the content for each set
        function toggleSmsContent(checkboxId, contentId) {
            const checkbox = document.getElementById(checkboxId);
            const content = document.getElementById(contentId);

            if (checkbox.checked) {
                content.style.display = "block"; // Show content
            } else {
                content.style.display = "none"; // Hide content
                // Uncheck all checkboxes and hide all spans within this content
                // document.querySelectorAll(`#${contentId} input[type='checkbox']`).forEach(cb => cb.checked = false);
                // document.querySelectorAll(`#${contentId} span`).forEach(span => span.style.display = "none");
            }
        }

        // Function to toggle the visibility of a span based on a checkbox
        function toggleSpan(checkboxId, spanId) {
            const checkbox = document.getElementById(checkboxId);
            const span = document.getElementById(spanId);

            span.style.display = checkbox.checked ? "inline" : "none";
        }
    </script>

    {{-- email settings  --}}
    <script>
        // Function to toggle the visibility of the input section
        function toggleEmailInput(checkboxId, inputId) {
            const checkbox = document.getElementById(checkboxId);
            const inputSection = document.getElementById(inputId);

            inputSection.style.display = checkbox.checked ? 'block' : 'none';
        }
    </script>
@endsection
