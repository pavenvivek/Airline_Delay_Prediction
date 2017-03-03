<?php

/*************************************
edit.php

[-] For editing records

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

$flag = 0;

if (!empty($_POST))
{
   $year = $_POST['eyear'];
   $month = $_POST['emonth'];
   $carrier = $_POST['ecarrier'];

   $flag = 1;

   if (!isset($_POST['back']))
   {
      delete_temp();
      $result = create_temp_table ();

      if (0 == $result)
      {
         $conn = connect_db();
         $db_entry = array('year' => $year, 'month' => $month, 'carrier' => $carrier);

         $result = pg_insert($conn, 'temp', $db_entry);

         if (NULL == $result)
         {
  	    echo "Database insertion failed for temp table <br>";
         }

         pg_close($conn);
      }
   }
}
else if (!empty($_GET) && isset($_GET['year']))
{
   $year = $_GET['year'];
   $month = $_GET['month'];
   $carrier = $_GET['carrier'];
   $flag = 1;
   //echo "enters into GET distribution <br>";
}

if (!empty($_POST) && !isset($_POST['back']))
{
   //include ("./cas.php");
   cas_authenticate();
}

/*if (!empty($_POST) || !empty($_GET))
{
   //$username = $_SESSION['user'];
   $conn = connect_db();

   //echo "enter into this func";
   $return = pg_query("SELECT * FROM users");

   if (NULL == $return)
   {
      echo "<br> Details retrieval failed for users table ! <br>";
   }

   $details = pg_fetch_all($return);

   if (NULL == $details)
   {
      echo "<br> Fetch failed for users ! <br>";
   }

   contents($details);
   $username = $details[0]["username"];
   pg_close($conn); 

   echo "<b> Username : " . $username . " </b> <br>";

   $users = array("pvivekan", "user2", "user3");

   if (!in_array($username, $users))
   {
      die("<h3> Sorry you do not have access to this page ! </h3>");
   }
   else
   {
      echo "<b> User Authentication Success !!! </b> <br> <br>"; 
   }
}*/

//echo "<b> Username : " . $username . " </b> <br>";

if ($flag == 0)
{
   $conn = connect_db();

   //echo "enter into this func";
   $return = pg_query("SELECT * FROM temp");

   if (NULL == $return)
   {
      echo "<br> Details retrieval failed for temp table ! <br>";
   }

   $details = pg_fetch_all($return);

   if (NULL == $details)
   {
      echo "<br> Fetch failed for temp ! <br>";
   }

   //contents($details);
   $year = $details[0]["year"];
   $month = $details[0]["month"];
   $carrier = $details[0]["carrier"];
   //echo "<br> year is " . $year . "<br>";

   pg_close($conn);
}

$file_array = file_get_contents('map_year.txt');
$files_ex = explode(PHP_EOL, $file_array);
$file_count = count($files_ex) - 1;

$files = array();
$header = explode("\t", $files_ex[0]);

for ($i = 1; $i < $file_count; $i++)
{
   $files[$i - 1] = explode("\t", $files_ex[$i]);
   $files[$i - 1] = array_combine($header, $files[$i - 1]);

   if ($files[$i - 1]["Year"] == $year && $files[$i - 1]["Month"] == $month)
   {
      $filename = $files[$i - 1]["Filename"];
      break;
   }
}

$mod_array = get_edited_data($filename);

$fp = fopen($filename, 'r');
$airline_data = array();

//$carrier = $_POST['ecarrier'];

$headers = fgetcsv($fp);

$i = 0;

