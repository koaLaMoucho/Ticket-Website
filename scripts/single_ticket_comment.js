const textBox = document.querySelector('.fixed-textbox');
const cancelButton = document.querySelector('.cancel-button');

if(cancelButton != null)
cancelButton.addEventListener('click', () => {
    textBox.value = ''; // clears the contents of the textbox
});
