<?php

ini_set('display_errors', 1);

    if(!isset($_GET['hash'])) exit;
    
    $id = base64_decode($_GET['hash']);
    
    include 'mysql_connection.php';
    
    $conn = get_connection();
    
    $r = $conn->query('select image from zolken_invisiblefriend.lists where user='.$id.';');
    if($r){
        $item = $r->fetch_array(MYSQLI_ASSOC);
        header('Content-Type: image/png');
        if(!is_null($item['image']) && $item['image'] != ""){
            $data = explode(',', $item['image']);
            echo base64_decode($data[1]);
        }else{
            $im = imagecreatetruecolor(400, 50);
            $text_color = imagecolorallocate($im, 233, 14, 91);
            imagestring($im, 1, 5, 5,  'No s\'ha pogut carregar l\'imatge...', $text_color);
            imagejpeg($im);
            imagedestroy($im);
        }
        
    }
    
    $conn->close();

?>