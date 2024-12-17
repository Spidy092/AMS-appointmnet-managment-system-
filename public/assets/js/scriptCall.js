



function handleCalenderDialog(info) {
    console.log("calling function")
    const calendarDialog = document.getElementById('calendar-dialog');
    
    if ( info == "close") {
        calendarDialog.close();
    } else {
        calendarDialog.style.left = (info.jsEvent.pageX - ((info.jsEvent.pageX < window.outerWidth/2) ? 0 : 290)) + "px";
        calendarDialog.style.top = info.jsEvent.pageY + 5 + "px";
        calendarDialog.showModal();
    }
    
}





document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        buttonText : {
            today : 'Today',
            month: 'Month',
            week:  'Week',
            day: 'Day',
        },
        titleFormat:{ year: 'numeric', month: 'short', },
        customButtons: {
            clinicName: {
              text: 'Clinics',
              click: function() {
                alert('will change to view by clinic here!');
              }
            },
            patient: {
                text: 'Patient',
                click: function() {
                  alert('will change to view by clinic here!');
                }
            },
            treatment: {
                text: 'Treatment.',
                click: function() {
                  alert('will change to view by clinic here!');
                }
              }
          },
        initialView: 'timeGridWeek',
        headerToolbar: {
            left: 'clinicName,patient,treatment',
            center: 'prev title next',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        
        footerToolbar:{
            end: 'today'
          },
        editable: true,
        // selectable: true,
        // select: function(info) {
        //     showAppointmentPopup(info);
        // },
        dateClick: function(info) {
            console.log(info);
            showAppointmentPopup(info);
         },
         eventClick: function(info) {
            // alert('Event: ' + info.event.title);
            console.log('Coordinates: ' + info.jsEvent.pageX + ',' + info.jsEvent.pageY);
            // alert('View: ' + info.view.type);
            handleCalenderDialog(info);
          }
    });
    calendar.addEvent({ title: 'new event', start: '2024-11-23' } );
    calendar.addEvent({ title: 'new event', start: '2024-11-17' } );

    calendar.render();

    // Auto-calculate end time when duration or start time changes
    // document.getElementById('start_time').addEventListener('change', updateEndTime);
    // document.getElementById('appointment_duration').addEventListener('change', updateEndTime);

    // function updateEndTime() {
    //     const startTime = document.getElementById('start_time').value;
    //     const duration = document.getElementById('appointment_duration').value;
        
    //     if (startTime && duration) {
    //         const [hours, minutes] = startTime.split(':');
    //         const startDate = new Date();
    //         startDate.setHours(parseInt(hours), parseInt(minutes), 0);
            
    //         const endDate = new Date(startDate.getTime() + parseInt(duration) * 60000);
    //         const endHours = String(endDate.getHours()).padStart(2, '0');
    //         const endMinutes = String(endDate.getMinutes()).padStart(2, '0');
            
    //         // document.getElementById('end_time').value = `${endHours}:${endMinutes}`;
    //     }
    // }

  

    var headerContainer = document.querySelector('.fc-toolbar-chunk');
    var plainTextElement = document.createElement('span');
    plainTextElement.textContent = 'View by :';
    plainTextElement.classList.add('custom-header-text'); // Add a CSS class for styling
    headerContainer.insertBefore(plainTextElement, headerContainer.firstChild);




    function showAppointmentPopup(info) {
        const popup = document.getElementById('appointment_popup');
        const overlay = document.createElement('div');
        overlay.id = 'appointment_overlay';
        overlay.className = 'popup-overlay';
        document.body.appendChild(overlay);
        
        // Set the selected date and default time
        const selectedDate = new Date(info.date);

        console.log(selectedDate);
        
        // Set form values
        document.getElementById('appointment_date').value = formatDate(selectedDate);
        document.getElementById('start_time').value = formatTime(selectedDate);
        // document.getElementById('appointment_duration').value = '30'; // Default 30 minutes
        // updateEndTime(); // Calculate initial end time
        
        // Clear other form fields
        document.getElementById('patient_name').value = '';
        document.getElementById('contact_number').value = '';
        document.getElementById('email_address').value = '';
        // document.getElementById('consultation_reason').value = '';
        // document.getElementById('clinic_name').value = '';
        // document.getElementById('doctor_name').value = '';

        // Show popup and overlay
        popup.style.display = 'block';
        overlay.style.display = 'block';
        document.body.classList.add('popup-open');

        // Add event listener to overlay
        overlay.addEventListener('click', hideAppointmentPopup);
    }

    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    function formatTime(date) {
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        return `${hours}:${minutes}`;
    }

    // Form submission handler
    // document.getElementById('appointment_form').addEventListener('submit', function(e) {
    //     e.preventDefault();
        
    //     const formData = new FormData(this);
    //     const appointmentData = Object.fromEntries(formData.entries());

    //     $.ajax({
    //         url: 'save_appointment.php',
    //         type: 'POST',
    //         data: appointmentData,
    //         success: function(response) {
    //             calendar.refetchEvents();
    //             hideAppointmentPopup();
    //             alert('Appointment saved successfully!');
    //         },
    //         error: function() {
    //             alert('Error saving appointment');
    //         }
    //     });
    // });

    function hideAppointmentPopup() {
        const popup = document.getElementById('appointment_popup');
        const overlay = document.getElementById('appointment_overlay');
        
        if (popup) {
            popup.style.display = 'none';
        }
        
        if (overlay) {
            overlay.remove();
        }
        
        document.body.classList.remove('popup-open');
        calendar.render();
    }

    // Close popup handlers
    document.getElementById('cancel_appointment').addEventListener('click', hideAppointmentPopup);

    // Close popup on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            hideAppointmentPopup();
        }
    });
});