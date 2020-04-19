function addEventListeners() {
  let itemCheckers = document.querySelectorAll('article.card li.item input[type=checkbox]');
  [].forEach.call(itemCheckers, function(checker) {
    checker.addEventListener('change', sendItemUpdateRequest);
  });

  let itemCreators = document.querySelectorAll('article.card form.new_item');
  [].forEach.call(itemCreators, function(creator) {
    creator.addEventListener('submit', sendCreateItemRequest);
  });

  let itemDeleters = document.querySelectorAll('article.card li a.delete');
  [].forEach.call(itemDeleters, function(deleter) {
    deleter.addEventListener('click', sendDeleteItemRequest);
  });

  let cardDeleters = document.querySelectorAll('article.card header a.delete');
  [].forEach.call(cardDeleters, function(deleter) {
    deleter.addEventListener('click', sendDeleteCardRequest);
  });

  let cardCreator = document.querySelector('article.card form.new_card');
  if (cardCreator != null)
    cardCreator.addEventListener('submit', sendCreateCardRequest);

  let postCreator = document.querySelector('form#post_form');
  if(postCreator != null)
    postCreator.addEventListener('submit', sendCreatePostRequest);

  let postDeleters = document.querySelectorAll('article.post button.delete');
  [].forEach.call(postDeleters, function(deleter) {
    deleter.addEventListener('click', sendDeletePostRequest);
  });
}


function encodeForAjax(data) {
  if (data == null) return null;
  return Object.keys(data).map(function(k){
    return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
  }).join('&');
}

function sendAjaxRequest(method, url, data, handler) {
  let request = new XMLHttpRequest();

  request.open(method, url, true);
  request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
  request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  request.addEventListener('load', handler);
  request.send(encodeForAjax(data));
}

function sendItemUpdateRequest() {
  let item = this.closest('li.item');
  let id = item.getAttribute('data-id');
  let checked = item.querySelector('input[type=checkbox]').checked;

  sendAjaxRequest('post', '/api/item/' + id, {done: checked}, itemUpdatedHandler);
}

function sendDeleteItemRequest() {
  let id = this.closest('li.item').getAttribute('data-id');

  sendAjaxRequest('delete', '/api/item/' + id, null, itemDeletedHandler);
}


function sendCreateItemRequest(event) {
  let id = this.closest('article').getAttribute('data-id');
  let description = this.querySelector('input[name=description]').value;

  if (description != '')
    sendAjaxRequest('put', '/api/cards/' + id, {description: description}, itemAddedHandler);

  event.preventDefault();
}

function sendDeleteCardRequest(event) {
  let id = this.closest('article').getAttribute('data-id');

  sendAjaxRequest('delete', '/api/cards/' + id, null, cardDeletedHandler);
}

function sendDeletePostRequest(event) {
  let id = this.closest('article').getAttribute('data-id');

  sendAjaxRequest('delete', '/api/posts/' + id, null, postDeletedHandler);
}

function sendCreateCardRequest(event) {
  let name = this.querySelector('input[name=name]').value;

  if (name != '')
    sendAjaxRequest('put', '/api/cards/', {name: name}, cardAddedHandler);

  event.preventDefault();
}

function sendCreatePostRequest(event){
  //TODO Add files
  let title = this.querySelector('input[name=title]').value;
  let body = this.querySelector('textarea').value;

  if(title != '' && body != '')
    sendAjaxRequest('put', '/api/posts/', {title: title, body: body}, postAddedHandler);

  event.preventDefault();
}

function itemUpdatedHandler() {
  let item = JSON.parse(this.responseText);
  let element = document.querySelector('li.item[data-id="' + item.id + '"]');
  let input = element.querySelector('input[type=checkbox]');
  element.checked = item.done == "true";
}

function itemAddedHandler() {
  if (this.status != 200) window.location = '/';
  let item = JSON.parse(this.responseText);

  // Create the new item
  let new_item = createItem(item);

  // Insert the new item
  let card = document.querySelector('article.card[data-id="' + item.card_id + '"]');
  let form = card.querySelector('form.new_item');
  form.previousElementSibling.append(new_item);

  // Reset the new item form
  form.querySelector('[type=text]').value="";
}

function itemDeletedHandler() {
  if (this.status != 200) window.location = '/';
  let item = JSON.parse(this.responseText);
  let element = document.querySelector('li.item[data-id="' + item.id + '"]');
  element.remove();
}

function postDeletedHandler() {
  if (this.status != 200) window.location = '/';
  let post = JSON.parse(this.responseText);
  let element = document.querySelector('article.post[data-id="'+ post.post_id + '"]');
  //let parentElement = element.parentElement;
  $('#popup-'+post.post_id).modal('hide');
  element.remove();
}

function cardDeletedHandler() {
  if (this.status != 200) window.location = '/';
  let card = JSON.parse(this.responseText);
  let article = document.querySelector('article.card[data-id="'+ card.id + '"]');
  article.remove();
}

