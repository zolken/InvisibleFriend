<?php

    ini_set('display_errors', 1);

    $simulation = $_GET['simulate']; //////////////// POST
    $universe = $_GET['universe'];
    $year = $_GET['year'];
    
    $ids = array();
    
    function send_mail($rem,$rew){
        
        global $year;
        
        $conn = get_connection();
        
        if($conn->multi_query("select u.name name1, u.email email
					 from zolken_invisiblefriend.users u
                     where u.id=".$rem.";
					 select u.id id2, u.name name2,l.list
					 from zolken_invisiblefriend.users u
					 left join zolken_invisiblefriend.lists l on l.user = u.id
					 where u.id=".$rew.";")){
			if($result = $conn->store_result()){
			  $info1 = $result->fetch_array(MYSQLI_ASSOC);
			} else return false;
			
			$conn->next_result();
			
			if($result = $conn->store_result()){
			  $info2= $result->fetch_array(MYSQLI_ASSOC);
			} else return false;
		  
		} else return false;

        send_raffle_mail(base64_decode($info1['email']),base64_decode($info1['name1']),base64_decode($info2['name2']),$info2['id2'],str_replace(';','\n',$info2['list']));
        
        $conn->close();
        
        return true;
    }
    
    /*              Get all information             */
    
    include_once "mysql_connection.php";
    
    $conn = get_connection();
    
    $query = "select u.id id, e.excluded eid
              from zolken_invisiblefriend.users u
              left join zolken_invisiblefriend.exclusions e
                on e.user = u.id and e.year = ".$year."
              where u.universe = ".$universe." and ready = 1;";
    
    $r = $conn->query($query);
    if(!$r) {echo "-1"; exit(1);}
    while($row = $r->fetch_array(MYSQLI_ASSOC)){
        $ids[$row['id']] = $row['eid'];
    }
    
	$conn->close();
    
    /*              Raffle Part             */
    
    $keys = array_keys($ids);
          
    $ig = 0;
    
   while($ig < 10){
    
        shuffle($keys);    
        $raffle = array_keys($ids);
        $final = array();
    
        for($i=0; $i < sizeof($keys); $i++){
            $intents = 0; $random=0;
            do{
                $random = rand(0,sizeof($raffle)-1);
                $intents++;
            } while(($keys[$i]==$raffle[$random] || $raffle[$random] == $ids[$keys[$i]]) && $intents < 10);
            if($intents<10) {
                $final[$keys[$i]]=$raffle[$random];
                unset($raffle[$random]);
                $raffle = array_values($raffle);
            }
            else { break;}
            //$exit = 0;
        }
        if(count($final)==count($keys)) break;
        ++$ig;
        
    }
    if(count($final)==count($keys)) echo '{"raffle":1,"mail":'; else {
        echo '{"raffle":0,"mail":0}';
        exit;
    }
    ksort($final);
	
    
    /*          Send Mails          */
    
    if(!$simulation) {
		include_once 'users/raffle_mail.php';
        $fkeys = array_keys($final);
        $status = true;
		$conn = get_connection();
        for($i=0; $i < sizeof($final) && $status; $i++){
            $status &= send_mail($fkeys[$i],$final[$fkeys[$i]]);
			if(!$status || !$conn->query('insert into raffles values ('.$universe.','.$fkeys[$i].','.$final[$fkeys[$i]].','.$year.');')) break;            
        }
        if($status) echo '1}'; else echo '0}';
    } else {
	  print_r($final);
	}  
    

?>