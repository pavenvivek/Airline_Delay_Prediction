<?php

/*************************************
page2.php

[-] Displays the delay records for given destination

**************************************/

session_save_path('/home/pvivekan/session');
session_start();

//require('/home/pvivekan/things.php');
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

$mod_array = get_edited_data($_SESSION["filename"]);

$fp = fopen($_SESSION["filename"], 'r');
$airline_data = array();
$headers = fgetcsv($fp);
$i = 0;

while($x = fgetcsv($fp)) 
{
   $airline_data[$i] = array_combine($headers, $x);

   if (NULL != $mod_array)
   {
      for ($j = 0; $j < count($mod_array); $j++)
      {
         if (isset($mod_array[$j][22]) && ($mod_array[$j][22] == $i))
         {
	    //echo "Before modifying <br>";
	    //contents($airline_data[$i]);
            array_pop($mod_array[$j]);
	    $airline_data[$i] = array_combine($headers, $mod_array[$j]);
	    //echo "After modifying <br>";
	    //contents($airline_data[$i]);

            break;
	 }
      }
   }

   $i++;
}

fclose($fp);

$mod_array = get_edited_data($_SESSION["filename"]);
$added_arr = get_added_array($mod_array, count($airline_data));

$added_new = array();

for ($k = 0; $k < count($added_arr); $k++)
{
   array_pop($added_arr[$k]);
   $added_new[] = array_combine($headers, $added_arr[$k]);
}

$airline_data = array_merge($airline_data, $added_new);

//contents($airline_data);

if (isset($_GET["end"]))
{
   if ($_GET["end"] != '#')
   {
      echo "<b> !!! Warning !!! Attempt to tamper the valid URL ! </b> <br> <br>";
   }
}

// Check for valid destination in the GET Request
if (isset($_GET["destination"]))
{
   // Checking for valid destinations using regex
   if (preg_match('/[^a-z, \/]/i', $_GET["destination"]))
   {
      echo '<b> !!! Warning !!! Invalid Destination in URL ! Changing to Default [Las Vegas, NV] </b> <br> <br>';
      $_GET["destination"] = 'Las Vegas, NV';
   }

   $value = array_search($_GET["destination"], $_SESSION['destinations']);

   if (($_GET["destination"] != $_SESSION['destinations'][0]) && (NULL == $value))
   {
      echo '<b> !!! Warning !!! Invalid Destination in URL ! Changing to Default [Las Vegas, NV] </b> <br> <br>';
      $_GET["destination"] = 'Las Vegas, NV';
   }
}

$query = 0;
$greater = 0;
$less = 0;

if (!empty($_POST))
{
   $filename = "./search.txt";

   if (isset($_POST['query2']))
   {
      $query = 2;
   }
   else if (isset($_POST['query3']))
   {
      $query = 3;
   }
   else if (isset($_POST['query4']))
   {
      $query = 4;
   }
   else if (isset($_POST['query8']))
   {
      $query = 8;
   }
   else if (isset($_POST['query9']))
   {
      $query = 9;
   }
   else if (isset($_POST['query10']))
   {
      $query = 10;
   }

   if (isset($_POST['greater']) && (NULL == $_POST['greater']))
   {
      $greater = 0;
   }
   else if (isset($_POST['greater']))
   {
      $greater = $_POST['greater'];
   }
   else
   {
      $greater = 0;
   }

   if (isset($_POST['less']) && (NULL == $_POST['less']))
   {
      $less = 0;
   }
   else if (isset($_POST['less']))
   {
      $less = $_POST['less'];
   }
   else
   {
      $less = 0;
   }

   //echo "comes here 3 : g = " . $greater . " and l = " . $less . "<br> <br>";
   $file2 = explode(".", $_SESSION["filename"]);

   if ($query != 0)
   {
      $chars = array(",");
      $dest2 = str_replace($chars, "", $_POST['dest_airport']);
      $timestamp = time();
      $file_entry = $timestamp . ',' . $_SERVER['REMOTE_ADDR'] . ',' . $query . ',' . $greater . ',' . $less . ',' . $file2[0] . ',' . $dest2;
      file_put_contents($filename, $file_entry.PHP_EOL, FILE_APPEND);
   }
}

if ($query == 0)
{
   if (isset($_GET["query"]))
   {
      $query = $_GET["query"];
      $greater = $_GET['greater'];
      $less = $_GET['less'];
   }
   else if ($_SESSION['query'] != NULL)
   {
      $query = $_SESSION['query'];
      $greater = $_SESSION['greater'];
      $less = $_SESSION['less'];
   }
}

//echo "value of query is still " . $query . "<br> <br>";

// Database creation
$ret = create_db();
$destination = NULL;

