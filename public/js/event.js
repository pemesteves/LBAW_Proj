function addEventListeners() {
  
    let eventReporters = document.querySelectorAll('#event_menu_options button.report');
    [].forEach.call(eventReporters, function(reporter) {
      reporter.addEventListener('click', openReportEventModal);
    });
  
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
    sendAjaxRequest('put', '/api/events/' + id + '/report', {'title' : title, 'description' : description}, eventReportedHandler);
  }



  function eventReportedHandler() {
    if (this.status !== 201 && this.status !== 200) {
      window.location = '/';
      return;
    }
    let post = JSON.parse(this.responseText);
    //$('#popup-'+post.post_id).modal('hide');
  
    addFeedback("Event reported sucessfully");
  }








  addEventListeners();