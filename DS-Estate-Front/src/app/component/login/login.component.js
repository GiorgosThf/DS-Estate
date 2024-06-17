import {navigateTo} from "../nav/nav.component.js";

document.getElementById('login-form').addEventListener('input', function (event) {
    event.preventDefault();
    document.getElementById('error-message').innerText = '';

});

document.getElementById('login-form').addEventListener('submit', function (event) {
    event.preventDefault();
    event.stopPropagation();
    let form = event.target;
    if (form.checkValidity() === false) {
        form.classList.add('was-validated');
    } else {
        const formData = new FormData(event.target);
        fetch('http://localhost:5000/login', {
            method: 'POST',
            body: JSON.stringify(Object.fromEntries(formData))
        })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if (data.success) {
                    login(data);
                    navigateTo('/')
                } else {
                    document.getElementById('error-message').innerText = data?.message;
                }
            })
            .catch(error => {
                showPopup('Error', error.message);
            });
    }
});


document.getElementById('register-Form').addEventListener('submit', function (event) {
    event.preventDefault();
    const formData = new FormData(event.target);

    fetch('http://localhost:5000/register', {
        method: 'POST',
        body: JSON.stringify(Object.fromEntries(formData))
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                login(data);
                navigateTo('/');

            } else document.getElementById('register-message').innerText = data?.message;
        })
        .catch(error => {
            showPopup('Error:', error.message);
        });
});


document.getElementById('signUp').addEventListener('click', function (e) {
    const container = document.getElementById('over-cont');
    container.classList.add("right-panel-active");
});

document.getElementById('signIn').addEventListener('click', function (e) {
    const container = document.getElementById('over-cont');
    container.classList.remove("right-panel-active");
})

document.getElementById('register_mobile')
    .addEventListener('click', function (e) {
        console.log('register_mobile')
        document.getElementById('sign-up-cont').classList.remove('mobile-hidden');
        document.getElementById('log-in-cont').classList.add('mobile-hidden');
    })

document.getElementById('login_mobile')
    .addEventListener('click', function (e) {
        console.log('login_mobile')
        document.getElementById('log-in-cont').classList.remove('mobile-hidden');
        document.getElementById('sign-up-cont').classList.add('mobile-hidden');
    })

function login(data) {
    window.localStorage.setItem('isLoggedIn', 'true')
    window.localStorage.setItem('user', JSON.stringify(data.user));
    window.localStorage.setItem('username', data.user.username);
    window.localStorage.setItem('token', data.token);
}


