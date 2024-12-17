@extends('layouts.adminLayout')
@section('css')

   
@endsection
@section('content')
<div class="main-heading">
    <h2>Manage Timings for Doctors</h2>

</div>
<div class="form-card doctor-timings-page">

     <!-- Clinic selection form -->
     <form method="GET" action="{{ route('doctor.timings.form', ['doctorId' => 2]) }}">
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

    @if ($selectedClinic)
        
   
        {{-- <h2>{{ $clinic->clinic_name }}</h2> --}}
        
        <form action="{{ route('updateDoctorTimings') }}" method="POST" name="doctorTimingForm"
            onsubmit="return handleOnDoctorTimingForm('{{ csrf_token() }}', '{{ $doctor->id }}')" id="doctorTimingForm">
            @csrf
            <div class="note">
                <span><i class="fa-solid fa-circle-info"></i></span>
                <p>
                    Make sure that the timings you select are within the clinic's operating hours. The timings listed in brackets represent the clinic's available timings.
                </p>
            </div>

            <input type="hidden" name = "clinic_id" value="{{$selectedClinic['id']}}"> 

            <table class="doctor-timings">
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
                        $weeks = [
                            'sun' => 'Sunday',
                            'mon' => 'Monday',
                            'tue' => 'Tuesday',
                            'wed' => 'Wednesday',
                            'thu' => 'Thursday',
                            'fri' => 'Friday',
                            'sat' => 'Saturday',
                        ];
                        $formatTime = function ($time) {
                            return $time ? date('h:i A', strtotime($time)) : '';
                        };
                    @endphp
                   

                    @foreach ($weeks as $shortDay => $fullDay)
                        @php
                            $doctorTiming = $doctorTimings->firstWhere('day', strtolower($fullDay));

                            $isChecked = $doctorTiming ? 'checked' : '';
                            $isVisible = $doctorTiming ? 'visible' : 'hidden';

                            $clinicTiming = collect($selectedClinic['clinic_timings'])->firstWhere('day', $shortDay);
                        @endphp


                        <tr>
                            <td class="day-name">
                                <input type="checkbox" id="doctor-timing-{{ $shortDay }}" name="doctor-timing-{{ $shortDay }}"
                                    {{ $isChecked }} onchange="toggleVisibility('{{ $shortDay }}')">
                                {{ $fullDay }}
                            </td>
                            <td><input type="text" class="time-pickable {{ $shortDay }}-time"
                                    style="visibility: {{ $isVisible }};"
                                    name="morning_from_{{ $shortDay }}" 
                                    value="{{ $doctorTiming && $doctorTiming->morning_from ? date('h:i a', strtotime($doctorTiming->morning_from)) : '' }}">
                                <span>({{ $clinicTiming ? !$clinicTiming['morning_from']== null ? $formatTime($clinicTiming['morning_from']) : "Closed" : "closed"  }})</span>
                            </td>
                            <td><input type="text" class="time-pickable {{ $shortDay }}-time"
                                    style="visibility: {{ $isVisible }};"
                                    name="morning_to_{{ $shortDay }}" 
                                    value="{{ $doctorTiming && $doctorTiming->morning_to ? date('h:i a', strtotime($doctorTiming->morning_to)) : '' }}">
                                <span>({{ $clinicTiming ? !$formatTime($clinicTiming['morning_to']) == null ? $formatTime($clinicTiming['morning_to']) : "Closed" : "closed"  }})</span>    
                            </td>
                            <td><input type="text" class="time-pickable {{ $shortDay }}-time"
                                    style="visibility: {{ $isVisible }};"
                                    name="evening_from_{{ $shortDay }}" 
                                    value="{{ $doctorTiming && $doctorTiming->evening_from ? date('h:i a', strtotime($doctorTiming->evening_from)) : '' }}">
                                
                                <span>({{ $clinicTiming ? !$formatTime($clinicTiming['evening_from']) == null ? $formatTime($clinicTiming['evening_from']) : "Closed" : "closed"  }})</span>
                            </td>
                            <td><input type="text" class="time-pickable {{ $shortDay }}-time"
                                    style="visibility: {{ $isVisible }};"
                                    name="evening_to_{{ $shortDay }}" 
                                    value="{{ $doctorTiming && $doctorTiming->evening_to ? date('h:i a', strtotime($doctorTiming->evening_to)) : '' }}">
                                
                                <span>({{ $clinicTiming ? !$formatTime($clinicTiming['evening_to']) == null ? $formatTime($clinicTiming['evening_to']) : "Closed" : "closed"  }})</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{!! $error !!}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="timing-save-button" >
                <button type="submit" id = "doctorTimingsFormBtn">Update Timings</button>
            </div>
        </form>

    @endif
</div>


@endsection

@section("javaScript")
<script src = "{{asset('public/assets/js/doctorTimings.js')}}"> </script>
@endsection