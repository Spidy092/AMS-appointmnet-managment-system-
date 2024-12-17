@extends('layouts.admin-layout')

@section('content')

<div class="main-content">
    <div class="heading-div">
        <h2>Manage Users</h2>
    </div>
    <div class="main-inner">
        <div class="data-table">
            <table id="datatable1" class="display">
                <thead>
                    <tr>
                        <th>Sl. No.</th>
                        <th>Name</th>
                        <th>Email</th>
                        {{-- <th>Role</th> --}}
                        <th>Date Added</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        function checkSession() {
            $.ajax({
                url: "{{ url('check-session') }}",
                type: 'GET',
                success: function (data) {
                    if (data.authenticated !== true) {
                        window.location.href = "{{ route('login') }}";
                    }
                },
                error: function () {
                    console.error('Error checking session status');
                }
            });
        }

        $("#alert").hide();
        let ajaxInProgress = false;
        let currentUserId;
        let currentAction;
        let table = $('#datatable1').DataTable({
            'processing': true,
            'serverSide': true,
            'ajax': {
                'url': "{{ url('user-data') }}",
                'type': 'POST',
                'data': function (data) {
                    data._token = "{{ csrf_token() }}";
                }
            },
            'order': [[ 0, "desc" ]],
            'columns': [
                { data: 'custom_id', name: 'id' },
                { data: 'name' },
                { data: 'email' },
                // { data: 'role' },
                { data: 'date_addeda' },
                { data: 'action', orderable: false, searchable: false }
            ]
        });

        $('#datatable1').on('click', 'button', function (e) {
            checkSession();
            currentUserId = $(this).val();
            currentAction = $(this).attr("id");

            if (currentAction === "edit") {
                window.location.href = "{{ url('editUser') }}/" + currentUserId;
            } else if (currentAction === "delete") {
                var userConfirmed = confirm("Are you sure you want to delete this user?");
                if (userConfirmed) {
                    ajaxInProgress = true;
                    $.ajax({
                        url: '{{ url('userDeleteFunc') }}',
                        type: 'POST',
                        data: {
                            'currentUserId': currentUserId,
                            '_token': "{{ csrf_token() }}"
                        },
                        success: function (data) {
                            alert(data);
                        },
                        error: function (xhr, status, error) {
                            console.error("AJAX Error:", status, error);
                            alert("An error occurred while processing your request. Please try again later.");
                        },
                        complete: function () {
                            table.ajax.reload(null, false);
                            $("#alert").fadeTo(2000, 500).slideUp(500, function() {
                                $("#alert").slideUp(500);
                            });
                            ajaxInProgress = false;
                        }
                    });
                }
            } else {
                ajaxInProgress = true;
                $.ajax({
                    url: '{{ url('userActionFunc') }}',
                    type: 'POST',
                    data: {
                        'currentUserId': currentUserId,
                        'currentAction': currentAction,
                        '_token': "{{ csrf_token() }}"
                    },
                    success: function (data) {
                        alert(data);
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                        alert("An error occurred while processing your request. Please try again later.");
                    },
                    complete: function () {
                        table.ajax.reload(null, false);
                        $("#alert").fadeTo(2000, 500).slideUp(500, function() {
                            $("#alert").slideUp(500);
                        });
                    }
                });
            }
        });

    });
</script>
@endsection
