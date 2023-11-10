// example cookie {"220":{"amount":0,"id":"220"},"18":{"amount":1,"id":"18"}}
// cookie is json encoded in the cookie called basket

function getCookie(name) {
    console.log(document.cookie)
    let nameEQ = name + "=";
    let ca = document.cookie.split(';');
    for (let i=0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0)===' ') c = c.substring(1,c.length); //delete spaces
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function setCookie(name, value, days) {
    let date = new Date();
    date.setTime(date.getTime() + (days*24*60*60*1000));
    let expires = "expires=" + date.toUTCString();
    document.cookie = name + "=" + value + ";" + expires + ";path=/";
}

function changeAmount(id, amount) {
    let basket = decodeURI(getCookie("basket"));
    const numberInput = document.getElementById("numberInput"+id);
    basket[id].amount = amount;
    numberInput.value = amount;
    setCookie("basket", JSON.stringify(basket), 1000);
}

function incrementAmount(id) {
    let basket = JSON.parse(decodeURI(getCookie("basket")));
    const numberInput = document.getElementById("numberInput"+id);
    basket[id].amount++;
    numberInput.value++;
    setCookie("basket", JSON.stringify(basket), 1000);
}

function decrementAmount(id) {
    let basket = JSON.parse(decodeURI(getCookie("basket")));
    const numberInput = document.getElementById("numberInput"+id);
    console.log(basket)
    basket[id].amount--;
    numberInput.value--;
    setCookie("basket", JSON.stringify(basket), 1000);
}
