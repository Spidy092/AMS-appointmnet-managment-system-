@extends('layouts.adminLayout')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
    <style>
        :root {
            --blue: #3d5ed8;
            --dark-blue: #051f7e;
            --white: #fff;
            --gray: #261c1c;
            --black1: #222;
            --black2: #999;
            --success-green: #2bcf2b;
            --success-green-hover: #169e16;
            --cancle-red: #e63505;
            --edit-yellow: #FFDB58;
            --dark-icon-bg: #51656C;
            --light-icon-bg: #8595A4;
            --main-bg:#283857;
            --input-icon-bg:#ececec;
        }

        .add-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--main-bg);
            color: white;
            border: none;
            border-radius: 6px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        /* .add-btn:hover { background-color: var(--success-green-hover); } */
        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            z-index: 1000;
        }

        .popup-container {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            padding: 0 25px 25px;
            max-width: 500px;
            width: 90%;
            z-index: 1100;
            overflow: hidden;
        }

        .special-input {
            flex: 1;
            padding: 8px 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            transition: border-color 0.3s;
        }

        .special-input:focus {
            outline: none;
            border-color: var(--blue);
        }

        .popup-button-group {
            text-align: center;
            margin-top: 20px;
        }

        .d-none {
            display: none
        }

        .popup-container form {
            margin-top: 20px;
        }

        .set-spe .form-card {
            padding: 0 0 20px 0;
        }
    </style>
@endsection

