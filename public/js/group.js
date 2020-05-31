function addEventListeners() {
  
    let groupReporters = document.querySelectorAll('#group_menu_options button.report');
    [].forEach.call(groupReporters, function(reporter) {
      reporter.addEventListener('click', openReportGroupModal);
    });

    let memberCards = document.querySelectorAll('#memberPopup span.remove_button');
    [].forEach.call(memberCards, function(card) {
      card.addEventListener('click', sendRemoveMemberRequest);
    });
  
}

function openReportGroupModal(event){
    let id = this.getAttribute('data-id');
    $('#reportModal').modal('show')
    let modal = document.querySelector('#reportModal');
    modal.querySelector('#reportModalLabel').innerHTML = "Report group"
    modal.querySelector("#report_id").value = id;
    modal.querySelector(".sendReport").removeEventListener('click' , sendReportCommentRequest);
    modal.querySelector(".sendReport").removeEventListener('click' , sendReportEventRequest);
    modal.querySelector(".sendReport").removeEventListener('click' , sendReportPostRequest);
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

  function sendRemoveMemberRequest(event) {
    let group_id = this.closest('#feed_container').getAttribute('data-id');
    let user_id = this.closest('div.member_card').getAttribute('data-id');
  
    sendAjaxRequest('delete', '/api/groups/' + group_id + '/members/' + user_id, null, memberRemovedHandler);
  }
  function memberRemovedHandler() {
    if (this.status != 200 && this.status != 201) {
      //window.location = '/';
      addErrorFeedback("Member removal failed. Error " + this.status);
      return;
      }
    let member = JSON.parse(this.responseText);
    let element = document.querySelector('div.member_card[data-id="'+ member.user_id + '"]');
    let count = document.querySelector("button#memberButton p");
    count.innerHTML = (parseInt(count.textContent)-1) + " members";
  
    element.remove();

    addFeedback("Member removed successfully.");
  }








  addEventListeners();