let teacherDeleteAppointment = document.querySelectorAll('span.appointment');
[].forEach.call(teacherDeleteAppointment, function(span) {
    span.addEventListener('click', createDeleteDialog);
});

let teacherAddAppointment = document.querySelectorAll('button.appointment');
[].forEach.call(teacherAddAppointment, function(button){
    button.addEventListener('click', createInputAppointment);
});

function createDeleteDialog(event){
    let time = this.getAttribute('data-time');
    let teacher = this.getAttribute('data-teacher');
    
    let popUp = document.createElement("dialog");
    popUp.classList.add('d-print-none');
    popUp.setAttribute('id', 'deleteAppointmentPopUp');
    popUp.setAttribute('open', 'open');
    popUp.setAttribute('style', 'height: 95%; width: 90%; z-index: 1;');
    popUp.setAttribute('data-time', time);
    popUp.setAttribute('data-teacher', teacher);
    popUp.innerHTML = `
        <div style="font-size: 2em;">
            <p style="text-align: center; ">Are you sure you want to delete this appointment?</p>
            <div style="text-align: center; ">
                <button class="btn btn-success" style="font-size: 1.25em;">Yes</button>
                <button class="btn btn-danger" onclick="deletePopUp()" style="font-size: 1.25em;">No</button>
            </div>
        </div>
        `;

    popUp.querySelector('div div button.btn-success').addEventListener('click', deleteAppointmentSpan);

    let agenda = document.getElementById('collapseTwo');
    agenda.parentNode.insertBefore(popUp, agenda);

    event.preventDefault();
    return false;
}

function createInputAppointment(event){
    let time = this.getAttribute('data-time');
    let teacher = this.getAttribute('data-teacher');
    let popUp = document.createElement("dialog");
    popUp.classList.add('d-print-none');
    popUp.setAttribute('id', 'addAppointmentPopUp');
    popUp.setAttribute('open', 'open');
    popUp.setAttribute('style', 'height: 95%; width: 90%; z-index: 1;');
    popUp.setAttribute('data-time', time);
    popUp.setAttribute('data-teacher', teacher);
    popUp.innerHTML = `
        <form method="put" action="/api/users/${teacher}/appointments/">
            <div style="font-size: 1.5em;">
                <div style="max-width: 100%; width: 100%; display: flex; flex-direction: row-reverse;">
                    <button class="btn" onclick="closeDialog()">
                        <span class="fa fa-times"></span>
                    </button>
                </div>
                <p style="text-align: center; ">Insert the appointment description:</p>
                <input type="text" name="description" placeholder="New Appointment" style="max-width:100%; width:100%; margin-bottom: 1em;"/>
                <div style="text-align: center; ">
                    <button type="submit" class="btn btn-success">Add Appointment</button>
                </div>
            </div>
        </form>
        `;

    popUp.querySelector('form').addEventListener('submit', submitAppointmentForm);

    let agenda = document.getElementById('collapseTwo');
    agenda.parentNode.insertBefore(popUp, agenda);

    event.preventDefault();
    return false;
}

function submitAppointmentForm(event){
    let dialog = document.getElementById('addAppointmentPopUp');
    let time = dialog.getAttribute('data-time');
    let teacher = dialog.getAttribute('data-teacher');
    
    let description = this.querySelector('input').value;

    if(description != '')
        sendAjaxRequest('put', '/api/users/'+teacher+'/appointments/' + time, {description: description}, appointmentAddedHandler, appointmentAddErrorHandler);

    event.preventDefault();
    return false;
}

function deleteAppointmentSpan(event) {
    let dialog = document.getElementById('deleteAppointmentPopUp');
    let time = dialog.getAttribute('data-time');
    let teacher = dialog.getAttribute('data-teacher');

    sendAjaxRequest('delete', '/api/users/' + teacher + '/appointments/' + time, null, appointmentDeletedHandler, appointmentDeletedErrorHandler);
    
    event.preventDefault();
    return false;
}

function appointmentAddedHandler(){
    if (this.status != 200 && this.status != 201){
        addErrorFeedback("Failed to add appointment.");
        return;
    }

    let appointment = JSON.parse(this.responseText)[0];

    // Create the new appointment
    let new_appointment = document.createElement("div");
    new_appointment.classList.add('container');
    new_appointment.setAttribute('style', 'padding: 0;');
    new_appointment.innerHTML = `
        <div class="row" style="padding-left: .25em; padding-right: .25em;">
            <div class="col-sm-10" style="padding: 0">
                ${appointment.description}
            </div>
            <div class="col-sm-2" style="padding: 0">
                <span class="fa fa-trash appointment"
                data-time="${appointment.time_id}"
                data-teacher="${appointment.teacher_id}"></span>
            </div>
        </div>
    `;
  
    new_appointment.querySelector('span').addEventListener('click', createDeleteDialog);

    let buttons = document.querySelectorAll('td button');
    let button = null;
    for(let i = 0; i < buttons.length; i++){
        if(buttons[i].getAttribute('data-time') == appointment.time_id){
            button = buttons[i];
            break;
        }
    }
    
    let tableData = button.parentNode;

    tableData.insertBefore(new_appointment, button);

    button.remove();

    let element = document.getElementById('addAppointmentPopUp');
    element.remove();
  
    addFeedback("Appointment added successfully.")
}

function closeDialog(){
    let element = document.getElementById('addAppointmentPopUp');
    element.remove();
}

function appointmentAddErrorHandler(){
    addErrorFeedback("Failed to add appointment.");
}

function appointmentDeletedHandler(){
    if (this.status != 200) {
        addErrorFeedback("Failed to delete appointment.");
        return;
    }
    
    let appointment = JSON.parse(this.responseText)[0];
    let span = document.querySelector(`span[data-time="${appointment.time_id}"]`);
    let div = span.parentNode.parentNode.parentNode;

    let button = document.createElement("button");
    button.classList.add('btn', 'btn-primary');
    button.setAttribute('data-time', appointment.time_id);
    button.setAttribute('data-teacher', appointment.teacher_id);
    button.innerHTML = `Add`;
    button.addEventListener('click', createInputAppointment);
    
    let tableData = div.parentNode;

    tableData.insertBefore(button, div);

    div.remove();

    let element = document.getElementById('deleteAppointmentPopUp');
    element.remove();

    addFeedback("Appointment deleted successfully");
}

function appointmentDeletedErrorHandler(){
    addErrorFeedback("Failed to delete appointment.");
}

function deletePopUp(){
    document.querySelector('dialog').remove();
}