const addButton = document.querySelector(".tag-input-button .button");


if(addButton != null){
    const tagInput = document.querySelector(".tag-input-button input");
    const tagList = document.querySelector("#new-ticket-form input[name='tags']");
    const currentTags = document.querySelector(".tags-list");
    
    addButton.addEventListener('click', async function (event) {
        event.preventDefault(); 
        
        //check if tag exists in database
        const response = await fetch('../api/api_tags.php?' + encodeForAjax({exists: tagInput.value}), {
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
        
        const tagExists = await response.json();
        if(!tagExists){
            return;
        }
        
        //check if tag is already in tag list
        const tagListArray = tagList.value.split(",");
        if(tagListArray.includes(tagInput.value)){
            return;
        }
        
        //add tag to tag list in input field
        if(tagList.value == ""){
            tagList.value = tagInput.value;
        }else{
            tagList.value = tagList.value + "," + tagInput.value;
        }
        
        //add tag to tag list in html
        const newTag = document.createElement("div");
        newTag.classList.add("tag");
        const p = document.createElement("p");
        p.innerHTML = tagInput.value;
        newTag.appendChild(p);
        
        const removeButton = document.createElement("button");
        removeButton.setAttribute("id", "tag-remove-button");
        removeButton.setAttribute("type", "button");
        removeButton.innerHTML = "X";
        
        
        newTag.appendChild(removeButton);
        
        currentTags.appendChild(newTag);
        
        //clear input field
        tagInput.value = "";
        
        //add event listener to remove button
        removeButton.addEventListener('click', function (event) {
            event.preventDefault();
            
            //remove tag from tag list in input field
            const tagToBeRemoved = removeButton.previousElementSibling.innerHTML;
            const tagList = document.querySelector("#new-ticket-form input[name='tags']").value.split(","); 
            const newTagList = tagList.filter(tag => tag != tagToBeRemoved);
            newTagList.join(",");
            document.querySelector("#new-ticket-form input[name='tags']").value = newTagList;

            //remove tag from tag list in html
            const tag = removeButton.parentNode;
            tag.parentNode.removeChild(tag);

        });
    });
}

//if there are tags from the beginning in the page
const removeButtons = document.querySelectorAll("#tag-remove-button");

if(removeButtons.length != 0){
    removeButtons.forEach(function (button) {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            
            //remove tag from tag list in input field
            const tagToBeRemoved = button.previousElementSibling.innerHTML;
            const tagList = document.querySelector("#new-ticket-form input[name='tags']").value.split(","); 
            const newTagList = tagList.filter(tag => tag != tagToBeRemoved);
            newTagList.join(",");
            document.querySelector("#new-ticket-form input[name='tags']").value = newTagList;

            //remove tag from tag list in html
            const tag = button.parentNode;
            tag.parentNode.removeChild(tag);

        });
    });
}
