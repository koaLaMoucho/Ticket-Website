const modal = document.querySelector('[data-modal]');
const openModal = document.querySelector('.tags');
if (openModal != null) {
    openModal.addEventListener('click', function (event) {
        event.preventDefault();
        modal.showModal();
    });
}

if(modal != null)
modal.addEventListener('click', function (event) {
    const modalDimensions = modal.getBoundingClientRect();
    if (
        event.clientX < modalDimensions.left ||
        event.clientX > modalDimensions.right ||
        event.clientY < modalDimensions.top ||
        event.clientY > modalDimensions.bottom
        ) {
        modal.close();
    }
});