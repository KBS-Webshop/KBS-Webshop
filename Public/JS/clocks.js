function clockCountdown(clockId, until) {
    const clock = document.getElementById(clockId);
    const countDownDate = new Date(until).getTime();

    const now = new Date().getTime();
    const distance = countDownDate - now;

    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    clock.innerHTML = (days ? (days + ":") : '') + (hours ? (hours + ":") : '') + minutes + ":" + seconds;
    if (hours < 24) {clock.style.color = 'red';}
    if (distance < 0) return;

    setTimeout(clockCountdown, 1000, clockId, until);
}