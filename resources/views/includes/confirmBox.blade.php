<dialog id = "confirmBox-dialog">

    <div class="confirm-content">
        <div class="heading-div">
            <h2 id = "confirmbox-title"></h2>
        </div>
        <div class="dialog-msg"> 
            <p id = "confirmBox-message"> </p>
        </div>
        <div class="confirmBox-footer">
            <div class="controls"> 
                <button class="button button-danger confirmBox-button" id = "confirmbox-yes">Yes</button>
                <button class="button button-default confirmBox-button" id = "confirmbox-cancle">Cancel</button>
            </div>
        </div>    
    </div>


</dialog>




<script>
       

       function confirmation(title, message) {
        const confirmBoxTitle = document.getElementById('confirmbox-title');
        confirmBoxTitle.textContent = title;
        const confirmBoxMsg = document.getElementById('confirmBox-message');
        confirmBoxMsg.textContent = message;
        const confirmBox = document.getElementById('confirmBox-dialog');
        confirmBox.showModal();

        return new Promise(resolve => {
            const confirmBoxButtons = document.querySelectorAll('.confirmBox-button');

            confirmBoxButtons.forEach(button => {
            button.addEventListener('click', () => {
                confirmBox.close();
                resolve(button.id === 'confirmbox-yes');
            });
            });
        });
        }


        
      
</script>