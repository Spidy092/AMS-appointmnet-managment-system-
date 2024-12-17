@extends('layouts.adminLayout')


@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
@endsection


@section('content')

        <div class="main-heading">
            <h2>Manage Appointments</h2>
        </div>
        <div class="manage-doctors ">
            
            <div class="select-clinic">
                <select name="clinic" id="select-clinic">
                    <option value="Today">Today's</option>
                    <option value="Upcoming">Upcoming</option>
                    <option value="Previous">Previous</option>
                </select>
                <label for="select-clinic">Appointments</label>
            </div>

            <div class="manage-doctor-list">
                <table id="myTable"> 
                    <thead>
                        <tr>
                            <th>Doctor</th>
                            <th>Appointment Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Status</th>
                            <th>Check Out</th>
                            <th>Action</th>
                        </tr>

                    </thead>
                    <tbody>
                        <tr>
                            <td>Dr. Sarah Johnson</td>
                            <td>2024-11-14</td>
                            <td>09:00 AM</td>
                            <td>10:00 AM</td>
                            <td>Confirmed</td>
                            <td>Checked Out</td>
                            <td class="clinic-action-container">
                                 <a href="editAppointment.shtml" class="clinic-action-button">
                                    <span><img src="{{ asset('public/assets/images/icons/edit.png') }}" alt="Edit icon"></span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>Dr. Michael Smith</td>
                            <td>2024-11-15</td>
                            <td>11:30 AM</td>
                            <td>12:30 PM</td>
                            <td>Pending</td>
                            <td>Not Checked Out</td>
                            <td class="clinic-action-container">
                                 <a href="editAppointment.shtml" class="clinic-action-button">
                                    <span><img src="{{ asset('public/assets/images/icons/edit.png') }}" alt="Edit icon"></span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>Dr. Emily Davis</td>
                            <td>2024-11-16</td>
                            <td>02:00 PM</td>
                            <td>03:00 PM</td>
                            <td>Canceled</td>
                            <td>Not Applicable</td>
                            <td class="clinic-action-container">
                                 <a href="editAppointment.shtml" class="clinic-action-button">
                                    <span><img src="{{ asset('public/assets/images/icons/edit.png') }}" alt="Edit icon"></span>
                                </a>
                            </td>
                        </tr>
                    </tbody>                    
                    
                    </table>
            </div>
        </div>

         


</body>

</html>
@endsection



@section('javaScript')
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script>let table = new DataTable('#myTable'); </script>

@endsection