if (0 != $ret)
{
   die('Table creation failed !!!');
}
else
{
   if (!isset($_POST['dest_airport']))
   {
      if (isset($_GET["destination"]))
      {
         $destination = $_GET["destination"];
      }
      else if ($_SESSION['destination'] != NULL)
      {
         $destination = $_SESSION['destination'];
      }
      else
      {
         die("Invalid Destination Airport !");
      }
   }
   else
   {
      $destination = $_POST['dest_airport'];
   }

   // Database population
   distribute_db ($airline_data, $destination);
}

echo '<h3> Delay records for different airlines from [New York, NY >>>>>>>>>> to >>>>>>>>>> ' . $destination . '] </h3>';
//echo "<hr>";

// Connect to the database
$conn = connect_db();

// Retrieve all the carriers for the given destination
$carriers = array();
$result = pg_query("SELECT carrier FROM airline_db");

if (NULL == $result)
{
   echo "Carriers retrieval failed ! <br>";
}

$carriers = pg_fetch_all($result);

if (NULL == $carriers)
{
   echo "No airline carriers found ! <br>";
}

$cnt = count ($carriers);
$i = 0;
$airlines = array();

while ($i < $cnt)
{
   $airlines[] = $carriers[$i]['carrier'];
   $i++;
}

$carriers = array_values(array_unique($airlines));

$j = 0;
$new_carriers = array();

while (($query != 2) && $j < count($carriers))
{
   $airline = $carriers[$j];

   //echo "carrier " . $airline . "<br>";

   $return = pg_query("SELECT * FROM airline_db WHERE carrier = '$airline'");

   if (NULL == $return)
   {
      //echo "Details retrieval failed ! <br>";
   }

   $details = pg_fetch_all($return);

   if (NULL == $details)
   {
      //echo "No airline carriers found ! <br>";
   }

   $cancelled_count = get_cancelled_counts($airline_data, $carriers, $details[0]['org_airport'], $details[0]['dest_airport']);
   $ontime_count = get_ontime_counts($airline_data, $carriers, $details[0]['org_airport'], $details[0]['dest_airport']);
   $delayed_count = get_delayed_counts($airline_data, $carriers, $details[0]['org_airport'], $details[0]['dest_airport']);

   $total_trips = $delayed_count[$details[0]['carrier']] + $ontime_count[$details[0]['carrier']];

   $delay_percentage = (($delayed_count[$details[0]['carrier']]/$total_trips) * 100);
   //if ($greater == 0 && $total_trips < $less)
   //echo "comes here 4 : query = " . $query . " and delay percent " . $delay_percentage . " and g = " . $greater . " and l = " . $less . "<br> <br>";

   if (($query == 3) && 
       (($greater == 0 && $less == 0) ||
        ($greater == 0 && $cancelled_count[$details[0]['carrier']] <= $less) ||
        ($less == 0 && $cancelled_count[$details[0]['carrier']] >= $greater) ||
        ($cancelled_count[$details[0]['carrier']] >= $greater && $cancelled_count[$details[0]['carrier']] <= $less)))
   {
      $new_carriers[] = $carriers[$j];
   }
   else if (($query == 4) && 
	    (($greater == 0 && $less == 0) ||
	     ($greater == 0 && $total_trips <= $less) ||
	     ($less == 0 && $total_trips >= $greater) ||
	     ($total_trips >= $greater && $total_trips <= $less)))
   {
      $new_carriers[] = $carriers[$j];
   }
   else if (($query == 8) && 
	    (($greater == 0 && $less == 0) ||
	     ($greater == 0 && $delayed_count[$details[0]['carrier']] <= $less) ||
	     ($less == 0 && $delayed_count[$details[0]['carrier']] >= $greater) ||
	     ($delayed_count[$details[0]['carrier']] >= $greater && $delayed_count[$details[0]['carrier']] <= $less)))
   {
      $new_carriers[] = $carriers[$j];
   }
   else if (($query == 9) && 
	    (($greater == 0 && $less == 0) ||
	     ($greater == 0 && $ontime_count[$details[0]['carrier']] <= $less) ||
	     ($less == 0 && $ontime_count[$details[0]['carrier']] >= $greater) ||
	     ($ontime_count[$details[0]['carrier']] >= $greater && $ontime_count[$details[0]['carrier']] <= $less)))
   {
      $new_carriers[] = $carriers[$j];
   }
   else if (($query == 10) && 
	    (($greater == 0 && $less == 0) ||
	     ($greater == 0 && $delay_percentage <= $less) ||
	     ($less == 0 && $delay_percentage >= $greater) ||
	     ($delay_percentage >= $greater && $delay_percentage <= $less)))
   {
      $new_carriers[] = $carriers[$j];
   }

   $j++;
}

if ($query != 2)
{
  $carriers = $new_carriers;
}

$_SESSION['carriers'] = $carriers;

// Pagenation
$records = array_chunk($carriers, 3); // 3 records per page
$index = 0;
$j = 0;

