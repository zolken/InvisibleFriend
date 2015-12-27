function load_universes() {
    
    var buttonrenderer = function(row, column, value, htmlElement){
        var img = $('<img id="'+value+'" src="../src/delete_icon.png" title="Delete universe" alt="Delete universe button"/>');
        $(htmlElement).append(img);
        img[0].onclick = delete_universe;
    }

    var initwidget = function(row, column, value, htmlElement){
        var img = $(htmlElement).find("img")[0];
        img.id = value;
    }

    var columns = [
      {text: 'Id', datafield: 'id', width:'5%', cellsalign: 'center', align: 'center'},
      {text: 'Name', datafield: 'name', width:'20%', cellsalign: 'center', align: 'center'},
      {text: 'Description', datafield: 'description', width:'45%', cellsalign: 'left', align: 'left'},
      {text: 'Stars', datafield: 'stars', width:'8%', cellsalign: 'center', align: 'center'},
      {text: 'Supernovas', datafield: 'supernovas', width:'7%', cellsalign: 'center', align: 'center'},
      {text: 'Year', datafield: 'year', width:'8%', cellsalign: 'center', align: 'center'},
      {text: '', datafield: 'delete', width:'7%', cellsalign: 'center', align: 'center', createwidget: buttonrenderer, initwidget: initwidget}
    ];
    var source = {
        datatype: "json",
        datafields: [
            {name:"id"},
            {name:"name"},
            {name:"description"},
            {name:"hash"},
            {name:"stars"},
            {name:"supernovas"},
            {name:"year"},
            {name:"delete", map:"id"}
        ],
        id : 'id',
        url: "../php/universes/get_universes_info.php",
        root: 'data'
    };
    
    var adapter = new $.jqx.dataAdapter(source);

    $("#universe-table").jqxGrid({
            width: "90%",
            height: "250px",
            source: adapter,
            columns: columns
    });

}

function init_stars() {
    /*var columns = [
      {text: 'Id', datafield: 'id', width:'5%', cellsalign: 'center', align: 'center'},
      {text: 'Name', datafield: 'name', width:'20%', cellsalign: 'center', align: 'center'},
      {text: 'Email', datafield: 'email', width:'35%', cellsalign: 'left', align: 'left'},
      {text: 'Admin', datafield: 'admin', width:'5%', cellsalign: 'center', align: 'center'},
      {text: 'New', datafield: 'first_time', width:'5%', cellsalign: 'center', align: 'center'},
      {text: 'Status', datafield: 'status', width:'5%', cellsalign: 'center', align: 'center'},
      {text: 'Ready', datafield: 'ready', width:'5%', cellsalign: 'center', align: 'center'},
      {text: 'Universe', datafield: 'universe', width:'5%', cellsalign: 'center', align: 'center'},
      {text: 'Year', datafield: 'year', width:'8%', cellsalign: 'center', align: 'center'},
      {text: '', datafield: 'delete', width:'7%', cellsalign: 'center', align: 'center', createwidget: buttonrenderer, initwidget: initwidget}
    ];
    $("#stars-table").jqxGrid({
            width: "90%",
            height: "250px",
            //source: adapter,
            columns: columns
    });*/
}

function create_universe() {
    var name = document.getElementById("universe_name").value.trim();
    if (name == "" || name == "Name" ) {
        document.getElementById("universe_name").style.backgroundColor="rgba(255,0,0,0.3)";
        document.getElementById("universe_name").style.color="#ff0000";
        return;
    }
    var description = document.getElementById("universe_description").value.trim();
    
    document.getElementById("dialog_message").innerHTML="The universe <b>"+name+"</b> must be created, are you sure?";
    document.getElementById("ok").value="Oh Yeah!";
    document.getElementById("cancel").value="No! God!";
    document.getElementsByClassName("frontground")[0].style.display="block";
    document.getElementsByClassName("dialog")[0].style.display="block";
    
    document.getElementById("ok").onclick = function(){
        var conn = new XMLHttpRequest();
        conn.onreadystatechange = universe_created;
    
        var d = new Date(); d.setMonth(new Date().getMonth()+2);
        var params = "name="+name+"&description="+description+"&year="+d.getFullYear();
    
        document.getElementsByClassName("dialog")[0].style.display="none";
        
        conn.open("POST","../php/universes/create_universe.php");
        conn.setRequestHeader("Content-type","application/x-www-form-urlencoded; charset=utf-8");
        conn.send(params);
    }
    
    document.getElementById("cancel").onclick = function(){ 
        document.getElementsByClassName("dialog")[0].style.display="none";
        document.getElementsByClassName("frontground")[0].style.display="none";
    }
       
}

function delete_universe() {
    var id =this.id;
    if (id < 2 || id == undefined) return;
    var rdata = $("#universe-table").jqxGrid('getrowdatabyid', id);
    
    document.getElementById("dialog_message").innerHTML="The universe <b>"+rdata.name+"</b> and her <b>"+rdata.stars+"</b> stars must be deleted, are you sure?";
    document.getElementById("ok").value="Bye bye!";
    document.getElementById("cancel").value="I love it!";
    document.getElementsByClassName("frontground")[0].style.display="block";
    document.getElementsByClassName("dialog")[0].style.display="block";
    
    document.getElementById("ok").onclick = function(){
        document.getElementsByClassName("dialog")[0].style.display="none";
        var conn = new XMLHttpRequest();
        conn.open("POST","../php/universes/delete_universe.php");
        conn.setRequestHeader("Content-type","application/x-www-form-urlencoded; charset=utf-8");
        conn.onreadystatechange = function(){
            if (this.readyState==4 && this.status==200 && parseInt(this.responseText) == 200) {
                $("#universe-table").jqxGrid('deleterow', id);
                document.getElementsByClassName("frontground")[0].style.display="none";
            } else if (this.readyState == 4) document.getElementsByClassName("frontground")[0].style.display="none";
        }
    }
    
    document.getElementById("cancel").onclick = function(){ 
        document.getElementsByClassName("dialog")[0].style.display="none";
        document.getElementsByClassName("frontground")[0].style.display="none";
    }
}

function universe_created() {
    if (this.readyState==4 && this.status==200) {
        var row = new Object();

        try{
            row['id'] = JSON.parse(this.responseText).id;
            if (row['id'] == 0) throw 0;
        } catch(e){
            $("#error_cu").fadeIn();
            document.getElementsByClassName("frontground")[0].style.display="none";
            setInterval(function(){$("#error_cu").fadeOut();},3000)
            return;
        }
        
        row['name'] = document.getElementById("universe_name").value.trim();
        row['description'] = document.getElementById("universe_description").value.trim();
        row['stars']=0;
        row['supernovas']=0;
        var d = new Date(); d.setMonth(new Date().getMonth()+2);
        row['year']=d.getFullYear();
        $("#universe-table").jqxGrid('addrow', null, row);
        
        document.getElementsByClassName("frontground")[0].style.display="none";
    }
}

window.onload=function(){
    function clear_text(){
        if(this.value=='Name' || this.value=='Description'){
            this.style.color = "#727272";
            this.style.backgroundColor = "#ffffff";
            this.value="";
        }        
    }
    document.getElementById("universe_name").onclick=clear_text;
    document.getElementById("universe_description").onclick=clear_text;
    document.getElementById("create_universe_btn").onclick=create_universe;
    load_universes();
    init_stars();
}