let sent_request = false;

window.onscroll = function(ev) {
    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 800) {

        if(!sent_request){
            sent_request = true;
            let last_id = null;
            
            let resource = "";

            let pathParts = window.location.pathname.split('/');
            if(pathParts[1] == "feed"){
                resource = '/api/posts/';
                last_id = document.querySelector('#postFeed_container article:last-of-type').getAttribute('data-id')
            }else if(pathParts[1] == "groups"){
                resource = '/api/groups/' + pathParts[2] + '/posts/';
                last_id = document.querySelector('#group_form_container article:last-of-type').getAttribute('data-id')
            }else if(pathParts[1] == "events"){
                resource = '/api/events/' + pathParts[2] + '/posts/';
                last_id = document.querySelector('#event_form_container article:last-of-type').getAttribute('data-id')
            }else if(pathParts[1] == "users"){
                resource = '/api/users/' + pathParts[2] + '/posts/';
                last_id = document.querySelector('#user_posts_container article:last-of-type').getAttribute('data-id')
            }else
                return;

            sendAjaxRequest('get', resource+last_id, null , addPosts);
        }
    }
};


function addPosts(){
    if (this.status != 200 && this.status != 201){
        //window.location = '/';
        sent_request = false;
        return;
    }

    if(this.responseText.length < 5) // no important content available
        return;

    let last_id = null;

    let pathParts = window.location.pathname.split('/');
    if(pathParts[1] == "feed"){
        last_id = document.querySelector('#postFeed_container article:last-of-type').getAttribute('data-id'); //last id inserted
        document.querySelector('#postFeed_container').insertAdjacentHTML('beforeend',this.responseText);
    }else if(pathParts[1] == "groups"){
        last_id = document.querySelector('#group_form_container article:last-of-type').getAttribute('data-id'); //last id inserted
        document.querySelector('#group_form_container').insertAdjacentHTML('beforeend',this.responseText);
    }else if(pathParts[1] == "events"){
        last_id = document.querySelector('#event_form_container article:last-of-type').getAttribute('data-id'); //last id inserted
        document.querySelector('#event_form_container').insertAdjacentHTML('beforeend',this.responseText);
    }else if(pathParts[1] == "users"){
        last_id = document.querySelector('#user_posts_container article:last-of-type').getAttribute('data-id'); //last id inserted
        document.querySelector('#user_posts_container').insertAdjacentHTML('beforeend',this.responseText);
    }else{
        last_id = document.querySelector('#postFeed_container article:last-of-type').getAttribute('data-id'); //last id inserted
        document.querySelector('#postFeed_container').insertAdjacentHTML('beforeend',this.responseText);
    }



    let posts = document.querySelectorAll('article.post');

    [].forEach.call(posts, function(new_post) {
        let id = new_post.getAttribute('data-id') ;
        if(parseInt(id) < parseInt(last_id)){
            let postDeleter = new_post.querySelector('button.delete');
            if(postDeleter) postDeleter.addEventListener('click', sendDeletePostRequest);
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
        }
    });

    sent_request = false;
}

/*
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
*/