function addEventListeners(){
    let nameInputs = document.querySelectorAll('input#name');
    [].forEach.call(nameInputs, function(checker) {
        checker.addEventListener('input', nameCheck);
    });
    let universityInputs = document.querySelectorAll('input#university');
    [].forEach.call(universityInputs, function(checker) {
        checker.addEventListener('input', universityCheck);
    });
    let postTitleInputs = document.querySelectorAll('input#post_title');
    [].forEach.call(postTitleInputs, function(checker) {
        checker.addEventListener('input', postTitleCheck);
    });
    let postTextInputs = document.querySelectorAll('input#post_text');
    [].forEach.call(postTextInputs, function(checker) {
        checker.addEventListener('input', postTextCheck);
    });
    let commentInputs = document.querySelectorAll('div.post_comment_form > div > textarea');
    [].forEach.call(commentInputs, function(checker) {
        checker.addEventListener('input', commentCheck);
    });
    let informationInputs = document.querySelectorAll('textarea#information');
    [].forEach.call(informationInputs, function(checker) {
        checker.addEventListener('input', informationCheck);
    });
    let personalInfoInputs = document.querySelectorAll('textarea#personal_info');
    [].forEach.call(personalInfoInputs, function(checker) {
        checker.addEventListener('input', personalInfoCheck);
    });
    let reportTitleInputs = document.querySelectorAll('input#report_title');
    [].forEach.call(reportTitleInputs, function(checker) {
        checker.addEventListener('input', reportTitleCheck);
    });
    let reportDescriptionInputs = document.querySelectorAll('textarea#report_description');
    [].forEach.call(reportDescriptionInputs, function(checker) {
        checker.addEventListener('input', reportDescriptionCheck);
    });
}

function nameCheck(){
    let field = document.getElementById('name');
    let regex = /^[a-z0-9áàãâéêíóõôú]+(?:[a-z0-9áàãâéêíóõôú ]*[a-z0-9áàãâéêíóõôú])?$/i;
    if(regex.test(field.value)){
        field.setCustomValidity("");
    }
    else{
        field.setCustomValidity("Name can only contain letters, digits and spaces and can't start or end with space.");
    }
}

function universityCheck(){
    let field = document.getElementById('university');
    let regex = /^[a-z0-9áàãâéêíóõôú]+(?:[a-z0-9áàãâéêíóõôú ]*[a-z0-9áàãâéêíóõôú])?$/i;
    if(regex.test(field.value)){
        field.setCustomValidity("");
    }
    else{
        field.setCustomValidity("University can only contain letters, digits and spaces and can't start or end with space.");
    }
}

function postTitleCheck(){
    let field = document.getElementById('post_title');
    let regex = /^[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]+(?:[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@ ]*[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@])?$/i;
    if(regex.test(field.value)){
        field.setCustomValidity("");
    }
    else{
        field.setCustomValidity("Post title contains invalid characters or a space at the start or end.");
    }
}

function postTextCheck(){
    let field = document.getElementById('post_text');
    let regex = /^[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]+(?:[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@ ]*[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@])?$/i;
    if(regex.test(field.value)){
        field.setCustomValidity("");
    }
    else{
        field.setCustomValidity("Post text contains invalid characters or a space at the start or end.");
    }
}

function commentCheck(){
    let field = document.querySelector('article.post div[style="display: block;"] div.post_comment_form textarea');
    let regex = /^[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]+(?:[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@ ]*[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@])?$/i;
    if(regex.test(field.value)){
        field.setCustomValidity("");
    }
    else{
        field.setCustomValidity("Comment contains invalid characters or a space at the start or end.");
    }
}

function informationCheck(){
    let field = document.getElementById('information');
    let regex = /^[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]+(?:[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@ ]*[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@])?$/i;
    if(regex.test(field.value)){
        field.setCustomValidity("");
    }
    else{
        field.setCustomValidity("Information contains invalid characters or a space at the start or end.");
    }
}

function personalInfoCheck(){
    let field = document.getElementById('personal_info');
    let regex = /^[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]+(?:[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@ ]*[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@])?$/i;
    if(regex.test(field.value)){
        field.setCustomValidity("");
    }
    else{
        field.setCustomValidity("Personal Information contains invalid characters or a space at the start or end.");
    }
}

function reportTitleCheck(){
    let field = document.getElementById('report_title');
    let regex = /^[a-z0-9áàãâéêíóõôú]+(?:[a-z0-9áàãâéêíóõôú ]*[a-z0-9áàãâéêíóõôú])?$/i;
    if(regex.test(field.value)){
        field.setCustomValidity("");
    }
    else{
        field.setCustomValidity("Title contains invalid characters or a space at the start or end.");
    }
}

function reportDescriptionCheck(){
    let field = document.getElementById('report_description');
    let regex = /^[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@]+(?:[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@ ]*[a-z0-9áàãâéêíóõôú\[\]\(\)<>\-_!?\.',;:@])?$/i;
    if(regex.test(field.value)){
        field.setCustomValidity("");
    }
    else{
        field.setCustomValidity("Description contains invalid characters or a space at the start or end.");
    }
}

addEventListeners();