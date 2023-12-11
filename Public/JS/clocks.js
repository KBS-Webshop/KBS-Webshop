function formatTimeStamp(d, h, m, s, text=true) {
    return (
        // sorry hiervoor
        (text ? 'Nog ' : '') +
        // als er geen dagen zijn, laat het weg; anders voeg een 0 toe als er maar 1 getal is
        (d !== '0' ? ((d.length === 1 ? '0' : '') + d + ":") : '') +
        (h !== '0' ? ((h.length === 1 ? '0' : '') + h + ":") : '') +
        // voeg een 0 toe als er maar 1 getal is
        (m.length === 1 ? '0' : '') + m + ":" +
        (s.length === 1 ? '0' : '') + s +
        (text ? ' over' : '')
    );
}

function clockCountdown(clockId, until, text=true) {
    const clock = document.getElementById(clockId);
    const countDownDate = new Date(until).getTime();

    const now = new Date().getTime();
    const distance = countDownDate - now;

    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    clock.innerHTML = formatTimeStamp(days.toString(), hours.toString(), minutes.toString(), seconds.toString(), text);
    if (hours < 24) {clock.style.color = 'red';}
    if (distance < 0) return;

    setTimeout(clockCountdown, 1000, clockId, until, text);
}