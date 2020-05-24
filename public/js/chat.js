
function createMessage(message) {
    let new_message = document.createElement('p');
    new_message.className = "chat_my_message";
    new_message.innerHTML = `
      ${message.body}
    `;
    return new_message;
  
  }