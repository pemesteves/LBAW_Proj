function addEventListeners() {

    let postLikers = document.querySelectorAll('article.post button.upvote');
    [].forEach.call(postLikers, function(liker) {
      liker.addEventListener('click', sendLikePostRequest);
    });

    let postDislikers = document.querySelectorAll('article.post button.downvote');
    [].forEach.call(postDislikers, function(disliker) {
      disliker.addEventListener('click', sendDislikePostRequest);
    });


    let commentDelleters = document.querySelectorAll('.comment_container div.options_menu .comment_delete');
    [].forEach.call(commentDelleters, function(deleter){
      deleter.addEventListener('click', sendDeleteCommentRequest);
    });

    let commentEditTransformers = document.querySelectorAll('.comment_container div.options_menu .comment_edit');
    [].forEach.call(commentEditTransformers, function(editers){
      editers.addEventListener('click', setCommentEditBox);
    });

    let commentReporters = document.querySelectorAll('article.post button.comment_report');
    [].forEach.call(commentReporters, function(reporter) {
      reporter.addEventListener('click', openReportCommentModal);
    });

    let postCreator = document.querySelector('form#post_form');
    if(postCreator != null)
      postCreator.addEventListener('submit', sendCreatePostRequest);

    let postDeleters = document.querySelectorAll('article.post button.delete');
    [].forEach.call(postDeleters, function(deleter) {
      deleter.addEventListener('click', sendDeletePostRequest);
    });

    let commentCreators = document.querySelectorAll('div.post_container form');
    [].forEach.call(commentCreators, function(creator){
      creator.addEventListener('submit', sendCreateCommentRequest);
    });

    let messageCreators = document.querySelectorAll('article.chat form#newmessage');
    [].forEach.call(messageCreators, function(creator){
    creator.addEventListener('submit', sendCreateMessageRequest);
    });

    let postReporters = document.querySelectorAll('article.post button.report');
    [].forEach.call(postReporters, function(reporter) {
      reporter.addEventListener('click', openReportPostModal);
    });


}


function sendLikePostRequest(event) {
    let id = this.closest('article').getAttribute('data-id');
    sendAjaxRequest('put', '/api/posts/' + id +"/like/1", null, postLikeHandler);
    event.preventDefault();
}
function sendDislikePostRequest(event) {
    let id = this.closest('article').getAttribute('data-id');
    sendAjaxRequest('put', '/api/posts/' + id +"/like/0", null, postLikeHandler);
    event.preventDefault();
}

function postLikeHandler() {
    if (this.status != 200) window.location = '/';
    let like = JSON.parse(this.responseText);
    let element = document.querySelector('article.post[data-id="'+ like.post_id + '"] .votes');
    let likes = element.querySelector('.post_like');
    let dislikes = element.querySelector('.post_dislike');
    likes.textContent=parseInt(likes.textContent)+like.upvotes;
    dislikes.textContent=parseInt(dislikes.textContent)+like.downvotes;
    element = document.querySelector('article.post[data-id="'+ like.post_id + '"] .post_votes');
    likes = element.querySelector('.post_like');
    dislikes = element.querySelector('.post_dislike');
    likes.textContent= String.fromCharCode(160)+ (parseInt(likes.textContent)+like.upvotes) + String.fromCharCode(160);
    dislikes.textContent= String.fromCharCode(160)+ (parseInt(dislikes.textContent)+like.downvotes) + String.fromCharCode(160);
  }



function sendDeleteCommentRequest(event) {
  let id = this.closest('div.comment_container').getAttribute('data-id');

  sendAjaxRequest('delete', '/api/comments/' + id, null, commentDeletedHandler);
}
function commentDeletedHandler() {
  if (this.status != 200) window.location = '/';
  let comment = JSON.parse(this.responseText);
  let element = document.querySelector('div.comment_container[data-id="'+ comment.comment_id + '"]');
  let count = document.querySelector("#post_" + comment.post_id + " .comments_count");
  count.innerHTML = (parseInt(count.textContent)-1) + " comments";

  element.remove();
}

