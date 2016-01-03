<?php

function send_raffle_mail($email,$name1,$name2,$id2,$list_text){

    $boundary = "__imafuckinggodbaby";
    
    $header="From:ma_inocent@ztools.tk\r\nX-PHP-Script: PHP ztools.tk\r\nContent-Type:multipart/alternative; boundary=$boundary\r\n\r\n";
    
    $plain="--$boundary\r\nContent-Type:text/plain; charset=\"UTF-8\"\r\nContent-Transfer-Encoding: 8bit\r\n\r\n".file_get_contents('../pages/raffle_mail.txt')."\r\n";
    $html="--$boundary\r\nContent-Type:text/html; charset=\"UTF-8\"\r\nContent-Transfer-Encoding: 8bit\r\n\r\n".file_get_contents('../pages/raffle_mail.html')."\r\n";

    
    $plain = str_replace('#user#',$name1,$plain);
    $plain = str_replace('#raffle#',$name2,$plain);
    $plain = str_replace('#letter-text#',$list_text,$plain);
    $html = str_replace('#user#',$name1,$html);
    $html = str_replace('#raffle#',$name2,$html);
    $year = date('Y', strtotime("+3 months", strtotime(date('Y-m-d'))));
    $image_link = 'www.ztools.tk/php/letter_visualizer.php?hash='.base64_encode($id2.'&'.$year);
    $html = str_replace('#letter_image#',$image_link,$html);
    $rew = 5;
    
    while(!mail($email,"Sorteig de l'Amic Invisible",$plain.$html,$header) && $rew > 0){
        $rew--;
    }
    
    return $rew > 0;

}

?>