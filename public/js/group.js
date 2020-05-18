function addEventListeners() {
  
    let groupReporters = document.querySelectorAll('#group_menu_options button.report');
    [].forEach.call(groupReporters, function(reporter) {
      reporter.addEventListener('click', openReportGroupModal);
    });
  
}

function openReportGroupModal(event){
    let id = this.getAttribute('data-id');
    $('#reportModal').modal('show')
    let modal = document.querySelector('#reportModal');
    modal.querySelector('#reportModalLabel').innerHTML = "Report group"
    modal.querySelector("#report_id").value = id;
    modal.querySelector(".sendReport").addEventListener('click' , sendReportGroupRequest);
  }




  function sendReportGroupRequest(event) {
    let modal = this.closest('#reportModal');
    let id = modal.querySelector("#report_id").value;
    modal.querySelector("#report_id").value = ""
    let title = modal.querySelector("#report_title").value;
    modal.querySelector("#report_title").value = "";
    let description = modal.querySelector("#report_description").value;
    modal.querySelector("#report_description").value = "";
    $('#reportModal').modal('hide')
    sendAjaxRequest('put', '/api/groups/' + id + '/report', {'title' : title, 'description' : description}, groupReportedHandler, groupReportErrorHandler);
  }



  function groupReportedHandler() {
    if (this.status !== 201 && this.status !== 200) {
      addErrorFeedback("Failed to report group.");
      return;
    }
    let post = JSON.parse(this.responseText);
    //$('#popup-'+post.post_id).modal('hide');
  
    addFeedback("Group reported sucessfully");
  }

  function groupReportErrorHandler() {
    addErrorFeedback("Failed to report group.");
  }








  addEventListeners();