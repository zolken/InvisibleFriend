/*      Validate Fields and send variables to php       */
function register() {
 
    var name = document.getElementById("name");

    if(name.value.trim() == ""){
        name.style.backgroundColor="rgba(255,0,0,0.3)";
        name.style.color="#ff0000";
        return;
    }
    name = name.value.trim();
    
    var email = document.getElementById("email");
    if(!email.value.trim().match(/^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i)){
        email.style.backgroundColor="rgba(255,0,0,0.3)";
        email.style.color="#ff0000";
        return;
    }
    email = email.value.trim();
    
    var password = document.getElementById("password").value.trim();
    var password2 = document.getElementById("password2").value.trim();
    if (password=="" || password != password2) {
        document.getElementById("password").style.backgroundColor="rgba(255,0,0,0.3)";
        document.getElementById("password2").style.backgroundColor="rgba(255,0,0,0.3)";
        return;
    }
    var universe = document.getElementById("universe");
    
    if(universe.selectedIndex == -1){
        document.getElementById("universe_error").style.display = "block";
        return;
    }
    
    universe=universe.options[universe.selectedIndex].value;
    
    var d = new Date();
    
    document.getElementById("load_animation").style.visibility = "visible";
   
    var params = "name="+name+"&email="+email+"&password="+password+"&universe="+universe+"&year="+d.getFullYear();
    var conn = new XMLHttpRequest();
    conn.onreadystatechange = get_register_response;
    conn.open("POST","../php/users/register_user.php",true);
    conn.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    conn.send(params);
    
}

/*      Wait for loggin php response        */
function get_register_response() {
    if(this.readyState==4 && this.status==200){
        console.log(this.responseText);
        var result = JSON.parse(this.responseText);
        if(result.id < 1){
            document.getElementById("container").style.display="none";
            document.getElementById("register_error").style.display="block";
            setInterval(function(){window.location.href="";},5000);
            return;
        }
        if(result.mail == 0){
            document.getElementById("container").style.display="none";
            document.getElementById("register_partial").style.display="block";
            setInterval(function(){window.location.href="..";},5000);
            return;
        }
        
        document.getElementById("container").style.display="none";
        document.getElementById("register_complete").style.display="block";

    }
}

window.onload = function(){
    document.getElementById("register_btn").onclick = register;
    document.getElementById("name").onkeypress = function(event){if(event.keyCode == 13) document.getElementById("email").focus();}
    document.getElementById("email").onkeypress = function(event){if(event.keyCode == 13) document.getElementById("password").focus();}
    document.getElementById("password").onkeypress = function(event){if(event.keyCode == 13) document.getElementById("password2").focus();}
    document.getElementById("password2").onkeypress = function(event){if(event.keyCode == 13) document.getElementById("universe").focus();}
    document.getElementById("password").onfocus = function(event){this.style.backgroundColor="#ffffff"; this.value="";}
    document.getElementById("password2").onfocus = function(event){this.style.backgroundColor="#ffffff"; this.value="";}
    document.getElementById("name").onfocus = function(event){this.style.backgroundColor="#ffffff"; this.style.color="#000000";}
    document.getElementById("email").onfocus = function(event){this.style.backgroundColor="#ffffff"; this.style.color="#000000";}
}