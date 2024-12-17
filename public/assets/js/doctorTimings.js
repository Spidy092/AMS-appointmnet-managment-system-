function activate() {

	document.querySelectorAll(".time-pickable").forEach(timePickable => {
		let activePicker = null;

		timePickable.addEventListener("focus", () => {
            timePickable.value = "";
			if (activePicker) return;

			activePicker = show(timePickable);

			const onClickAway = ({ target }) => {
				if (
					target === activePicker
					|| target === timePickable
					|| activePicker.contains(target)
				) {
					return;
				}
                console.log(activePicker);
				document.removeEventListener("mousedown", onClickAway);
                // const timePicker = document.querySelectorAll("time-picker");
                // timePicker.forEach(element => element.remove());

                timePickable.parentNode.removeChild(activePicker);

				// document.body.removeChild(activePicker);
				activePicker = null;
			};

			document.addEventListener("mousedown", onClickAway);
		});
	});
}

function show(timePickable) {
	const picker = buildPicker(timePickable);
	// const { bottom: top, left } = timePickable.getBoundingClientRect();

	// picker.style.top = `${top}px`;
	// picker.style.left = `${left}px`;

	document.body.appendChild(picker);
    timePickable.parentNode.insertBefore(picker, timePickable.nextSibling);

	return picker;
    // console.log(timePickable)
}

function buildPicker(timePickable) {
	const picker = document.createElement("div");
	const hourOptions = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12].map(numberToOption);
	const minuteOptions = [0, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55].map(numberToOption);

	picker.classList.add("time-picker");
	picker.innerHTML = `
		<select class="time-picker__select">
			${hourOptions.join("")}
		</select>
		:
		<select class="time-picker__select">
			${minuteOptions.join("")}
		</select>
		<select class="time-picker__select">
			<option value="am">am</option>
			<option value="pm">pm</option>
		</select>
	`;

	const selects = getSelectsFromPicker(picker);

	selects.hour.addEventListener("change", () => timePickable.value = getTimeStringFromPicker(picker));
	selects.minute.addEventListener("change", () => timePickable.value = getTimeStringFromPicker(picker));
	selects.meridiem.addEventListener("change", () => timePickable.value = getTimeStringFromPicker(picker));

	if (timePickable.value) {
		const { hour, minute, meridiem } = getTimePartsFromPickable(timePickable);

		selects.hour.value = hour;
		selects.minute.value = minute;
		selects.meridiem.value = meridiem;
	}

	return picker;
}

function getTimePartsFromPickable(timePickable) {
	const pattern = /^(\d+):(\d+) (am|pm)$/;
	const [hour, minute, meridiem] = Array.from(timePickable.value.match(pattern)).splice(1);

	return {
		hour,
		minute,
		meridiem
	};
}

function getSelectsFromPicker(timePicker) {
	const [hour, minute, meridiem] = timePicker.querySelectorAll(".time-picker__select");

	return {
		hour,
		minute,
		meridiem
	};
}

function getTimeStringFromPicker(timePicker) {
	const selects = getSelectsFromPicker(timePicker);

	return `${selects.hour.value}:${selects.minute.value} ${selects.meridiem.value}`;
}

function numberToOption(number) {
	const padded = number.toString().padStart(2, "0");

	return `<option value="${padded}">${padded}</option>`;
}

activate();

function changeTimingInputs(dayTimes, inputValue){
    for (let i = 0; i < dayTimes.length; i++) {
        const time = dayTimes[i]; 
      
        if (inputValue === false) {
            time.value = "";
            time.style.visibility = 'hidden';
        } else {
            if (i == 0) {
                time.value = "09:00 am";
            }
            if (i == 1) {
                time.value = "01:00 pm";
            }
            if (i == 2) {
                time.value = "02:00 pm";
            }
            if (i == 3) {
                time.value = "06:00 pm";
            }
            time.style.visibility = 'visible';
        }
      }
}

function selectAddEventListener(dayId, timeClass) {
    const inputClinicTimingMon = document.getElementById(dayId);
    inputClinicTimingMon.addEventListener('change', (event) => {
    const inputValue = event.target.checked;
    const monTimes= document.getElementsByClassName(timeClass);
    changeTimingInputs(monTimes, inputValue);     
    });
}

selectAddEventListener("doctor-timing-mon", "mon-time");
selectAddEventListener("doctor-timing-tue", "tue-time");
selectAddEventListener("doctor-timing-wed", "wed-time");
selectAddEventListener("doctor-timing-thu", "thu-time");
selectAddEventListener("doctor-timing-fri", "fri-time");
selectAddEventListener("doctor-timing-sat", "sat-time");
selectAddEventListener("doctor-timing-sun", "sun-time");

