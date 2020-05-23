function addEventListeners() {
  
    let eventReporters = document.querySelectorAll('#event_menu_options button.report');
    [].forEach.call(eventReporters, function(reporter) {
      reporter.addEventListener('click', openReportEventModal);
    });

    let eventInterest = document.querySelector('.show_interest');
    if(eventInterest) eventInterest.addEventListener('click', eventInsterestRequest);

    let eventDesinterest = document.querySelector('.remove_interest');
    if(eventDesinterest) eventDesinterest.addEventListener('click', eventDesinsterestRequest);

  
}


function eventDesinsterestRequest(event) {
  let id = this.getAttribute('data-id');
  sendAjaxRequest('put', '/api/events/' + id +"/desinterest", null, desinterestHandler);
  event.preventDefault();
}

function eventInsterestRequest(event) {
  let id = this.getAttribute('data-id');
  sendAjaxRequest('put', '/api/events/' + id +"/interest", null, interestHandler);
  event.preventDefault();
}

function desinterestHandler() {
  if (this.status != 200){window.location = '/';return}
  let button = document.querySelector('.remove_interest')
  button.classList.remove('remove_interest');
  button.classList.add('show_interest');
  button.innerHTML = "Show interest";
  button.removeEventListener('click',eventDesinsterestRequest);
  button.addEventListener('click',eventInsterestRequest);
}

function interestHandler() {
  if (this.status != 200){window.location = '/';return}
  let button = document.querySelector('.show_interest')
  button.classList.remove('show_interest');
  button.classList.add('remove_interest');
  button.innerHTML = "Remove interest";
  button.removeEventListener('click',eventInsterestRequest);
  button.addEventListener('click',eventDesinsterestRequest);
}


function openReportEventModal(event){
    let id = this.getAttribute('data-id');
    $('#reportModal').modal('show')
    let modal = document.querySelector('#reportModal');
    modal.querySelector('#reportModalLabel').innerHTML = "Report event"
    modal.querySelector("#report_id").value = id;
    modal.querySelector(".sendReport").addEventListener('click' , sendReportEventRequest);
  }




  function sendReportEventRequest(event) {
    let modal = this.closest('#reportModal');
    let id = modal.querySelector("#report_id").value;
    modal.querySelector("#report_id").value = ""
    let title = modal.querySelector("#report_title").value;
    modal.querySelector("#report_title").value = "";
    let description = modal.querySelector("#report_description").value;
    modal.querySelector("#report_description").value = "";
    $('#reportModal').modal('hide')
    sendAjaxRequest('put', '/api/events/' + id + '/report', {'title' : title, 'description' : description}, eventReportedHandler, eventReportErrorHandler);
  }



  function eventReportedHandler() {
    if (this.status !== 201 && this.status !== 200) {
      addErrorFeedback("Failed to report event.");
      return;
    }
    let post = JSON.parse(this.responseText);
    //$('#popup-'+post.post_id).modal('hide');
  
    addFeedback("Event reported sucessfully.");
  }

  function eventReportErrorHandler() {
    addErrorFeedback("Failed to report event.");
  }








  addEventListeners();