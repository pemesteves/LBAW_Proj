function addEventListeners() {

    let postLikers = document.querySelectorAll('article.post button.upvote');
    [].forEach.call(postLikers, function(liker) {
      liker.addEventListener('click', sendLikePostRequest);
    });

    let postDislikers = document.querySelectorAll('article.post button.downvote');
    [].forEach.call(postDislikers, function(disliker) {
      disliker.addEventListener('click', sendDislikePostRequest);
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












addEventListeners();