function initUpdateStatus () {

const WORKORDER_STATUSES = [
    'In Progress',
    'On Hold',
    'To Be Built',
    'Upcoming'
];

let statusClass = ''
const URLROOT = "localhost/SAM/"

/**
 * 
 * create event listener:click on status cell
 * set workorder id and status vars from DOM
 * create selectEL in DOM and assign class
 * populate select with statuses from above
 * set current status as selected
 * 
 * append child and focus on it
 * create function to restore selectEL to textEL
 * add event listener:change for select
 * on select: grab new status, send to API endpoint in workorder controller
 * set error handling
 */

document.addEventListener('click', async function (e) {
    const cell = e.target.closest('.wko-status-cell'); //<--- this sets the cell to be the closest wko status cell
    if(!cell) return;
    if(cell.querySelector('select')) return;

    const workorderId = cell.dataset.wkoId;
    const workorderStatus = cell.dataset.wkoStatus;
    

    const select = document.createElement('select');
    select.className = 'wko-status-select';

    WORKORDER_STATUSES.forEach(status =>{
        const option = document.createElement('option');
        option.value = status;
        option.textContent = status;

        if(status === workorderStatus) {
            option.selected = true;
        }

        select.appendChild(option);
    })

    cell.innerHTML = '';
    cell.appendChild(select);
    select.focus();

    const restoreText = (statusText) => {
        cell.dataset.workorderStatus = statusText;
        cell.innerHTML = `<span class="wko-status-text">${statusText}</span>`; // ` <-- this is a back tick or grave accent, known in js as a template liberal
    }

    // select.addEventListener('blur', () => {
    //     restoreText(workorderStatus);
    // }, {once: true}) //<-- this makes the listener automatically remove itself after firing once

    select.addEventListener('change', async () => {
        //now trigger the API
        const newStatus = select.value;
        
        try { //send this and `await` a response from the api endpoint
            const response = await fetch(`../apiworkorders/updateStatus`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    workorder_id: workorderId,
                    status: newStatus
                })
            });

            const results = await response.json(); //results = the extracted response when it arrives back from the endpoint

            if(!response.ok || !results.sucess) {
                throw new Error(results.message || 'Failed to update status.');
            }
            statusClass = getStatusClass(workorderStatus);
            select.parentElement.parentElement.classList.remove(statusClass)
            statusClass = getStatusClass(newStatus)
            select.parentElement.parentElement.classList.add(statusClass)
            restoreText(newStatus);
            // updateStatusClass(e);

            
        } catch (error) {
            console.error(error);
            restoreText(workorderStatus);
            alert('Could not update workorder status');
        }
    }, {once: true});





})
}

function getStatusClass(status){
    switch (status) {
        case 'To be Built':
            return 'tobebuilt'
        case 'On Hold':
            return 'onhold'
        case 'Upcoming':
            return 'upcoming'
        case 'In Progress':
            return 'inprogress'
        default:
            break;
    }
}

export { initUpdateStatus }

