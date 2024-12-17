

function handleCommunicationContactForm(formId, csrfToken, clinicId) {
    const form= document.getElementById(formId);
    const formData = new FormData(form);

    const data = {};
    formData.forEach((value, key) => {
        data[key] = value;
        console.log(value);
        console.log(key);
    });
    console.log(data);
    $.ajax({
        url: form.getAttribute('action'),
        type: 'POST',
        data: {...data, _token:csrfToken, clinicId:clinicId },
       
        success: function (response) {
            console.log("AJAX Success:", response);
            alert('Communication Contacts Added Successfully');
            // gallery.empty();
            // logoImageForm[0].reset();
            // location.reload();
        },
        error: function (xhr, status, error) {
            var response = xhr.responseJSON;
            // Show the error alert
            alert(response.message);
            var submitButton = $('#clinicTimingsFormBtn');
            submitButton.prop('disabled', false).html('Save');
        }
    });
    return false;
}