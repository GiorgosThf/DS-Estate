export function getUserFromStorage() {

    return window.localStorage.getItem('user');
}

export function setUserToStorage(value) {

    return window.localStorage.setItem('user', value);
}

export function getSelectedListingFromStorage() {

    return window.localStorage.getItem('listing');
}

export function setSelectedListingToStorage(value) {

    return window.localStorage.setItem('listing', value);
}

export function setJwtToStorage(value) {
    return window.localStorage.setItem('token', value);
}

export function getJwtFromStorage() {
    return localStorage.getItem('token');
}