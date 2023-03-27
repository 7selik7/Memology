let time = 60;
let answer = 0;
const timer = document.getElementById("timer");


setInterval(updateTimer, 500);

function updateTimer() {
    const now = new Date();

    const hours = now.getHours().toString().padStart(2, '0');
    const minutes = now.getMinutes().toString().padStart(2, '0');
    const seconds = now.getSeconds().toString().padStart(2, '0');

    const time2 = `${hours}:${minutes}:${seconds}`;

    const date1 = new Date('1970-01-01T' + start_time + 'Z');
    const date2 = new Date('1970-01-01T' + time2 + 'Z');

    const diffInMilliseconds = date2.getTime() - date1.getTime();
    const diffInSeconds = diffInMilliseconds / 1000;

    answer = time - diffInSeconds;

    timer.innerHTML = `${answer}`;
}

