const addMoreButton = document.querySelector('.add-btn');
const popupContainer = document.getElementById('appointment_popup');
const popupOverlay = document.getElementById('popup_overlay');
const closeBtn = document.getElementById('close-add-specialization');
const specializationInput = document.getElementById('specialization_input');
const tagContainer = document.getElementById('tag_container');
const saveButton = document.getElementById('save_appointment');

// Show popup
addMoreButton.addEventListener('click', function() {
    popupContainer.style.display = 'block';
    popupOverlay.style.display = 'block';
});

// Hide popup
// saveButton.addEventListener('click', function() {
//     popupContainer.style.display = 'none';
//     popupOverlay.style.display = 'none';
// });

popupOverlay.addEventListener('click', function() {
    popupContainer.style.display = 'none';
    popupOverlay.style.display = 'none';
});
closeBtn.addEventListener('click', function() {
    popupContainer.style.display = 'none';
    popupOverlay.style.display = 'none';
});
