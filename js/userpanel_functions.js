var MODE = 0;

function load_data(){
  if(this.readyState==4 && this.status==200 && this.responseText != ""){

	var data = JSON.parse(this.responseText);
	console.log(data);
	document.getElementById('name').value = data.name;
	document.getElementById('email').value = data.email;
	document.getElementById('universe').innerHTML = data.universe;
	document.getElementById('year').innerHTML += data.year;
	if(data.list != null) decode_letter_text(data.list);
	if (data.exclusion != null) {
        var ex = document.getElementById("exclusions");
		for(var i=0; i < ex.options.length; ++i){
		  if (data.exclusion == ex.options[i].value){
			ex.selectedIndex = i;
			break;
		  }
		}
    }
	if (data.ready=="1") {
        document.getElementById("ready").className="ready";
		document.getElementById("ready").value = "No estic llest";
    }
	
	if(data.first_time == 1) {
	  $(".wmessage")[0].innerHTML = $(".wmessage")[0].innerHTML.replace(/#user#/,data.name.replace(/[ ].*$/,""));
	  begin_firsttime();
	}
	else $("#first-time-div").fadeOut(500,function(){$(this).remove();});
  }
}

function getMetaContent(propName) {
    var metas = document.getElementsByTagName('meta');

    for (i = 0; i < metas.length; i++) {
        if (metas[i].getAttribute("name") == propName) {
            return metas[i].getAttribute("content");
        }
    }

    return "";
} 

function new_exclusion() {
    var exclusion_id = document.getElementById("exclusions").options[document.getElementById("exclusions").selectedIndex].value; //get exclusion id
	if (exclusion_id=="0") exclusion_id = "NULL";
	var d = new Date(); d.setMonth(new Date().getMonth()+2);

	var conn = new XMLHttpRequest();
	conn.open("POST","../php/variable_info/update_exclusion.php",true);
	conn.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	conn.send("excluded="+exclusion_id+"&year="+d.getFullYear());
}

function change_ready_state() {
    var ref = this;
	var conn = new XMLHttpRequest();
	conn.open("POST","../php/variable_info/update_ready.php",true);
	conn.onreadystatechange = function(){
	  if(this.readyState==4 && this.status==200 && this.responseText.trim() == "1"){
		if (ref.className == "notready") {
            ref.className = "ready";
			ref.value = "No estic llest";
        } else {
			ref.className = "notready";
			ref.value = "Sí, estic llest!";
		}
	  }
	}
	conn.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	document.getElementsByClassName("frontground")[0].style.display="block";
	document.getElementsByClassName("dialog")[0].style.display="block";
    if(this.className == "notready"){
	  document.getElementById("dialog_void_message").innerHTML = "Felicitats! has entrat en el sorteig.";
	  conn.send("ready=1");
	} else{
	  document.getElementById("dialog_void_message").innerHTML = "Has sortit del sorteig. Pots tornar-hi a entrar prement el mateix botó.";
	  conn.send("ready=0");
	}
	setTimeout(function(){
	  document.getElementsByClassName("frontground")[0].style.display="none";
	  document.getElementsByClassName("dialog")[0].style.display="none";  
	},3000);
}

function change_name_email() {
	this.readOnly = false;
	var prev = this.value;
	this.focus();
	this.select();
	this.selectionStart = this.selectionEnd;
    if (this.id=="name") {
        this.onblur = function(){
		  if (this.value.trim() != "" && this.value != prev_value) update_name_email();
		  else this.value = prev;
		}
    } else {
		this.onblur = function(){
		  if (this.value != prev_value && this.value.trim().match(/^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i)) update_name_email();
		  else this.value = prev;
		}
	}
}

function update_name_email() {
	var conn = new XMLHttpRequest();
	conn.open("POST","../php/variable_info/update_name_email.php",true);
	conn.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	conn.send("name="+document.getElementById("name").value+"&email="+document.getElementById("email").value);
}

function unloggin() {
    var conn = new XMLHttpRequest();
	conn.onreadystatechange = function(){
	  if(this.readyState==4 && this.status==200){
		window.location.href = "..";
	  }
	}
	conn.open("POST","../php/users/unloggin_user.php",true);
	conn.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	conn.send();
}

/*			First time	*/

function begin_firsttime() {
  
  var backg = $("#first-time-div");
  window.scrollTo(0,0);
  var messages = $(".wmessage");
  var mask = $(".mask");
  var button = $("#next");
  
  function remove() {
    $(this).remove();
  }
  function first_slide() {
	  messages[0].style.display="block";
	  messages[1].style.display="block";
	  if(!MODE) $(messages[0]).position({my: "left bottom",at: "left center-150",of: "#first-time-div"});
	  else $(messages[0]).position({my: "left top",at: "left top-50px",of: "#first-time-div"});
	  if(!MODE) $(messages[1]).position({my: "left top",at: "left center-25",of: "#first-time-div"});
	  else $(messages[1]).position({my: "left top",at: "left bottom+150px",of: messages[0]});

	  if(!MODE) button.position({my: "center top",at: "center center+225",of: "#first-time-div"});
	  else button.position({my: "center top",at: "center center+185",of: "#first-time-div"});
	  $(messages[0]).delay(1000).animate({opacity: 1.0,top: "+=100"},1500);
	  $(messages[1]).delay(2500).animate({opacity: 1.0},1500);
	  button.delay(4000).animate({opacity: 1.0,top: "-=100"},1000);
	  button.click(function(){
		$(messages[0]).animate({opacity: 0.0},{queue:false,duration: 500, complete: remove});
		$(messages[1]).animate({opacity: 0.0},{queue:false,duration: 500, complete: remove});
		second_slide();
	  });
  }
  
  function second_slide() {
	messages[2].style.display="block";
	if (MODE) messages[2].style.fontSize="250%";
    $(messages[2]).position({
		my: "left center",
		at: "left center",
		of: "#first-time-div"
	  });
	
	 $(messages[2]).delay(500).animate({opacity: 1.0},{queue:false},1500);
	  button.click(function(){
		$(messages[2]).animate({opacity: 0.0},{queue:false,duration: 500, complete: remove});
		if(!MODE) button.animate({top:"80%"},{queue:false,duration:1000});
        else button.animate({top:"85%"},{queue:false,duration:1000});
		third_slide();
	  });
  }
  
  function third_slide() {
     messages[3].style.display="block";
	 if(!MODE) $(mask[0]).position({my: "left top",at: "center-488px top+136",of: "#first-time-div"});
     else {
        $(mask[0]).position({my: "left top",at: "left top+136",of: "#first-time-div"});
        mask[0].style.width="100%";
        mask[0].style.height = "400px";
     }
     $(mask[0]).delay(1000).fadeIn();
	 if(!MODE) $(messages[3]).position({my: "left top",at: "center+200 top+93",of: "#first-time-div"});
     else {
        $(messages[3]).position({my: "left top",at: "left top",of: "#first-time-div"});
        messages[3].style.fontSize="90%";
        messages[3].style.width="98%";
        messages[3].style.padding="10px 1%";
     }

	 $(messages[3]).delay(1000).animate({opacity: 1.0},{queue:false,duration:1500});

	  button.click(function(){
		$(messages[3]).animate({opacity: 0.0},{queue:false,duration: 500, complete: remove});
		$(mask[0]).fadeOut({queue:false,duration: 500, complete: remove});
		fourth_slide();
	  });
  }
  
  function fourth_slide() {
     messages[4].style.display="block";
	 if(!MODE) $(mask[1]).delay(500).fadeIn();
	 if(!MODE) $(messages[4]).position({my: "left top",at: "center-315 top+185",of: "#first-time-div"});
     else {
        if(!backg[0].style.top) backg.delay(500).animate({top: "+=150"},{queue:false,duration:1500})
        $(messages[4]).position({my: "left top",at: "left top+10",of: "#first-time-div"});
        messages[4].style.fontSize="100%";
        messages[4].style.width="98%";
        messages[4].style.padding="10px 1%";
        messages[4].style.textAlign="center";
     }
	 $(messages[4]).delay(500).animate({opacity: 1.0},{queue:false,duration:1500});
	 if (MODE) button.animate({top: "40%"},{queue:false,duration:1500});
	  button.click(function(){

		$(messages[4]).animate({opacity: 0.0},{queue:false,duration: 500, complete: remove});
		if(!MODE)$(mask[1]).fadeOut({queue:false,duration: 500, complete: remove});
		five_slide();
	  });
  }
  
  function five_slide() {

	 messages[5].style.display="block";
	 if(!MODE) $(mask[2]).delay(500).fadeIn();
	 if(!MODE) $(messages[5]).position({my: "left top",at: "center-415 top+275",of: "#first-time-div"});
     else {
        $(messages[5]).position({my: "left top",at: "left top",of: "#first-time-div"});
        messages[5].style.fontSize="100%";
        messages[5].style.width="98%";
        messages[5].style.padding="10px 1%";
        messages[5].style.textAlign="center";
     }

	 $(messages[5]).delay(500).animate({opacity: 1.0,duration:1500});
	 button[0].value="¡Acaba ja!";
     backg[0].style.top="150px";
	  button.click(function(){
		backg.fadeOut({queue:false,duration: 500, complete: remove})
		var conn = new XMLHttpRequest();
		conn.open("POST","../php/variable_info/update_first_time.php",true);
		conn.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		conn.send();
	  });
  }
  
  first_slide();  
    
}

function showhide_bar() {
    var bar = document.getElementById("var-fields");
	if (bar.style.display == "" || bar.style.display == "none") {
        bar.style.display = "block";
		document.getElementById("goodbye").style.marginTop="-73px";
    } else {
	  bar.style.display = "none";
	  document.getElementById("goodbye").style.marginTop="-33px";
	}
}

$("document").ready(function(){

	var menu = document.getElementById("menu-icon");

	if (((window.innerWidth > 0) ? window.innerWidth : screen.width) < 700) {
		MODE = 1;
	   	menu.onclick=showhide_bar;
    }
	var conn = new XMLHttpRequest();
	conn.onreadystatechange = load_data;
	conn.open("POST","../php/users/get_user_info.php",true);
	conn.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	conn.send();
  
	var lines = document.getElementsByClassName("cline");
	for(var i=0; i < lines.length; ++i){
		lines[i].onkeyup = control_length;
	}
	
	document.getElementById("save-letter").onclick = save_letter;
	document.getElementById("exclusions").onchange = new_exclusion;
	document.getElementById("ready").onclick = change_ready_state;
	
	document.getElementById("name").ondblclick = change_name_email;
	document.getElementById("email").ondblclick = change_name_email;
	
	document.getElementById("goodbye").onclick = unloggin;
});
