const menuButton = document.querySelector('#mobile-navbar #menu');
const filterButton = document.querySelector('#mobile-navbar #filters');

/* show filters button in mobile if page has actions */
const filter = document.querySelector('.actions');
if (filter == null) {
    filterButton.classList.add('d-none');
} else {
    filterButton.classList.remove('d-none');
    filterButton.addEventListener("click", () => {
        filter.classList.toggle('active-actions');
    });
}

menuButton.addEventListener("click", () => {
    const sidebar = document.querySelector('#sidebar');
    sidebar.classList.toggle('active');
});


const dash_button = document.getElementById("navbutton");

dash_button.addEventListener("mouseover", () => {
    const elements = document.querySelectorAll('.subnav');


    {
        elements.forEach(function (element) {
            element.classList.toggle('show');
        })
    }
});