function openReportCommentModal(event){
  let id = this.closest('.comment_container').getAttribute('data-id');
  $('#reportModal').modal('show')
  let modal = document.querySelector('#reportModal');
  modal.querySelector('#reportModalLabel').innerHTML = "Report comment"
  modal.querySelector("#report_id").value = id;
  modal.querySelector(".sendReport").removeEventListener('click' , sendReportPostRequest);
  modal.querySelector(".sendReport").addEventListener('click' , sendReportCommentRequest);
}

function sendReportCommentRequest(event) {
  let modal = this.closest('#reportModal');
  let id = modal.querySelector("#report_id").value;
  modal.querySelector("#report_id").value = ""
  let title = modal.querySelector("#report_title").value;
  modal.querySelector("#report_title").value = "";
  let description = modal.querySelector("#report_description").value;
  modal.querySelector("#report_description").value = "";
  $('#reportModal').modal('hide')
  sendAjaxRequest('put', '/api/comments/' + id + '/report', {'title' : title, 'description' : description}, commentReportedHandler);
}

function commentReportedHandler() {
  if (this.status !== 201 && this.status !== 200) {
    window.location = '/';
    return;
  }
  let post = JSON.parse(this.responseText);

  addFeedback("Comment reported sucessfully");

}


function setCommentEditBox(event){
  let element = this.closest('div.comment_container');
  let previous = element.querySelector('p').textContent;
  let id = element.getAttribute("data-id");
  element.innerHTML = `
    <div class="modal-body comment_edit_container" style="overflow-y: auto;" data-id = ${id}>
      <div class="container" style="border-bottom:0;border-top:0;border-radius:0;height:100%;">
        <form class = "comment_edit">                    
          <div class="row post_comment_form" >
              <div class="col-2">
                  <img src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" class="mx-auto d-block" alt="..." style="border-radius:50%; max-width:2rem; ">
              </div>
              <div class="col-9 post_comment_form_text">
                  <textarea name="body" class="form-control" required placeholder="Comment..." rows="1">${previous}</textarea>
              </div>
              <div class="col-1" style="padding: 0">
                  <button class="comment_edit" type="submit" style="padding: 0; max-height: 100%; height: 100%; max-width: 100%; width: 100%; background-color: white; border: 0;"><span class="fa fa-caret-right" style="float: left; font-size: 1.5em;margin-left: 0.75em;"></span></button>
              </div>
          </div>
        </form>
      </div>
    </div>`;
  let editer = element.querySelector("form.comment_edit");
  editer.addEventListener('submit' , sendEditCommentRequest);
}

function sendEditCommentRequest(event){
  let body = this.querySelector('textarea').value;

  //let post_id = this.closest('article').getAttribute('data-id');
  let comment_id = this.closest('div.comment_edit_container').getAttribute('data-id');

  if(body != '')
    sendAjaxRequest('put', '/api/comments/'+comment_id+'/edit', {'body': body}, commentUpdateHandler);

  event.preventDefault();
  return false;
}

function commentUpdateHandler(){
  if (this.status != 200 && this.status != 201){
    window.location = '/';
    return;
  }

  let comment = JSON.parse(this.responseText);

  let old_comment_form = document.querySelector('div.comment_edit_container[data-id="'+ comment.comment_id + '"]');
  old_comment_form.remove();

  // Create the new comment
  let new_comment = createComment(comment);

  // Reset the new comment input
  let toSelect = document.querySelector('article.post[data-id="'+ comment.post_id + '"]');
  let form = toSelect.querySelector('div.post_container form');
  form.querySelector('textarea').value="";

  // Insert the new comment
  //form.parentElement.insertBefore(new_comment, form.nextSibling);
  document.querySelector("div.comments").prepend(new_comment);
}

