

function formatDoctorOption(doctor) {
    if (!doctor.id) {
        return doctor.text;
    }
    return $(
        `<div>
            <div style="font-size: 16px; font-weight: bold;">${doctor.text.split(' - ')[0]}</div>
            <div style="font-size: 12px;">${doctor.text.split(' - ')[1]}  ${doctor.text.split(' - ')[2]}</div>
        </div>`
    );
}


function formatDoctorSelection(doctor) {
    if (!doctor.id) {
        return doctor.text;
    }
    return $(
        `<div>
            <span style="font-size: 16px; ">${doctor.text.split(' - ')[0]}</span>
        </div>`
    );
}

function handleEditClinicDoctor(doctorBtn, clinic, url, csrfToken){
    const doctor = doctorBtn.value;
    const data = {
        clinic : clinic,
        doctor : doctor,
        _token : csrfToken
    }
    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        success: function (response) {
            console.log(response);
            const doctorData = response.data.doctor;

            const commonSpecializations = response.data.commonSpecializations;

            const selectedSpecializations = response.data.selectedSpecializations;

           
            const doctorSel = document.getElementById('update_clinic_doctor');
            doctorSel.innerHTML = `
                <option value="${doctorData.id}" selected>
                    <div>
                        <div style="font-size: 16px; font-weight: bold;">${doctorData.user.name}</div>
                    </div>
                </option>
            `;
            doctorSel.disabled = true;

            $('#doctor-ClinicDoctorMaping').val(doctorData.id);

            const specializationsSel = document.getElementById('update_clinic_specialization');

            specializationsSel.innerHTML = '';

            commonSpecializations.forEach(specialization => {
                const isSelected = selectedSpecializations.some(
                    selected => selected.id === specialization.id
                );

                const option = document.createElement('option');
                option.value = specialization.id;
                option.textContent = specialization.specialization_name;

                if (isSelected) {
                    option.selected = true; 
                }

                specializationsSel.appendChild(option);
            });

            console.log(specializationsSel);
            specializationsSel.disabled = false;
            document.getElementById('update-clinic-doctor-dialog').showModal();
        },
        error: function (xhr, status, error) {
            var response = xhr.responseJSON;
            alert(response.message);
        }
    });
}

function handleSelectDoctor(select, url, token, clinic){
    const p = document.getElementById('specializations-error-message');
    const specializationDiv =  document.getElementById('spe-sel2');
    const speSelect = document.getElementById('clinic_specialization');
    if(select.value){
    const data = {
        _token : token,
        doctor : select.value,
        clinic : clinic,
    }
    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        success: function (response) {
            console.log("AJAX Success:", response);
        
            if (response.data && response.data.length > 0) {
                p.innerText = "";
                p.style.display = "none"; 
                
                response.data.forEach((specialization) => {
                    const option = document.createElement('option');
                    option.value = specialization.id; 
                    option.textContent = specialization.specialization_name; 
                    speSelect.appendChild(option);
                });
                specializationDiv.style.visibility = "visible";

            } else {
                p.innerText = response.message;
                p.style.display = "block"; 
                specializationDiv.style.visibility = "hidden"; 
            }
        },
        error: function (xhr, status, error) {
            var response = xhr.responseJSON;
            alert(response.message);
        }
    });

    } else {
        console.log("remove spe and empyt");
        p.innerText = "";
        speSelect.innerText = "";
        p.style.display = "none"; 
        specializationDiv.style.visibility = "hidden";  
    }
}

function handleDeleteclinicDoctor(doctorBtn, clinic, url, csrfToken){
    const doctor = doctorBtn.value;
    const data = {
        clinic : clinic,
        doctor : doctor,
        _token : csrfToken
    }
    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        success: function (response) {
            alert("Doctor removed from this clinic successfully");
            console.log("AJAX Success:", response);  
            document.getElementById(`clinicDoctor-${doctor}`).remove();

            const $clinicDoctorCards = $("#clinic-doctors-container");
            if ($clinicDoctorCards.children().length === 0) {
                $clinicDoctorCards.append('<p id="no-doctor-msg">No doctor is added. <span onclick="handleAddDoctorDialogBtn(\'open\')">click here</span> to add</p>');
            }
        },
        error: function (xhr, status, error) {
            var response = xhr.responseJSON;
            alert(response.message);
        }
    });

}

$('#add-clinic-doctor-form').on('submit', function (event) {
    event.preventDefault();

    const form = $(this);
    const url = form.attr('action');
    const data = form.serialize();

    $('#doctor-error').text('');
    $('#specializations-error').text('');

    const submitButton = $('#submit-button');
    submitButton.prop('disabled', true).text('Submitting...');

    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        success: function (response) {
            if (response.success && response.doctor) {
                const doctorCard = `
                    <div class="clinic-doctors-card" id="clinicDoctor-${response.doctor.id}">
                        <span><img src="${response.doctor.image}" alt="Doctor"></span>
                        <div class="clinic-doctors-name">
                            <p>Dr. ${response.doctor.name}</p>
                            <p>${response.doctor.email}</p>
                        </div>
                        <div class = "edit-clinic-doctor-btns">
                            <button class="del-clinic-doctor-btn" type="button" value="${response.doctor.id}" 
                                onclick="handleEditClinicDoctor(this,  '${response.clinic_id}', '${response.edit_url}', '${response.csrf_token}')">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button class="del-clinic-doctor-btn" type="button" value="${response.doctor.id}" 
                                onclick="handleDeleteclinicDoctor(this, '${response.clinic_id}', '${response.delete_url}', '${response.csrf_token}')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>`;

                $('.clinic-doctors-cards').append(doctorCard);

                form[0].reset();

                $('#clinic_doctor').val(null).trigger('change');
                $('#clinic_specialization').val(null).trigger('change');

                $('#spe-sel2').css('visibility', 'hidden');

                const pElement = document.querySelector('.clinic-doctors-cards > p');

                if (pElement) {
                    pElement.remove();
                }
            }
        },
        error: function (xhr) {
            const errors = xhr.responseJSON.errors;
            if (errors) {
                if (errors.doctor) {
                    $('#doctor-error').text('*' + errors.doctor[0]);
                }
                if (errors.specializations) {
                    $('#specializations-error').text('*' + errors.specializations[0]);
                }
            } else {
                $('#doctor-error').text('* An unexpected error occurred.');
            }
        },
        complete: function () {
            submitButton.prop('disabled', false).text('Submit');
        },
    });
});

function handleUpdateClinicDoctorMappingForm() {
    const form = $('#update-clinic-doctor-form');
    const url = form.attr('action');
    const data = form.serialize();

    $('#update-doctor-error').text('');
    $('#update-specializations-error').text('');

    const submitButton = $('#update-submit-button');
    submitButton.prop('disabled', true).text('Submitting...');

    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        success: function (response) {
          alert(response.message);
          document.getElementById('update-clinic-doctor-dialog').close();
        },
        error: function (xhr) {
            const errors = xhr.responseJSON.errors;
            if (errors) {
                if (errors.doctor) {
                    $('#update-doctor-error').text('*' + errors.doctor[0]);
                }
                if (errors.specializations) {
                    $('#update-specializations-error').text('*' + errors.specializations[0]);
                }
            } else {
                $('#update-doctor-error').text('* An unexpected error occurred.');
            }
        },
        complete: function () {
            submitButton.prop('disabled', false).text('Submit');
        },
    });

    return false;
}

