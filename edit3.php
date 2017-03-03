<?php

/*************************************
edit3.php

[-] For editing records

**************************************/

session_save_path('/home/pvivekan/session');
session_start();

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


if (isset($_GET["index"]))
{
   $_SESSION["index"] = $_GET["index"];
}

//echo "line is " . $_SESSION["line"];

if (isset($_GET["index"]))
{
   // Checking for valid carriers using regex pattern matching
   if (preg_match('/[^0-9]/i', $_GET["index"]))
   {
      die ('<b> !!! Warning !!! Invalid Record number in URL ! Cannot edit </b> <br> <br>');
   }
}

echo '<h3> Add New Record </h3> <hr>';

//echo "line is " . $_GET["line"] . "<br>";
$filename = $_SESSION['file'];
$file = explode(".", $filename);
$mfile = $file[0] . "_2.csv";

if (isset($_GET["index"]))
{
   echo "<b> Record # : " . $_GET["index"] . " <br>";
}
else
{
   echo "<b> Record # : <br>";
}

/*if (isset($_GET["line"]))
{
   contents($_SESSION['record'][$_GET["line"]]);
   echo "</b>";
   echo "<br>";

   $_SESSION['mrecord'] = $_SESSION['record'][$_GET["line"]];
}*/

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

if (!isset($_GET["index"]))
{
   //$mrecord2 = array_values($_SESSION['mrecord']);
   //contents($mrecord2);

   $mrecord2['YEAR'] = $year;
   $mrecord2['MONTH'] = $month;
   $mrecord2['FL_DATE'] = $_POST["date"];
   $mrecord2['UNIQUE_CARRIER'] = $_POST["ucarr"];
   $mrecord2['AIRLINE_ID'] = $_POST["airline_id"];
   $mrecord2['CARRIER'] = $_POST["carr"];
   $mrecord2['ORIGIN_AIRPORT_ID'] = 12478;
   $mrecord2['ORIGIN'] = "JFK";
   $mrecord2['ORIGIN_CITY_NAME'] = "New York, NY";
   $mrecord2['DEST_AIRPORT_ID'] = $_POST["dest_id"];
   $mrecord2['DEST'] = $_POST["dest_airport"];
   $mrecord2['DEST_CITY_NAME'] = $_POST["dest_city"];
   $mrecord2['DEP_DELAY_NEW'] = $_POST["dep_delay"];
   $mrecord2['ARR_DELAY_NEW'] = $_POST["arriv_delay"];
   $mrecord2['CANCELLED'] = $_POST["cancel"];
   $mrecord2['DISTANCE'] = $_POST["distance"];
   $mrecord2['CARRIER_DELAY'] = $_POST["carrier_delay"];
   $mrecord2['WEATHER_DELAY'] = $_POST["weather_delay"];
   $mrecord2['NAS_DELAY'] = $_POST["nas_delay"];
   $mrecord2['SECURITY_DELAY'] = $_POST["sec_delay"];
   $mrecord2['LATE_AIRCRAFT_DELAY'] = $_POST["aircraft_delay"];
   $mrecord2['DIV_ARR_DELAY'] = $_POST["div_delay"];

   contents($mrecord2);
}

/*
    [YEAR] => 2015
    [MONTH] => 1
    [FL_DATE] => 2015-01-01
    [UNIQUE_CARRIER] => AA
    [AIRLINE_ID] => 19805
    [CARRIER] => AA
    [ORIGIN_AIRPORT_ID] => 12478
    [ORIGIN] => JFK
    [ORIGIN_CITY_NAME] => New York, NY
    [DEST_AIRPORT_ID] => 12892
    [DEST] => LAX
    [DEST_CITY_NAME] => Los Angeles, CA
    [DEP_DELAY_NEW] => 0
    [ARR_DELAY_NEW] => 0
    [CANCELLED] => 0
    [DISTANCE] => 2475
    [CARRIER_DELAY] => 0
    [WEATHER_DELAY] => 0
    [NAS_DELAY] => 0
    [SECURITY_DELAY] => 0
    [LATE_AIRCRAFT_DELAY] => 0
    [DIV_ARR_DELAY] => 0
*/

