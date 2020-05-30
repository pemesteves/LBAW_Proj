function addEventListeners() {


    let orgRequesters = document.querySelectorAll('button.verify_org');
    [].forEach.call(orgRequesters, function(requester) {
      requester.addEventListener('click', sendOrgRequest);
    });

}

function sendOrgRequest(event) {
    let id = this.closest('button').getAttribute('data-id');
  
    sendAjaxRequest('put', '/api/users/' + id + '/orgVerify', null, orgRequestHandler, orgRequestErrorHandler);
  
    event.preventDefault();
    return false;
  }
  

  function orgRequestHandler() {
    if (this.status != 200 && this.status != 201) {
      console.log(this.responseText);
      let x = JSON.parse(this.responseText);
      addErrorFeedback("Request processing failed.");
      return;
    }
    let x = JSON.parse(this.responseText);
  
    addFeedback("Request sent successfully");
  
  }
  
  function orgRequestErrorHandler() {
  addErrorFeedback("Request processing failed.");
  }


addEventListeners();