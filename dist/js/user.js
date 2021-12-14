function convertTZ(date, tzString) {
    return new Date((typeof date === "string" ? new Date(date) : date).toLocaleString("en-US", {timeZone: tzString}));   
}

function _getCurrentDate() {
    //get Current Date
    var Europ_fullDate = new Date().toLocaleString("en-US", {
        timeZone: "Europe/Lisbon"
    }); //Thu May 19 2011 17:25:38 GMT+1000 {}
    var fullDate = new Date(Europ_fullDate);
    //convert month to 2 digits
    var twoDigitMonth = ("0" + (fullDate.getMonth() + 1)).slice(-2);
    //convert date to 2 digits
    var twoDigitDate = ("0" + fullDate.getDate()).slice(-2);
    var currentDate = twoDigitDate + "/" + twoDigitMonth + "/" + fullDate.getFullYear();

    // const date = new Date()
    // var currentDate = convertTZ(date, "Europe/Lisbon") // current date-time in jakarta.

    return currentDate;
}

function _getHttpGetRequest(name) {
    if (name = (new RegExp('[?&]' + encodeURIComponent(name) + '=([^&]*)')).exec(location.search))
        return decodeURIComponent(name[1]);
}