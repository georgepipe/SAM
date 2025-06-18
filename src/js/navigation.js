function initNav () {
/**
 * TODO:
 * - Set nav button as a const
 * - Set nav list/el to be toggled as a const
 * - Add on click to button and pass btn & nav el
 */
    const navBtnEl = document.querySelector('[data-target-action="nav"]');
    if (navBtnEl) {
        const navListEl = document.querySelector('[type="menu"]');
        navBtnEl.addEventListener('click', () => {
            toggleNav(navBtnEl, navListEl);
        }, false);
    }
    
}


function toggleNav (btnEl, listEl){
/**
 * TODO:
 * Create a toggle nav function to show hide the navigation list.
 * - Should accept btn and nav el as parameters
 */
    if (listEl) {

    } else {
        console.warn('Naa BRUV! Toggle-nav list EL not found');
    }

    if (btnEl) {
        let svgClosed = btnEl.querySelector('.nav-closed');
        let svgOpen = btnEl.querySelector('.nav-open');

        if (svgClosed.classList.contains('block')) {
            svgClosed.classList.remove('block');
            svgClosed.classList.add('hidden');
            svgOpen.classList.remove('hidden');
            svgOpen.classList.add('block');
            listEl.classList.remove('hidden');
            listEl.classList.add('block');

        } else {
            svgClosed.classList.add('block');
            svgClosed.classList.remove('hidden');
            svgOpen.classList.add('hidden');
            svgOpen.classList.remove('block');
            listEl.classList.add('hidden');
            listEl.classList.remove('block');
        }
    }
}

document.addEventListener('keydown', function(event) {
    console.log(`Key pressed: ${event.key}`);
    const key = event.key
    if(key === '†') {
        window.location.href = "http://localhost/SAM/tools"
    };
});

export { initNav }