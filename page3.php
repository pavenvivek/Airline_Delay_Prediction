<?php

/*************************************
page3.php

[-] Displays the detailed version of delay records for given carrier

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

//contents($_SESSION['carriers']);

if (isset($_GET["carrier"]))
{
   // Checking for valid carriers using regex pattern matching
   if (preg_match('/[^a-z0-9]/i', $_GET["carrier"]))
   {
      echo '<b> !!! Warning !!! Invalid Carrier in URL ! Changing to Default ' . $_SESSION['carriers'][0] . ' </b> <br> <br>';
      $_GET["carrier"] = $_SESSION['carriers'][0];
   }

   $value = array_search($_GET["carrier"], $_SESSION['carriers']);

   if (($_GET["carrier"] != $_SESSION['carriers'][0]) && (NULL == $value))
   {
      echo '<b> !!! Warning !!! Invalid Carrier in URL ! Changing to Default ' . $_SESSION['carriers'][0] . ' </b> <br> <br>';
      $_GET["carrier"] = $_SESSION['carriers'][0];
   }
}

if (isset($_GET["end"]))
{
   if ($_GET["end"] != '#')
   {
      echo "<b> !!! Warning !!! Attempt to tamper the valid URL ! </b> <br> <br>";
   }
}

$airline_record = $_SESSION['airline_record'][$_GET['carrier']];

echo "<h4> Delay Analysis for Carrier " . $_GET['carrier'] . " flying from [ " . $airline_record[0]['org_city'] . "  >>>>>>>>>> to >>>>>>>>>>  " . $airline_record[0]['dest_city'] . " ]</h4>";
//echo "<hr>";  

$max_dept_delay = 0;
$max_arr_delay = 0;
$max_carrier_delay = 0;
$max_weather_delay = 0;
$max_nas_delay = 0;
$max_security_delay = 0;
$max_aircraft_delay = 0;
$max_diverted_delay = 0;

$total_dept_delay = 0;
$total_arr_delay = 0;
$total_carrier_delay = 0;
$total_weather_delay = 0;
$total_nas_delay = 0;
$total_security_delay = 0;
$total_aircraft_delay = 0;
$total_diverted_delay = 0;

// Loop to calculate maximum delay instances
for ($i = 0; $i < count($airline_record); $i++)
{
   if ($airline_record[$i]['dep_delay'] > $max_dept_delay)
   {
      $max_dept_delay = $airline_record[$i]['dep_delay'];
   }

   if ($airline_record[$i]['arr_delay'] > $max_arr_delay)
   {
      $max_arr_delay = $airline_record[$i]['arr_delay'];
   }

   if ($airline_record[$i]['carrier_delay'] > $max_carrier_delay)
   {
      $max_carrier_delay = $airline_record[$i]['carrier_delay'];
   }

   if ($airline_record[$i]['weather_delay'] > $max_weather_delay)
   {
      $max_weather_delay = $airline_record[$i]['weather_delay'];
   }

   if ($airline_record[$i]['nas_delay'] > $max_nas_delay)
   {
      $max_nas_delay = $airline_record[$i]['nas_delay'];
   }

   if ($airline_record[$i]['security_delay'] > $max_security_delay)
   {
      $max_security_delay = $airline_record[$i]['security_delay'];
   }

   if ($airline_record[$i]['aircraft_delay'] > $max_aircraft_delay)
   {
      $max_aircraft_delay = $airline_record[$i]['aircraft_delay'];
   }

   if ($airline_record[$i]['diverted_delay'] > $max_diverted_delay)
   {
      $max_diverted_delay = $airline_record[$i]['diverted_delay'];
   }

   $total_dept_delay += $airline_record[$i]['dep_delay'];
   $total_arr_delay += $airline_record[$i]['arr_delay'];
   $total_carrier_delay += $airline_record[$i]['carrier_delay'];
   $total_weather_delay += $airline_record[$i]['weather_delay'];
   $total_nas_delay += $airline_record[$i]['nas_delay'];
   $total_security_delay += $airline_record[$i]['security_delay'];
   $total_aircraft_delay += $airline_record[$i]['aircraft_delay'];
   $total_diverted_delay += $airline_record[$i]['diverted_delay'];
}

$min_dept_delay = $max_dept_delay;
$min_arr_delay = $max_arr_delay;
$min_carrier_delay = $max_carrier_delay;
$min_weather_delay = $max_weather_delay;
$min_nas_delay = $max_nas_delay;
$min_security_delay = $max_security_delay;
$min_aircraft_delay = $max_aircraft_delay;
$min_diverted_delay = $max_diverted_delay;

// Loop to calculate minimum delay instances
for ($i = 0; $i < count($airline_record); $i++)
{
   if ($airline_record[$i]['dep_delay'] < $min_dept_delay && ($airline_record[$i]['dep_delay'] != 0))
   {
      $min_dept_delay = $airline_record[$i]['dep_delay'];
   }

   if ($airline_record[$i]['arr_delay'] < $min_arr_delay && ($airline_record[$i]['arr_delay'] != 0))
   {
      $min_arr_delay = $airline_record[$i]['arr_delay'];
   }

   if ($airline_record[$i]['carrier_delay'] < $min_carrier_delay && ($airline_record[$i]['carrier_delay'] != 0))
   {
      $min_carrier_delay = $airline_record[$i]['carrier_delay'];
   }

   if ($airline_record[$i]['weather_delay'] < $min_weather_delay && ($airline_record[$i]['weather_delay'] != 0))
   {
      $min_weather_delay = $airline_record[$i]['weather_delay'];
   }

   if ($airline_record[$i]['nas_delay'] < $min_nas_delay && ($airline_record[$i]['nas_delay'] != 0))
   {
      $min_nas_delay = $airline_record[$i]['nas_delay'];
   }

   if ($airline_record[$i]['security_delay'] < $min_security_delay && ($airline_record[$i]['security_delay'] != 0))
   {
      $min_security_delay = $airline_record[$i]['security_delay'];
   }

   if ($airline_record[$i]['aircraft_delay'] < $min_aircraft_delay && ($airline_record[$i]['aircraft_delay'] != 0))
   {
      $min_aircraft_delay = $airline_record[$i]['aircraft_delay'];
   }

   if ($airline_record[$i]['diverted_delay'] < $min_diverted_delay && ($airline_record[$i]['diverted_delay'] != 0))
   {
      $min_diverted_delay = $airline_record[$i]['diverted_delay'];
   }

}

// Delay Records display

echo "<b> Departure Delay </b> <hr>";
echo "Max Departure Delay : " . $max_dept_delay . " <br> ";
echo "Min Departure Delay : " . $min_dept_delay . " <br> ";
echo "Average Departure Delay : " . ($total_dept_delay/count($airline_record)) . " <br> <br> ";
//echo "<hr>";

echo "<b> Arrival Delay </b> <hr>"; 
echo "Max Arrival Delay : " . $max_arr_delay . " <br> ";
echo "Min Arrival Delay : " . $min_arr_delay . " <br> ";
echo "Average Arrival Delay : " . ($total_arr_delay/count($airline_record)) . " <br> <br> ";
//echo "<hr>";

echo "<b> Carrier Delay </b> <hr>";
echo "Max Carrier Delay : " . $max_carrier_delay . " <br> ";
echo "Min Carrier Delay : " . $min_carrier_delay . " <br> ";
echo "Average Carrier Delay : " . ($total_carrier_delay/count($airline_record)) . " <br> <br> ";
//echo "<hr>";

echo "<b> Weather Delay </b> <hr>";
echo "Max Weather Delay : " . $max_weather_delay . " <br> ";
echo "Min Weather Delay : " . $min_weather_delay . " <br> ";
echo "Average Weather Delay : " . ($total_weather_delay/count($airline_record)) . " <br> <br> ";
//echo "<hr>";

echo "<b> NAS Delay </b> <hr> ";
echo "Max NAS Delay : " . $max_nas_delay . " <br> ";
echo "Min NAS Delay : " . $min_nas_delay . " <br> ";
echo "Average NAS Delay : " . ($total_nas_delay/count($airline_record)) . " <br> <br> ";
//echo "<hr>";

echo "<b> Security Delay </b> <hr> ";
echo "Max Security Delay : " . $max_security_delay . " <br> ";
echo "Min Security Delay : " . $min_security_delay . " <br> ";
echo "Average Security Delay : " . ($total_security_delay/count($airline_record)) . " <br> <br> ";
//echo "<hr>";

echo "<b> Late Aircraft Delay </b> <hr> ";
echo "Max Late Aircraft Delay : " . $max_aircraft_delay . " <br> ";
echo "Min Late Aircraft Delay : " . $min_aircraft_delay . " <br> ";
echo "Average Late Aircraft Delay : " . ($total_aircraft_delay/count($airline_record)) . " <br> <br> ";
//echo "<hr>";

echo "<b> Diversion Delay </b> <hr> ";
echo "Max Diversion Delay : " . $max_diverted_delay . " <br> ";
echo "Min Diversion Delay : " . $min_diverted_delay . " <br> ";
echo "Average Diversion Delay : " . ($total_diverted_delay/count($airline_record)) . " <br> <br> ";
//echo "<hr>";

echo '<form method = "POST" action="'. $_SESSION['previous_page_2'] .'">';
echo '<input type = "Submit" name = "submit" value="Back"> <br>';

echo "</body>
      </html>";

?>
