function addEventListeners() {

    let postLikers = document.querySelectorAll('article.post button.upvote');
    [].forEach.call(postLikers, function(liker) {
      liker.addEventListener('click', sendLikePostRequest);
    });

    let postDislikers = document.querySelectorAll('article.post button.downvote');
    [].forEach.call(postDislikers, function(disliker) {
      disliker.addEventListener('click', sendDislikePostRequest);
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

    let commentDelleters = document.querySelectorAll('.comment_container div.options_menu .comment_delete');
    [].forEach.call(commentDelleters, function(deleter){
      deleter.addEventListener('click', sendDeleteCommentRequest);
    });

    let commentEditTransformers = document.querySelectorAll('.comment_container div.options_menu .comment_edit');
    [].forEach.call(commentEditTransformers, function(editers){
      editers.addEventListener('click', setCommentEditBox);
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
    likes.textContent=parseInt(likes.textContent)+like.upvotes;
    dislikes.textContent=parseInt(dislikes.textContent)+like.downvotes;
}

function sendDeletePostRequest(event) {
  let id = this.closest('article').getAttribute('data-id');

  sendAjaxRequest('delete', '/api/posts/' + id, null, postDeletedHandler);
}

function sendDeleteCommentRequest(event) {
  let id = this.closest('div.comment_container').getAttribute('data-id');

  sendAjaxRequest('delete', '/api/comments/' + id, null, commentDeletedHandler);
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
    sendAjaxRequest('put', resource, {title: title, body: body}, postAddedHandler);
  
  event.preventDefault();
  return false;
}

function sendCreateCommentRequest(event){
  let body = this.querySelector('textarea').value;

  let id = this.closest('article').getAttribute('data-id');

  if(body != '')
    sendAjaxRequest('put', '/api/posts/'+id+'/comment', {body: body}, commentAddedHandler);

  event.preventDefault();
  return false;
}


function postDeletedHandler() {
  if (this.status != 200) window.location = '/';
  let post = JSON.parse(this.responseText);
  let element = document.querySelector('article.post[data-id="'+ post.post_id + '"]');
  //let parentElement = element.parentElement;
  $('#popup-'+post.post_id).modal('hide');
  element.remove();
}

function commentDeletedHandler() {
  if (this.status != 200) window.location = '/';
  let comment = JSON.parse(this.responseText);
  let element = document.querySelector('div.comment_container[data-id="'+ comment.comment_id + '"]');

  element.remove();
}



function postAddedHandler() {
  if (this.status !== 201 && this.status !== 200) {
    window.location = '/';
    return;
  }
  
  let post = JSON.parse(this.responseText);

  // Create the new post
  let new_post = createPost(post);

  // Reset the new post input
  let form = document.querySelector('form#post_form');
  form.querySelector('[type=text]').value="";
  form.querySelector('textarea').value="";

  // Insert the new post
  form.parentElement.insertBefore(new_post, form.nextSibling);

  let postDeleter = new_post.querySelector('button.delete');
  postDeleter.addEventListener('click', sendDeletePostRequest);

  let commentAdder = new_post.querySelector('div.post_container form');
  commentAdder.addEventListener('submit', sendCreateCommentRequest);

}

function commentAddedHandler(){
  if (this.status != 200 && this.status != 201){
    window.location = '/';
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
  document.querySelector("div.comments").prepend(new_comment);
}


/* TODO Convert Dates */
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
                                        <h3 class="list-group-item" style="background-color: transparent; border:none;padding-top:0.2rem;padding-bottom:0.2rem">`/*${author.uni}*/+`</h3>
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
                              <li class="list-group-item options_entry" style="text-align: left;">
                                  <button style="background-color: white; border: 0;">Report</button>
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
                            <li class="list-group-item" style="border:none;padding-top:0.2rem;padding-bottom:0.2rem">`/*{{ $post['uni'] }}*/+`</li>
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

/* TODO Get User Name */ 
function createComment(comment){
  let new_comment = document.createElement('div');
  new_comment.classList.add('row', 'comment_container', 'comment_no_padding'); 
  new_comment.setAttribute("data-id" , comment.comment_id)
  new_comment.innerHTML = `
    <div class="col-2 comment_user_info" >
        <div class="row">   
            <img src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" class="mx-auto d-block" alt="..." style="border-radius:50%; max-width:2rem; "  onclick="window.location.href='./users/${comment.user.user_id}'">
        </div>
        <div class="row">
            <h4 style="font-size: 1em; margin: 0 auto;">${comment.user.name}</h4>
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
                        <button style=" margin-left:auto; margin-right:auto; background-color: white; border: 0;">Edit</button>
                    </li>
                    <li class="list-group-item options_entry" style="text-align: left;">
                        <button class='comment_delete' style=" background-color: white; border: 0;" > 
                            Delete
                        </button>
                    </li>
                    <li class="list-group-item options_entry" style="text-align: left;">
                        <button style="background-color: white; border: 0;">Report</button>
                    </li>
                </ul>
            </div>
        </div>
    </div>`;
  return new_comment;
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



addEventListeners();