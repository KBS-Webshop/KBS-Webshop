// example cookie {"220":{"amount":0,"id":"220"},"18":{"amount":1,"id":"18"}}

function changeAmount(cookie, id, amount) {
    cookie[id].amount = amount;
    return cookie;
}

function decrementAmount(cookie, id) {
    cookie[id].amount--;
    return cookie;
}

function incrementAmount(cookie, id) {
    cookie[id].amount++;
    return cookie;
}