#!/usr/bin/php
<?php
// Paw

// Must be run (by rc.firewall) on boot) and periodically by cron


require_once('dbconnect.inc.php');


$debug = 0;

# command for iptables

$cmd_del = "iptables -t nat -D PREROUTING -p tcp -m mac --mac-source %s  -j DNAT --to-destination 172.16.0.12\n";
$cmd_ins = "iptables -t nat -I PREROUTING -p tcp -m mac --mac-source %s  -j DNAT --to-destination 172.16.0.12\n";



$macs = array();

/* unblock all seen macs */
$sql_query = "SELECT mac, valid_from FROM karsten_block WHERE expiry_date > NOW() AND valid_from < NOW() AND processed = 0";


$result = mysql_query($sql_query)
    or die(mysql_error());

$file = '/etc/firewall/karsten/removed_macs.txt';
while ($current_row = mysql_fetch_array($result)) {
  $macs[] = $current_row['mac'];

  $mac = $current_row['mac'];
  $username = user_from_mac($mac,'mac_info');
  $log = sprintf("%s \t %s \t %s \n",$mac, $current_row['valid_from'], $username);
  file_put_contents($file, $log , FILE_APPEND);
}


// now we have an array of macs to unblock - now do it
foreach($macs as $mac){
    $out .= sprintf($cmd_del,$mac); // make the actual iptables command to insert rules
}



if($debug)
    print $out;
else{

    `$out`; // execute everything in $out

    /* update the db */
    $valid_from =  date('Y-m-d H:i:s', time() - 60); // valid in 5 min
    $sql_query = "update karsten_block set processed = '1' WHERE valid_from < '$valid_from'";
    $result = mysql_query($sql_query)
      or die(mysql_error());

}




/* delete and insert into iptables again  */

$sql_query = "select mac FROM karsten_block WHERE expiry_date < NOW()";
$result = mysql_query($sql_query)
    or die(mysql_error());

$macs2 = array();
while ($current_row = mysql_fetch_array($result)) {
	$macs2[] = $current_row['mac'];
}

// now we have an array of macs to block - now do it
$out ='';
foreach($macs2 as $mac){
    $out .= sprintf($cmd_ins,$mac); // make the actual iptables command to insert rules
}



if($debug)
    print $out;
else{
    `$out`; // execute everything in $out

    $sql_query = "DELETE FROM karsten_block WHERE expiry_date < NOW()";
    $result = mysql_query($sql_query)
      or die(mysql_error());
}


?>