function sendDeletePostRequest(event) {
  let id = this.closest('article').getAttribute('data-id');

  sendAjaxRequest('delete', '/api/posts/' + id, null, postDeletedHandler, postDeleteErrorHandler);
}



function openReportPostModal(event){
  let id = this.closest('article').getAttribute('data-id');
  $('#reportModal').modal('show')
  let modal = document.querySelector('#reportModal');
  modal.querySelector('#reportModalLabel').innerHTML = "Report post"
  modal.querySelector("#report_id").value = id;
  modal.querySelector(".sendReport").removeEventListener('click' , sendReportCommentRequest);
  modal.querySelector(".sendReport").removeEventListener('click' , sendReportEventRequest);
  modal.querySelector(".sendReport").removeEventListener('click' , sendReportGroupRequest);
  modal.querySelector(".sendReport").addEventListener('click' , sendReportPostRequest);
}


function sendReportPostRequest(event) {
  let modal = this.closest('#reportModal');
  let id = modal.querySelector("#report_id").value;
  modal.querySelector("#report_id").value = ""
  let title = modal.querySelector("#report_title").value;
  modal.querySelector("#report_title").value = "";
  let description = modal.querySelector("#report_description").value;
  modal.querySelector("#report_description").value = "";
  $('#reportModal').modal('hide')
  sendAjaxRequest('put', '/api/posts/' + id + '/report', {'title' : title, 'description' : description}, postReportedHandler, postReportErrorHandler);
}


function sendCreatePostRequest(event){
  //TODO Add files
  let title = this.querySelector('input[name=title]').value;
  let body = this.querySelector('textarea').value;

  let resource = "";

  let pathParts = window.location.pathname.split('/');
  if(pathParts[1] == "feed")
    resource = '/api/posts/';
  else if(pathParts[1] == "groups"){
    resource = '/api/groups/' + pathParts[2] + '/posts/';
  }else if(pathParts[1] == "events"){
    resource = '/api/events/' + pathParts[2] + '/posts/';
  }else
    resource = '/api/posts/';

  if(title != '' && body != '')
    sendAjaxRequest('put', resource, {title: title, body: body}, postAddedHandler, postAddErrorHandler);
  
  event.preventDefault();
  return false;
}

function sendCreateCommentRequest(event){
  let body = this.querySelector('textarea').value;

  let id = this.closest('article').getAttribute('data-id');

  if(body != '')
    sendAjaxRequest('put', '/api/posts/'+id+'/comment', {body: body}, commentAddedHandler, commentAddErrorHandler);

  event.preventDefault();
  return false;
}

function sendCreateMessageRequest(event){
  
  let body = this.querySelector('textarea').value;

  let id = this.closest('article.chat').getAttribute('data-id');

  if(body != '') {
    sendAjaxRequest('put', '/api/chats/'+id+'/message', {body: body}, messageAddedHandler);
  }
  else {
  }

  event.preventDefault();
  return false;
}

function postDeletedHandler() {
  if (this.status != 200) {
    addErrorFeedback("Failed to delete post.");
    return;
  }
  let post = JSON.parse(this.responseText);
  let element = document.querySelector('article.post[data-id="'+ post.post_id + '"]');
  //let parentElement = element.parentElement;
  $('#popup-'+post.post_id).modal('hide');
  element.remove();

  addFeedback("Post deleted successfully");

}



function postDeleteErrorHandler() {
  addErrorFeedback("Failed to delete post.");
}

function postReportedHandler() {
  if (this.status !== 201 && this.status !== 200) {
    addErrorFeedback("Failed to report post.");
    return;
  }
  let post = JSON.parse(this.responseText);
  //$('#popup-'+post.post_id).modal('hide');

  addFeedback("Post reported sucessfully.");

}

function postReportErrorHandler() {
  addErrorFeedback("Failed to report post.");
}

