$(document).ready(function () {

    // const dropArea = $('#dropArea');
    // const fileInput = $('#fileInput');
    // const gallery = $('#gallery');
    // const imageForm = $('#imageForm');

    // Define maximum dimensions for resized images
    const maxWidthBig = 800;
    const maxHeightBig = 800;
    // const maxWidthThumb = 400;
    // const maxHeightThumb = 400;

    // // Prevent default drag behaviors
    // ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    //     dropArea.on(eventName, preventDefaults);
    // });

    // // Highlight drop area when item is dragged over it
    // ['dragenter', 'dragover'].forEach(eventName => {
    //     dropArea.on(eventName, () => dropArea.addClass('highlight'));
    // });

    // ['dragleave', 'drop'].forEach(eventName => {
    //     dropArea.on(eventName, () => dropArea.removeClass('highlight'));
    // });

    // // Handle dropped files
    // dropArea.on('drop', handleDrop);

    // // Handle file selection via input
    // fileInput.on('change', handleFiles);


    // function preventDefaults(e) {
    //     e.preventDefault();
    //     e.stopPropagation();
    // }

    // function handleDrop(e) {
    //     const dt = e.originalEvent.dataTransfer;
    //     const files = dt.files;
    //     handleFiles({ target: { files } });
    // }

    // let selectedFiles = [];
    // function handleFiles(e) {
    //     const files = e.target.files;
    //     selectedFiles = [...files];
    //     console.log("Files selected:", selectedFiles);
    //     selectedFiles.forEach((file, index) => previewFile(file, index));
    //     fileInput.data('files', selectedFiles);
    // }

    // function previewFile(file, index) {
    //     const reader = new FileReader();
    //     reader.readAsDataURL(file);
    //     reader.onloadend = function () {
    //         const imgContainer = $('<div>', { class: 'preview-container' });
    //         const img = $('<img>', {
    //             src: reader.result,
    //             class: 'preview-image'
    //         });

    //         const removeBtn = $('<i>', {
    //             class: 'fa-solid fa-xmark remove-image'
    //         });

    //         // Remove image on click
    //         removeBtn.on('click', function() {
    //             imgContainer.remove();
    //             selectedFiles = selectedFiles.filter(f => f !== file);
    //             console.log("Updated files after removal:", selectedFiles);
    //             fileInput.data('files', selectedFiles); // Update the data attribute with the remaining files
    //         });

    //         imgContainer.append(img).append(removeBtn);
    //         gallery.append(imgContainer);
    //     };
    // }


    const fileInput =  document.getElementById('logo-img-input');

    // Handle form submission via AJAX
    const logoImageForm = document.getElementById('upload-logo-form');
    logoImageForm.addEventListener('submit', (event) => {
       
       handleFormSubmit(event);
      });

    async function handleFormSubmit(e) {
        e.preventDefault();
        // Retrieve stored files
        // const files = fileInput.data('files');
        const formData = new FormData(logoImageForm);
        const files = formData.get('logo_image');


        console.log(files);
        // const formData = new FormData(logoImageForm[0]);
        // if (files && files.length > 0) {
        if (files.name) {
            var submitButton = $('#logoSubmitbtn');
            submitButton.prop('disabled', true).text('Please wait...');
            const resizedLogoImage = await resizeImage(files, maxWidthBig,maxHeightBig);
            formData.append('logo_image', resizedLogoImage);
            submitForm(formData);
        } else {
            alert('No images selected.');
        }
    }

    // async function processImages(files, formData) {
    //     const indiaTime = new Date().toLocaleString("en-US", { timeZone: "Asia/Kolkata" });
    //     const date = new Date(indiaTime);

    //     // Format the date and time as ddmmyyhhss
    //     const day = String(date.getDate()).padStart(2, '0');
    //     const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are 0-based in JS
    //     const year = String(date.getFullYear()).slice(-2); // Get last 2 digits of year
    //     const hours = String(date.getHours()).padStart(2, '0');
    //     const seconds = String(date.getSeconds()).padStart(2, '0');

    //     // Combine into ddmmyyhhss format
    //     const timestamp = `${day}${month}${year}${hours}${seconds}`;
    //     console.log("Processing images...");

    //     $('#progressContainer').show();
    //     let progress = 0;
    //     const increment = 100 / selectedFiles.length;

    //     for (const file of selectedFiles) {
    //         if (file.type.match('image.*')) {
    //             const randomNum = Math.floor(Math.random() * 1000);
    //             let imageTitle = imageForm.find('input[name="imageTitle"]').val().toLowerCase();
    //             let sanitizedTitle = imageTitle.replace(/[^a-z0-9_\-]/g, '_');

    //             const baseFileName = `${sanitizedTitle}-${timestamp}${randomNum}`;
    //             console.log(`Processing file: ${file.name}`);

    //             try {
    //                 const resizedBig = await resizeImage(file, maxWidthBig, maxHeightBig);
    //                 console.log(`Resized big image: ${resizedBig.name}`);
    //                 formData.append('bigImages[]', resizedBig, `${baseFileName}.${file.type.split('/')[1]}`);
    //                 const resizedThumb = await resizeImage(file, maxWidthThumb, maxHeightThumb);
    //                 console.log(`Resized thumbnail: ${resizedThumb.name}`);
    //                 formData.append('thumbImages[]', resizedThumb, `${baseFileName}.${file.type.split('/')[1]}`);
    //             } catch (error) {
    //                 console.error(`Error processing ${file.name}:`, error);
    //                 alert(`Error processing ${file.name}. Please try again.`);
    //                 return;
    //             }

    //             // Update progress bar
    //             progress += increment;
    //             $('#progressBar').css('width', `${progress}%`);
    //         } else {
    //             alert('Only PNG, JPG, or JPEG images are allowed.');
    //         }
    //     }

    //     console.log("All images processed. Submitting form...");
    //     submitForm(formData);
    // }

    // resizeImage(file, maxWidthThumb, maxHeightThumb);

    function resizeImage(file, maxWidth, maxHeight) {
        return new Promise((resolve, reject) => {
            console.log(`Resizing image: ${file.name}`);
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = function(event) {
                const img = new Image();
                img.src = event.target.result;
                img.onload = function() {
                    console.log(`Original dimensions of ${file.name}: ${img.width}x${img.height}`);
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
                    console.log(`Resized dimensions of ${file.name}: ${width}x${height}`);
                    const canvas = document.createElement('canvas');
                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);
                    canvas.toBlob(function(blob) {
                        if (blob) {
                            const resizedFile = new File([blob], file.name, { type: file.type });
                            console.log(`Resized file created for ${file.name}`);
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
        console.log("Submitting form via AJAX.");
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }

        console.log(formData);
        $.ajax({
            url: logoImageForm.getAttribute('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            // xhr: function() {
            //     const xhr = new window.XMLHttpRequest();
            //     xhr.upload.addEventListener("progress", function(evt) {
            //         if (evt.lengthComputable) {
            //             const percentComplete = evt.loaded / evt.total;
            //             const progressPercent = percentComplete * 100;
            //             $('#progressBar').css('width', `${progressPercent}%`);

            //             // If progress reaches 100%, hide the progress bar after a short delay
            //             if (progressPercent === 100) {
            //                 setTimeout(() => {
            //                     $('#progressContainer').hide();
            //                     $('#progressBar').css('width', '0');
            //                 }, 10); // Adjust the delay as needed
            //             }
            //         }
            //     }, false);
            //     return xhr;
            // },
            success: function (response) {
                console.log("AJAX Success:", response);
                alert('Images uploaded successfully');
                // gallery.empty();
                // logoImageForm[0].reset();
                location.reload();
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

});


// working correctly