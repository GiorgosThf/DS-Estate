import {getJwtFromStorage} from "../../utils/local-storage.utils.js";

let redirect = null;

await loadListings();


document.getElementById('create-listing-form')
    .addEventListener('submit', function (event) {
        event.preventDefault();
        event.stopPropagation();
        let form = event.target;
        if (form.checkValidity() === false) {
            form.classList.add('was-validated');
        } else {
            const formData = new FormData(event.target);
            formData.append('owner_id', JSON.parse(localStorage.getItem('user')).id)

            fetch('http://localhost:5000/property', {
                method: 'POST',
                body: JSON.stringify(Object.fromEntries(formData)),
                headers: {
                    'Authorization': `Bearer ${getJwtFromStorage()}`
                }
            })
                .then(response => response.json())
                .then(data => {
                    console.log(data.success);
                    const message = document.getElementById('message');
                    if (data.success) {

                        showPopup('Success', data.message, '/');

                    } else {
                        if (!data?.isLoggedIn) {
                            window.localStorage.setItem('isLoggedIn', false);
                        }
                        showPopup('Error', data.message, '/')
                    }
                })
                .catch(error => {
                    showPopup('Error', error.message, null);
                });
        }
    });

async function loadListings() {
    const userId = JSON.parse(window.localStorage.getItem('user')).id
    fetch(`http://localhost:5000/property?owner_id=${userId}`, {
        headers: {
            'Authorization': `Bearer ${getJwtFromStorage()}`
        }
    })
        .then(async response => await response.json())
        .then(data => {
            if (data.success && data.properties) {
                data.properties.forEach(addListing);
            } else {
                if (!data?.isLoggedIn) {
                    window.localStorage.removeItem('isLoggedIn');
                    window.localStorage.removeItem('username');
                    window.localStorage.removeItem('user');
                }
                showPopup('Error', data.message, '/')
            }
        })
        .catch(error => {
            showPopup('Error', error.message, '/');
        });
}

function addListing(property) {
    const listingsDiv = document.getElementById('listings');
    const listing = document.createElement('div');
    listing.className = 'listing col-lg-4 col-sm-6 p-1';

    listing.innerHTML = `
        <form id="my-listing-form">
        <img src="${property.imageUrl}" alt="${property.title}">
        <h3>${property.title}</h3>
        <input disabled value="Location: ${property.location}"/>
        <input disabled value="Number of rooms: ${property.rooms}">
        <input disabled value="Price per night: $${property.price}">
        </form>
    `;

    listingsDiv.appendChild(listing);
}


function showPopup(title, message, red) {
    const popup = document.getElementById('generic-popup');
    const popupTitle = document.getElementById('popup-title');
    const popupMessage = document.getElementById('popup-message');
    redirect = red;
    popupTitle.innerText = title;
    popupMessage.innerText = message;

    popup.style.display = 'block';
}

document.getElementById('popup-close')
    .addEventListener('click', hidePopup);


// Function to hide the popup
function hidePopup() {
    const popup = document.getElementById('generic-popup');
    popup.style.display = 'none';

    if (redirect !== null) {
        window.location.href = redirect;
    }
}

function validate() {
    'use strict';
    let forms = document.getElementsByClassName('needs-validation');
    let validation = Array.prototype.filter.call(forms, function (form) {
        form.addEventListener('submit', function (event) {
            if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
}