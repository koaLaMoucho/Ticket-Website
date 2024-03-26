function encodeForAjax(data) {
	return Object.keys(data).map(function(k){
	  return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
	}).join('&')
}

const b = document.querySelectorAll('.faq-remove-icon.delete-icon');
// const b = document.getElementsByClassName('faq-remove-icon delete-icon');

if(b.length != 0){
b.forEach(function(button) {
    button.addEventListener('click', async function(event) {
        event.preventDefault();
        deletion = button.parentElement.textContent;
        id = button.parentElement.getAttribute("data-id");
        type = button.parentElement.getAttribute("data-class");
        console.log(type);

        if (type==="DEPT"){
          const response = await fetch('../api/api_departments.php?' + encodeForAjax({id: id}), {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        });
        if(!response.ok){
          const message = await response.json();
          drawMessage('error', message.error);
          return;
      }
        }

        else if (type === "STATUS"){
          const response = await fetch('../api/api_status.php?' + encodeForAjax({id: id}), {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        });
        if(!response.ok){
          const message = await response.json();
          drawMessage('error', message.error);
          return;
      }
        }

        else if (type === "PRIOR"){
          const response = await fetch('../api/api_priorities.php?' + encodeForAjax({id: id}), {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        });
        if(!response.ok){
          const message = await response.json();
          drawMessage('error', message.error);
          return;
      }

        }

        else if (type === "TAG"){
          const response = await fetch('../api/api_tags.php?' + encodeForAjax({id: id}), {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        });
        if(!response.ok){
          const message = await response.json();
          drawMessage('error', message.error);
          return;
      }

        }


      updateDashboard(id, type);

    });
  });}
  