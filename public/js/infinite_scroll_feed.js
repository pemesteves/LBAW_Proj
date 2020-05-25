let sent_request = false;

window.onscroll = function(ev) {
    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 800) {
        //this.console.log('a chegar a baixo');
        if(!sent_request){
            sent_request = true;
            let last_id = document.querySelector('#postFeed_container article:last-of-type').getAttribute('data-id')
            sendAjaxRequest('get', '/api/posts/'+last_id, null , addPosts);
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
    document.querySelector('#postFeed_container').insertAdjacentHTML('beforeend',this.responseText);
    sent_request = false;
}