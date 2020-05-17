function addEventListeners() {

    let requestAccepters = document.querySelectorAll('div.request button.accept');
    [].forEach.call(requestAccepters, function(accepter) {
      accepter.addEventListener('click', sendAcceptRequest);
    });

    let requestDecliners = document.querySelectorAll('div.request button.decline');
    [].forEach.call(requestDecliners, function(decliner) {
      decliner.addEventListener('click', sendDeclineRequest);
    });


}

function sendAcceptRequest(event) {
    let id = this.closest('div.request').getAttribute('data-id');
    sendAjaxRequest('put', '/api/orgApproval/' + id + '/accept', null, requestStatusHandler);
    event.preventDefault();
}
function sendDeclineRequest(event) {
    let id = this.closest('div.request').getAttribute('data-id');
    sendAjaxRequest('put', '/api/orgApproval/' + id + '/decline', null, requestStatusHandler);
    event.preventDefault();
}


function requestStatusHandler(){
    if (this.status != 200) {
      //window.location = '/';
      addErrorFeedback("Request processing failed.");
      return;
      }
    let response = JSON.parse(this.responseText);
    let element = document.querySelector('div.request[data-id="'+ response.id + '"]');
    element.parentElement.remove();

    addFeedback("Request processed successfully.");
}

function requestFailedHandler(){
  addErrorFeedback("Request processing failed.");
}

addEventListeners();