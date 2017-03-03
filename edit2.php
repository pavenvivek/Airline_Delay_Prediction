<?php

/*************************************
edit.php

[-] For editing records

**************************************/

session_save_path('/home/pvivekan/session');
session_start();

//require('/home/pvivekan/things.php');
//include('./cas.php');
require('./functions.php');

echo "<html>";
echo "<head>";
echo "<style>";
echo "body { background-color: #e6e6e6; 
             font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; }
      h2   { background-color: #d0d0e1;
             font-family: 'Trebuchet MS', Helvetica, sans-serif; }
      h3   { font-family: 'Trebuchet MS', Helvetica, sans-serif; }";
echo "</style>";
echo "</head>";
echo "<body>";
echo '<h2 style = "text-align: center; border-style : inset; border-width : 2px;"> Airline Performance Reporter </h2>';

//cas_authenticate();
 
//$username = $_SESSION['user'];
 
//echo "<b> Username </b> : " . $username . "<br>";
//CHANGE THIS LIST TO THE USERS YOU'D LIKE TO HAVE ACCESS
/*$users = array("pvivekan", "localhost", "paven");
if(!in_array($username, $users)){
  die("<b> Sorry you do not have access to this page ! </b>");
}*/


if (isset($_GET["line"]))
{
   $_SESSION["line"] = $_GET["line"];
}

//echo "line is " . $_SESSION["line"];

if (isset($_GET["line"]))
{
   // Checking for valid carriers using regex pattern matching
   if (preg_match('/[^0-9]/i', $_GET["line"]))
   {
      die ('<b> !!! Warning !!! Invalid Record number in URL ! Cannot edit </b> <br> <br>');
   }
}

echo '<h3> Edit the following record </h3> <hr>';

//echo "line is " . $_GET["line"] . "<br>";
$filename = $_SESSION['file'];
$file = explode(".", $filename);
$mfile = $file[0] . "_2.csv";

if (isset($_GET["line"]))
{
   echo "<b> Record # : " . $_GET["line"] . " <br>";
}
else
{
   echo "<b> Record # : <br>";
}

if (isset($_GET["line"]))
{
   contents($_SESSION['record'][$_GET["line"]]);
   echo "</b>";
   echo "<br>";

   $_SESSION['mrecord'] = $_SESSION['record'][$_GET["line"]];
}
else
{
   $mrecord = $_SESSION['mrecord'];

   $mrecord['DEP_DELAY_NEW'] = $_POST["dep_delay"];
   $mrecord['ARR_DELAY_NEW'] = $_POST["arriv_delay"];
   $mrecord['CARRIER_DELAY'] = $_POST["carrier_delay"];
   $mrecord['WEATHER_DELAY'] = $_POST["weather_delay"];
   $mrecord['NAS_DELAY'] = $_POST["nas_delay"];
   $mrecord['SECURITY_DELAY'] = $_POST["sec_delay"];
   $mrecord['LATE_AIRCRAFT_DELAY'] = $_POST["aircraft_delay"];
   $mrecord['DIV_ARR_DELAY'] = $_POST["div_delay"];
   //$mrecord[22] = $_SESSION["line"];

   contents($mrecord);
}

if (!empty($_POST))
{
   $year = $_POST['eyear'];
   $month = $_POST['emonth'];
   $carrier = $_POST['ecarrier'];
}
else if (!empty($_GET))
{
   $year = $_GET['year'];
   $month = $_GET['month'];
   $carrier = $_GET['carrier'];
}

if (empty($_POST))
{
   echo '<h3> Provide new values for the following delay fields </h3> <hr> <br>';

   echo '<form method="POST" action="'.$_SERVER['PHP_SELF'].'">';
   echo '<table>';
   echo '<tr> <td> DEP_DELAY_NEW : </td> <td> <input type="text" name="dep_delay" value = 0> </td> </tr>';
   echo '<tr> <td> ARR_DELAY_NEW : </td> <td> <input type="text" name="arriv_delay" value = 0> </td> </tr>';
   echo '<tr> <td> CARRIER_DELAY : </td> <td> <input type="text" name="carrier_delay" value = 0> </td> </tr>';
   echo '<tr> <td> WEATHER_DELAY : </td> <td> <input type="text" name="weather_delay" value = 0> </td> </tr>';
   echo '<tr> <td> NAS_DELAY : </td> <td> <input type="text" name="nas_delay" value = 0> </td> </tr>';
   echo '<tr> <td> SECURITY_DELAY : </td> <td> <input type="text" name="sec_delay" value = 0> </td> </tr>';
   echo '<tr> <td> LATE_AIRCRAFT_DELAY : </td> <td> <input type="text" name="aircraft_delay" value = 0> </td> </tr>';
   echo '<tr> <td> DIV_ARR_DELAY : </td> <td> <input type="text" name="div_delay" value = 0> </td> </tr>';
   echo "</table>";
   echo "<br>";
   echo '<input type = "hidden" name = "eyear" value = "' . $year . '">';
   echo '<input type = "hidden" name = "emonth" value = "' . $month . '">';
   echo '<input type = "hidden" name = "ecarrier" value = "' . $carrier . '">';
   echo '<input type = "Submit" name = "submit" value="Update"> <br>';
   echo '</form>';
}
else
{
   echo "<br> <b> Record Updated Successfully ! </b> <br> <br>";
}