@section('content')
    <div class="main-heading" >
        <h2>Manage Specializations</h2>
    </div>
    <div class="set-spe">
        <div class="form-card">

            <div class="heading-div" style="display: flex; align-items: center; justify-content: space-between;">
                <h2>Specializations</h2>
                <button class="add-btn" style="max-width: 150px;">Add More</button>
            </div>

            <div class="manage-specification-list">
                <table id="myTable">
                    <thead>
                        <tr>
                            <th>Specialization</th>
                            <th data-sortable="false">Level</th>
                            <th>Date Added</th>
                            <th data-sortable="false">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>


            <div class="popup-overlay" id="popup_overlay"></div>
            <div id="appointment_popup" class="popup-container">
                <div class="heading-div pop-up-header">
                    <h2>Add Specialization</h2>
                    <span id = "close-add-specialization"><img src="{{ asset('public/assets/images/icons/close.png') }}"
                            alt="cancle icon"></span>
                </div>
                <form id="specialization_form" method="POST" action="">
                    @csrf
                    <div class="form-group">
                        <label for="top_level_specialization">Select Top-Level Specialization</label>
                        <select name="parent_id" id="top_level_specialization" class="form-control">
                            <option value="">-- Select Top-Level Specialization --</option>
                            @foreach ($topSpecializations as $specialization)
                                <option value="{{ $specialization->id }}">{{ $specialization->specialization_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" id="level1_specialization_group">
                        <label for="level1_specialization">Enter Level 1 Specialization</label>
                        <input type="text" id="level1_specialization" name="level1_specialization" class="form-control"
                            placeholder="Enter specialization..." required>
                    </div>

                    <div class="form-group d-none" id="level2_specialization_group">
                        <label for="level2_specialization">Enter Level 2 Specialization</label>
                        <input type="text" id="level2_specialization" name="level2_specialization" class="form-control"
                            placeholder="Enter specialization...">
                    </div>

                    <div class="popup-button-group button-appointment-drprofile">
                        <button type="button" class="popup-btn" id="save_specialization">Save</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection


@section('javaScript')
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    {{-- <script>let table = new DataTable('#myTable'); </script> --}}

    <script src="{{ asset('public/assets/js/specialization.js') }}"></script>
    <script src="{{ asset('public/assets/js/ajaxRequest.js') }}"></script>

    <script>
        $(document).ready(function() {


            $('#top_level_specialization').on('change', function() {
                let selectedValue = $(this).val();

                if (selectedValue) {
                    $('#level1_specialization_group').addClass('d-none');
                    $('#level2_specialization_group').removeClass('d-none');
                    $('#level2_specialization').attr('required', true);
                    $('#level1_specialization').attr('required', false);
                } else {
                    $('#level1_specialization_group').removeClass('d-none');
                    $('#level2_specialization_group').addClass('d-none');
                    $('#level1_specialization').attr('required', true);
                    $('#level2_specialization').attr('required', false);
                }

            });

            // Save specialization with AJAX
            $('#save_specialization').on('click', function(e) {
                e.preventDefault();
                let formData = {
                    parent_id: $('#top_level_specialization').val(),
                    specialization_name: $('#level2_specialization').is(':visible') ?
                        $('#level2_specialization').val() : $('#level1_specialization').val(),
                    _token: $('input[name="_token"]').val(),
                };

                $.ajax({
                    url: "{{ route('store_specialization') }}",
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        alert(response.message);
                        if (response.success) {

                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        alert('Error: ' + xhr.responseJSON.message);
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            var table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('getSpecializationData') }}',
                    dataType: 'json',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    }
                },
                columns: [
                    // { data: 'id' },
                    {
                        data: 'specialization_name'
                    },
                    {
                        data: 'level'
                    },
                    // { data: 'created_by_name' },
                    {
                        data: 'date_addeda'
                    },
                    // { data: 'modified_by_name' },
                    {
                        data: 'action'
                    }
                ],
                order: [
                    [0, 'desc']
                ]
            });

            // Add New Service
            // $('#add-new').on('click', function() {
            //     $('#start-popup').fadeIn();
            //     $('.popup-form').fadeIn();
            //     $('#send')[0].reset();
            //     $('#send').attr('action', "{{ url('addService') }}");
            //     $('#send').find('#add').show();
            //     $('#send').find('#edit').hide();
            // });

            // $(document).on('click', 'button[id="edit"]', function() {
            //     var currentServiceId = $(this).val();
            //     $.ajax({
            //         url: "{{ url('getServiceDetails') }}",
            //         type: 'POST',
            //         data: { currentServiceId: currentServiceId, _token: '{{ csrf_token() }}'  },
            //         success: function(response) {
            //             $('#send').find('#service').val(response.service);
            //             $('#send').find('#id').val(response.id); // Add the Service ID for editing

            //             // Update the form's action to the editService route
            //             $('#send').attr('action', "{{ url('editService') }}");

            //             // Toggle the buttons: show 'Update', hide 'Add'
            //             $('.add-btn').hide();
            //             $('.update-btn').show();

            //             $('.popup-form').show();
            //         },
            //         error: function(xhr, status, error) {
            //             var response = xhr.responseJSON;
            //             alert(response.message);
            //             $('.popup-form').hide();
            //         }
            //     });
            // });

            // Add or Update Service
            // $('#send').on('submit', function(e) {
            //     e.preventDefault();
            //     var url = $(this).attr('action');
            //     $.ajax({
            //         url: url,
            //         method: 'POST',
            //         data: $(this).serialize(),
            //         success: function(response) {
            //             table.ajax.reload();
            //             $('#start-popup').fadeOut();
            //             $('.popup-form').fadeOut();
            //             alert(response.message);
            //         },
            //         error: function(xhr, status, error) {
            //             var response = xhr.responseJSON;
            //             alert(response.message);
            //             $('.popup-form').hide();
            //         }
            //     });
            // });


            $('#myTable tbody').on('click', 'button', function() {
                var id = $(this).val();
                var action = $(this).attr('id');
                console.log(id);
                if (action == 'suspend' || action == 'activate') {
                    var url = "{{ url('doctorActionFunc') }}"
                    token = "{{ csrf_token() }}"
                    sendAjaxRequest(url, id, action, token, table);
                }
                if (action == 'delete') {
                    var url = "{{ url('doctorDeleteFunction') }}";
                    token
                    if (confirm('Are you sure you want to delete this Specialization?')) {
                        token = "{{ csrf_token() }}"
                        sendAjaxRequest(url, id, action, token, table);
                    }
                }
            });

        });
    </script>
@endsection
