@extends('layouts.adminLayout')

@section('content')
    <div class="main-heading">
        <h2> Staff Settings</h2>

    </div>
    <div class="clinic-staff-settings form-card">

        <div class="content-div">
            <div class="clinic-staff-access-select">


                <!-- Clinic selection form -->
                <form method="GET" action="{{ route('createClinicStaffAccess') }}">
                    <div>
                        {{-- <label for="clinic_id">Select Clinic</label> --}}
                        <select name="clinic_id" id="clinic_id" class="form-control"  onchange="handleClinicChange(this)">
                            <option value="">-- Select a Clinic --</option>
                            @foreach ($clinics as $clinic)
                                <option value="{{ $clinic->id }}"
                                    {{ request('clinic_id') == $clinic->id ? 'selected' : '' }}>
                                    {{ $clinic->clinic_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @isset($selectedClinic)
                        <div>
                            {{-- <label for="staff_id">Select Staff</label> --}}
                            <select name="staff_id" id="staff_id" class="form-control" onchange="this.form.submit()">
                                <option value="">-- Select a Staff --</option>
                                @foreach ($staffs as $staff)
                                    <option value="{{ $staff->id }}"

                                        {{ isset($selectedStaff->id) ? ($selectedStaff->id == $staff->id ? 'selected' : '') : "" }}>
                                        {{ $staff->user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endisset
                </form>


            </div>

            @if ($selectedStaff && $selectedClinic)
                <form action="{{ route('storeClinicStaffAccess') }}" method="POST">
                    @csrf

                    <input type="hidden" name = "staff_profile_id" value="{{ $selectedStaff->id }}">

                    <div class="staff-settings-wrapper">
                        <table class="settings-table">
                            <thead>
                                <tr>
                                    <th>Categories</th>
                                    <th>View</th>
                                    <th>Add</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $accesses = [
                                    'dashboard',
                                    'appointments',
                                    'clinics',
                                    'doctors',
                                    'reports',
                                    'settings',
                                ];
                            @endphp
                            
                            @foreach ($accesses as $access)
                                @php
                                    // Find the access record for the current category
                                    $currentAccess = $staffAccess->firstWhere('categories', $access);
                                @endphp
                                <tr>
                                    <td style="text-align: start;">{{ ucfirst($access) }}</td>
                                    <td>
                                        <input name="{{ $access }}_view" type="checkbox" value="1" 
                                            @checked($currentAccess ? $currentAccess->view : false)>
                                    </td>
                                    <td>
                                        <input name="{{ $access }}_add" type="checkbox" value="1" 
                                            @checked($currentAccess ? $currentAccess->add : false)>
                                    </td>
                                    <td>
                                        <input name="{{ $access }}_edit" type="checkbox" value="1" 
                                            @checked($currentAccess ? $currentAccess->edit : false)>
                                    </td>
                                    <td>
                                        <input name="{{ $access }}_delete" type="checkbox" value="1" 
                                            @checked($currentAccess ? $currentAccess->delete : false)>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="button-clinic">
                        <button>Save</button>
                    </div>
                </form>
            @endif


        </div>
    </div>
@endsection

@section('javaScript')
<script>
    function handleClinicChange(select) {
        const staffSelect = document.getElementById('staff_id');
        if (staffSelect) {
            staffSelect.innerHTML = '<option value="">-- Select a Staff --</option>';
        }

        select.form.submit();
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const table = document.querySelector('.settings-table');

        table.addEventListener('change', function (event) {
            if (event.target.type === 'checkbox') {
                const checkboxName = event.target.name;

                if (checkboxName.endsWith('_add') || checkboxName.endsWith('_edit') || checkboxName.endsWith('_delete')) {
                    const baseName = checkboxName.split('_')[0];
                    const viewCheckbox = document.querySelector(`input[name="${baseName}_view"]`);

                    if (viewCheckbox) {
                        viewCheckbox.checked = true;
                    }
                }
            }
        });
    });
</script>
@endsection