if (!empty($_POST))
{
   if (!file_exists($mfile))
   {
	$fp = fopen($mfile, 'w');
	//$header = array("YEAR", "MONTH", "FL_DATE", "UNIQUE_CARRIER", "AIRLINE_ID", "CARRIER", "ORIGIN_AIRPORT_ID", "ORIGIN", "ORIGIN_CITY_NAME", "DEST_AIRPORT_ID", 
	//		"DEST", "DEST_CITY_NAME", "DEP_DELAY_NEW", "ARR_DELAY_NEW", "CANCELLED", "DISTANCE", "CARRIER_DELAY", "WEATHER_DELAY", "NAS_DELAY", "SECURITY_DELAY",
	//		"LATE_AIRCRAFT_DELAY", "DIV_ARR_DELAY"); 
	//fputcsv($fp, $header);
	fclose($fp);
   }

   $mrecord2 = array_values($_SESSION['mrecord']);
   //contents($mrecord2);

   $mrecord2[12] = $_POST["dep_delay"];
   $mrecord2[13] = $_POST["arriv_delay"];
   $mrecord2[16] = $_POST["carrier_delay"];
   $mrecord2[17] = $_POST["weather_delay"];
   $mrecord2[18] = $_POST["nas_delay"];
   $mrecord2[19] = $_POST["sec_delay"];
   $mrecord2[20] = $_POST["aircraft_delay"];
   $mrecord2[21] = $_POST["div_delay"];
   $mrecord2[22] = $_SESSION["line"];

   //contents($mrecord2);

   $fp = fopen($mfile, 'r+');

   //echo "comes here 1 <br>";
   if (0 == filesize($mfile))
   {
      //echo "comes here 2 <br>";
      fputcsv($fp, $mrecord2);
   }
   else
   {
      $i = 0;
      $flag = 0;
      $arr = array();
      $prev = $fp;

      $fp = fopen($mfile, 'r');

      while($x = fgetcsv($fp)) 
      {
	 //echo "comes here 3 <br>";
	 $arr[$i] = $x;
	 $i++;
      }

      fclose($fp);

      //contents($arr);

      $fp = fopen($mfile, 'w');

      for ($i = 0; $i < count($arr); $i++)
      {
	 //echo "arr[$i][22] == mrecord2[22] => " . $arr[$i][22] . " and " . $mrecord2[22] . "<br>"; 
         if ($arr[$i][22] == $mrecord2[22])
	 {
            fputcsv($fp, $mrecord2);
	    $flag = 1;
	 }
	 else
	 {
	    fputcsv($fp, $arr[$i]);
	 }
      }

      if ($flag == 0)
      {
	 fputcsv($fp, $mrecord2);
      }

      fclose($fp);
   }

   //fclose($fp);
}

/*$records = array();
$index = array();

for ($i = 0; $i < count($airline_data); $i++)
{
   if ($airline_data[$i]['CARRIER'] == $_POST["ecarrier"])
   {
      $records[] = $airline_data[$i];
      $index[] = $i;
   }
}*/

echo '<form method = "POST" action="'. $_SESSION['previous_page_3'] .'">';
echo '<input type = "hidden" name = "eyear" value = "' . $year . '">';
echo '<input type = "hidden" name = "emonth" value = "' . $month . '">';
echo '<input type = "hidden" name = "ecarrier" value = "' . $carrier . '">';
echo '<input type = "hidden" name = "back" value = "back">';
echo '<input type = "Submit" name = "submit" value="Back"> <br>';
echo '</form>';
echo "</body>
      </html>";

?>
