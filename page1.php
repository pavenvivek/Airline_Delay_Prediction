<?php

/*************************************
page1.php

[-] Home Page which retrieves the destinations for given month and year [for origin New York]

**************************************/
error_reporting(E_ALL);
ini_set('html_errors', 1);
ini_set("log_errors", 1);
ini_set("track_errors", 1);
ini_set("error_log", "php-error.log");

session_save_path('/home/pvivekan/session');
session_start();

//include("./cas.php");
require('./functions.php');

echo "<html>";
echo "<head>";
echo "<style>";

echo "div  { width: 500px; height: 500px; margin:50 auto;}
      img  { display: block; float: left; width: 50%; height: 50%; }
      body { background-color: #e6e6e6; 
             font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; }
      h2   { background-color: #d0d0e1;
             font-family: 'Trebuchet MS', Helvetica, sans-serif; }
      h3   { font-family: 'Trebuchet MS', Helvetica, sans-serif; }";
echo "</style>";
echo "</head>";
echo "<body>";

echo '<h2 style = "text-align: center; border-style : inset; border-width : 2px;"> Airline Performance Reporter </h2>';

echo '<div>
         <img src="./img4.jpg"/>
         <img src="./img1.jpg"/>
         <img src="./img3.jpg" />
         <img src="./img2.jpg" />
      </div>';

$_SESSION['previous_page_1'] = $_SERVER['PHP_SELF'];

// Form to edit/add entries [for authorized users only]
echo '<form method="POST" action="edit.php">';

echo '<b> Please select the year, month and carrier to edit or add records (Requires CAS Authentication) </b>: <hr> <br>';

echo '<b> Year </b>: ';
echo '<select name = "eyear">';
echo '<option value = 2015> 2015';
echo '<option value = 2016> 2016';
echo '</select>';

echo '<b> Month </b>: ';
echo '<select name = "emonth">';
echo '<option value = 1> Januray';
echo '<option value = 2> Febraury';
echo '<option value = 3> March';
echo '<option value = 4> April';
echo '<option value = 5> May';
echo '<option value = 6> June';
echo '<option value = 7> July';
echo '<option value = 8> August';
echo '<option value = 9> September';
echo '<option value = 10> October';
echo '<option value = 11> November';
echo '<option value = 12> December';
echo '</select>';

echo '<b> Carrier </b>: ';
echo '<select name = "ecarrier">';
echo '<option value = "AA"> American Airlines';
echo '<option value = "UA"> United Airlines';
echo '<option value = "B6"> JetBlue';
echo '<option value = "DL"> Delta Airlines';
echo '<option value = "VX"> Virgin America';
echo '<option value = "EV"> ExpressJet';
echo '<option value = "F9"> Frontier Airlines';
echo '<option value = "HA"> Hawaiian Airlines';
echo '<option value = "MQ"> Envoy Air';
echo '<option value = "NK"> Spirit Airlines';
echo '<option value = "US"> US Air';
echo '<option value = "WN"> Southwest Airlines';
echo '</select>';

echo '<input type = "hidden" name="query5" value = 3>';
echo '<br> <br> <input type="Submit" name = "submit3" id = "submit3" value = "Modify">';
echo '</form>';

echo '<br> <br>';


// Form requesting airport
echo '<form method="POST" action="page4.php">';

echo '<b> Please select the airport to retrieve months based on delayed count </b>: <hr> <br>';

echo '<b> Airport </b>: ';
echo '<select name = "airport">';
echo '<option value = "Los Angeles, CA"> Los Angeles, CA';
echo '<option value = "San Francisco, CA"> San Francisco, CA';
echo '<option value = "Las Vegas, NV"> Las Vegas, NV';
echo '<option value = "San Juan, PR"> San Juan, PR';
echo '<option value = "Boston, MA"> Boston, MA';
echo '<option value = "San Diego, CA"> San Diego, CA';
echo '<option value = "Chicago, IL"> Chicago, IL';
echo '<option value = "Austin, TX"> Austin, TX';
echo '<option value = "Washington, DC"> Washington, DC';
echo '<option value = "Seattle, WA"> Seattle, WA';
echo '<option value = "Miami, FL"> Miami, FL';
echo '<option value = "Orlando, FL"> Orlando, FL';
echo '<option value = "Charlotte, NC"> Charlotte, NC';
echo '<option value = "Eagle, CO"> Eagle, CO';
echo '<option value = "Phoenix, AZ"> Phoenix, AZ';
echo '</select>';

