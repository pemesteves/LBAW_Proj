function addEventListeners() {

    let resetEmail = document.querySelector('form#reset_form');
    if(resetEmail != null)
        resetEmail.addEventListener('submit', sendResetEmailRequest);

}


function sendResetEmailRequest(){
    let email = this.querySelector('input[name=email]').value;

    if(email != '')
        sendAjaxRequest('post', 'api/resetPass/email', {'email': email}, resetEmailHandler);
  
    event.preventDefault();
    return false;
}

function sendCodeRequest(){
    let email = this.querySelector('input[name=email]').value;
    let code = this.querySelector('input[name=code]').value;

    if(email != '' && code != '')
        sendAjaxRequest('post', 'api/resetPass/code', {'email': email,'code':code}, checkCodeHandler);
  
    event.preventDefault();
    return false;
}


function resetEmailHandler(){
    if (this.status !== 201 && this.status !== 200) {
        window.location = '/';
        return;
    }
    let form = document.querySelector('form#reset_form');
    let resetinfo = JSON.parse(this.responseText);
    let error = form.querySelector('#error_message');
    if(resetinfo.success == 0){
        error.innerHTML = resetinfo.error;
        return;
    }
    error.innerHTML="";
    let email = form.querySelector('input[name=email]');
    email.setAttribute("disabled",true);
    let code = form.querySelector('input[name=code]');
    code.style.display = 'block';
    code.setAttribute("required",true);
    form.removeEventListener('submit',sendResetEmailRequest);
    form.addEventListener('submit',sendCodeRequest);
}

function checkCodeHandler(){
    if (this.status !== 201 && this.status !== 200) {
        window.location = '/';
        return;
    }
    let form = document.querySelector('form#reset_form');
    let resetinfo = JSON.parse(this.responseText);
    let error = form.querySelector('#error_message');
    if(resetinfo.success == 0){
        error.innerHTML = resetinfo.error;
        return;
    }
    error.innerHTML="";

    let email = form.querySelector('input[name=email]');
    email.removeAttribute("disabled");
    email.style.display = 'none';
    let code = form.querySelector('input[name=code]');
    code.style.display = 'none';
    form.querySelector('input[name=pass]').style.display = 'block';
    form.querySelector('input[name=pass_confirmation]').style.display = 'block';
    form.removeEventListener('submit',sendCodeRequest);
    form.addEventListener('submit',function(event){
        let pass1 = form.querySelector('input[name=pass]').value;
        let pass2 = form.querySelector('input[name=pass_confirmation]').value;
        if(pass1 != pass2){
            error.innerHTML = "Passwords do not match";
            event.preventDefault();
            return false;
        }
    });
}










addEventListeners();