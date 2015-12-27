/*      Validate Fields and send variables to php       */
function loggin() {
    
    var email = document.getElementById("email");
    if(!email.value.trim().match(/^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i)){
        email.style.backgroundColor="rgba(255,0,0,0.3)";
        email.style.color="#ff0000";
        return;
    }
    email = email.value.trim();
    
    var password = document.getElementById("password").value.trim();
    if (password=="") {
        document.getElementById("password").style.backgroundColor="rgba(255,0,0,0.3)";
        return;
    }
    
    var universe = document.getElementById("universe");
    
    if(universe.selectedIndex == -1){
        document.getElementById("universe_error").style.display = "block";
        return;
    }
    
    universe=universe.options[universe.selectedIndex].value;
    
    document.getElementById("load_animation").style.visibility = "visible";
    
    var params = "email="+email+"&password="+password+"&universe="+universe;
    var conn = new XMLHttpRequest();
    conn.onreadystatechange = get_loggin_response;
    conn.open("POST","php/users/loggin_user.php",true);
    conn.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    conn.send(params);
    
}

/*      Wait for loggin php response        */
function get_loggin_response() {
    if(this.readyState==4 && this.status==200){
        var r = JSON.parse(this.responseText);
        switch (r.status) {
            case 0:
                window.location.href = "pages/upanel.php";
                break;
            case 1:
                window.location.href = "pages/godpanel.php";
                break;
            case 2:
                document.getElementById("link").onclick = function(){
                    window.location.href = 'php/users/resend_mail.php?id='+r.id;
                }
                document.getElementById("link").style.display="block";
                document.getElementById("status_error").style.display="block";
                document.getElementById("load_animation").style.visibility = "hidden";
                break;
            default:
                document.getElementById("loggin_error").style.display="block";
                document.getElementById("load_animation").style.visibility = "hidden";
                break;
        }
    }
}

window.onload = function(){
    document.getElementById("access_btn").onclick = loggin;
    document.getElementById("password").onkeypress = function(event){if(event.keyCode == 13) loggin();}
    document.getElementById("password").onfocus = function(event){this.style.backgroundColor="#ffffff"; this.value="";}
    document.getElementById("email").onkeypress = function(event){if(event.keyCode == 13) document.getElementById("password").focus();}
}