const ticketStatus = document.querySelector('#actions-content #status');
const ticketAssignee = document.querySelector('#actions-content #assignee');
const ticketDepartment = document.querySelector('#actions-content .department');
const ticketRequester = document.querySelector('#actions-content #requester');
const ticketTag = document.querySelector('#actions-content #tags');
const ticketDate = document.querySelector('#actions-content #order');


if(ticketStatus && ticketAssignee && ticketDepartment && ticketRequester && ticketTag) {
    ticketStatus.addEventListener('change', handleFiltersChange);
    ticketAssignee.addEventListener('change', handleFiltersChange);
    ticketDepartment.addEventListener('change', handleFiltersChange);
    ticketRequester.addEventListener('change', handleFiltersChange);
    ticketTag.addEventListener('change', handleFiltersChange);
    ticketDate.addEventListener('change', handleFiltersChange);
    
}

async function handleFiltersChange() {
    const response = await fetch('../api/api_tickets.php? ' + encodeForAjax({
        status: ticketStatus.value,
        assignee: ticketAssignee.value,
        department: ticketDepartment.value,
        requester: ticketRequester.value,
        tag: ticketTag.value,
        order: ticketDate.value === 'newest' ? 'DESC' : 'ASC',
        csrf: document.querySelector('#actions-content input[name="csrf"]').value
    }), {
        method: 'GET',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        
    });

    if(!response.ok){
        const message = await response.json();
        drawMessage('error', message.error);
        return;
    }

    const tickets = await response.json();

    drawTicketsFromJson(tickets);
}