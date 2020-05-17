function addEventListeners(){
    let nameInputs = document.querySelectorAll('input#name');
    [].forEach.call(nameInputs, function(checker) {
        checker.addEventListener('change', nameCheck);
    });
    let universityInputs = document.querySelectorAll('input#university');
    [].forEach.call(universityInputs, function(checker) {
        checker.addEventListener('change', universityCheck);
    });
    let postTitleInputs = document.querySelectorAll('input#post_title');
    [].forEach.call(postTitleInputs, function(checker) {
        checker.addEventListener('change', postTitleCheck);
    });
    let postTextInputs = document.querySelectorAll('input#post_text');
    [].forEach.call(postTextInputs, function(checker) {
        checker.addEventListener('change', postTextCheck);
    });
    let commentInputs = document.querySelectorAll('div.post_comment_form > div > textarea');
    [].forEach.call(commentInputs, function(checker) {
        checker.addEventListener('change', commentCheck);
    });
    let informationInputs = document.querySelectorAll('textarea#information');
    [].forEach.call(informationInputs, function(checker) {
        checker.addEventListener('change', informationCheck);
    });
    let personalInfoInputs = document.querySelectorAll('textarea#personal_info');
    [].forEach.call(personalInfoInputs, function(checker) {
        checker.addEventListener('change', personalInfoCheck);
    });
    let reportTitleInputs = document.querySelectorAll('input#report_title');
    [].forEach.call(reportTitleInputs, function(checker) {
        checker.addEventListener('change', reportTitleCheck);
    });
    let reportDescriptionInputs = document.querySelectorAll('textarea#report_description');
    [].forEach.call(reportDescriptionInputs, function(checker) {
        checker.addEventListener('change', reportDescriptionCheck);
    });
}

function nameCheck(){
    let field = document.getElementById('name');
    let regex = /^[a-z0-9]+[a-z0-9 ]*[a-z0-9]$/i;
    if(regex.test(field.value)){
        field.setCustomValidity("");
    }
    else{
        field.setCustomValidity("Name can only contain letters, digits and spaces.");
    }
}

function universityCheck(){
    let field = document.getElementById('university');
    let regex = /^[a-z0-9]+[a-z0-9 ]*[a-z0-9]$/i;
    if(regex.test(field.value)){
        field.setCustomValidity("");
    }
    else{
        field.setCustomValidity("University can only contain letters, digits and spaces.");
    }
}

function postTitleCheck(){
    let field = document.getElementById('post_title');
    let regex = /^[a-z0-9\[\]\(\)<>\-_!?\.&',;:@]+[a-z0-9\[\]\(\)<>\-_!?\.&',;:@ ]*[a-z0-9\[\]\(\)<>\-_!?\.&',;:@]$/i;
    if(regex.test(field.value)){
        field.setCustomValidity("");
    }
    else{
        field.setCustomValidity("Post title contains invalid characters.");
    }
}

function postTextCheck(){
    let field = document.getElementById('post_text');
    let regex = /^[a-z0-9\[\]\(\)<>\-_!?\.&',;:@]+[a-z0-9\[\]\(\)<>\-_!?\.&',;:@ ]*[a-z0-9\[\]\(\)<>\-_!?\.&',;:@]$/i;
    if(regex.test(field.value)){
        field.setCustomValidity("");
    }
    else{
        field.setCustomValidity("Post text contains invalid characters.");
    }
}

function commentCheck(){
    let field = document.querySelector('article.post div[style="display: block;"] div.post_comment_form textarea');
    let regex = /^[a-z0-9\[\]\(\)<>\-_!?\.&',;:@]+[a-z0-9\[\]\(\)<>\-_!?\.&',;:@ ]*[a-z0-9\[\]\(\)<>\-_!?\.&',;:@]$/i;
    if(regex.test(field.value)){
        field.setCustomValidity("");
    }
    else{
        field.setCustomValidity("Comment contains invalid characters.");
    }
}

function informationCheck(){
    let field = document.getElementById('information');
    let regex = /^[a-z0-9\[\]\(\)<>\-_!?\.&',;:@]+[a-z0-9\[\]\(\)<>\-_!?\.&',;:@ ]*[a-z0-9\[\]\(\)<>\-_!?\.&',;:@]$/i;
    if(regex.test(field.value)){
        field.setCustomValidity("");
    }
    else{
        field.setCustomValidity("Information contains invalid characters.");
    }
}

function personalInfoCheck(){
    let field = document.getElementById('personal_info');
    let regex = /^[a-z0-9\[\]\(\)<>\-_!?\.&',;:@]+[a-z0-9\[\]\(\)<>\-_!?\.&',;:@ ]*[a-z0-9\[\]\(\)<>\-_!?\.&',;:@]$/i;
    if(regex.test(field.value)){
        field.setCustomValidity("");
    }
    else{
        field.setCustomValidity("Personal Information contains invalid characters.");
    }
}

function reportTitleCheck(){
    let field = document.getElementById('report_title');
    let regex = /^[a-z0-9]+[a-z0-9 ]*[a-z0-9]$/i;
    if(regex.test(field.value)){
        field.setCustomValidity("");
    }
    else{
        field.setCustomValidity("Title contains invalid characters.");
    }
}

function reportDescriptionCheck(){
    let field = document.getElementById('report_description');
    let regex = /^[a-z0-9\[\]\(\)<>\-_!?\.&',;:@]+[a-z0-9\[\]\(\)<>\-_!?\.&',;:@ ]*[a-z0-9\[\]\(\)<>\-_!?\.&',;:@]$/i;
    if(regex.test(field.value)){
        field.setCustomValidity("");
    }
    else{
        field.setCustomValidity("Description contains invalid characters.");
    }
}

addEventListeners();