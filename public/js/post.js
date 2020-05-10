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



function sendDeleteCommentRequest(event) {
  let id = this.closest('div.comment_container').getAttribute('data-id');

  sendAjaxRequest('delete', '/api/comments/' + id, null, commentDeletedHandler);
}
function commentDeletedHandler() {
  if (this.status != 200) window.location = '/';
  let comment = JSON.parse(this.responseText);
  let element = document.querySelector('div.comment_container[data-id="'+ comment.comment_id + '"]');

  element.remove();
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