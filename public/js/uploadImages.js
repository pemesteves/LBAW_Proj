// Loaded via <script> tag, create shortcut to access PDF.js exports.
let pdfjsLib = window['pdfjs-dist/build/pdf'];
// The workerSrc property shall be specified.
if(pdfjsLib !== null && pdfjsLib !== undefined)
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://mozilla.github.io/pdf.js/build/pdf.worker.js';


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

        /** CREATE POST **/
        let postInputImage = document.querySelector('form#post_form input[name="image"]');
        if(postInputImage !== null)
            postInputImage.addEventListener('change', function(){
                if(this.files && this.files[0]){
                    let imagesDiv = document.querySelector('form#post_form #postInputImages');
                    imagesDiv.style.display = "block";
                    let img = imagesDiv.querySelector('img#image');
                    img.style.display = "block";
                    img.src = URL.createObjectURL(this.files[0]);
                }
           });
        let postInputFile = document.querySelector('form#post_form input[name="file"]');
        if(postInputFile !== null)
        postInputFile.addEventListener('change', function(){
                if(this.files && this.files[0]){
                    if(this.files[0].type === "application/pdf"){
                        let fileReader = new FileReader();  
                        fileReader.onload = function() {
                            let pdfData = new Uint8Array(this.result);
                            // Using DocumentInitParameters object to load binary data.
                            let loadingTask = pdfjsLib.getDocument({data: pdfData});
                            loadingTask.promise.then(function(pdf) {
                                console.log('PDF loaded');
                                
                                // Fetch the first page
                                let pageNumber = 1;
                                pdf.getPage(pageNumber).then(function(page) {
                                    console.log('Page loaded');
                                    
                                    let scale = 1.5;
                                    let viewport = page.getViewport({scale: scale});

                                    // Prepare canvas using PDF page dimensions
                                    let canvas = document.querySelector('canvas.file');
                                    let context = canvas.getContext('2d');
                                    canvas.height = viewport.height;
                                    canvas.width = viewport.width;
                                    canvas.style.display = "block";
                                    document.querySelector('img.file').style.display = "none";
                                    let imagesDiv = document.querySelector('form#post_form #postInputImages');
                                    imagesDiv.style.display = "block";

                                    // Render PDF page into canvas context
                                    let renderContext = {
                                        canvasContext: context,
                                        viewport: viewport
                                    };

                                    let renderTask = page.render(renderContext);
                                    renderTask.promise.then(function () {
                                        console.log('Page rendered');
                                    });
                                });
                            }, function (reason) {
                                // PDF loading error
                                console.error(reason);
                            });
                        };
                        fileReader.readAsArrayBuffer(this.files[0]);
                    }
                    else if(this.files[0].type.split('/')[0] === "image"){
                        let imagesDiv = document.querySelector('form#post_form #postInputImages');
                        imagesDiv.style.display = "block";
                        document.querySelector('canvas.file').style.display = "none";
                        let img = imagesDiv.querySelector('img.file');
                        img.style.display = "block";
                        img.src = URL.createObjectURL(this.files[0]);
                    }
                    else{
                        let imagesDiv = document.querySelector('form#post_form #postInputImages');
                        imagesDiv.style.display = "block";
                        let img = imagesDiv.querySelector('img.file');
                        img.style.display = "block";
                        document.querySelector('canvas.file').style.display = "none";
                        
                        if(this.files[0].type.split('/')[0] === "audio"){
                            img.src = "/images/audio.png";
                        }
                        else if(this.files[0].type.split('/')[0] === "video"){
                            img.src = "/images/video.png";
                        }
                        else if(this.files[0].type === "text/javascript"){
                            img.src = "/images/js.png";
                        }
                        else if(this.files[0].type === "text/css"){
                            img.src = "/images/css.svg";
                        }
                        else if(this.files[0].type === "text/html"){
                            img.src = "/images/html.png";
                        }
                        else{
                            img.src = "/images/file.png";
                        }
                    }
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