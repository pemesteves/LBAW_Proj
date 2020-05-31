function addEventListeners() {
  

    let notification = document.querySelector('#notificationDrop');
    if(notification) notification.addEventListener('click',sendSeenNotificationsRequest);

  
}

function sendSeenNotificationsRequest(event) {
    sendAjaxRequest('put', '/api/users/notifications', null, seenNotificationsHandler);
    event.preventDefault();
}

function seenNotificationsHandler(){
    if (this.status != 200){
        return;
    }
    let count = document.querySelector('#notifications_count');
    count.style.display='none';
    count.textContent = "0";
}





  addEventListeners();