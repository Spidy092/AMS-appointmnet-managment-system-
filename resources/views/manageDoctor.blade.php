@extends('layouts.adminLayout')

@section('css')

<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
@endsection

@section('content')
<div class="main-heading">
    <h2>Manage Doctors</h2>
</div>
<div class="manage-doctors ">

    <div class="select-clinic">
        <label for="select-clinic">Select Clinic:</label>
        <select name="clinic" id="select-clinic">
            <option value="clinic-a">Clinic A</option>
            <option value="clinic-b">Clinic B</option>
            <option value="clinic-c">Clinic C</option>
        </select>
    </div>

    <div class="manage-doctor-list">
        <table id="myTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Contact No</th>
                    <th>E-Mail</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
@endsection


@section("javaScript")

<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script src="{{asset('public/assets/js/ajaxRequest.js')}}"></script>

<script>
    const table = $('#myTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("getDoctorsData") }}',
            dataType: 'json',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' }
        },
        columns: [
            // { data: 'id' },
            { data: 'name' },
            { data: 'phone_no' },
            { data: 'email' },
            { data: 'action' }
        ],
        order: [[0, 'desc']]
    });

    $('#myTable tbody').on('click', 'button', function() {
        var id = $(this).val();
        var action = $(this).attr('id');
        console.log(id);
        
        if(action == 'suspend' || action == 'activate'){
            var url = "{{ url('doctorActionFunc') }}"
            token = '{{ csrf_token() }}';
            sendAjaxRequest(url, id, action, token, table);
        }
        if(action == 'delete'){
            var url = "{{ url('doctorDeleteFunction') }}";
            let token;
            if (confirm('Are you sure you want to delete this Doctor?')) {
                token = "{{ csrf_token() }}"
                sendAjaxRequest(url, id, action, token, table);
            }
        }
    });
</script>


@endsection



