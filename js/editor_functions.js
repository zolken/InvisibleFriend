/*				Editor			*/

String.prototype.width = function(font) {
  var f = font || 'LetterFont',
      o = $('<div>' + this + '</div>')
            .css({'position': 'absolute', 'float': 'left', 'white-space': 'nowrap', 'visibility': 'hidden', 'font-family': f,'font-size':'170%'})
            .appendTo($('.cbody')),
      w = o.width();

  o.remove();

  return w;
}

function create_new_line(parent,text,prev_id){
	var node = document.createElement("input");
	node.dataset.nline = prev_id+1;
	node.className = "cline";
	node.type="text";
	node.onkeyup = control_length;
	parent.appendChild(node);
	node.focus();
	node.value=text;
    if(text=="") node.dataset.endl="1"; else node.dataset.endl="0";
	return node;
}

function remove_line(lines,fthis){
		var i = 0;
		for(;i<lines.length;++i){
			if (lines[i] == fthis) break;
		}
		if(i>0) lines[i-1].focus();
		fthis.parentNode.removeChild(fthis);

		try {
		  return lines[i-1];
		} catch(e) {
		  return null;
		}
		
}

function create_new_item(parent,text,prev_id){

	var stext = text.replace(/^[ ]*(-|·)[ ]*/g,'');
	var node = document.createElement("input");
	node.dataset.nline = prev_id+1;
	node.className = "cline";
	node.type="text";
	node.style.width = "62%";
	node.style.padding = "10px 2% 0px 30%";
	node.onkeyup = control_length;
	parent.appendChild(node);
	node.focus();
	node.value="· "+stext;
	return node;
}

function control_length(event){
	
	var fthis = this;
	
	var lines = document.getElementsByClassName("cline");
	var last_id = parseInt(lines[lines.length-1].dataset.nline);
	
	/*				By key code			*/
			
	switch(event.keyCode){
		case 8:
		  if(fthis.value.match(/(^[ ]*·[ ]*$)|(^$)/)){
			if(fthis.dataset.endl=="1") fthis.dataset.endl="0"; else{
			  var parent = fthis.parentNode;
			  remove_line(lines,fthis);
			  if(lines.length<1) {
				last_id--;
				create_new_line(parent,"",last_id);
			  }
			}
			return;
		  }
		  break;
		case 13:
			create_new_line(fthis.parentNode,"",last_id);
			var lines = document.getElementsByClassName("cline");
			var prev = "", cur = "";
			try{
			for(var i = parseInt(fthis.dataset.nline); i < lines.length; ++i){
			  cur = lines[i].value;
			  lines[i].value = prev;
			  prev = cur;
			}
			lines[parseInt(fthis.dataset.nline)].focus();
			} catch(e){}
			return;
		case 16: return;
		case 17: return;
		case 18: return;
		case 38:
			if(parseInt(fthis.dataset.nline)-1>=0) lines[parseInt(fthis.dataset.nline)-1].focus();
			return;
		case 40:
			if(parseInt(fthis.dataset.nline)+1<=lines.length) lines[parseInt(fthis.dataset.nline)+1].focus();
			return;
		case 225: return;
	}
	
	/*			By text length			*/

	var text = fthis.value;
	var pad = parseInt($(fthis).css('padding-left').replace("px", ""));
	
	if (text.width() > fthis.offsetWidth-20-pad) {
			// new line
			var nt = "";
			do{
				nt += text.charAt(text.length-1);
				text = text.substring(0,text.length-1);
			} while(text.length != 0 && text.charAt(text.length-1) != ' ')
			if (text.length == 0) {
				text = nt.split("").reverse().join(""); nt = '';
				while(text.width() > fthis.offsetWidth-20-pad){
					nt += text.charAt(text.length-1);
					text = text.substring(0,text.length-1);
				}
			}
			nt = nt.split("").reverse().join("");
			var nl;
			if (text.match(/^[ ]*·[ ]*/)) nl = create_new_item(fthis.parentNode,"",last_id);
			else nl = create_new_line(fthis.parentNode,"",last_id);
			fthis.value = text;
			var lines = document.getElementsByClassName("cline");
			var prev = nt, cur = "";
			for(var i = parseInt(fthis.dataset.nline); i < lines.length; ++i){
			  cur = lines[i].value;
			  lines[i].value = prev;
			  prev = cur;
			}
			lines[parseInt(fthis.dataset.nline)].focus();
			fthis = nl;
	}
	
	/*			Formating		*/

	if (fthis.value.match(/^[ ]*(-|·)[ ]/)) {
	  var text = fthis.value;
	  var parent = fthis.parentNode;
	  fthis = remove_line(lines,fthis);
	  last_id--;
	  create_new_item(parent,text,last_id);
	}
}

