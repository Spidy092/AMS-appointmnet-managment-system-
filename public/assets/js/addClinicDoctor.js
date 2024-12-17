function handleAddDoctorDialogBtn(btn){
    const dialog = document.getElementById('add-clinic-doctor-dialog');
    if (btn == "open"){
        dialog.showModal();
    } else{
        dialog.close();
    }
}