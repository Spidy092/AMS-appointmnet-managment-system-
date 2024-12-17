    
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.parent-checkbox').forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                const targetList = document.querySelector(this.dataset.target);
                if (this.checked) {
                    targetList.style.display = 'block'; 
                } else {
                    targetList.style.display = 'none'; 
                    targetList.querySelectorAll('.child-checkbox').forEach(child => {
                        child.checked = false;
                    });
                }
            });
            
            const targetList = document.querySelector(checkbox.dataset.target);
            if (checkbox.checked) {
                targetList.style.display = 'block';
            }
        });
    
        document.querySelectorAll('.child-checkbox').forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                const parentCheckbox = document.getElementById(this.dataset.parent);
                if (this.checked) {
                    parentCheckbox.checked = true; 
                }
            });
        });
    });
    

    async function handleSpecializationSave(token, clinic_id) {

        const isConfirmed = await confirmation("Update Specializations", "Are you sure that you want to update the specializations Changed?");


        if (isConfirmed) {
            const selectedSpecializations = [];
            document.querySelectorAll('#specialization-form input[type="checkbox"]:checked').forEach(function (checkbox) {
                selectedSpecializations.push(checkbox.value);
            });
        
            const formData = new FormData();
            selectedSpecializations.forEach((id, index) => {
                formData.append(`specializations[${index}]`, id); 
            });
            formData.append('clinic_id', clinic_id);
            const url = document.getElementById('specialization-form').getAttribute('action');
        
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': token
                },
                success: function (response) {
                    alert('Specializations saved successfully!');
                    location.reload();
                },
                error: function (xhr, status, error) {
                    var response = xhr.responseJSON;
                    alert(response.message || 'An error occurred while saving specializations.');
                }
            });
        }

    }
    