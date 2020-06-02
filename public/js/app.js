function encodeForAjax(data) {
  if (data == null) return null;
  return Object.keys(data).map(function(k){
    return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
  }).join('&');
}

/*function encodeFormDataForAjax(data){
  let object = {};
  data.forEach(function(value, key){
      object[key] = value;
  });
  return JSON.stringify(object);
}
*/
function sendAjaxRequest(method, url, data, successHandler, errorHandler) {
  let request = new XMLHttpRequest();

  request.open(method, url, true);
  request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
  request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  request.addEventListener('load', successHandler);
  request.addEventListener('error', errorHandler);
  request.send(encodeForAjax(data));
}

function sendEnctypeAjaxRequest(method, url, data, successHandler, errorHandler) {
  let request = new XMLHttpRequest();

  request.open(method, url, true);
  request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
  request.addEventListener('load', successHandler);
  request.addEventListener('error', errorHandler);
  request.send(data);
}


function addFeedback(message){
  let feedback = document.getElementById('feedback');
  feedback.innerHTML = `<div id='feedback_message' class="alert alert-success alert-dismissible fade show d-print-none" role="alert">
                              <span class='fa fa-check-circle'>&nbsp</span>${message}
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                              </button>
                          </div>`; 
  setTimeout(closeFeedBack,5000);
}

function addErrorFeedback(message){
  let feedback = document.getElementById('feedback');
  feedback.innerHTML = `<div id='feedback_message' class="alert alert-danger alert-dismissible fade show" role="alert">
                              <span class='fa fa-exclamation-triangle'>&nbsp</span>${message}
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                              </button>
                          </div>`; 
  setTimeout(closeFeedBack,7000);
}

function closeFeedBack(){
  let message = document.querySelector('#feedback_message');
  $('#feedback_message').alert()
  $('#feedback_message').alert('close')
}
