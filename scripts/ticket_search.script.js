function encodeForAjax(data) {
    return Object.keys(data).map(function(k) {
        return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&')
}

const searchTicket = document.getElementById('searchTicket');

if(searchTicket !== null){
    searchTicket.addEventListener('input', async () => {
        const value = searchTicket.value;

        const response = await fetch('../api/api_tickets.php?' + encodeForAjax({search: value}), {
            method: 'GET',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        });

        if(!response.ok){
            const message = await response.json();
            drawMessage('error', message.error);
            return;
        }

        const tickets = await response.json();

        drawTicketsFromJson(tickets);
    })
}