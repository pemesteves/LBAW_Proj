function addEventListeners() {


    let orgRequesters = document.querySelectorAll('button.verify_org');
    [].forEach.call(orgRequesters, function(requester) {
      requester.addEventListener('click', sendOrgRequest);
    });

    let orgApplier = document.querySelectorAll('.apply_org');
    [].forEach.call(orgApplier, function(applier) {
      applier.addEventListener('click', sendApplyRequest);
    });

    let orgUserAccept = document.querySelectorAll('.accept_user_org');
    [].forEach.call(orgUserAccept, function(acceptor) {
      acceptor.addEventListener('click', sendAcceptRequest);
    });

    let orgUserReject = document.querySelectorAll('.reject_user_org');
    [].forEach.call(orgUserReject, function(rejector) {
      rejector.addEventListener('click', sendRejectRequest);
    });

}

  function sendOrgRequest(event) {
    let id = this.closest('button').getAttribute('data-id');
  
    sendAjaxRequest('put', '/api/users/' + id + '/orgVerify', null, orgRequestHandler, orgRequestErrorHandler);
  
    event.preventDefault();
    return false;
  }

  function sendApplyRequest(event) {
    let id = this.closest('button').getAttribute('data-id');
    sendAjaxRequest('put', '/api/users/' +id + '/apply', {id:id}, orgApplyHandler, orgApplyErrorHandler);
    event.preventDefault();
  }

  function sendAcceptRequest(event) {
    let id = this.getAttribute('data-id');
    let org_reg_id = document.getElementById('manage_applications').getAttribute('data-id');
    sendAjaxRequest('put', '/api/users/' +  org_reg_id + '/accept', {id:id}, orgAcceptorHandler, orgAcceptorErrorHandler);
    event.preventDefault();
  }

  function sendRejectRequest(event) {
    let id = this.getAttribute('data-id');
    let org_reg_id = document.getElementById('manage_applications').getAttribute('data-id');
    sendAjaxRequest('put', '/api/users/' +  org_reg_id + '/reject', {id:id}, orgRejectorHandler, orgRejectorErrorHandler);
    event.preventDefault();
  }

  

  function orgRequestHandler() {
    if (this.status != 200 && this.status != 201) {
      let x = JSON.parse(this.responseText);
      addErrorFeedback("Request processing failed.");
      return;
    }
    let x = JSON.parse(this.responseText);
  
    addFeedback("Request sent successfully");
  
  }

  function orgApplyHandler() {
    if (this.status != 200 && this.status != 201) {
      let x = this.responseText;
      addErrorFeedback("Request processing failed.");
      return;
    }

    let x = this.responseText;
    addFeedback("Request sent successfully");
  }

  function orgAcceptorHandler() {
    if (this.status != 200 && this.status != 201) {
      let response = this.responseText;
      addErrorFeedback("Request processing failed.");
      return;
    }

    let response = JSON.parse(this.responseText);
    addFeedback("Request sent successfully");
    let element = document.querySelectorAll('.member_applied[data-id="'+ response['user'].regular_user_id + '"]');
    element[0].remove();

    addToMembers(response['user'], response['image']);

  }

  function orgRejectorHandler() {
    if (this.status != 200 && this.status != 201) {
      let x = this.responseText;
      addErrorFeedback("Request processing failed.");
      return;
    }

    let x = JSON.parse(this.responseText);
    addFeedback("Request sent successfully");
    let element = document.querySelectorAll('.member_applied[data-id="'+ x + '"]');
    element[0].remove();

  }
  
  function orgRequestErrorHandler() {
  addErrorFeedback("Request processing failed.");
  }

  function orgApplyErrorHandler() {
    addErrorFeedback("Request processing failed.");
  }

  function orgAcceptorErrorHandler() {
    addErrorFeedback("Request processing failed.");
  }

  function orgRejectorErrorHandler() {
    addErrorFeedback("Request processing failed.");
  }


  function addToMembers(reg_user, image) {
    let new_member = document.createElement('div');
    new_member.classList.add('card', 'mb', 'member_card', 'member_applied'); 
    new_member.setAttribute('data-id', reg_user.regular_user_id);
    new_member.setAttribute('style', "margin-bottom:0px;border-radius:0px;");
    new_member.innerHTML = `
                        <div class="row no-gutters">
                            <div class="col-md" style="flex-grow:1; max-width:100%; text-align: left;">
                                <a href="../users/${reg_user.regular_user_id}" style="text-decoration: none; color:black">
                                    <div class="row no-gutters">
                                        <div class="col-sm">
                                            <div class="card text-center" style="border-right:none;border-bottom:none;border-top:none;border-radius:0;height:100%;">
                                                <img 
                                                src=${image}
                                                class="card-img-top mx-auto d-block" 
                                                alt="..." style="border-radius:50%; width:3rem;height:3rem; padding:0.1rem;padding-top:0.2rem">
                                            </div>
                                        </div>
                                        <div class="col-md-8" style="flex-grow:1; max-width:100%; text-align: left;">
                                            <div class="" style="margin-bottom: 0;padding-bottom: 0;">
                                                <p class="card-text small_post_body" style="margin-bottom:0;margin-left:0.2rem;display:inline-block;">
                                                  ${reg_user.user.name}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </a> 
                            </div>
                        </div>
    `

    document.querySelector('#all_org_members').appendChild(new_member);
    let count = document.querySelector("button#org_members_button p");
    count.innerHTML = (parseInt(count.textContent)+1) + " members";

  }


addEventListeners();