function handleGetDoctor(select, url, csrfToken){
    const clinicId = select.value;

    const selectDoctor = document.getElementById('sel_doctor');

    if (clinicId == "") {
        selectDoctor.innerHTML = "<option value=''>Select Doctor</option>";
        document.getElementById('appointment_duration').value = "";
        document.getElementById('durationSelect').innerHTML = "";
        return
    }

    $.ajax({
        url: url,
        type: 'POST',
        data: {_token:csrfToken, clinicId:clinicId },
       
        success: function (response) {
            selectDoctor.innerHTML = "<option value=''>Select Doctor</option>";

            console.log(response);

            if (response.doctors.length === 0){
                alert("There are no doctors assigned to this clinic. Please add a doctor.");
                return
            }

            response.doctors.forEach(doctor => {
                const option = document.createElement('option');
                option.value = doctor.id; 
                option.textContent = doctor.user.name; 
        
                selectDoctor.appendChild(option);
            });

            document.getElementById('appointment_duration').value = response.duration;
            document.getElementById('durationSelect').innerHTML = `<option value="" selected>${response.duration}</option>`;
            
        },
        error: function (xhr, status, error) {
            var response = xhr.responseJSON;
            alert(response.message);
        }
    });
}

function handleAppointmentForm(form) {
    event.preventDefault(); 
    const formAction = form.getAttribute("action");
    const formData = new FormData(form);

    $.ajax({
        url: formAction,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            console.log("Form submitted successfully:", response);
            alert("Appointment saved successfully!");
        },
        error: function (xhr) {
            $(".form-validation-error").text("");

            if (xhr.responseJSON && xhr.responseJSON.errors) {
                for (const [field, messages] of Object.entries(xhr.responseJSON.errors)) {
                    const errorContainer = document.getElementById(`error-${field}`);
                    if (errorContainer) {
                        errorContainer.textContent = messages[0]; 
                    }
                }
            } else {
                alert("An error occurred. Please try again.");
            }
        },
    });

    return false;
}


function handleGetDoctorAvailableTime(select, url, csrfToken) {
    const clinic = document.getElementById('sel_clinic').value;
    const doctor = select.value;
    const date = document.getElementById('appointment_date').value; 
    const data = {
        clinic : clinic,
        doctor: doctor,
        date: date,
        _token: csrfToken
    };

    if (doctor == "") {
        return;
    } else {
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            success: function (response) {
                console.log(response);
                if (response.success) {

                    const timings = response.data; 
                    let timingsText = 'Doctor Available Timings:';

                    timings.forEach(timing => {
                        timingsText += ` ${timing.morning_from} to ${timing.morning_to} or `;
                        timingsText += `${timing.evening_from} to ${timing.evening_to}`;
                    });

                    const p = document.createElement('p');
                    p.innerHTML = timingsText;
                    p.classList.add('available-timings'); 
                    
                    $('#appointment_popup').prepend(p);
                } else {
                    const errorMessage = response.message || 'No timings available.';
                    const errorP = document.createElement('p');
                    errorP.innerHTML = errorMessage;
                    errorP.classList.add('error-message'); 
                    $('#appointment_popup').prepend(errorP);
                }
            },
            error: function (xhr) {
                const response = xhr.responseJSON;
                alert(response.message);
            },
        });
    }
}