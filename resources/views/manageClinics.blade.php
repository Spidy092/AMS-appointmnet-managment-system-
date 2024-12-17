@extends('layouts.adminLayout')


@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
@endsection

@section('content')
    <div class="main-heading">
        <h2>Manage Clinics</h2>
    </div>

    <div class="manage-doctors ">


        <div class="manage-clinic-list">
            <table id="myTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Contact No</th>
                        <th>Location</th>
                        <th>Web Address</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
@endsection


@section('javaScript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <script src="{{ asset('public/assets/js/ajaxRequest.js') }}"></script>
    <script>
        var table = $('#myTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('getClinicsData') }}',
                dataType: 'json',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                }
            },
            columns: [
                // { data: 'id' },
                {
                    data: 'clinic_name'
                },
                {
                    data: 'contact_no_1'
                },
                {
                    data: 'district'
                },
                {
                    data: 'web_address'
                },
                {
                    data: 'action'
                }
            ],
            order: [
                [0, 'desc']
            ]
        });

        $('#myTable tbody').on('click', 'button', function() {
            var id = $(this).val();
            var action = $(this).attr('id');
            console.log(id);
            if (action == 'suspend' || action == 'activate') {
                var url = "{{ url('clinicActionFunc') }}"
                token = "{{ csrf_token() }}"
                sendAjaxRequest(url, id, action, token, table);
            }
            if (action == 'delete') {
                var url = "{{ url('clinicDeleteFunction') }}";
                token
                if (confirm('Are you sure you want to delete this Specialization?')) {
                    token = "{{ csrf_token() }}"
                    sendAjaxRequest(url, id, action, token, table);
                }
            }
        });
    </script>
@endsection
