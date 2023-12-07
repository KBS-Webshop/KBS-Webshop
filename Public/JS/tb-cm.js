function updateClocks(until) {
    const countDownDate = new Date(until).getTime();

    const now = new Date().getTime();
    const distance = countDownDate - now;

    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
    const time = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";

    for (let clock of document.getElementsByClassName("countdownClock")) {
        clock.innerHTML = time;
    }

    if (distance < 0) {
        document.getElementById("demo").innerHTML = "EXPIRED";
        return;
    }

    setTimeout(updateClocks, 1000);
}

document.addEventListener("DOMContentLoaded", () => {
    updateClocks("Jan 5, 2024 15:37:25");
});