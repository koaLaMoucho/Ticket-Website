const tagAutocomplete = document.querySelector('#new-ticket-form #tags, #actions-content #tags');

const departmentAutocomplete = document.querySelector('#new-ticket-form .department');

const faqAutocomplete = document.querySelector('#faqSearch');
if(faqAutocomplete)
    faqAutocomplete.addEventListener('input', handleFAQAutocomplete);

if(tagAutocomplete) {
    tagAutocomplete.addEventListener('input', handleTagAutocomplete);
    addEventListener("keydown", function(event) {
        if (event.key === "Enter") {
            event.preventDefault();
        }
    });
}

if(departmentAutocomplete) 
    departmentAutocomplete.addEventListener('input', handleDepartmentAutocomplete);

async function handleTagAutocomplete() {
    const response = await fetch('../api/api_tags.php?' + encodeForAjax({query : tagAutocomplete.value}), {
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

    let tags = await response.json();

    //build array from response
    tags = Object.values(tags);

    const tagList = document.querySelector('#new-ticket-form datalist[data-tag-autocomplete], #actions-content datalist[data-tag-autocomplete]');
    drawDatalistFromJson(tagList, tags);
}


async function handleDepartmentAutocomplete() {
    
    const response = await fetch('../api/api_departments.php?' + encodeForAjax({query : departmentAutocomplete.value}), {
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

    const departments = await response.json();

    const departmentList = document.querySelector('#new-ticket-form #department-list');
    drawDatalistFromJson(departmentList, departments);
}

async function handleFAQAutocomplete() {

    const response = await fetch('../api/api_faqs.php?' + encodeForAjax({query : faqAutocomplete.value}), {
        method: 'GET',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    });

    if (!response.ok) {
        const message = await response.json();
        drawMessage('error', message.error);
        return;
    }

    const faqs = await response.json();

    const faqList = document.querySelector('#faq-list');
    drawFAQListFromJson(faqList, faqs);
}