function addEventListeners() {
    window.addEventListener('load', function(){
        /** CREATE AND EDIT EVENT **/
        let eventInputFile = document.querySelector('form#event_image_upload input[type="file"]');
        if(eventInputFile !== null)
            eventInputFile.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    let img = document.querySelector('form#event_image_upload img');  // $('img')[0]
                    img.src = URL.createObjectURL(this.files[0]); // set src to blob url
                    
                    if(document.querySelector('div#create_card') != null){
                        document.querySelector('form#event_image_upload input[type="file"]').style.opacity = 0;
                    }
                }
            });

        /** CREATE AND EDIT GROUP **/
        let groupInputFile = document.querySelector('form#group_image_upload input[type="file"]');
        if(groupInputFile !== null)
            groupInputFile.addEventListener('change', function(){
                if(this.files && this.files[0]){
                    let img = document.querySelector('form#group_image_upload img');
                    img.src = URL.createObjectURL(this.files[0]);
                }
            });

        /** EDIT USER PROFILE **/
        let userInputFile = document.querySelector('form#user_image_upload input[type="file"]');
        if(userInputFile !== null)
            userInputFile.addEventListener('change', function(){
                if(this.files && this.files[0]){
                    let img = document.querySelector('form#user_image_upload img');
                    img.src = URL.createObjectURL(this.files[0]);
                }
            });
    });
    
    let eventImage = document.querySelector('form#event_image_upload img');
    if(eventImage != null)
        eventImage.addEventListener('click', uploadEventImage);

    let groupForm = document.querySelector('form#group_image_upload');
    if(groupForm != null){
        groupForm.addEventListener('submit', checkGroupImageUpload);

        let groupImage = groupForm.querySelector('img');
        if(groupImage != null)
            groupImage.addEventListener('click', uploadGroupImage);
    }

    let userImage = document.querySelector('form#user_image_upload img');
    if(userImage != null)
        userImage.addEventListener('click', uploadUserImage);
}

function uploadEventImage(event){
    return uploadImage(event, document.querySelector('form#event_image_upload input[type="file"]'));
}

function uploadGroupImage(event){
    return uploadImage(event, document.querySelector('form#group_image_upload input[type="file"]'));
}

function uploadUserImage(event){
    return uploadImage(event, document.querySelector('form#user_image_upload input[type="file"]'));
}

function uploadImage(event, inputFile){
    inputFile.click();

    event.preventDefault();
    return false;
}

function checkGroupImageUpload(event){
    const img = this.querySelector('img');
    let message = "";
    if(img.src === "http://www.pluspixel.com.br/wp-content/uploads/services-socialmediamarketing-optimized.png"){
       message = "You can also add an image to your group!"
    }
    
    let popUp = document.createElement("dialog");
    popUp.classList.add('d-print-none');
    popUp.setAttribute('open', 'open');
    popUp.setAttribute('style', 'margin-top: 3.5em;');
    popUp.innerHTML = `
        <p style="text-align: center;">Are you sure you want to create the group? ${message}</p>
        <div style="text-align: center;">
            <button class="btn btn-success" onclick="submitForm()">Yes</button>
            <button class="btn btn-danger" onclick="deletePopUp()">No</button>
        </div>
        `;
    img.parentNode.insertBefore(popUp, img);

    event.preventDefault();
    return false;
}

function submitForm(){
    document.querySelector('form#group_image_upload').submit();
}

function deletePopUp(){
    document.querySelector('dialog').remove();
}

addEventListeners();