function postAddedHandler() {
  if (this.status !== 201 && this.status !== 200) {
    addErrorFeedback("Failed to add post.");
    return;
  }
  
  //let post = JSON.parse(this.responseText);

  // Create the new post
  //let new_post = createPost(post);
  let new_post = this.responseText;

  // Reset the new post input
  let form = document.querySelector('form#post_form');
  form.querySelector('[type=text]').value="";
  form.querySelector('textarea').value="";

  // Insert the new post
  //form.parentElement.insertBefore(new_post, form.nextSibling);
  form.insertAdjacentHTML('afterend',new_post);
  new_post = document.querySelectorAll('.post')[0];
  let postDeleter = new_post.querySelector('button.delete');
  postDeleter.addEventListener('click', sendDeletePostRequest);
  let postLikers = new_post.querySelectorAll('article.post button.upvote');
  [].forEach.call(postLikers, function(liker) {
    liker.addEventListener('click', sendLikePostRequest);
  });

  let postDislikers = new_post.querySelectorAll('article.post button.downvote');
  [].forEach.call(postDislikers, function(disliker) {
    disliker.addEventListener('click', sendDislikePostRequest);
  });

  new_post.querySelector('div.post_container form').addEventListener('submit', sendCreateCommentRequest);

  let script = new_post.getElementsByTagName('script')[0];
  eval(script.innerHTML);

  addFeedback("Post added successfully.")
}

function postAddErrorHandler() {
  addErrorFeedback("Failed to add post.");
}

function commentAddedHandler(){
  if (this.status != 200 && this.status != 201){
    addErrorFeedback("Failed to add comment.");
    return;
  }

  let comment = JSON.parse(this.responseText);

  // Create the new comment
  let new_comment = createComment(comment);

  // Reset the new comment input
  let toSelect = document.querySelector('article.post[data-id="'+ comment.post_id + '"]');
  let form = toSelect.querySelector('div.post_container form');
  form.querySelector('textarea').value="";

  // Insert the new comment
  //form.parentElement.insertBefore(new_comment, form.nextSibling);

  addFeedback("Comment added successfully.")
}

function commentAddErrorHandler() {
  addErrorFeedback("Failed to add comment.");
}

function messageAddedHandler(){
  if (this.status != 200 && this.status != 201){
    window.location = '/';
    return;
  }
  let message = JSON.parse(this.responseText);

  // Create the new message
  let new_message = createMessage(message);

  // Reset the new message input
  let toSelect = document.querySelector('article.chat[data-id="'+ message.chat_id + '"]');
  let form = toSelect.querySelector('div.chat_message_input');
  form.querySelector('textarea').value="";
}

