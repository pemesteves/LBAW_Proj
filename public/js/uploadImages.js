function addEventListeners() {
    window.addEventListener('load', function(){
        document.querySelector('form#event_image_upload input[type="file"]').addEventListener('change', function() {
            console.error("ola");
            if (this.files && this.files[0]) {
                console.error("alo");
                let img = document.querySelector('form#event_image_upload img');  // $('img')[0]
                img.src = URL.createObjectURL(this.files[0]); // set src to blob url
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

function sendEventImageUploadRequest(event){
    let image = document.querySelector('form#event_image_upload input[name=image]').value;
    
    let resource = "";
    
    let pathParts = window.location.pathname.split('/');
    if(pathParts[1] == "events")
        resource = '/api/events/' + pathParts[2] + '/upload_image';
    
    if(image != null){
        let request = new XMLHttpRequest();

        request.open('post', resource, true);
        request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
        request.setRequestHeader('Content-Type', 'multipart/form-data');
        request.addEventListener('load', imageUploadedHandler);

        let data = new FormData();
        data.append('image', image);

        request.send(data);
        //sendAjaxRequest('post', resource, {image: image}, imageUploadedHandler);
    }

    event.preventDefault();
    return false;
}

function imageUploadedHandler() {
    if (this.status !== 200) {
        window.location = '/';
        return;
    }
    
    console.error(this.responseText);

    let image_name = this.responseText;
    // Set the new image
    let image = document.querySelector('form#event_image_upload img');

    image.src = '/images/' + image_name;
}

addEventListeners();