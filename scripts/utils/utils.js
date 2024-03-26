function escapeHTML(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;");

}
function drawStatus(status) {
    const statusColors = {
        'Open': '#24a0ed',
        'Pending': '#f5a623',
        'Closed': '#2ecc71'
    };

    const statusDiv = document.createElement('div');
    statusDiv.id = 'status';
    statusDiv.style.backgroundColor = statusColors[status];

    const statusText = document.createElement('p');
    statusText.textContent = status;

    statusDiv.appendChild(statusText);

    return statusDiv;
}

function drawTicketsFromJson(tickets) {
    const months = ["Jan", "Feb", "Mar", "Apr",
        "May", "Jun", "Jul", "Aug",
        "Sep", "Oct", "Nov", "Dec"];

    const ticketList = document.querySelector('.tickets>#main-content');
    ticketList.innerHTML = '';

    if (tickets.length !== 0) {
        for (const ticket of tickets) {

            const userPicId = (ticket.user_id % 30) + 1;
            const userPic = `../docs/users/${userPicId}.jpg`;

            const ticketItem = document.createElement('a');
            ticketItem.setAttribute('href', "../pages/ticket.php?id=" + ticket.id);
            ticketItem.setAttribute('tabindex', '0');
            ticketItem.setAttribute('class', 'no-link ticket');

            const ticketHeader = document.createElement('header');
            ticketItem.appendChild(ticketHeader);

            const userImage = document.createElement('img');
            userImage.setAttribute('src', userPic);
            userImage.setAttribute('alt', 'User');
            ticketHeader.appendChild(userImage);

            const userInfo = document.createElement('div');
            ticketHeader.appendChild(userInfo);

            const userTitle = document.createElement('p');
            userTitle.textContent = escapeHTML(ticket.user);
            userInfo.appendChild(userTitle);

            const userEmail = document.createElement('p');
            userEmail.setAttribute('class', 'email');
            userEmail.textContent = escapeHTML(ticket.email);
            userInfo.appendChild(userEmail);

            const ticketSubject = document.createElement('section');
            ticketSubject.setAttribute('class', 'ticket-tags');

            const ticketTitle = document.createElement('p');
            ticketTitle.textContent = escapeHTML(ticket.title);
            ticketSubject.appendChild(ticketTitle);

            const ticketTags = document.createElement('p');
            for (const tag of ticket.tags) {
                ticketTags.textContent += escapeHTML(tag.name);
                ticketTags.textContent += ' ';
            }
            ticketSubject.appendChild(ticketTags);

            ticketItem.appendChild(ticketSubject);

            const ticketAssignee = document.createElement('p');
            ticketAssignee.textContent = ticket.assigned_to !== null ? escapeHTML(ticket.assigned_to) : 'Unassigned';
            ticketItem.appendChild(ticketAssignee);

            ticketItem.appendChild(drawStatus(escapeHTML(ticket.status)));

            const ticketUpdated = document.createElement('p');
            const updatedDate = new Date(ticket.last_update.date);
            const updatedDateString = `${updatedDate.getDate()} ${months[updatedDate.getMonth()]} ${updatedDate.getFullYear()}`;
            ticketUpdated.textContent = updatedDateString; ticketItem.appendChild(ticketUpdated);

            ticketList.appendChild(ticketItem);
        }
    } else {
        const noTickets = document.createElement('p');
        noTickets.textContent = 'No tickets found';
        ticketList.appendChild(noTickets);
    }
}

function drawDatalistFromJson(tagList, tags) {

    tagList.innerHTML = '';
    console.log(tags);

    // tags = Array.from(tags, tag => tag.name);
    
    //iterate through tags
    for (const tag of tags) {
        console.log(tag);
        const tagOption = document.createElement('option');
        tagOption.setAttribute('value', tag.name);

        tagList.appendChild(tagOption);

    }

    // tags.forEach(function (tag) {
    //     console.log(tag);
    //     const tagOption = document.createElement('option');
    //     tagOption.setAttribute('value', tag.name);

    //     tagList.appendChild(tagOption);
    // }   );
    
}

function drawFAQListFromJson(faqList, faqs) {

    faqList.innerHTML = '';

    for (const faq of faqs) {
        const faqOption = document.createElement('option');
        faqOption.setAttribute('value', faq);

        faqList.appendChild(faqOption);
    }
}

function drawUpdatedUser(username, name, email) {
    const paragraphs = Array.from(document.querySelectorAll('form.edit p'));

    let usernameParagraph = paragraphs[0];
    let nameParagraph = paragraphs[1];
    let emailParagraph = paragraphs[2];

    if (username !== "") {
        usernameParagraph.textContent = username;
    }
    if (name !== "") {
        nameParagraph.textContent = name;
    }
    if (email !== "") {
        emailParagraph.textContent = email;
    }

    // newFormSection = document.createElement('section');
    // newFormSection.setAttribute('id', 'edit-user');
    // newFormSection.setAttribute('class', 'actions default-actions-grid');

    // const newHeader = document.createElement('header');
    // newFormSection.appendChild(newHeader);

    // newFormArticle = document.createElement('article');
    // newFormArticle.setAttribute('class', 'edit-profile');

}

function drawUpdatedTicket(username, email) {
    const paragraphs = Array.from(document.querySelectorAll('p.email'));

    paragraphs.forEach(function (paragraph) {
        if (email !== "") {
            paragraph.textContent = email;
        }

    });

const usernames = Array.from(document.querySelectorAll('a.ticket > header > div > p:first-child'));
console.log(usernames);

    usernames.forEach(function (n_username) {
        if (username !== "") {
            n_username.textContent = username;
        }

    });
}

function drawMessage(type, message) {
    const messageDiv = document.querySelector('#general-messages');

    const newMessage = document.createElement('article');
    newMessage.setAttribute('id', type);

    const newParagraph = document.createElement('p');
    newParagraph.textContent = message;

    newMessage.appendChild(newParagraph);

    messageDiv.appendChild(newMessage);

    setTimeout(function () {
        messageDiv.removeChild(newMessage);
    }, 3000);
}

function updateDashboard(id, type){
    // const section = document.querySelectorAll(`li[data-class="${type}"]`);
    // console.log(section);
    const item = document.querySelector(`li[data-id="${id}"][data-class="${type}"]`)
    console.log(item);
    item.classList.add('hidden');
    // item.style.display ="none";

}