function createPost(post){
  const date = new Date(post.date);
  let day;
  if(date.getDate() < 10){
    day = '0' + date.getDate();
  } else{
    day = date.getDate();
  }
  let month;
  if(date.getMonth()+1 < 10){
    month = '0' + (date.getMonth()+1);
  } else{
    month = date.getMonth()+1;
  }
  const year = date.getFullYear();

  let hour;
  if(date.getHours() < 10){
    hour = '0' + date.getHours();
  }else{
    hour = date.getHours();
  }

  let minutes;
  if(date.getMinutes() < 10){
    minutes = '0' + date.getMinutes();
  }else{
    minutes = date.getMinutes();
  }

  let new_post = document.createElement('article');
  new_post.classList.add('post'); 
  new_post.setAttribute('data-id', post.post_id);
  new_post.innerHTML = `
  <div class="modal fade" id="popup-${post.post_id}" tabindex="-1" role="dialog" 
        aria-labelledby="postModal-${post.post_id}" aria-hidden="true">
    <div class="modal-dialog" role="document" style="overflow: initial; max-width: 90%; width: 90%; max-height: 90%; height: 90%">
        <div class="modal-content" style="height: 100%;">
            <div class="modal-header post_header" >
                <div class="container" style="border-bottom:0;border-radius:0;max-width: 90%;">
                    <div class="row">
                        <div class="col-sm-2">
                            <img src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" class="mx-auto d-block" alt="..." style="border-radius:50%; max-width:7rem; " onclick="window.location.href='./users/${post.author_id}'"/>
                        </div>
                        <div class="col-sm-10">
                            <div class="row">
                                <div class="col-sm-9" style="background-color: transparent;">
                                    <div class="row" style="background-color: transparent;">
                                        <h2 class="list-group-item" style="background-color: transparent; border:none;padding-top:0.2rem;padding-bottom:0.2rem">${post.regular_user.user.name}</h2>
                                    </div>
                                    <div class="row" style="background-color: transparent;">
                                        <h3 class="list-group-item" style="background-color: transparent; border:none;padding-top:0.2rem;padding-bottom:0.2rem">${post.regular_user.university}</h3>
                                    </div>
                                </div>
                                <div class="col-sm-3" style="padding-top:0.2rem;padding-bottom:0.2rem; text-align: right; font-size: 1.25em;">
                                    <p class="card-text" style="margin-bottom:0rem"> ${day}-${month}-${year}</p>
                                    <p class="card-text">${hour}:${minutes}</p>
                                </div>
                            </div>
                            <div class="row justify-content-end" style="font-size: 1.2em;">
                                <span class="fa fa-thumbs-up post_like">&nbsp;0&nbsp;</span>
                                <span class="fa fa-thumbs-down post_dislike">&nbsp;0&nbsp;</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                  <button type="button" data-dismiss="modal" style="font-size: 150%; margin-right: 0; padding-right: 0; width: 100%; background-color: white; border: 0;"><span class="fa fa-times"></span></button>
                  <div class="btn-group dropleft" style="margin-right: 0; padding-right: 0; width: 100%">
                      <button type="button" data-toggle="dropdown" style="font-size: 150%; margin-right: 0; padding-right: 0; width: 100%; background-color: white; border: 0;"> 
                      <span class="fa fa-ellipsis-v" ></span></button>
                      <div class="dropdown-menu options_menu" style="min-width:5rem">
                          <ul class="list-group">
                              <li class="list-group-item options_entry" style="text-align: left;">
                                  <button style=" margin-left:auto; margin-right:auto; background-color: white; border: 0;">Edit</button>
                              </li>
                              <li class="list-group-item options_entry" style="text-align: left;">
                                  <button class='delete' style=" background-color: white; border: 0;" > 
                                      Delete
                                  </button>
                              </li>
                          </ul>
                      </div>
                  </div>
                </div>
            </div>
            <div class="modal-body post_container" style="overflow-y: auto;">
                <div class="container" style="border-bottom:0;border-top:0;border-radius:0;height:100%;">
                    <div class="row">
                        <h2>${post.title}</h2>  
                    </div>
                    <div class="row post_content">
                        <p>${post.body}</p>
                    </div>
                    <form method="post">
                        <div class="row post_comment_form" >
                            <div class="col-2">
                                <img src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" class="mx-auto d-block" alt="..." style="border-radius:50%; max-width:2rem; ">
                            </div>
                            <div class="col-9 post_comment_form_text">
                                <textarea class="form-control" required placeholder="Comment..." rows="1"></textarea>
                            </div>
                            <div class="col-1" style="padding: 0">
                                <button type="submit" style="padding: 0; max-height: 100%; height: 100%; max-width: 100%; width: 100%; background-color: white; border: 0;"><span class="fa fa-caret-right" style="float: left; font-size: 1.5em;margin-left: 0.75em;"></span></button>
                            </div>
                        </div>
                    </form>
                    <div style="">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
  <div class="card mb-3" style="max-width:70%;margin:5% 15%">
        <button type="button" id="postModal-${post.post_id}" class="btn btn-primary" data-toggle="modal" data-target="#popup-${post.post_id}" style="text-align:left;background: none; color: inherit; border: none; padding: 0; font: inherit; cursor: pointer; outline: inherit;"> 
            <div class="row no-gutters">
                <div class="col-sm">
                    <div class="card text-center" style="border-bottom:none;border-top:none;border-radius:0;height:100%;">
                        <img src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" class="card-img-top mx-auto d-block" alt="..." style="border-radius:50%; max-width:5rem; padding-top:0.8rem">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item" style="border:none;padding-top:0.2rem;padding-bottom:0.2rem">${post.regular_user.user.name}
                            </li>
                            <li class="list-group-item" style="border:none;padding-top:0.2rem;padding-bottom:0.2rem">${post.regular_user.university}</li>
                            <li class="list-group-item" style="border:none;padding-top:0.2rem;padding-bottom:0.2rem">4 friends
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-8" style="flex-grow:1; max-width:100%; text-align: left;">
                    <div class="card" style="height: 100%; margin-bottom: 0;">
                        <div class="card-body" style="margin-bottom: 0;padding-bottom: 0;">
                            <h3 class="card-title">${post.title}</h3>
                            <p class="card-text">
                            ${post.body}
                            </p>
                            <p class="card-text" style="margin-bottom:0rem; float: right;"><small class="text-muted" style="margin-bottom:0rem">${day}-${month}-${year}</small>, <small class="text-muted" style="margin-bottom:0.2rem">${hour}:${minutes}</small></p>
                        </div>
                        <div class="card-footer" style="border-left:none;border-right:none;border-bottom:none">
                            <span class="comment"> 0 comments </span>
                            <div style="float: right;">
                                <span class="fa fa-thumbs-up post_like">&nbsp;0&nbsp;</span>
                                <span class="fa fa-thumbs-down post_dislike">&nbsp;0&nbsp;</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </button>
    </div>`;



    

  return new_post;
}

