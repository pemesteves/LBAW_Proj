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
    });
    
    let eventImage = document.querySelector('form#event_image_upload img');
    if(eventImage != null)
        eventImage.addEventListener('click', uploadEventImage);

    let groupImage = document.querySelector('form#group_image_upload img');
    if(groupImage != null)
        groupImage.addEventListener('click', uploadGroupImage);
}

function uploadEventImage(event){
    return uploadImage(event, document.querySelector('form#event_image_upload input[type="file"]'));
}

function uploadGroupImage(event){
    return uploadImage(event, document.querySelector('form#group_image_upload input[type="file"]'));
}

function uploadImage(event, inputFile){
    inputFile.click();

    event.preventDefault();
    return false;
}

addEventListeners();