let redirect = null;

fetchProperty().then(
    r => console.log('Property fetched successfully'));

setDates();


function fetchProperty() {
    const propertyId = window.localStorage.getItem('reservationId');

    return fetch(`http://localhost:5000/property?id=${propertyId}`, {
        method: 'GET',
        headers: {'Authorization': `Bearer ${window.localStorage.getItem('token')}`},
    }).then(response => response.json())
        .then(data => {
            if (data.success) {
                displayProperty(data.property);

            } else showPopup('Error', data.message, '/')
        })
        .catch(error => console.error(error));
}

function displayProperty(property) {
    const propertyList = document.getElementById('property-list');

    const propertyHTML = `
                <div class="property-item card">
                    <img class="card-img-top" src="${property.imageUrl}" alt="${property.title}">
                    <div class="card-body">
                        <h2 class="card-title">${property.title}</h2>
                        <p class="card-text">Location: ${property.location}</p>
                        <p class="card-text">Rooms: ${property.rooms}</p>
                        <p class="card-text">Price per night: ${property.price} €</p>
                        <div class="divider"></div>
                    </div>
                </div>
            `;

    propertyList.innerHTML = propertyHTML;
}

document.getElementById('check-in').addEventListener(
    'click', function (e) {
        document.getElementById('check-availability').disabled = false;
    }
)

document.getElementById('check-out').addEventListener(
    'click', function (e) {
        document.getElementById('check-availability').disabled = false;
    }
)

document.getElementById('check-availability').addEventListener('click',
    function (e) {
        e.preventDefault();
        const cIn = document.getElementById('check-in').value;
        const cOut = document.getElementById('check-out').value;

        if (cIn && cOut) {
            const resId = localStorage.getItem('reservationId')
            const formData = new URLSearchParams();
            formData.set('listing_id', resId);
            formData.set('start_date', cIn);
            formData.set('end_date', cOut);

            fetch(`http://localhost:5000/reservation?` + formData, {
                method: 'GET',
                headers: {'Authorization': `Bearer ${window.localStorage.getItem('token')}`},
            }).then(
                response => response.json()
            ).then(data => {
                    console.log(data);
                    if (data.success) {
                        isAvailable(cIn, cOut, data.totalCost);
                    } else {
                        showPopup('Error', data.message, null);
                    }
                }
            );
        } else showPopup('Error', 'Dates cannot be empty', null);
    });


document.getElementById('book-form').addEventListener(
    'submit', function (event) {
        event.preventDefault();
        event.stopPropagation();
        let form = event.target;
        if (form.checkValidity() === false) {
            form.classList.add('was-validated');
        } else {
            document.getElementById('final-payment')
                .innerText.replace(/[^\d.,]/g, '');


            const formData = new FormData(event.target);

            formData.append('listing_id', window.localStorage.getItem('reservationId'));
            formData.append('user_id', JSON.parse(window.localStorage.getItem('user')).id);
            formData.append('total_price', finalPayment.toString());

            fetch('http://localhost:5000/reservation', {
                method: 'POST',
                body: JSON.stringify(Object.fromEntries(formData)),
                headers: {'Authorization': `Bearer ${window.localStorage.getItem('token')}`},

            })
                .then(response => response.json())
                .then(data => {
                    console.log(data.message);
                    if (data.success) {
                        showPopup('Booking Confirmed', 'Your reservation has been successfully submitted.', null);
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
                    console.error('Error:', error);
                    document.getElementById('message').textContent = 'An error occurred. Please try again.';
                });
        }
    });


function isAvailable(checkOut, checkIn, totalCost) {

    document.getElementById('check-availability').disabled = true;

    document.getElementById('step-2').classList.add('active');


    document.getElementById('name').value = JSON.parse(window.localStorage.getItem('user')).username;
    document.getElementById('email').value = JSON.parse(window.localStorage.getItem('user')).email;


    const discountPercentage = (Math.floor(Math.random() * (30 - 10 + 1)) + 10);
    const finalPayment = totalCost - (totalCost * discountPercentage / 100);
    document.getElementById('total-price').textContent = `Total Price: ${totalCost}€`;
    document.getElementById('discount-percentage').textContent = `Discount: ${discountPercentage}%`;
    document.getElementById('final-payment').textContent = `Total Payment: ${finalPayment}€`;

}


export function showPopup(title, message, red) {
    const popup = document.getElementById('generic-popup');
    const popupTitle = document.getElementById('popup-title');
    const popupMessage = document.getElementById('popup-message');
    redirect = red;
    popupTitle.innerText = title;
    popupMessage.innerText = message;

    popup.style.display = 'block';
}

// Function to hide the popup
export function hidePopup() {
    const popup = document.getElementById('generic-popup');
    popup.style.display = 'none';

    if (redirect !== null) {
        window.location.href = redirect;
    }
}

// Add event listener for the close button
document.getElementById('popup-close')
    .addEventListener('click', hidePopup);


function setDates() {
    let today = new Date();

    let minDate = today.getFullYear()
        + '-' + String(today.getMonth() + 1).padStart(2, '0')
        + '-' + String(today.getDate()).padStart(2, '0');

    document.getElementById("check-in").min = minDate;
    document.getElementById("check-out").min = minDate;

}
