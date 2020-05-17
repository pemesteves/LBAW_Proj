function addEventListeners() {

    let reportAccepters = document.querySelectorAll('div.report button.accept');
    [].forEach.call(reportAccepters, function(accepter) {
      accepter.addEventListener('click', sendAcceptReportRequest);
    });

    let reportDecliners = document.querySelectorAll('div.report button.decline');
    [].forEach.call(reportDecliners, function(decliner) {
      decliner.addEventListener('click', sendDeclineReportRequest);
    });


}

function sendAcceptReportRequest(event) {
    let id = this.closest('div.report').getAttribute('data-id');
    sendAjaxRequest('put', '/api/reports/' + id + '/accept', null, reportStatusHandler);
    event.preventDefault();
}
function sendDeclineReportRequest(event) {
    let id = this.closest('div.report').getAttribute('data-id');
    sendAjaxRequest('put', '/api/reports/' + id + '/decline', null, reportStatusHandler);
    event.preventDefault();
}


function reportStatusHandler(){
    if (this.status != 200) {
      //window.location = '/';
      addErrorFeedback("Report processing failed.");
      return;
      }
    let response = JSON.parse(this.responseText);
    let element = document.querySelector('div.report[data-id="'+ response.id + '"]');
    element.parentElement.remove();

    addFeedback("Report processed successfully.");
}

function reportFailedHandler(){
  addErrorFeedback("Report processing failed.");
}



















addEventListeners();