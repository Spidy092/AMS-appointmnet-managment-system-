$(document).ready(function () {

   
    const maxWidthBig = 800;
    const maxHeightBig = 800;
    const maxThumbWidthBig = 400;
    const maxThumbHeightBig = 400;
    
    const fileInput =  document.getElementById('clinic-images-input');
    let imageFileImages ;

    fileInput.addEventListener('change', (event) => {
        imageFileImages = event.target.files;
      });

    // Handle form submission via AJAX
    const logoImageForm = document.getElementById('upload-images-form');
    logoImageForm.addEventListener('submit', (event) => {
       
       handleFormSubmit(event);
      });



    async function handleFormSubmit(e) {
        e.preventDefault();
        // Retrieve stored files
        // const files = fileInput.data('files');
        const formData = new FormData(logoImageForm);
        
        const files = imageFileImages;

        


        // const formData = new FormData(logoImageForm[0]);
        if (files && files.length > 0) {
            var submitButton = $('#imagesSubmitbtn');
            submitButton.prop('disabled', true).text('Please wait...');
            for (let i = 0; i < files.length; i++) {
                const file = files[i];

                const resizedClinicImage = await resizeImage(file, maxWidthBig, maxHeightBig);
                const thumbResizedClinicImage = await resizeImage(file, maxThumbWidthBig, maxThumbHeightBig);
                formData.append(`image_${i}`, resizedClinicImage);
                formData.append(`thumb_image_${i}`, thumbResizedClinicImage);
                
                
            }
            console.log(formData);
            submitForm(formData);
        } else {
            alert('No images selected.');
        }
    }

   

    function resizeImage(file, maxWidth, maxHeight) {
        return new Promise((resolve, reject) => {
            // console.log(`Resizing image: ${file.name}`);
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = function(event) {
                const img = new Image();
                img.src = event.target.result;
                img.onload = function() {
                    // console.log(`Original dimensions of ${file.name}: ${img.width}x${img.height}`);
                    let width = img.width;
                    let height = img.height;
                    if (width > maxWidth) {
                        height *= maxWidth / width;
                        width = maxWidth;
                    }
                    if (height > maxHeight) {
                        width *= maxHeight / height;
                        height = maxHeight;
                    }
                    // console.log(`Resized dimensions of ${file.name}: ${width}x${height}`);
                    const canvas = document.createElement('canvas');
                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);
                    canvas.toBlob(function(blob) {
                        if (blob) {
                            const resizedFile = new File([blob], file.name, { type: file.type });
                            // console.log(`Resized file created for ${file.name}`);
                            resolve(resizedFile);
                        } else {
                            reject(new Error('Blob creation failed'));
                        }
                    }, file.type, 0.8);
                }
                img.onerror = reject;
            }
            reader.onerror = reject;
        });
    }




    function submitForm(formData) {
        // console.log("Submitting form via AJAX.");
        for (let [key, value] of formData.entries()) {
            // console.log(key, value);
        }

        // console.log(formData);
        $.ajax({
            url: logoImageForm.getAttribute('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
           
            success: function (response) {
                // console.log("AJAX Success:", response);
                alert('Images uploaded successfully');
                // gallery.empty();
                // logoImageForm[0].reset();
                location.reload();
            },
            error: function (xhr, status, error) {
                var response = xhr.responseJSON;
                // Show the error alert
                alert(response.message);
                var submitButton = $('#imagesSubmitbtn');
                submitButton.prop('disabled', false).html('<i class="fa-solid fa-cloud-arrow-up"></i>Upload');
            }
        });
    }

});
