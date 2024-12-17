<div class="navigation Flipped">
    <ul>
        <li>
            <a href="{{route('dashboard')}}">
                <span class="icon">
                    <i class="fa-regular fa-calendar-check"></i>
                </span>
                <span class="title">Dashboard</span>
            </a>
        </li>

        <li>
            <a href="#" class="dropdown-toggle">
                <span class="icon">
                    <i class="fa-regular fa-calendar-days"></i>
                </span>
                <span class="title">Appointment</span>
            </a>
            <ul class="dropdown">
                <li><a href="{{route('appointmentBooking')}}">Add Appointment</a></li>
                <li><a href="{{route('appointmentList')}}">Manage Appointments</a></li>
            </ul>
        </li>

        <li>
            <a href="#" class="dropdown-toggle">
                <span class="icon">
                    <i class="fa-solid fa-house-chimney-medical"></i>
                </span>
                <span class="title">Clinics</span>
               </a>

            <ul class="dropdown">
                <li><a href="{{route('showAddClinicForm')}}">Add New Clinic</a></li>
                <li><a href="{{route('manageClinicView')}}">Manage Clinic</a></li>
            </ul>
        </li>

        <li>
            <a href="#" class="dropdown-toggle">
                <span class="icon">
                    <i class="fa-solid fa-user-doctor"></i>
                </span>
                <span class="title">Doctors</span>
            </a>
            <ul class="dropdown">
                <li><a href="{{route('showAddDoctorForm')}}">Add Doctor</a></li>
                <li><a href="{{route('manageDoctorView')}}">Manage Doctors</a></li>
            </ul>
        </li>

        {{-- <li>
            <a href="{{route('appointmentBookingView')}}">
                <span class="icon">
                    <i class="fa-regular fa-calendar-days"></i>
                </span>
                <span class="title">Appointment</span>
            </a>
        </li> --}}

        <li>
            <a href="#" class="dropdown-toggle">
                <span class="icon">
                    <i class="fa-solid fa-sheet-plastic"></i>
                </span>
                <span class="title">Reports</span>
            </a>
            <ul class="dropdown">
                <li><a href="{{ route('appointmentReport') }}">Appointment Report</a></li>
                <li><a href="{{ route('clinicReport') }}">Clinic Report</a></li>
            </ul>
        </li>


        <li>
            <a href="#" class="dropdown-toggle">
                <span class="icon">
                    <i class="fa-solid fa-gear"></i>
                </span>
                <span class="title">Settings</span>
            </a>
            <ul class="dropdown">
                <li><a href="{{route('clinic.communication.show')}}">Communcation Settings</a></li>
                <li><a href="{{route('clinic.settings.show')}}">Clinic Settings</a></li>
                <li><a href="{{route('createClinicStaff')}}">Add Staff</a></li>
                <li><a href="{{route('createClinicStaffAccess')}}">Staff Settings</a></li>
                <li><a href="{{route('specializations')}}">Manage Specializations</a></li>
            </ul>
        </li>

    </ul>
    <!-- <div class="toggle">
        <i class="fa-solid fa-bars"></i>
    </div> -->
</div>