if (empty($_POST))
{
   echo '<h3> Provide values for the following fields : </h3> ';

   echo '<form method="POST" action="'.$_SERVER['PHP_SELF'].'">';
   echo '<table>';
   echo '<tr> <td> YEAR : </td> <td> '. $year .' </td> </tr>';
   echo '<tr> <td> MONTH : </td> <td> '. $month .' </td> </tr>';
   echo '<tr> <td> FL_DATE : </td> <td> <input type="text" name="date" value = "2015-01-01"> </td> </tr>';
   echo '<tr> <td> UNIQUE_CARRIER : </td> <td> <input type="text" name="ucarr" value = "AA"> </td> </tr>';
   echo '<tr> <td> AIRLINE_ID : </td> <td> <input type="text" name="airline_id" value = 19805> </td> </tr>';
   echo '<tr> <td> CARRIER : </td> <td> <input type="text" name="carr" value = "AA"> </td> </tr>';
   echo '<tr> <td> ORIGIN_AIRPORT_ID : </td> <td> 12478 </td> </tr>';
   echo '<tr> <td> ORIGIN : </td> <td> JFK </td> </tr>';
   echo '<tr> <td> ORIGIN_CITY_NAME : </td> <td> New York, NY </td> </tr>';
   echo '<tr> <td> DEST_AIRPORT_ID : </td> <td> <input type="text" name="dest_id" value = 12892> </td> </tr>';
   echo '<tr> <td> DEST : </td> <td> <input type="text" name="dest_airport" value = "LAX"> </td> </tr>';
   echo '<tr> <td> DEST_CITY_NAME : </td> <td> <input type="text" name="dest_city" value = "Los Angeles, CA"> </td> </tr>';
   echo '<tr> <td> DEP_DELAY_NEW : </td> <td> <input type="text" name="dep_delay" value = 0> </td> </tr>';
   echo '<tr> <td> ARR_DELAY_NEW : </td> <td> <input type="text" name="arriv_delay" value = 0> </td> </tr>';
   echo '<tr> <td> CANCELLED : </td> <td> <input type="text" name="cancel" value = 0> </td> </tr>';
   echo '<tr> <td> DISTANCE : </td> <td> <input type="text" name="distance" value = 0> </td> </tr>';
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
   echo '<input type = "Submit" name = "submit" value="Add Record"> <br>';
   echo '</form>';
}
else
{
   echo "<br> <b> Above Record Added Successfully ! </b> <br> <br>";
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

   $mrecord2[0] = $year;
   $mrecord2[1] = $month;
   $mrecord2[2] = $_POST["date"];
   $mrecord2[3] = $_POST["ucarr"];
   $mrecord2[4] = $_POST["airline_id"];
   $mrecord2[5] = $_POST["carr"];
   $mrecord2[6] = 12478;
   $mrecord2[7] = "JFK";
   $mrecord2[8] = "New York, NY";
   $mrecord2[9] = $_POST["dest_id"];
   $mrecord2[10] = $_POST["dest_airport"];
   $mrecord2[11] = $_POST["dest_city"];
   $mrecord2[12] = $_POST["dep_delay"];
   $mrecord2[13] = $_POST["arriv_delay"];
   $mrecord2[14] = $_POST["cancel"];
   $mrecord2[15] = $_POST["distance"];
   $mrecord2[16] = $_POST["carrier_delay"];
   $mrecord2[17] = $_POST["weather_delay"];
   $mrecord2[18] = $_POST["nas_delay"];
   $mrecord2[19] = $_POST["sec_delay"];
   $mrecord2[20] = $_POST["aircraft_delay"];
   $mrecord2[21] = $_POST["div_delay"];
   $mrecord2[22] = $_SESSION["index"];

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
