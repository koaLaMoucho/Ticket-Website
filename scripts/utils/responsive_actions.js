//refresh width on resize
window.addEventListener('resize', function () {
    const actions = document.querySelector('.actions');
    if(actions){
        const actionsWidth = actions.offsetWidth;
        document.documentElement.style.setProperty('--actions-width', -actionsWidth + 'px');
    }
});

//refresh width on load
window.addEventListener('load', function () {
    const actions = document.querySelector('.actions');
    if(actions){
        const actionsWidth = actions.offsetWidth;
        document.documentElement.style.setProperty('--actions-width', -actionsWidth + 'px');
    }
}   );