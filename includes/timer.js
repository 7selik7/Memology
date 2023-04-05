function updateTimer(time, start_time){
    const now = new Date();

    const hours = now.getHours().toString().padStart(2, '0');
    const minutes = now.getMinutes().toString().padStart(2, '0');
    const seconds = now.getSeconds().toString().padStart(2, '0');

    const time2 = `${hours}:${minutes}:${seconds}`;

    const date1 = new Date('1970-01-01T' + start_time + 'Z');
    const date2 = new Date('1970-01-01T' + time2 + 'Z');

    const diffInMilliseconds = date2.getTime() - date1.getTime();
    const diffInSeconds = diffInMilliseconds / 1000;

    let answer = time - diffInSeconds;
    if(answer < 0){
        answer = 0;
    }
    timer.innerHTML = `${answer}`;

    return answer;
}


  





