function fromSeconds(seconds, showHours) {
    if (showHours) {
        var hours = Math.floor(seconds / 3600),
            seconds = seconds - hours * 3600;
    }
    var minutes = ("0" + Math.floor(seconds / 60)).slice(-2);
    var seconds = ("0" + parseInt(seconds % 60, 10)).slice(-2);

    if (showHours) {
        var timestring = hours + ":" + minutes + ":" + seconds;
    } else {
        var timestring = minutes + ":" + seconds;
    }
    return timestring;
}