echo '<br> <br>';
echo '<b> Please select the range of the delayed count </b> (Leave empty to retrieve all records): <br> <br>';
echo '<table>';
echo '<tr> <td> <b> Greater than </b> : </td> <td> <input type="text" name="greater"> </td> </tr>';
echo '<tr> <td> <b> Less than </b> : </td> <td> <input type="text" name="less"> </td> </tr>';
echo '</table>';
echo '<input type = "hidden" name="query1" value = 3>';
echo '<br> <br> <input type="Submit" name = "submit2" id = "submit2" value = "Retrieve">';
echo '</form>';

echo '<br> <br>';

// -------------------------

// Form requesting airport
echo '<form method="POST" action="page4.php">';

echo '<b> Please select the airport to retrieve months based on cancelled count </b>: <hr> <br>';

echo '<b> Airport </b>: ';
echo '<select name = "airport">';
echo '<option value = "Los Angeles, CA"> Los Angeles, CA';
echo '<option value = "San Francisco, CA"> San Francisco, CA';
echo '<option value = "Las Vegas, NV"> Las Vegas, NV';
echo '<option value = "San Juan, PR"> San Juan, PR';
echo '<option value = "Boston, MA"> Boston, MA';
echo '<option value = "San Diego, CA"> San Diego, CA';
echo '<option value = "Chicago, IL"> Chicago, IL';
echo '<option value = "Austin, TX"> Austin, TX';
echo '<option value = "Washington, DC"> Washington, DC';
echo '<option value = "Seattle, WA"> Seattle, WA';
echo '<option value = "Miami, FL"> Miami, FL';
echo '<option value = "Orlando, FL"> Orlando, FL';
echo '<option value = "Charlotte, NC"> Charlotte, NC';
echo '<option value = "Eagle, CO"> Eagle, CO';
echo '<option value = "Phoenix, AZ"> Phoenix, AZ';
echo '</select>';

echo '<br> <br>';
echo '<b> Please select the range of the cancelled count </b> (Leave empty to retrieve all records): <br> <br>';
echo '<table>';
echo '<tr> <td> <b> Greater than </b> : </td> <td> <input type="text" name="greater"> </td> </tr>';
echo '<tr> <td> <b> Less than </b> : </td> <td> <input type="text" name="less"> </td> </tr>';
echo '</table>';
echo '<input type = "hidden" name="query6" value = 3>';
echo '<br> <br> <input type="Submit" name = "submit2" id = "submit2" value = "Retrieve">';
echo '</form>';

echo '<br> <br>';

// Form requesting airport
echo '<form method="POST" action="page4.php">';

echo '<b> Please select the airport to retrieve months based on diverted count </b>: <hr> <br>';

echo '<b> Airport </b>: ';
echo '<select name = "airport">';
echo '<option value = "Los Angeles, CA"> Los Angeles, CA';
echo '<option value = "San Francisco, CA"> San Francisco, CA';
echo '<option value = "Las Vegas, NV"> Las Vegas, NV';
echo '<option value = "San Juan, PR"> San Juan, PR';
echo '<option value = "Boston, MA"> Boston, MA';
echo '<option value = "San Diego, CA"> San Diego, CA';
echo '<option value = "Chicago, IL"> Chicago, IL';
echo '<option value = "Austin, TX"> Austin, TX';
echo '<option value = "Washington, DC"> Washington, DC';
echo '<option value = "Seattle, WA"> Seattle, WA';
echo '<option value = "Miami, FL"> Miami, FL';
echo '<option value = "Orlando, FL"> Orlando, FL';
echo '<option value = "Charlotte, NC"> Charlotte, NC';
echo '<option value = "Eagle, CO"> Eagle, CO';
echo '<option value = "Phoenix, AZ"> Phoenix, AZ';
echo '</select>';

