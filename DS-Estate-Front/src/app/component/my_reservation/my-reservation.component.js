let redirect = null;
fetchReservations();

function fetchReservations() {
    const user = window.localStorage.getItem('user');
    const id = user == null ? user : JSON.parse(user).id;
    fetch(`http://localhost:5000/reservation?userId=${id}`, {
        headers: {
            'Authorization': `Bearer ${window.localStorage.getItem('token')}`
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                data.reservations.forEach(reservation => addReservation(reservation));
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

function addReservation(reservation) {
    const reservationsContainer = document.getElementById('reservation-listings');
    const reservationItem = document.createElement('div');
    reservationItem.className = 'reservation col-lg-4 col-sm-6 p-1';

    reservationItem.innerHTML = `
                <form id="my-reservation-form">
                    <img src="${reservation.property.imageUrl}" alt="${reservation.property.title}"/>
                    <h3>${reservation.property.title}</h3>
                        <input disabled value="Location: ${reservation.property.location}"/>
                        <input disabled value="Number of rooms: ${reservation.property.rooms}">
                         <input disabled value="Check in: ${new Date(reservation.reservation.startDate.date).toDateString()}">   
                         <input disabled value="Check out: ${new Date(reservation.reservation.endDate.date).toDateString()}">                    
                        <input disabled value=" Total Price: ${reservation.reservation.totalPrice} €"/>
                </form>
            `;

    reservationsContainer.appendChild(reservationItem);
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

document.getElementById('popup-close')
    .addEventListener('click', hidePopup);