function cardAddedHandler() {
  if (this.status != 200) window.location = '/';
  let card = JSON.parse(this.responseText);

  // Create the new card
  let new_card = createCard(card);

  // Reset the new card input
  let form = document.querySelector('article.card form.new_card');
  form.querySelector('[type=text]').value="";

  // Insert the new card
  let article = form.parentElement;
  let section = article.parentElement;
  section.insertBefore(new_card, article);

  // Focus on adding an item to the new card
  new_card.querySelector('[type=text]').focus();
}

function postAddedHandler() {
  if (this.status != 200) window.location = '/';

  let post = JSON.parse(this.responseText);

  // Create the new post
  let new_post = createPost(post);

  // Reset the new post input
  let form = document.querySelector('form#post_form');
  form.querySelector('[type=text]').value="";

  // Insert the new post
  form.parentElement.insertBefore(new_post, form.nextSibling);
}

function createCard(card) {
  let new_card = document.createElement('article');
  new_card.classList.add('card');
  new_card.setAttribute('data-id', card.id);
  new_card.innerHTML = `

  <header>
    <h2><a href="cards/${card.id}">${card.name}</a></h2>
    <a href="#" class="delete">&#10761;</a>
  </header>
  <ul></ul>
  <form class="new_item">
    <input name="description" type="text">
  </form>`;

  let creator = new_card.querySelector('form.new_item');
  creator.addEventListener('submit', sendCreateItemRequest);

  let deleter = new_card.querySelector('header a.delete');
  deleter.addEventListener('click', sendDeleteCardRequest);

  return new_card;
}

function createPost(post){
  let new_post = document.createElement('div');
  new_post.classList.add('modal', 'fade'); 
  new_post.setAttribute('id', 'popup-{{ $post->post_id }}');
  new_post.setAttribute('tabindex', '-1');
  new_post.setAttribute('role', 'dialog');
  new_post.setAttribute('aria-labelledby', 'postModal-{{ $post->post_id }}');
  new_post.setAttribute('aria-hidden', 'true');
  new_post.innerHTML = `
  <div class="modal-dialog" role="document" style="overflow: initial; max-width: 90%; width: 90%; max-height: 90%; height: 90%">
      <div class="modal-content" style="height: 100%;">
          <div class="modal-header post_header" >
              <div class="container" style="border-bottom:0;border-radius:0;max-width: 90%;">
                  <div class="row">
                      <div class="col-sm-2">
                          <img src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" class="mx-auto d-block" alt="..." style="border-radius:50%; max-width:7rem; " onclick="window.location.href='./profile.php'"/>
                      </div>
                      <div class="col-sm-10">
                          <div class="row">
                              <div class="col-sm-9" style="background-color: transparent;">
                                  <div class="row" style="background-color: transparent;">
                                      <h2 class="list-group-item" style="background-color: transparent; border:none;padding-top:0.2rem;padding-bottom:0.2rem">{{ $post->name }}</h2>
                                  </div>
                                  <div class="row" style="background-color: transparent;">
                                      <h3 class="list-group-item" style="background-color: transparent; border:none;padding-top:0.2rem;padding-bottom:0.2rem">{{ $post->uni }}</h3>
                                  </div>
                              </div>
                              <div class="col-sm-3" style="padding-top:0.2rem;padding-bottom:0.2rem; text-align: right; font-size: 1.25em;">
                                  <p class="card-text" style="margin-bottom:0rem">{{ $post->date }}</p>
                                  <p class="card-text">{{ $post->hour }}</p>
                              </div>
                          </div>
                          <div class="row justify-content-end" style="font-size: 1.2em;">
                              <span class="fa fa-thumbs-up post_like">&nbsp;{{ $post->upvotes }}&nbsp;</span>
                              <span class="fa fa-thumbs-down post_dislike">&nbsp;{{ $post->downvotes }}&nbsp;</span>
                          </div>
                      </div>
                  </div>
              </div>
              <div>
                  <button type="button" data-dismiss="modal" style="font-size: 150%; margin-right: 0; padding-right: 0; width: 100%; background-color: white; border: 0;"><span class="fa fa-times"></span></button>
                  <button type="button" style="font-size: 150%; margin-right: 0; padding-right: 0; width: 100%; background-color: white; border: 0;"> <span class="fa fa-ellipsis-v" ></span></button>
              </div>
          </div>
          <div class="modal-body post_container" style="overflow-y: auto;">
              <div class="container" style="border-bottom:0;border-top:0;border-radius:0;height:100%;">
                  <div class="row">
                      <h2>{{ $post['title'] }}</h2>  
                  </div>
                  <div class="row post_content">
                      <p> {{ $post['body'] }}</p>
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
  </div>`;

  return new_post;
}

function createItem(item) {
  let new_item = document.createElement('li');
  new_item.classList.add('item');
  new_item.setAttribute('data-id', item.id);
  new_item.innerHTML = `
  <label>
    <input type="checkbox"> <span>${item.description}</span><a href="#" class="delete">&#10761;</a>
  </label>
  `;

  new_item.querySelector('input').addEventListener('change', sendItemUpdateRequest);
  new_item.querySelector('a.delete').addEventListener('click', sendDeleteItemRequest);

  return new_item;
}

addEventListeners();