echo '<br> <br>';
echo '<b> Please select the range of the diverted count </b> (Leave empty to retrieve all records): <br> <br>';
echo '<table>';
echo '<tr> <td> <b> Greater than </b> : </td> <td> <input type="text" name="greater"> </td> </tr>';
echo '<tr> <td> <b> Less than </b> : </td> <td> <input type="text" name="less"> </td> </tr>';
echo '</table>';
echo '<input type = "hidden" name="query7" value = 3>';
echo '<br> <br> <input type="Submit" name = "submit2" id = "submit2" value = "Retrieve">';
echo '</form>';

echo '<br> <br>';


// Form requesting year and month
echo '<form method="POST" action="'.$_SERVER['PHP_SELF'].'">';

echo '<b> Please select the year and month for more queries </b> : <hr> <br>';

echo '<b> Year </b>: ';
echo '<select name = "year">';
echo '<option value = 2015> 2015';
echo '<option value = 2016> 2016';
echo '</select>';

echo '<b> Month </b>: ';
echo '<select name = "month">';
echo '<option value = 1> Januray';
echo '<option value = 2> Febraury';
echo '<option value = 3> March';
echo '<option value = 4> April';
echo '<option value = 5> May';
echo '<option value = 6> June';
echo '<option value = 7> July';
echo '<option value = 8> August';
echo '<option value = 9> September';
echo '<option value = 10> October';
echo '<option value = 11> November';
echo '<option value = 12> December';
echo '</select>';

echo '<br> <br> <input type="Submit" name = "submit1" id = "submit1" value = "Retrieve">';
echo '</form>';


