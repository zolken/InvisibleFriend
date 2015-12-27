<?php

function send_welcome_mail($id){

    $boundary = "__imafuckinggodbaby";
    $eencoded = "";
    $epass = "";
    $uu = "";
    $name = "";

    include_once '../mysql_connection.php';

    $conn = get_connection();
    
    $res = $conn->query("select u.name,u.email,u.password,un.name uname from zolken_invisiblefriend.users u inner join zolken_invisiblefriend.universes un on un.id = u.universe where u.id=".$id.";");

    if($res && $res->num_rows>0){
        $row = $res->fetch_array(MYSQLI_ASSOC);
        $name = base64_decode($row['name']);
        $eencoded = $row['email'];
        $epass = $row['password'];
        $uu = $row['uname'];
    } else return false;
    
    $conn->close();
    
    $header="From:no-reply@ztools.tk\r\nX-PHP-Script: PHP ztools.tk\r\nContent-Type:multipart/alternative; boundary=$boundary\r\n\r\n";
    
    $plain="--$boundary\r\nContent-Type:text/plain; charset=\"UTF-8\"\r\nContent-Transfer-Encoding: 8bit\r\n\r\n".file_get_contents('../../pages/welcome_mail.txt')."\r\n";
    $plain=str_replace('#user#',$name,$plain);
    $html="--$boundary\r\nContent-Type:text/html; charset=\"UTF-8\"\r\nContent-Transfer-Encoding: 8bit\r\n\r\n".file_get_contents('../../pages/welcome_mail.html')."\r\n";
    $html=str_replace('#user#',$name,$html);   
    
    $link = "http://www.ztools.tk/php/users/active_user.php?id=".$id."&hash=".md5($eencoded.$epass);
    $unsub = "http://www.ztools.tk/php/users/unsub_user.php?id=".$id."&hash=".md5($eencoded.$epass);
    
    $plain=str_replace('#universe#',$uu,$plain);
    $plain=str_replace('#link#',$link,$plain);
    $plain=str_replace('#unsub#',$unsub,$plain);
    $html=str_replace('#universe#',$uu,$html);
    $html=str_replace('#link#',$link,$html);
    $html=str_replace('#unsub#',$unsub,$html);
    
    $rew = 5;
    
    while(!mail(base64_decode($eencoded),"Correu de confirmació",$plain.$html,$header) && $rew > 0){
        $rew--;
    }
    
    return $rew > 0;

}

?>