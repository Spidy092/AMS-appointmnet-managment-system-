
function handleDialog(btn, dialogId){
    const dialog = document.getElementById(dialogId);
    if (btn == "open"){
        dialog.showModal();
    } else if (btn == "close"){
        dialog.close();
    }
}