// Check for valid year and month
if (!empty($_POST) && !isset($_POST["home"]))
{
   if ($_POST["year"] != 2015 && $_POST["year"] != 2016)
   {
      echo "<h4> Invalid Year Entered ! (valid years are [2015 - 2016]) </h4> <br>";
   }
   else if ($_POST["year"] == 2016 && $_POST["month"] > 2)
   {
      echo "<h4> Invalid Month Entered ! (valid months are [2015 January - 2016 February]) </h4> <br>";
   }
   else
   {
      $file_array = file_get_contents('map_year.txt');
      $files_ex = explode(PHP_EOL, $file_array);
      $file_count = count($files_ex) - 1;

      $files = array();
      $header = explode("\t", $files_ex[0]);

      for ($i = 1; $i < $file_count; $i++)
      {
         $files[$i - 1] = explode("\t", $files_ex[$i]);
         $files[$i - 1] = array_combine($header, $files[$i - 1]);

	 if ($files[$i - 1]["Year"] == $_POST["year"] && $files[$i - 1]["Month"] == $_POST["month"])
	 {
	    $filename = $files[$i - 1]["Filename"];
	    break;
	 }
      }

      $fp = fopen($filename, 'r');
      $airline_data = array();

      $headers = fgetcsv($fp);

      $i = 0;

      while($x = fgetcsv($fp)) 
      {
	 $airline_data[$i] = array_combine($headers, $x);
	 $i++;
      }

      fclose($fp);

      $destinations = get_destination($airline_data);
      //contents($destinations);

      $_SESSION["destinations"] = $destinations;

      $_SESSION["filename"] = $filename;
      $filename2 = explode(".", $filename);
      $date = $filename2[0];

      echo '<br><b> Delay records for all airports in ' . $date . '</b>: <hr>';

      // Form requesting destination for origin New York
      echo '<form method="POST" action="page2.php">';

      echo '<br>';
      echo '<b> Origin Airport </b>: New York City <br> <br>';
      echo '<b> Destination Airport </b>: ';

      echo '<select name = "dest_airport">';
      for ($i = 0; $i < count($destinations); $i++)
      {
         echo '<option value = "'. $destinations[$i] .'"> ' . $destinations[$i];
      }
      echo '</select>';

      echo '<input type = "hidden" name="query2" value = 1>';
      echo '<br> <br> <input type="Submit" name = "check" id = "check" value = "check">';

      echo '</form>';

      echo '<br> <b> Delay records for all airports in ' . $date . ' based on total cancellations </b> : <hr>';

      // Form requesting destination for origin New York with atleast 5 total cancellations
      echo '<form method="POST" action="page2.php">';

      echo '<br>';
      echo '<b> Origin Airport </b>: New York City <br> <br>';
      echo '<b> Destination Airport </b>: ';

      echo '<select name = "dest_airport">';
      for ($i = 0; $i < count($destinations); $i++)
      {
         echo '<option value = "'. $destinations[$i] .'"> ' . $destinations[$i];
      }
      echo '</select>';

      echo '<br> <br>';
      echo '<b> Please select the range of the cancelled count </b> (Leave empty to retrieve all records): <br> <br>';
      echo '<table>';
      echo '<tr> <td> <b> Greater than (or equal to)</b> : </td> <td> <input type="text" name="greater"> </td> </tr>';
      echo '<tr> <td> <b> Less than (or equal to)</b> : </td> <td> <input type="text" name="less"> </td> </tr>';
      echo '</table>';

      echo '<input type = "hidden" name="query3" value = 2>';
      echo '<br> <br> <input type="Submit" name = "check" id = "check" value = "check">';

      echo '</form>';

      echo '<br> <b> Delay records for all airports in ' . $date . ' based on total trips </b> : <hr>';

      // Form requesting destination for origin New York
      echo '<form method="POST" action="page2.php">';

      echo '<br>';
      echo '<b> Origin Airport </b>: New York City <br> <br>';
      echo '<b> Destination Airport </b>: ';

      echo '<select name = "dest_airport">';
      for ($i = 0; $i < count($destinations); $i++)
      {
         echo '<option value = "'. $destinations[$i] .'"> ' . $destinations[$i];
      }
      echo '</select>';

      echo '<br> <br>';
      echo '<b> Please select the range of the total trips </b> (Leave empty to retrieve all records): <br> <br>';
      echo '<table>';
      echo '<tr> <td> <b> Greater than (or equal to)</b> : </td> <td> <input type="text" name="greater"> </td> </tr>';
      echo '<tr> <td> <b> Less than (or equal to)</b> : </td> <td> <input type="text" name="less"> </td> </tr>';
      echo '</table>';

      echo '<input type = "hidden" name="query4" value = 3>';
      echo '<br> <br> <input type="Submit" name = "check" id = "check" value = "check">';

      echo '</form>';

      echo '<br> <b> Delay records for all airports in ' . $date . ' based on total delays </b> : <hr>';

      // Form requesting destination for origin New York
      echo '<form method="POST" action="page2.php">';

      echo '<br>';
      echo '<b> Origin Airport </b>: New York City <br> <br>';
      echo '<b> Destination Airport </b>: ';

      echo '<select name = "dest_airport">';
      for ($i = 0; $i < count($destinations); $i++)
      {
         echo '<option value = "'. $destinations[$i] .'"> ' . $destinations[$i];
      }
      echo '</select>';

      echo '<br> <br>';
      echo '<b> Please select the range of the total delays </b> (Leave empty to retrieve all records): <br> <br>';
      echo '<table>';
      echo '<tr> <td> <b> Greater than (or equal to)</b> : </td> <td> <input type="text" name="greater"> </td> </tr>';
      echo '<tr> <td> <b> Less than (or equal to)</b> : </td> <td> <input type="text" name="less"> </td> </tr>';
      echo '</table>';

      echo '<input type = "hidden" name="query8" value = 3>';
      echo '<br> <br> <input type="Submit" name = "check" id = "check" value = "check">';

      echo '</form>';

      echo '<br> <b> Delay records for all airports in ' . $date . ' based on ontime departure counts </b> : <hr>';

      // Form requesting destination for origin New York
      echo '<form method="POST" action="page2.php">';

      echo '<br>';
      echo '<b> Origin Airport </b>: New York City <br> <br>';
      echo '<b> Destination Airport </b>: ';

      echo '<select name = "dest_airport">';
      for ($i = 0; $i < count($destinations); $i++)
      {
         echo '<option value = "'. $destinations[$i] .'"> ' . $destinations[$i];
      }
      echo '</select>';

      echo '<br> <br>';
      echo '<b> Please select the range of the total ontime departures </b> (Leave empty to retrieve all records): <br> <br>';
      echo '<table>';
      echo '<tr> <td> <b> Greater than (or equal to)</b> : </td> <td> <input type="text" name="greater"> </td> </tr>';
      echo '<tr> <td> <b> Less than (or equal to)</b> : </td> <td> <input type="text" name="less"> </td> </tr>';
      echo '</table>';

      echo '<input type = "hidden" name="query9" value = 3>';
      echo '<br> <br> <input type="Submit" name = "check" id = "check" value = "check">';

      echo '</form>';

      echo '<br> <b> Delay records for all airports in ' . $date . ' based on delay percentage </b> : <hr>';

      // Form requesting destination for origin New York
      echo '<form method="POST" action="page2.php">';

      echo '<br>';
      echo '<b> Origin Airport </b>: New York City <br> <br>';
      echo '<b> Destination Airport </b>: ';

      echo '<select name = "dest_airport">';
      for ($i = 0; $i < count($destinations); $i++)
      {
         echo '<option value = "'. $destinations[$i] .'"> ' . $destinations[$i];
      }
      echo '</select>';

      echo '<br> <br>';
      echo '<b> Please select the range of the delay percentage </b> (Leave empty to retrieve all records): <br> <br>';
      echo '<table>';
      echo '<tr> <td> <b> Greater than (or equal to)</b> : </td> <td> <input type="text" name="greater"> </td> </tr>';
      echo '<tr> <td> <b> Less than (or equal to)</b> : </td> <td> <input type="text" name="less"> </td> </tr>';
      echo '</table>';

      echo '<input type = "hidden" name="query10" value = 3>';
      echo '<br> <br> <input type="Submit" name = "check" id = "check" value = "check">';

      echo '</form>';

   }
}


