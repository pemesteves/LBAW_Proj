function addEventListeners() {

    let reportAccepters = document.querySelectorAll('div.report button.accept');
    [].forEach.call(reportAccepters, function(accepter) {
      accepter.addEventListener('click', sendAcceptReportRequest);
    });

    let reportDecliners = document.querySelectorAll('div.report button.decline');
    [].forEach.call(reportDecliners, function(decliner) {
      decliner.addEventListener('click', sendDeclineReportRequest);
    });

    let requestAccepters = document.querySelectorAll('div.request button.accept');
    [].forEach.call(requestAccepters, function(accepter) {
      accepter.addEventListener('click', sendAcceptRequest);
    });

    let requestDecliners = document.querySelectorAll('div.request button.decline');
    [].forEach.call(requestDecliners, function(decliner) {
      decliner.addEventListener('click', sendDeclineRequest);
    });

    let tabSelectors = document.querySelectorAll('.tab_selector');
    [].forEach.call(tabSelectors, function(selector) {
      selector.addEventListener('click', changeTab);
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

function sendAcceptReportRequest(event) {
    let id = this.closest('div.report').getAttribute('data-id');
    sendAjaxRequest('put', '/api/reports/' + id + '/accept', null, reportStatusHandler);
    event.preventDefault();
}
function sendDeclineReportRequest(event) {
    console.log("we le le");
    let id = this.closest('div.report').getAttribute('data-id');
    sendAjaxRequest('put', '/api/reports/' + id + '/decline', null, reportStatusHandler);
    event.preventDefault();
}


function reportStatusHandler(){
    if (this.status != 200) {
      //window.location = '/';
      addErrorFeedback("Report processing failed." + this.responseText);
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



function changeTab(){
  newTabInfo = this.getAttribute('data-id');
  document.querySelector('.tab_selected').classList.remove('tab_selected')
  this.classList.add('tab_selected')
  newTab = document.querySelector('#' + newTabInfo);
  oldTab = document.querySelector('div.selected');
  oldTab.classList.remove('selected');
  oldTab.classList.add('not_selected');
  newTab.classList.add('selected');
  newTab.classList.remove('not_selected');
  this.preventDefault();
}








addEventListeners();