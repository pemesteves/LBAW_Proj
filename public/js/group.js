function addEventListeners() {
  
    let groupReporters = document.querySelectorAll('#group_menu_options button.report');
    [].forEach.call(groupReporters, function(reporter) {
      reporter.addEventListener('click', openReportGroupModal);
    });

    let memberCards = document.querySelectorAll('#memberPopup span.remove_button');
    [].forEach.call(memberCards, function(card) {
      card.addEventListener('click', sendRemoveMemberRequest);
    });
  
    let lookMemberName = document.querySelector('#new_member_name');
    if(lookMemberName) lookMemberName.addEventListener('input',sendLookNamesRequest);
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

  function sendLookNamesRequest(event) {
    let name = this.value;
    let pathParts = window.location.pathname.split('/');
    let groupId = pathParts[2];
    sendAjaxRequest('get', '/api/groups/'+groupId+'/friends?string='+name, null, showNewMembersHandler);
    //event.preventDefault();
  }

  function showNewMembersHandler(){
    if (this.status != 200 && this.status != 201){
      return;
    }
    let new_members = JSON.parse(this.responseText);
    let container = document.querySelector('#members_search');
    container.innerHTML = "";
    new_members.new_members.forEach(element => {
      container.innerHTML+=createSearchedMember(element);
    });
    if(new_members.new_members.length == 0){
      container.innerHTML = "No new users match that description";
    }
  
    adders = container.querySelectorAll('.add_member');
    [].forEach.call(adders, function(adder) {
      adder.addEventListener('click', sendAddMemberToGroupRequest);
    });
  
  }

  function sendAddMemberToGroupRequest(event){
    let pathParts = window.location.pathname.split('/');
    let groupId = pathParts[2];
    let userid = this.getAttribute('data-id');
  
    sendAjaxRequest('put', '/api/groups/' + groupId +"/"+userid, null, addedUserGroupHandler);
  
    event.preventDefault();
  }
  
  function addedUserGroupHandler() {
    if (this.status != 200) return;
    let res = JSON.parse(this.responseText);
    document.querySelector('#addToGroup_'+res.regular_user_id).remove();

    let new_member = document.createElement('div');
    document.querySelector("#memberPopup div.modal-content").prepend(new_member);
    newMember(res, new_member);

    let count = document.querySelector("button#memberButton p");
    count.innerHTML = (parseInt(count.textContent)+1) + " members";

    let memberCard = document.querySelector('span.remove_button');
    memberCard.addEventListener('click', sendRemoveMemberRequest);

    addFeedback("Member added successfully.");
  }

  function createSearchedMember(user){
    let new_searched =`
          <div class="card mb" style="margin-bottom:0px;border-radius:0px;margin-right:0.5rem" id='addToGroup_${user.userable_id}'>
            <a href="/users/${user.userable_id}" style="text-decoration: none; color:black">
                <div class="row no-gutters">
                    <div class="col-sm">
                        <div class="card text-center" style="border-right:none;border-bottom:none;border-top:none;border-radius:0;height:100%;">`;
                           if(!user.file_path){
                              new_searched+=`<img src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" class="card-img-top mx-auto d-block" 
                              alt="..." style="border-radius:50%; max-width:3rem; padding:0.1rem;padding-top:0.2rem">`;
                           }else{
                            new_searched+=`<img src="${user.file_path}" class="card-img-top mx-auto d-block" 
                            alt="..." style="border-radius:50%; max-width:3rem; padding:0.1rem;padding-top:0.2rem">`;
                           }
                            
                        
          new_searched+=`</div>
                    </div>
                    <div class="col-md-8" style="flex-grow:1; max-width:100%; text-align: left;">
                        <div class="" style="margin-bottom: 0;padding-bottom: 0;">
                            <p class="card-text small_post_body" style="margin-bottom:0;margin-left:0.2rem;display:inline-block;">
                                ${user.name}
                            </p>
                            <span class="btn btn-light add_member" data-id='${user.userable_id}' 
                                style="background-color: rgba(0,0,150,.03);float:right; margin-right:0.5rem;margin-top:0.2rem;font-size:0.9rem ">
                                Add member
                            </span>
                        </div>
                    </div>
                </div>
            </a> 
        </div>`
  
    
    return new_searched;
  
  }

  function newMember(member, card){
    card.outerHTML = `
    <div class="card mb member_card" style="margin-bottom:0px;border-radius:0px;" data-id="${member.regular_user_id}">
      <div class="row no-gutters">
          <div class="col-md" style="flex-grow:1; max-width:100%; text-align: left;">
              <a href="../users/${member.regular_user_id}" style="text-decoration: none; color:black">
                  <div class="row no-gutters">
                      <div class="col-sm">
                          <div class="card text-center" style="border-right:none;border-bottom:none;border-top:none;border-radius:0;height:100%;">
                              <img src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" class="card-img-top mx-auto d-block" 
                              alt="user_image" style="border-radius:50%; max-width:3rem; padding:0.1rem;padding-top:0.2rem">
                          </div>
                      </div>
                      <div class="col-md-8" style="flex-grow:1; max-width:100%; text-align: left;">
                          <div class="" style="margin-bottom: 0;padding-bottom: 0;">
                              <p class="card-text small_post_body" style="margin-bottom:0;margin-left:0.2rem;display:inline-block;">
                                  ${member.name}
                              </p>
                          </div>
                      </div>
                  </div>
              </a> 
          </div>
          <div class="col-sm" style="flex-grow:0; max-width:100%; text-align: left;">
              <span class="btn btn-light remove_button" data-id='{{$member->regular_user_id}}' 
                  style="background-color: rgba(0,0,150,.03);float:right; margin-right:0.5rem;margin-top:0.2rem;font-size:0.9rem ">
                  Remove
              </span>
          </div>
      </div>
    </div>
    `
  }



  addEventListeners();