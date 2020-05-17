function addEventListeners() {
    window.addEventListener('load', function(){
        /** CREATE AND EDIT EVENT **/
        document.querySelector('form#event_image_upload input[type="file"]').addEventListener('change', function() {
            if (this.files && this.files[0]) {
                let img = document.querySelector('form#event_image_upload img');  // $('img')[0]
                img.src = URL.createObjectURL(this.files[0]); // set src to blob url
                
                if(document.querySelector('div#create_card') != null){
                    document.querySelector('form#event_image_upload input[type="file"]').style.opacity = 0;
                }
            }
        });
    });
    let eventImage = document.querySelector('form#event_image_upload img');
    if(eventImage != null)
        eventImage.addEventListener('click', uploadEventImage);
}

function uploadEventImage(event){
    let inputFile = document.querySelector('form#event_image_upload input[type="file"]');
    
    inputFile.click();

    event.preventDefault();
    return false;
}

addEventListeners();