function createComment(comment){
  let new_comment = document.createElement('div');
  new_comment.classList.add('row', 'comment_container', 'comment_no_padding'); 
  new_comment.setAttribute('data-id',comment.comment_id);
  new_comment.innerHTML = `
    <div class="col-2 comment_user_info" >
        <div class="row">   
            <img src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" class="mx-auto d-block" alt="..." style="border-radius:50%; max-width:2rem; "  onclick="window.location.href='./users/${comment.user.user_id}'">
        </div>
        <div class="row">
            <h4 style="font-size: 1em; margin: 0 auto;">${comment.user.user.name}</h4>
        </div>
    </div>
    <div class="col-9 comment_text">
        <p>${comment.body}</p>
    </div>
    <div>
        <div class="btn-group dropright" style="margin-right: 0; padding-right: 0; width: 100%">
            <button type="button" data-toggle="dropdown" style="font-size: 150%; margin-right: 0; padding-right: 0; width: 100%; background-color: white; border: 0;"> 
            <span class="fa fa-ellipsis-v" ></span></button>
            <div class="dropdown-menu options_menu" style="min-width:5rem">
                <ul class="list-group">
                    <li class="list-group-item options_entry" style="text-align: left;">
                        <button class='comment_edit' style=" margin-left:auto; margin-right:auto; background-color: white; border: 0;">
                            Edit
                        </button>
                    </li>
                    <li class="list-group-item options_entry" style="text-align: left;">
                        <button class='comment_delete' style=" background-color: white; border: 0;" > 
                            Delete
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </div>`;

    let commentDelleters = new_comment.querySelector('.comment_container div.options_menu .comment_delete');
    commentDelleters.addEventListener('click', sendDeleteCommentRequest);


    let commentEditTransformers = new_comment.querySelector('.comment_container div.options_menu .comment_edit');
    commentEditTransformers.addEventListener('click', setCommentEditBox);
  return new_comment;
}







addEventListeners();