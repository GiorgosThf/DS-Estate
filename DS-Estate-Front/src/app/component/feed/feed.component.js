fetchProperties().then(r => console.log(r));

async function fetchProperties() {
    try {
        await fetch('http://localhost:5000/feed')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    for (const property of data.properties) {
                        displayProperties(property)

                        const resBtn =
                            document.querySelector(`#property-${property.id} button`);

                        if (resBtn) {
                            resBtn.addEventListener('click', function () {
                                reserve(property.id)
                            });
                        }
                    }

                } else {
                    showPopup('Error', data.message);
                }
            })
    } catch (error) {
        console.error('Error fetching properties:', error);
    }
}

function displayProperties(property) {
    const propertyList = document.getElementById("property-list");
    const propertyBox = document.createElement('div');
    propertyBox.className = 'col-lg-4 col-sm-6 p-1';

    propertyBox.innerHTML =
        `<div class="portfolio-box" id="property-${property.id}">
            <img class="img-fluid" src="${property.imageUrl}" alt="${property.title}">
                <div class="portfolio-box-caption">
                    <div class="project-category text-white-50">${property.title}</div>
                    <div class="project-name">Location: ${property.location}</div>
                    <div class="project-name">Rooms: ${property.rooms}</div>
                    <div class="project-name">Price per night: ${property.price} €</div>
                    ${window.localStorage.getItem('isLoggedIn') === 'true'
            ? '<button class="reserve-button">Reserve Property</button>' : ''}
                </div>
        </div>
`;


    propertyList.appendChild(propertyBox);
}


function reserve(id) {
    window.localStorage.setItem('reservationId', id);
    window.location.href = '/reservation';
    return false;

}


function showPopup(title, message) {
    const popup = document.getElementById('generic-popup');
    const popupTitle = document.getElementById('popup-title');
    const popupMessage = document.getElementById('popup-message');
    popupTitle.innerText = title;
    popupMessage.innerText = message;

    popup.style.display = 'block';
}
