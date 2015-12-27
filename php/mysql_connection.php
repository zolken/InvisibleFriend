<?php

    $host = 'localhost';
    $database= 'zolken_invisiblefriend';
    $port = '3306';

    function get_connection(){
     
        global $host,$database,$port;

        $conn = new mysqli($host,'zolken_writer','wr1t3r',$database,$port);
        $conn->set_charset('utf8');
        
        return $conn;
    }
        
    function verify_loggin($id,$password){
        
        $conn = get_connection();
        $r = $conn->query("select id,status from users where id=".$id." and password='".$password."';");
        $st = 0;
        if($r && $r->num_rows>0){
            $st=$r->fetch_array(MYSQLI_ASSOC)['status'] == 1;
            if($st) $st=1; else $st=2;
        }
        $conn->close();
        return $st;
    }
    
    function get_user_data($id){
        
        $year = date('Y', strtotime("+3 months", strtotime(date('Y-m-d'))));
        
        $conn = get_connection();
        
        $r = $conn->query('select u.name name, u.email email, u.year year, un.name universe, u.first_time first_time, u.ready ready, u.admin admin,
                          l.list list, e.excluded exclusion
                          from zolken_invisiblefriend.users u
                          inner join zolken_invisiblefriend.universes un on un.id = u.universe 
                          left join zolken_invisiblefriend.lists l on u.id = l.user and l.year='.$year.'
                          left join zolken_invisiblefriend.exclusions e on u.id = e.user and e.year='.$year.'
                          where u.id='.$id.';');
        $json = null;
        if($r && $r->num_rows > 0) {
            $row = $r->fetch_array(MYSQLI_ASSOC);
            $row['name'] = base64_decode($row['name']);
            $row['email'] = base64_decode($row['email']);
            $json = json_encode($row);
        }
        $conn->close();
        return $json;
    }
    
?>
