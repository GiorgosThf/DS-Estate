import {routes} from "./route.js";
import {handleNavbar, navbarShrink} from "./src/app/component/nav/nav.component.js";

function initiate() {
    if (window.localStorage.getItem('isLoggedIn') === null) {
        window.localStorage.clear();
        window.localStorage.setItem('isLoggedIn', 'false');
    }
}


async function loadNav() {
    fetch('src/app/component/nav/nav.component.html')
        .then(response => response.text())
        .then(html => {
            document.getElementById('nav').innerHTML = html;
            handleNavbar();
        })
        .catch(error => {
            console.error('Error loading navigation:', error);
        });
}

function loadFooter() {
    fetch('src/app/component/footer/footer.component.html')
        .then(response => response.text())
        .then(html => {
            document.getElementById('footer').innerHTML = html;
        })
        .catch(error => {
            console.error('Error loading footer:', error);
        });
}

export function renderRoute() {
    const route = routes[window.location.pathname];
    if (route) {
        fetch(route.component)
            .then(async response =>
                await response.text()
            )
            .then(
                async html => {
                    await Promise.all([

                        await loadResource('css', route.css)
                            .then(async () => {
                                removePreviousResources('html');
                                document.getElementById('app').innerHTML = html
                            }),

                        await loadResource('js', route.js)
                            .catch(error => console.log(error)),
                    ])
                })
            .catch(err => {
                console.error(err);
            })
            .then(r => console.log("Component rendered successfully"));

    } else document.getElementById('app').innerHTML = '<h1>Page Not Found</h1>';
}

function loadResource(type, url) {
    return new Promise((resolve, reject) => {
        let element;
        if (type === 'css') {

            element = document.createElement('link');

            element.rel = 'stylesheet';
            element.href = url;


        } else if (type === 'js') {
            element = document.createElement("script");
            element.type = 'module';
            element.src = url;
        }

        element.onload = resolve;
        element.onerror = reject;

        document.head.appendChild(element);
    });
}

function removePreviousResources(type) {
    if (type === 'css' || type === 'js') {
        const elements =
            document.querySelectorAll(type === 'css'
                ? 'link[rel="stylesheet"]'
                : 'script[src]:not([type])');

        elements.forEach(el => el.remove());
    }

    if (type === 'html') {
        document.getElementById('app').innerHTML = '';
    }
}


function load() {
    document.addEventListener('DOMContentLoaded', () => {
        loadNav();
        loadFooter();
        renderRoute();
        initiate();


        window.addEventListener('popstate', renderRoute);
    });
}

load();

document.addEventListener("scroll", navbarShrink)
