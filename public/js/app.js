function encodeForAjax(data) {
  if (data == null) return null;
  return Object.keys(data).map(function(k){
    return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
  }).join('&');
}

function sendAjaxRequest(method, url, data, successHandler, errorHandler) {
  let request = new XMLHttpRequest();

  request.open(method, url, true);
  request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
  request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  request.addEventListener('load', successHandler);
  request.addEventListener('error', errorHandler);
  request.send(encodeForAjax(data));
}



function addFeedback(message){
  let feedback = document.getElementById('feedback');
  feedback.innerHTML = `<div class="alert alert-success alert-dismissible fade show d-print-none" role="alert">
                              ${message}
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                              </button>
                          </div>`; 
}

function addErrorFeedback(message){
  let feedback = document.getElementById('feedback');
  feedback.innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                              ${message}
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                              </button>
                          </div>`; 
}