echo '<br> <b> Your Favorite Searches </b> : <hr>';
echo "IP Address : " . $_SERVER['REMOTE_ADDR'] . "<br> <br>";
$output1 = file_get_contents('search.txt');
$data = explode(PHP_EOL, $output1);
$total_data = count($data) - 1;

$output2 = file_get_contents('queries.txt');
$queries = explode(PHP_EOL, $output2);
$total_query = count($queries) - 1;

for ($j = 1; $j < $total_query; $j++)
{
   $query_txt[$j-1] = explode("\t", $queries[$j]);
}

//contents($query_txt);

for ($i = 0; $i < $total_data; $i++)
{
  $search_data[$i] = explode(',', $data[$i]);
  //contents($search_data);

  if ($search_data[$i][1] == $_SERVER['REMOTE_ADDR'])
  {
     echo "[-] Timestamp : " . $search_data[$i][0] . "<br>";
  
     $query = $search_data[$i][2];

     for ($j = 0; $j < $total_query - 1; $j++)
     {
    	if ($query == $query_txt[$j][0])
        {
	   if ($query != 2)
	   {
	      if ($search_data[$i][3] != 0 && $search_data[$i][4] != 0)
	      {
                 echo "[-] Query : " . $query_txt[$j][1] . " greater than " . $search_data[$i][3] . " and less than " . $search_data[$i][4];
	      }
	      else if ($search_data[$i][3] == 0 && $search_data[$i][4] == 0)
	      {
                 echo "[-] Query : " . $query_txt[$j][1];
	      }
	      else if ($search_data[$i][3] != 0)
	      {
		  echo "[-] Query : " . $query_txt[$j][1] . " greater than " . $search_data[$i][3];
	      }
	      else if ($search_data[$i][4] != 0)
	      {
                  echo "[-] Query : " . $query_txt[$j][1] . " less than " . $search_data[$i][4];
	      }		

	      echo " for the airport " . $search_data[$i][6];
	   }
	   else
	   {
              echo "[-] Query : " . $query_txt[$j][1] . "  for the airport " . $search_data[$i][6];
	   }

	   if ($query != 1 && $query != 6 && $query != 7)
	   {
	      echo " for " . $search_data[$i][5] . "<br> <br>";
	   }
	   else
	   {
	      echo "<br> <br>";
	   }

  	   break;
        }
     }
  } 
}

echo "</body>
      </html>";

?>
