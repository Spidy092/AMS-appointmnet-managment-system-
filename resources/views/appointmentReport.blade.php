@extends('layouts.adminLayout')

@section('content')
<div class="main-heading">
    <h2>Appointments Report</h2>
</div>
    <div class="form-card">
        <div class="content-div">
            <form id = "doctor-profile-form" action="">
                <div class="flex-input-fields d-flex">
                    <div class="form-group">
                        <label for="date">From Date</label>
                        <div class="form-input d-flex1">
                            <span><i class="fa-regular fa-calendar-days"></i></span> <input class="form-control"
                                type="date" id="txtState" name="txtState">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="date">To Date</label>
                        <div class="form-input d-flex1">
                            <span><i class="fa-regular fa-calendar-days"></i></span> <input class="form-control"
                                type="date" id="txtState" name="txtState">
                        </div>
                    </div>
                </div>

            </form>
            <div class="button-appointment-drprofile">
                <button>Generate</button>
            </div>
        </div>

    </div>
@endsection