function convertTo24HourFormat(timeString) {
    const [time, modifier] = timeString.split(' ');
    let [hours, minutes] = time.split(':');
  
    if (hours === '12') {
      hours = '00';
    }
  
    if (modifier === 'pm') {
      hours = parseInt(hours, 10) + 12;
    }
  
    return `${hours}:${minutes}`;
  
  }


function handleOnDoctorTimingForm(csrfToken, clinicId){
    var submitButton = $('#doctorTimingsFormBtn');
    submitButton.prop('disabled', true).text('Please wait...');
    let dayAndTime = {mon:"", tue: "",wed:"", thu: "",fri:"", sat: "",sun:"" };
    function dayValidation(day) {
        const dayCheck =  document.forms['doctorTimingForm'][`doctor-timing-${day}`];
        const isDayOn = dayCheck.checked;
        if (isDayOn){
            let timeArr = [];
            const daytimes = document.getElementsByClassName(`${day}-time`);
            for (let time of daytimes){
                timeArr.push(time.value);               
            }

            const TimeArr = timeArr.map((time)=> time);
            


            timeArr.forEach((time, index)=> {
                if (!(time == "")){
                    const timeIn24 = convertTo24HourFormat(time);
                    timeArr[index] = new Date(`2023-11-23T${timeIn24}:00`).getTime();
                }else {
                    timeArr[index] = "";
                }
            })

            if (!timeArr[0] && !timeArr[1] && !timeArr[2] && !timeArr[3]){
                alert("all are empty");
                var submitButton = $('#doctorTimingsFormBtn');
                submitButton.prop('disabled', false).html('Save');
                return false
            }
            if (timeArr[0] && !timeArr[1] || !timeArr[0] && timeArr[1] || timeArr[2] && !timeArr[3] || !timeArr[2] && timeArr[3]){
                alert("if filled both from and to time should be filled");
                var submitButton = $('#doctorTimingsFormBtn');
                submitButton.prop('disabled', false).html('Save');
                return false
            }
            if(timeArr[0] ){
                if (timeArr[0] >= timeArr[1]){
                    alert("from time should be less then to time");
                    var submitButton = $('#doctorTimingsFormBtn');
                    submitButton.prop('disabled', false).html('Save');
                    return false
                }
            }
            if (timeArr[2] )
            if (timeArr[2] >= timeArr[3]){
                alert("from time should be less then to time");
                var submitButton = $('#doctorTimingsFormBtn');
                submitButton.prop('disabled', false).html('Save');
                return false
            }
            if (timeArr[2] ){
                if (timeArr[1] > timeArr[2]){
                    alert("Second element is greater than the third.");
                    var submitButton = $('#doctorTimingsFormBtn');
                    submitButton.prop('disabled', false).html('Save');
                    return false
                }

            }

            dayAndTime[day] = TimeArr;

            

            return true;
            

        }
        return true;

        
    }

    

    if (dayValidation("mon") && 
        dayValidation("tue") &&
        dayValidation("wed") &&
        dayValidation("thu") &&
        dayValidation("fri") &&
        dayValidation("sat") &&
        dayValidation("sun")  ) {

            console.log("calling backend");


            console.log(dayAndTime);

            // updateBackend(dayAndTime, csrfToken, clinicId);
            return true;
        }

    
    
    return false;
}




// function handleChangeTimeDialogBtn(btn){
//     const timeChangeDialog = document.getElementById("clinic-timing-dialog");
//     if (btn == "open"){
//         timeChangeDialog.showModal();
//     } else if (btn == "close"){
//         timeChangeDialog.close();
//     }
// }


function updateBackend (dayAndTime, csrfToken, clinicId) {
    const clinicTimingsForm = document.getElementById('clinicTimingForm');
    $.ajax({
        url: clinicTimingsForm.getAttribute('action'),
        type: 'POST',
        data: {dateAndTime : dayAndTime, _token:csrfToken, clinicId:clinicId },
       
        success: function (response) {
            console.log("AJAX Success:", response);
            alert('Timings updated successfully');
            // gallery.empty();
            // logoImageForm[0].reset();
            location.reload();
        },
        error: function (xhr, status, error) {
            var response = xhr.responseJSON;
            // Show the error alert
            alert(response.message);
            var submitButton = $('#clinicTimingsFormBtn');
            submitButton.prop('disabled', false).html('Save');
        }
    });
}