function encode_letter_text() {
    var lines = document.getElementsByClassName("cline");
	var text = "";
	for(var i=0; i < lines.length; ++i){
	  var ptext = lines[i].value;
	  if (lines[i].dataset.endl != undefined && lines[i].dataset.endl != "0") ptext += ";";
	  text += ptext.trim();
	}
	return text;
}

function draw_text(parent,text,line,last_id) {
	var nline;
	
	if(line) nline = create_new_line(parent,"",last_id);
	else nline = create_new_item(parent,"",last_id);
	
	var pad = parseInt($(nline).css('padding-left').replace("px", ""));
	
	  var i = text.length;
	  do{
		--i;
	  } while (i>0 && text.substr(0,i).width() > nline.offsetWidth-20-pad);

	nline.value = text.substr(0,i+1);

	try{
	  if (text.substr(i+1) != "") return draw_text(text.substr(i+1),line,parseInt(nline.dataset.endl));
	  else nline.dataset.endl="1";
	} catch(e){nline.dataset.endl="1"}
	return parseInt(nline.dataset.nline);
	
}

function decode_letter_text(text) {
    var lines = document.getElementsByClassName("cline");
	var parent = lines[0].parentNode;
	var last_id=0;
	for(var i = 0; i < lines.length; ++i) remove_line(lines,lines[i]);
	var items = text.split(";");
	if (items[items.length-1]=="") items.pop();
	for(var i=0; i < items.length; ++i){
	  if (items[i].match(/^[ ]*(-|·)[ ]/)) {
        last_id = draw_text(parent,items[i],false,last_id);
      } else {
		last_id = draw_text(parent,items[i],true,last_id);
	  }
	}
}

function save_letter() {
    var text = encode_letter_text();
	
	var d = new Date(); d.setMonth(new Date().getMonth()+2);
	
	var conn = new XMLHttpRequest();
	conn.onreadystatechange = function(){
	  if(this.readyState==4 && this.status==200){
		var msd = document.getElementById("error-list");

		if(this.responseText == "1"){
		  $(msd).fadeIn();
		  msd.style.backgroundColor="rgba(0,255,0,0.3)";
		  msd.style.color = "#0c0";
		  msd.innerHTML = "LLista desada correctament";
		} else {
		  $(msd).fadeIn();
		   msd.style.backgroundColor="rgba(255,0,0,0.3)";
		   msd.style.color = "#c00";
		   msd.innerHTML = "Hi ha hagut un error al desar la llista. Si us plau, torni-ho a provar o possis en contacte amb l'administrador de la pàgina.";
		}
		setTimeout(function(){$(msd).fadeOut();},3000);
	  }
	};
	var letter = document.getElementById("letter");
	document.getElementById("save-letter").style.display="none";

    html2canvas(letter,{
		  onrendered:function(canvas){
			document.getElementById("save-letter").style.display="block";
			conn.open("POST","../php/variable_info/update_list.php",true);
            conn.setRequestHeader("Content-type","application/x-www-form-urlencoded");
            conn.send("list="+encodeURIComponent(text)+"&image="+encodeURIComponent(canvas.toDataURL("image/jpg"))+"&year="+d.getFullYear());
		  }
	});
	
	
}