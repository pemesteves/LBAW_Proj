function addEventListeners() {

    let friendAdders = document.querySelectorAll('button.add_friend');
    [].forEach.call(friendAdders, function(adder) {
        adder.addEventListener('click', requestFriendRequest);
    });

    let friendCancelers = document.querySelectorAll('button.cancel_friend');
    [].forEach.call(friendCancelers, function(canceler) {
        canceler.addEventListener('click', calcelFriendRequest);
    });

    let friendAccepters = document.querySelectorAll('button.accept_friend');
    [].forEach.call(friendAccepters, function(accepter) {
        accepter.addEventListener('click', acceptFriendRequest);
    });

    let friendDecliners = document.querySelectorAll('button.decline_friend');
    [].forEach.call(friendDecliners, function(decliner) {
        decliner.addEventListener('click', declineFriendRequest);
    });

    let friendRemovers = document.querySelectorAll('button.remove_friend');
    [].forEach.call(friendRemovers, function(remover) {
        remover.addEventListener('click', removeFriendRequest);
    });

    let userReporter = document.querySelector('#profile_card button.report');
    if(userReporter) userReporter.addEventListener('click', openReportUserModal);

}

function openReportUserModal(event){
    let id = this.getAttribute('data-id');
    $('#reportModal').modal('show')
    let modal = document.querySelector('#reportModal');
    modal.querySelector('#reportModalLabel').innerHTML = "Report user"
    modal.querySelector("#report_id").value = id;
    modal.querySelector(".sendReport").addEventListener('click' , sendReportUserRequest);
}
function sendReportUserRequest(event) {
    let modal = this.closest('#reportModal');
    let id = modal.querySelector("#report_id").value;
    modal.querySelector("#report_id").value = ""
    let title = modal.querySelector("#report_title").value;
    modal.querySelector("#report_title").value = "";
    let description = modal.querySelector("#report_description").value;
    modal.querySelector("#report_description").value = "";
    $('#reportModal').modal('hide')
    sendAjaxRequest('put', '/api/users/' + id + '/report', {'title' : title, 'description' : description}, userReportedHandler);
}

function userReportedHandler() {
    if (this.status !== 201 && this.status !== 200) {
      window.location = '/';
      return;
    }
  
    addFeedback("User reported sucessfully");
  
  }

function requestFriendRequest(event){
    let id = this.closest('button').getAttribute('data-id');

    sendAjaxRequest('put', '/api/sendFriendRequest/' + id, null, requestFriendHandler);
  
    event.preventDefault();
    return false;
}

function calcelFriendRequest(event){
    let id = this.closest('button').getAttribute('data-id');

    sendAjaxRequest('put', '/api/cancelFriendRequest/' + id, null, cancelFriendHandler);
  
    event.preventDefault();
    return false;
}

function acceptFriendRequest(event){
    let id = this.closest('button').getAttribute('data-id');

    sendAjaxRequest('put', '/api/acceptFriendRequest/' + id, null, acceptFriendHandler);
  
    event.preventDefault();
    return false;
}

function declineFriendRequest(event){
    let id = this.closest('button').getAttribute('data-id');

    sendAjaxRequest('put', '/api/declineFriendRequest/' + id, null, declineFriendHandler);
  
    event.preventDefault();
    return false;
}

function removeFriendRequest(event){
    let id = this.closest('button').getAttribute('data-id');

    sendAjaxRequest('put', '/api/removeFriendRequest/' + id, null, removeFriendHandler);
  
    event.preventDefault();
    return false;
}


function requestFriendHandler(){
    if (this.status != 200 && this.status != 201){
        window.location = '/';
        return;
    }

    let status = JSON.parse(this.responseText);
    if(status.result == false)
        return false;

    let adder = document.querySelector('button.add_friend[data-id="'+ status.user_id + '"]');
    adder.classList.remove("add_friend");
    adder.classList.add("cancel_friend");
    /*adder.outerHTML=
    `<button type="button" class="btn btn-light cancel_friend" data-id='${status.user_id}' style="margin-left: auto; margin-right:4%;background-color: rgba(0,0,150,.03); ">
        Cancel Request
    </button>`;*/
    adder.innerHTML = "Cancel Request";
    adder.removeEventListener('click',requestFriendRequest);
    adder.addEventListener('click',calcelFriendRequest);
    return true;
}

function cancelFriendHandler(){
    if (this.status != 200 && this.status != 201){
        window.location = '/';
        return;
    }

    let status = JSON.parse(this.responseText);
    if(status.result == false)
        return false;

    let adder = document.querySelector('button.cancel_friend[data-id="'+ status.user_id + '"]');
    adder.classList.remove("cancel_friend");
    adder.classList.add("add_friend");/*
    adder.outerHTML=
    `<button type="button" class="btn btn-light add_friend" data-id='${status.user_id}' style="margin-left: auto; margin-right:4%;background-color: rgba(0,0,150,.03); ">
        Add Friend
    </button>`*/
    adder.innerHTML = "Add Friend";
    adder.removeEventListener('click',calcelFriendRequest);
    adder.addEventListener('click',requestFriendRequest);
    return true;
}

function acceptFriendHandler(){
    if (this.status != 200 && this.status != 201){
        window.location = '/';
        return;
    }
    let status = JSON.parse(this.responseText);
    if(status.result == false)
        return false;

    let adder = document.querySelector('button.accept_friend[data-id="'+ status.user_id + '"]');
    let decliner = document.querySelector('button.decline_friend[data-id="'+ status.user_id + '"]');
    decliner.remove();
    adder.outerHTML=
    `<button type="button" class="btn btn-light remove_friend" data-id='${status.user_id}' style="margin-left: auto; margin-right:4%;background-color: rgba(0,0,150,.03); ">
        Remove Friend
    </button>`
    document.querySelector('button.remove_friend[data-id="'+ status.user_id + '"]').addEventListener('click',removeFriendRequest);
    return true;
}

function declineFriendHandler(){
    if (this.status != 200 && this.status != 201){
        window.location = '/';
        return;
    }

    let status = JSON.parse(this.responseText);
    if(status.result == false)
        return false;

    let adder = document.querySelector('button.decline_friend[data-id="'+ status.user_id + '"]');
    let accepter = document.querySelector('button.accept_friend[data-id="'+ status.user_id + '"]');
    accepter.remove();
    adder.outerHTML=
    `<button type="button" class="btn btn-light add_friend" data-id='${status.user_id}' style="margin-left: auto; margin-right:4%;background-color: rgba(0,0,150,.03); ">
        Add Friend
    </button>`
    document.querySelector('button.add_friend[data-id="'+ status.user_id + '"]').addEventListener('click',requestFriendRequest);
    return true;
}

function removeFriendHandler(){
    if (this.status != 200 && this.status != 201){
        window.location = '/';
        return;
    }

    let status = JSON.parse(this.responseText);
    if(status.result == false)
        return false;

    let adder = document.querySelector('button.remove_friend[data-id="'+ status.user_id + '"]');
    adder.outerHTML=
    `<button type="button" class="btn btn-light add_friend" data-id='${status.user_id}' style="margin-left: auto; margin-right:4%;background-color: rgba(0,0,150,.03); ">
        Add Friend
    </button>`
    document.querySelector('button.add_friend[data-id="'+ status.user_id + '"]')    .addEventListener('click',requestFriendRequest);
    return true;
}


addEventListeners();