while($x = fgetcsv($fp)) 
{
   $airline_data[$i] = array_combine($headers, $x);

   if (NULL != $mod_array)
   {
      for ($j = 0; $j < count($mod_array); $j++)
      {
         if (isset($mod_array[$j][22]) && $mod_array[$j][22] == $i)
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

//contents($airline_data);

$mod_array = get_edited_data($filename);
$added_arr = get_added_array($mod_array, count($airline_data));

$added_new = array();

for ($k = 0; $k < count($added_arr); $k++)
{
   array_pop($added_arr[$k]);
   $added_new[] = array_combine($headers, $added_arr[$k]);
}

$airline_data = array_merge($airline_data, $added_new);

//contents($airline_data);

$file = explode(".", $filename);
echo '<h3> Delay records for the airline '. $carrier . ' in ' . $file[0] . ' </h3>';

//contents($mod_array);
$max_index = get_max_index($mod_array);

//echo "max index is " . $max_index . "<br>";

if ((count($airline_data) - 1) > $max_index)
{
   $max_index = count($airline_data);
}
else
{
   $max_index++;
}

$getRequest = http_build_query(array('year' => $year, 'month' => $month, 'carrier' => $carrier, 'index' => $max_index, 'end' => '#'));
echo '<a href="edit3.php?'.$getRequest.'"> Add New Record </a> <br> <hr>';

//contents($airline_data);

$records = array();
$index = array();

for ($i = 0; $i < count($airline_data); $i++)
{
   if ($airline_data[$i]['CARRIER'] == $carrier)
   {
      $records[] = $airline_data[$i];
      $index[] = $i;
   }
}

$precords = array_chunk($records, 10); // 10 records per page
$pindex = array_chunk($index, 10);
$ind = 0;
$i = 0;

if (!isset($_GET["page"]))
{
    $ind = 0;
}
else
{
    $ind = $_GET["page"] - 1;
}

if (isset($_GET["page"]))
{
   // Checking for invalid page using pattern matching
   if (preg_match('/[^0-9]/i', $_GET["page"]))
   {
      echo '<b> !!! Warning !!! Invalid Page in URL ! Changing to Default Page </b> <br> <br>';
      $ind = 0;
   }
}

if ($ind > count($records) || $ind < 0)
{
   $ind = 0;
} 

//contents($precords[$ind]);

while ($i < count($precords[$ind]))
{
//for ($i = 0; $i < count($records); $i++)
//{
      if (NULL == $precords[$ind][$i]['DEP_DELAY_NEW'])
      {
         $precords[$i][$ind]['DEP_DELAY_NEW'] = 0;
      }

      if (NULL == $precords[$ind][$i]['ARR_DELAY_NEW'])
      {
         $precords[$i][$ind]['ARR_DELAY_NEW'] = 0;
      }

      if (NULL == $precords[$ind][$i]['SECURITY_DELAY'])
      {
	 $precords[$ind][$i]['SECURITY_DELAY'] = 0;
      }

      echo "<u> <h3> Record For " . $precords[$ind][$i]['CARRIER'] . "</h3> </u>";
      echo "<b> Record no : </b>" . $pindex[$ind][$i] . "<br>";
      echo "<b> Carrier </b>: " . $precords[$ind][$i]['CARRIER'] . "<br>";
      echo "<b> Origin Airport </b>: " . $precords[$ind][$i]['ORIGIN_CITY_NAME'] ."<br>";
      echo "<b> Destination Airport </b>: " . $precords[$ind][$i]['DEST_CITY_NAME'] . "<br>";
      echo "<b> Departure Delay </b>: ". $precords[$ind][$i]['DEP_DELAY_NEW'] ."<br>";
      echo "<b> Arrival Delay </b>: ". $precords[$ind][$i]['ARR_DELAY_NEW'] ."<br>";
      echo "<b> Security Delay </b>: ". $precords[$ind][$i]['SECURITY_DELAY'] ."<br>";
      
      $_SESSION['file'] = $filename;
      $_SESSION['previous_page_3'] = $_SERVER['PHP_SELF'];
      $_SESSION['record'][$i] = $precords[$ind][$i];

      $getRequest = http_build_query(array('year' => $year, 'month' => $month, 'carrier' => $carrier, 'line' => $pindex[$ind][$i], 'end' => '#'));
      echo '<br> <a href="edit2.php?'.$getRequest.'"> Edit Record </a> <br> <hr>';

      $i++;
}

$total_records = count($precords);
$i = 0;
$current_page = $ind;

echo "<br> page : ";

while ($i < $total_records)
{
  if ($i != $current_page)
  {
    $getRequest = http_build_query(array('page' => ($i + 1), 'year' => $year, 'month' => $month, 'carrier' => $carrier, 'end' => '#'));
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
