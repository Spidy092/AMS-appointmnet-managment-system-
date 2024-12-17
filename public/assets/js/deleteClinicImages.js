async function handleDeleteClinicImage(imageClmName, thumbIMgClmName, clinicId, url, token){

    const isConfirmed = await confirmation("Delete Image", "Are you sure you want to delete the image?");

    if (isConfirmed) {
    
        $.ajax({
            url: url,
            type: 'POST',
            data: {clinicId : clinicId, imageClmName:imageClmName, thumbIMgClmName:thumbIMgClmName, _token:token},
        
        
            success: function (response) {
                console.log("AJAX Success:", response);
                document.getElementById(imageClmName).style.display = "none";
                alert('Images deleted successfully');
                const clinicImages = response.clinicData;

                const imageContainer = document.getElementById('clinic-images');
                imageContainer.parentNode.removeChild(imageContainer);

                // Clear existing images
                while (imageContainer.firstChild) {
                    imageContainer.removeChild(imageContainer.firstChild);
                }
                $('#addCarousel').prepend('<div id="clinic-images" class="owl-carousel add-clinic"></div>');

                let imageCount = 0;
                // Add new images
                Object.keys(clinicImages).forEach(key => {
                    if (key.endsWith('_thumb') && clinicImages[key]) {
                        const image = document.createElement('img');
                        image.src = clinicImages[key];
                        image.alt = 'clinic-img';
                        $(".owl-carousel").append(image);
                        imageCount++;
                    }
                });

                if (imageCount == 0) {

                    const addCarousel = document.getElementById('addCarousel');

                    const emptyImageContent = document.getElementById('logo-image-container')

                    emptyImageContent.removeChild(addCarousel);
                    // Remove the existing carousel
                    // const owlCarousel = document.getElementById('clinic-images');
                    // addCarousel.removeChild(owlCarousel);

                    // Create the new element
                    const newElement = document.createElement('div');
                    newElement.classList.add('add-clinic');
                    newElement.innerHTML = `
                        <img src="${response.emptyImgPath}" alt="clinic-img">
                        <p>Add Clinic Photos</p>
                        <a class="hover-text" href="#ex7" onclick="handleClinicAddImgDialog('open', 'clinic-image-dialog')">Add/Delete images</a>
                    `;

                    // Append the new element to the container
                    emptyImageContent.appendChild(newElement);

                }

                // Reinitialize the Owl Carousel
                var owl = $("#clinic-images");
                owl.owlCarousel({
                    // Your Owl Carousel configuration options
                    loop: true,
                    margin: 10,
                    nav: true,
                    autoplay: true,
                    autoplayTimeout: 3000,
                    responsive: {
                        0: {
                            items: 1
                        },
                        400: {
                            items: 1
                        },
                        490: {
                            items: 1
                        },
                        576: {
                            items: 1
                        },
                        776: {
                            items: 1
                        },
                        800: {
                            items: 1
                        },
                        1000: {
                            items: 1
                        },
                        1400: {
                            items: 1
                        }
                    }
                });
               
            },
            error: function (xhr, status, error) {
                var response = xhr.responseJSON;
                // Show the error alert
                alert(response.message);
        
                // // Reset progress bar and hide container
                // $('#progressContainer').hide();
                // $('#progressBar').css('width', '0');
        
                // Re-enable the submit button and reset the text to 'Submit'
                var submitButton = $('#logoSubmitbtn');
                submitButton.prop('disabled', false).html('<i class="fa-solid fa-cloud-arrow-up"></i>Upload');
            }
        });

    }


    

   
}