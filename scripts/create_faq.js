const faqFormToggle = document.querySelector("#create-faq-form-toggle");
const hideButton = document.querySelector("#create-hide-button");
const faqFormContainer = document.querySelector(".create-faq-form-container");
const textFormContainer = document.querySelector(".textbox-container-create-faq");

if(faqFormToggle != null)
faqFormToggle.addEventListener("click", () => {
  faqFormContainer.classList.remove("hidden");
  // textFormContainer.style.display = "block";
  textFormContainer.classList.remove("hidden");
  faqFormToggle.classList.add("hidden");

});

if(hideButton != null)
hideButton.addEventListener("click", () => {
  faqFormContainer.classList.add("hidden");
  // textFormContainer.style.display = "none";
  textFormContainer.classList.add("hidden");
  faqFormToggle.classList.remove("hidden");
});
