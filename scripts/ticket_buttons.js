const ticketPage = document.querySelector(".ticketPage");

if (ticketPage) {
  const faqFormToggle = document.querySelector("#faq-form-toggle");
  const editFormToggle = document.querySelector("#edit-form-toggle");
  const answerFaqFormToggle = document.querySelector("#answer-faq-form-toggle");
  const hideButton = document.querySelector("#hide-button");
  const hideButtonEdit = document.querySelector("#hide-button-edit");
  const hideAnswerButton = document.querySelector("#hide-answer-button");
  const textboxContainerFAQ = document.querySelector(".textbox-container-faq");
  const textboxContainerEdit = document.querySelector(".textbox-container-edit");
  const textboxContainerAnswerFAQ = document.querySelector(".textbox-container-answer-faq");
  const faqFormContainer = document.querySelector(".faq-form-container");
  const editFormContainer = document.querySelector(".edit-form-container");
  const answerFaqFormContainer = document.querySelector(".answer-faq-form-container");
  const deleteTicketButton = document.querySelector("#delete-form button");

  const toggleFormVisibility = (formContainer, toggleButton, otherButtons, textboxContainer) => {
    formContainer.classList.toggle("hidden");
    toggleButton.classList.toggle("hidden");
    otherButtons.forEach((button) => {
      if (button) {
        button.classList.toggle("hidden");
      }
    });
    // textboxContainer.style.display = formContainer.classList.contains("hidden") ? "none" : "block";
    if (formContainer.classList.contains("hidden")){
      textboxContainer.classList.add("hidden");
    }
    else{
      textboxContainer.classList.remove("hidden");
    }

    if (formContainer.classList.contains("hidden")){
      deleteTicketButton.classList.remove("hidden");
    }
    else{
      deleteTicketButton.classList.add("hidden");
    }


    // textboxContainer.classList.toggle("hidden");
    // deleteTicketButton.classList.toggle("hidden");


    // deleteTicketButton.style.display = formContainer.classList.contains("hidden") ? "flex" : "none";
  };

  if (faqFormToggle) {
    faqFormToggle.addEventListener("click", () => {
      toggleFormVisibility(faqFormContainer, faqFormToggle, [editFormToggle, answerFaqFormToggle], textboxContainerFAQ);
    });
  }

  if (editFormToggle) {
    editFormToggle.addEventListener("click", () => {
      toggleFormVisibility(editFormContainer, editFormToggle, [faqFormToggle, answerFaqFormToggle], textboxContainerEdit);
    });
  }

  if (answerFaqFormToggle) {
    answerFaqFormToggle.addEventListener("click", () => {
      toggleFormVisibility(answerFaqFormContainer, answerFaqFormToggle, [faqFormToggle, editFormToggle], textboxContainerAnswerFAQ);
    });
  }

  if (hideButton) {
    hideButton.addEventListener("click", () => {
      toggleFormVisibility(faqFormContainer, faqFormToggle, [editFormToggle, answerFaqFormToggle], textboxContainerFAQ);
    });
  }

  if (hideButtonEdit) {
    hideButtonEdit.addEventListener("click", () => {
      toggleFormVisibility(editFormContainer, editFormToggle, [faqFormToggle, answerFaqFormToggle], textboxContainerEdit);
    });
  }

  if (hideAnswerButton) {
    hideAnswerButton.addEventListener("click", () => {
      toggleFormVisibility(answerFaqFormContainer, answerFaqFormToggle, [faqFormToggle, editFormToggle], textboxContainerAnswerFAQ);
    });
  }
}

