
function addEventListeners() {

  let lookMemberName = document.querySelector('#new_member_name');
  if(lookMemberName) lookMemberName.addEventListener('input',sendLookNamesRequest);

  let lookChatName = document.querySelector('#new_chat_name');
  if(lookChatName) lookChatName.addEventListener('keypress', function(e) {
    if(e.key == 'Enter') {
      sendNewChatRequest(e);
    }
  });

  let messageCreators = document.querySelectorAll('article.chat form#newmessage');
    [].forEach.call(messageCreators, function(creator){
    creator.addEventListener('submit', sendCreateMessageRequest);
  });

}

function sendCreateMessageRequest(event){
  
  let body = this.querySelector('input').value;

  let id = this.closest('article.chat').getAttribute('data-id');

  if(body != '') {
    sendAjaxRequest('put', '/api/chats/'+id+'/message', {body: body}, messageAddedHandler);
  }
  else {
  }

  event.preventDefault();
  return false;
}


function messageAddedHandler(){
  console.log(this.responseText);
  if (this.status != 200 && this.status != 201){
    // window.location = '/';
    return;
  }
  let message = JSON.parse(this.responseText);

  // Create the new message
  let new_message = createMessage(message);

  // Reset the new message input
  let toSelect = document.querySelector('article.chat[data-id="'+ message.chat_id + '"]');
  let form = toSelect.querySelector('div.chat_message_input');
  form.querySelector('input').value="";
}

function sendLookNamesRequest(event) {
  let name = this.value;
  let pathParts = window.location.pathname.split('/');
  let chatId = pathParts[2];
  sendAjaxRequest('get', '/api/chats/'+chatId+'/friends?string='+name, null, showNewMembersHandler);
  //event.preventDefault();
}


function sendNewChatRequest(event) {
  let name = document.getElementById('new_chat_name').value;
  sendAjaxRequest('put', '/api/chats/create', {name : name}, newChatHandler);
  event.preventDefault();
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
    adder.addEventListener('click', sendAddMemberToChatRequest);
  });

}

function newChatHandler() {
  console.log(this.responseText);
  if (this.status != 200 && this.status != 201){
    return;
  }

  let chat = JSON.parse(this.responseText);

  let new_chat = createChat(chat);

  document.querySelector('div.user_chats').prepend(new_chat);

  window.location = '/chats/' + chat.chat_id;
  

}

function sendAddMemberToChatRequest(event){
  let pathParts = window.location.pathname.split('/');
  let chatId = pathParts[2];
  let userid = this.getAttribute('data-id');

  sendAjaxRequest('put', '/api/chats/' + chatId +"/"+userid, null, addedUserChatHandler);

  event.preventDefault();
}

function addedUserChatHandler() {
  if (this.status != 200) return;
  let res = JSON.parse(this.responseText);
  document.querySelector('#addToChat_'+res).remove();
}


function createChat(chat) {
  let new_chat = document.createElement('article');
  new_chat.classList.add('card-mb-3', 'chat_user_info_article'); 
  new_chat.innerHTML = `
    <a href = "/chats/${chat.chat_id}" style="color: inherit; text-decoration: none;">
    <div class="row chat_user_info_article_div" style="margin-left:15px">
      
          <img class="card-img" src="https://image.flaticon.com/icons/svg/166/166258.svg" alt="" style="width:2.5em;height:2.5em; border-radius:50%"/>
      
      
         <h2 class="card-title" style="margin-left:10px; margin-right:40px">${chat.chat_name}</h2>
         <img 
         @if (isset($image) && $image !== null)
                 src="{{$image->file_path}}"
         @else
         src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png"
         @endif
         alt="" class="rounded-circle" style="max-width:8%; max-height: 2%;" align="left"/>
  </div>
  </a>  
  `

  return new_chat;
}








function createSearchedMember(user){
  let new_searched =`
        <div class="card mb" style="margin-bottom:0px;border-radius:0px;margin-right:0.5rem" id='addToChat_${user.userable_id}'>
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


function createMessage(message) {
    let new_message = document.createElement('p');
    new_message.className = "chat_my_message";
    new_message.innerHTML = `
      ${message.body}
    `;
    return new_message;
  
}





addEventListeners();