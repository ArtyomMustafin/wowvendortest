function showUsers() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("usersList").innerHTML =
                this.responseText;
        }
    };
    xhttp.open("POST", "/", true);
    xhttp.send();
}