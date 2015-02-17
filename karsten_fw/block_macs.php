#!/usr/bin/php
<?php

   /* Paw - Feb 2015 
    * Block macs based on array of users
    */


require_once('dbconnect.inc.php');


$debug = 0;

# command for iptables

$cmd = "iptables -t nat -I PREROUTING -p tcp -m mac --mac-source %s  -j DNAT --to-destination 172.16.0.12\n";


// get blocked macs from the db

/*
echo "\n";
echo 'gang=>Alle ';
echo 'gang=6001>Cosmos ';
echo 'gang=6002>Under ';
echo 'gang=6003>Bers&aelig;rk ';
echo 'gang=6004>Kannibal ';
echo 'gang=6005>Mellem ';
echo 'gang=6006>Barbar ';
echo 'gang=6007>Abort ';
echo 'gang=6008>Pharis ';
echo 'gang=6009>Dyt ';
echo 'gang=6010>Psyko ';
echo 'gang=6011>IVde ';
echo "\n";
*/

$gang = 6005;
$sql_query = "SELECT name.name_id, name.name, name.room, name.phone, name.title, name.status "
  . "FROM name,grp_room "
  . "WHERE "
  . "(name.status='normal' OR name.status='fremlejer' OR name.status='udlejer') "
  . "AND  name.room = grp_room.room "
  . "AND  grp_room.grp_id = '$gang' "
  . "ORDER BY name.room";


$result = mysql_query($sql_query,$db_loki)
    or die(mysql_error());
$rc = mysql_numrows($result);
echo ' antal beboere: ' . $rc . "\n";

/* Get the username from name-id. */
$users = array();
while ($current_row = mysql_fetch_array($result)) {
  
  $res = mysql_query("SELECT user FROM user WHERE user.name_id=" . $current_row['name_id'],$db_loki)
    or die(mysql_error());
  if (mysql_numrows($res))
     $users[] = mysql_result($res,'user');
}

/* Get macs from each username */
$macs = array();
foreach($users as $user) {
  $sql_query = "SELECT mac FROM mac_info WHERE user = '$user'";
  $result = mysql_query($sql_query,$db_dragon)
    or die(mysql_error());
  while ($current_row = mysql_fetch_array($result)) {
    $macs[] = $current_row['mac'];
  } 
}
 

// now we have an array of macs to block - now do it
foreach($macs as $mac){
  /* add macs to db-table in order to keep track for long they are
     blocked */
    $out .= sprintf($cmd,$mac); // make the actual iptables command to insert rules
}



if($debug)
    print $out;
else{
    `$out`; // execute everything in $out
}


?>
