export function handleNavbar() {
    const toggle = document.getElementById('d-toggle');
    const logInBtn = document.getElementById('login-btn');
    const logOutBtn = document.getElementById('logout-btn');
    const reservationBtn = document.getElementById('reservation-btn');
    const listingBtn = document.getElementById('create-listing-btn');
    const username = localStorage.getItem('username');
    toggle.textContent = username === null ? 'Hi Guest' : 'Hi ' + username;

    if (toggle) {
        toggle.addEventListener('click', function (e) {
            e.preventDefault();


            if (localStorage.getItem('isLoggedIn') === 'true') {
                logInBtn.hidden = true;
                listingBtn.hidden = false;
                reservationBtn.hidden = false;
                logOutBtn.hidden = false;
            } else {
                logOutBtn.hidden = true;
                listingBtn.hidden = true;
                reservationBtn.hidden = true;
                logInBtn.hidden = false;
            }
        });
    }

    if (logOutBtn) {
        logOutBtn.addEventListener('click', () => {
            // Handle logout logic here
            console.log('User logged out');
            localStorage.removeItem('isLoggedIn');
            localStorage.removeItem('username');
            localStorage.removeItem('token');
            navigateTo('/')
        });
    }
    if (logInBtn) {
        logInBtn.addEventListener('click', () => {
            navigateTo(logInBtn.attributes.getNamedItem('data').value)
        })
    }

    if (listingBtn) {
        listingBtn.addEventListener('click', () => {
            navigateTo(listingBtn.attributes.getNamedItem('data').value)
        })
    }

    if (reservationBtn) {
        reservationBtn.addEventListener('click', () => {
            navigateTo(reservationBtn.attributes.getNamedItem('data').value)
        })
    }

    const navLinks = document.getElementsByClassName("nav-link");

    if (navLinks) {
        for (const navLink of navLinks) {
            navLink.addEventListener('click', () => {
                console.log('Attribute: ' + navLink.attributes.getNamedItem('data').value)
                navigateTo(navLink.attributes.getNamedItem('data').value)
            })
        }
    }

}

export function navigateTo(path) {
    window.history.pushState({}, '', path);
    window.location.href = path;
}

export function navbarShrink() {
    const navbarCollapsible = document.body.querySelector('#mainNav');
    if (!navbarCollapsible) {
        return;
    }
    if (window.scrollY === 0) {
        navbarCollapsible.classList.remove('navbar-shrink')
    } else {
        navbarCollapsible.classList.add('navbar-shrink')
    }

}
