<!DOCTYPE html>
<meta charset="utf-8"> 
<html>

<head>


<script language="JavaScript">
<!-- Hide from old browsers

   /* TIME Karsten has been kidnapped */
   window.onload=function() {
   // Month,Day,Year,Hour,Minute,Second
   upTime('feb,12,2014,03:00:00'); // ****** Change this line!
 }
   function upTime(countTo) {
     now = new Date();
     countTo = new Date(countTo);
     difference = (now-countTo);

     days=Math.floor(difference/(60*60*1000*24)*1);
     hours=Math.floor((difference%(60*60*1000*24))/(60*60*1000)*1);
     mins=Math.floor(((difference%(60*60*1000*24))%(60*60*1000))/(60*1000)*1);
     secs=Math.floor((((difference%(60*60*1000*24))%(60*60*1000))%(60*1000))/1000*1);
     
     // total seconds
     secs=Math.floor(difference/1000*1);

/*
     document.getElementById('days').firstChild.nodeValue = days;
     document.getElementById('hours').firstChild.nodeValue = hours;
     document.getElementById('minutes').firstChild.nodeValue = mins;
*/
     document.getElementById('seconds').firstChild.nodeValue = secs;
     

     clearTimeout(upTime.to);
     upTime.to=setTimeout(function(){ upTime(countTo); },1000);
   }


/* Show remaining time until the mac is temporarily removed from iptables */
var d = new Date();
var lockedSeconds = d.getMinutes() * 60 + d.getSeconds() + 2*60; // ****** Change this line!

setInterval(function () {
    var date = new Date();
    var seconds = date.getMinutes() * 60 + date.getSeconds();
    var result = lockedSeconds - seconds;
    if (result< 0)
      result = 0;
    document.getElementById('remaining').innerHTML = parseInt(result);

  }, 500) //calling it every 0.5 second to do a count down

// -->
</script>

</head>

<body>





<br><br><br>
<font size=8 color=red><center><b> Karsten er kidnappet </b></center></font>
 
<hr color="black" size="5" width="50%" noshade>

<center>

<font size=6 color=blue><b>
<p>Skal Karsten RÅDNE op på MG?</p>
</b></font>


I
<font size=4>
<div id="countup"  style="display:inline">
  <!--
  <p id="days">00</p>
  <p class="timeRefDays">days</p>
  <p id="hours">00</p>
  <p class="timeRefHours">hours</p>
  <p id="minutes">00</p>
  <p class="timeRefMinutes">minutes</p>
  -->
  <b><p id="seconds"  style="display:inline">00</p>
  <p class="timeRefSeconds"  style="display:inline">sekunder</p> </b>
</div>
</font>

har den Syriske guldhamster Karsten været kidnappet af Mellemgangen.<br>
Der er ingen tvivl om at Karsten nu må være så svag og afkræftet, at der<br>
kun kan være tale om kort tid før han tager varig skade.<br>
Derfor skal han hjem nu!

<br><br>
Terroristerne forlanger 11 billeter til AGF's kampe for at frigive Karsten.<br>
Det er urimeligt mange billetter og nu sættes hårdt mod hårdt.

<br><br>

<?php

require_once "functions.inc.php";   
require_once "dbconnect.inc.php";

function formattt($str){
  return '<tt>' . $str . '</tt>';
}

/* MySQL is case insensitive, unless you do a binary comparison - thus we don't need to
capitalize mac. mac-adr in table mac_info is capitalized */
$mac=get_mac();
$username = user_from_mac($mac,'mac_info');
$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";


$sql = "select expiry_date from karsten_block WHERE mac = '$mac' AND expiry_date > NOW()";

$result = mysql_query($sql,$db_dragon)
  or die(mysql_error());

/* if the mac is not present, insert it */
if (mysql_num_rows($result) == 0){
  $expiry_date =  date('Y-m-d H:i:s', time()+60*60); // expire in one hour
  $valid_from =  date('Y-m-d H:i:s', time()+ 2*60); // valid in 2 min  

  //$expiry_date =  date('Y-m-d H:i:s', time()+3*60); // expire in 3 min
  //$valid_from =  date('Y-m-d H:i:s', time()+ 60); // valid in 1 min  
  $sql= "INSERT INTO karsten_block (mac, expiry_date, valid_from, processed) VALUES ('$mac', '$expiry_date', '$valid_from', 0)";

  $result = mysql_query($sql,$db_dragon)
  or die(mysql_error());
}
/* else do nothing */


$sql = "
select department, name.name as name
  from grp, grp_user, user, name
where grp.grp_id = grp_user.grp_id
and grp.grp_id > 6000
and grp.grp_id < 6012
and user.user = '$username'
and user.name_id = name.name_id
and grp_user.user_id = user.user_id";
$result = mysql_query($sql,$db_loki);
$onerow = mysql_fetch_array($result);
$gang = $onerow['department'];
$name = $onerow['name'];

echo formattt($name) . ', du bor på ' . utf8_encode($gang) . ' ';

?>
og så længe du understøtter den terrorrede, vil dit internet<br>
være spærret to minutter hver time (Uhhh.....).<br>
Der er nu <div id="remaining"  style="display:inline"></div> sekunder tilbage i denne time - intet i forhold til hvad Karsten oplever.

<br><br>
<b>
#jesuiskarsten
</b>


<hr color="black" size="5" width="50%" noshade>
<img src="img/karsten_modif_profil.jpg" alt="Karstens som vi husker ham">
</center>


</body>
</html>