if (!isset($_GET["page"]))
{
    $index = 0;
}
else
{
    $index = $_GET["page"] - 1;
}

if (isset($_GET["page"]))
{
   // Checking for invalid page using pattern matching
   if (preg_match('/[^0-9]/i', $_GET["page"]))
   {
      echo '<b> !!! Warning !!! Invalid Page in URL ! Changing to Default Page </b> <br> <br>';
      $index = 0;
   }
}

if ($index > count($records) || $index < 0)
{
   $index = 0;
} 

$displayed = 0;

while (isset($records[$index]) && ($j < count($records[$index])))
{
   $airline = $records[$index][$j];

   $return = pg_query("SELECT * FROM airline_db WHERE carrier = '$airline'");

   if (NULL == $return)
   {
      echo "Details retrieval failed ! <br>";
   }

   $details = pg_fetch_all($return);

   if (NULL == $details)
   {
      echo "No airline carriers found ! <br>";
   }

   $cancelled_count = get_cancelled_counts($airline_data, $carriers, $details[0]['org_airport'], $details[0]['dest_airport']);
   $diverted_count = get_diverted_counts($airline_data, $carriers, $details[0]['org_airport'], $details[0]['dest_airport']);
   $ontime_count = get_ontime_counts($airline_data, $carriers, $details[0]['org_airport'], $details[0]['dest_airport']);
   $delayed_count = get_delayed_counts($airline_data, $carriers, $details[0]['org_airport'], $details[0]['dest_airport']);

   $total_trips = $delayed_count[$details[0]['carrier']] + $ontime_count[$details[0]['carrier']];
   if ($total_trips == 0)
   {
     $total_trips = 1;
   }

   /*if (isset($_POST['cancel']))
   {
     echo "cancelled set <br>";
   }
   else
   {
     echo "cancelled not set <br>";
   }*/

   //if (($query == 2 && $cancelled_count[$details[0]['carrier']] >= 5) || ($query == 1))
   //{
      echo "<u> <h3> Record For " . $details[0]['carrier'] . "</h3> </u>";
      echo "<b> Carrier </b>: " . $details[0]['carrier'] . "<br>";
      echo "<b> Origin Airport </b>: " . $details[0]['org_airport'] ."<br>";
      echo "<b> Destination Airport </b>: " . $details[0]['dest_airport'] . "<br>";
      echo "<b> Delayed # </b>: ". $delayed_count[$details[0]['carrier']] ."<br>";
      echo "<b> Ontime # </b>: ". $ontime_count[$details[0]['carrier']] ."<br>";
      echo "<b> Total trips </b>: ". $total_trips ."<br>";
      echo "<b> Delay Percentage </b>: ". (($delayed_count[$details[0]['carrier']]/$total_trips) * 100) ."<br>";
      echo "<b> Cancelled # </b>: ". $cancelled_count[$details[0]['carrier']] . "<br>";
      echo "<b> Diverted # </b>: ". $diverted_count[$details[0]['carrier']] ."<br>";

      $_SESSION['carrier'] = $details[0]['carrier'];
      $_SESSION['airline_record'][$_SESSION['carrier']] = $details;
      $_SESSION['previous_page_2'] = $_SERVER['PHP_SELF'];
      $_SESSION['destination'] = $destination;
      $_SESSION['query'] = $query;
      $_SESSION['greater'] = $greater;
      $_SESSION['less'] = $less;

      $getRequest = http_build_query(array('carrier' => $details[0]['carrier'], 'end' => '#'));
      echo '<br> <a href="page3.php?'.$getRequest.'"> More details ... </a> <br> <hr>';

      $displayed++;
   //}

   $j++;
}

$displayed = 0;
$j = 0;

//$carriers_cnt = count($carriers) - $displayed;
/*if (($query != 1) && $displayed == 0)
{
   echo "<b> No Records found for this query ! </b> <br> <br>";
}*/

/*if ($query == 1)
{
   $total_records = count($records);
}
else if ($query == 2)
{
   $total_records = ceil($displayed/3);
}*/

$total_records = count($records);

$i = 0;

$current_page = $index;



echo "<br> page : ";

while ($i < $total_records)
{
  if ($i != $current_page)
  {
    $getRequest = http_build_query(array('page' => ($i + 1), 'destination' => $destination, 'query' => $query, 'greater' => $greater, 'less' => $less, 'end' => '#'));
    echo '<a href="'.$_SERVER["PHP_SELF"].'?'.$getRequest.'">' . ($i + 1) . '</a> ';
  }
  else
  {
    echo ($i + 1) . " ";
  }
  $i++;
}
echo "<br><br>";

echo '<form method = "POST" action="'. $_SESSION['previous_page_1'] .'">';
echo '<input type="hidden" name="home" value= "home">';
echo '<input type = "Submit" name = "submit" value="HOME"> <br>';

echo "<br>";

echo "</body>
      </html>";

?>
