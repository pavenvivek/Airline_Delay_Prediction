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

//echo "IP is " . $_SERVER['REMOTE_ADDR'] . "<br>";

$airport = $_POST["airport"];

if (!empty($_POST))
{
   if (isset($_POST['query1']))
   {
      $query = 1;
   }
   else if (isset($_POST['query6']))
   {
      $query = 6;
   }
   else if (isset($_POST['query7']))
   {
      $query = 7;
   }

   if (NULL == $_POST['greater'])
   {
      $greater = 0;
   }
   else
   {
      $greater = $_POST['greater'];
   }

   if (NULL == $_POST['less'])
   {
      $less = 0;
   }
   else
   {
      $less = $_POST['less'];
   }

   $filename2 = "./search.txt";
   $chars = array(",");
   $dest2 = str_replace($chars, "", $_POST["airport"]);
   $timestamp = time();
   $file_entry = $timestamp . ',' . $_SERVER['REMOTE_ADDR'] . ',' . $query . ',' . $greater . ',' . $less . ',' . "-" . ',' . $dest2;
   file_put_contents($filename2, $file_entry.PHP_EOL, FILE_APPEND);
}

//echo "airport is " . $airport . "<br>";

$records = array();
$months = array();

$file_array = file_get_contents('map_year.txt');
$files_ex = explode(PHP_EOL, $file_array);
$file_count = count($files_ex) - 1;

$files = array();
$header = explode("\t", $files_ex[0]);

for ($i = 1; $i < $file_count; $i++)
{
   $files[$i - 1] = explode("\t", $files_ex[$i]);
   $files[$i - 1] = array_combine($header, $files[$i - 1]);

   $filename = $files[$i - 1]["Filename"];
   //echo "file " . $i . " is " . $filename . "<br>";

   $mod_array = get_edited_data($filename);

   $fp = fopen($filename, 'r');
   $airline_data = array();

   $headers = fgetcsv($fp);

   $j = 0;
   $k = 0;

   while($x = fgetcsv($fp))
   {
      $value = array_combine($headers, $x);

      if ($value["DEST_CITY_NAME"] == $airport)
      {
         $airline_data[$j] = $value;
 
  	 if ($mod_array != NULL)
	 {
            for ($j1 = 0; $j1 < count($mod_array); $j1++)
            {
               if (isset ($mod_array[$j1][22]) && ($mod_array[$j1][22] == $k))
               {
                  array_pop($mod_array[$j1]);
	          $airline_data[$j] = array_combine($headers, $mod_array[$j1]);
                  break;
               }
     	    }
         }

         $j++;
      }
      $k++;
   }

   $mod_array = get_edited_data($filename);
   $added_arr = get_added_array($mod_array, count($airline_data));

   $added_new = array();

   for ($k = 0; $k < count($added_arr); $k++)
   {
      array_pop($added_arr[$k]);

      if ($added_arr[$k][11] == $airport)
      {
         $added_new[] = array_combine($headers, $added_arr[$k]);
      }
   }

   $airline_data = array_merge($airline_data, $added_new);
   //if ($i == 1)
      //contents($airline_data);

   $months[$files[$i - 1]["Year"]][$files[$i - 1]["Month"]] = $airline_data;

   //if ($i == 1)
      //contents($months);

   fclose($fp);
}

//contents($months);

if (isset($_POST["query1"]))
{
   echo '<h3> Delay count for all months in 2015 and 2016 for airlines from [New York, NY >>>>>>>>>> to >>>>>>>>>> ' . $airport . '] </h3>';

   foreach ($months as $year => $details)
   {
      foreach ($details as $month => $data)
      {
         $delayed_count = 0;

         for ($i = 0; $i < count ($data); $i++)
         {
	    if ($data[$i]['DEP_DELAY_NEW'] != 0 || $data[$i]['ARR_DELAY_NEW'] != 0 || $data[$i]['CARRIER_DELAY'] != 0 ||  
	        $data[$i]['WEATHER_DELAY'] != 0 || $data[$i]['NAS_DELAY'] != 0 || $data[$i]['SECURITY_DELAY'] != 0 ||
	        $data[$i]['LATE_AIRCRAFT_DELAY'] != 0 || $data[$i]['DIV_ARR_DELAY'] != NULL)
	    {
	       $delayed_count += 1;
	    }
         }

         if ((($_POST['greater'] == NULL) && ($_POST['less'] == NULL)) ||
	     (($_POST['greater'] == NULL) && ($delayed_count < $_POST['less'])) ||
	     (($_POST['less'] == NULL) && ($delayed_count > $_POST['greater'])) || 
	     ($delayed_count > $_POST['greater'] && $delayed_count < $_POST['less']))
         {
            echo "<b> Year </b>: " . $year . "<br>";
            echo "<b> Month </b>: " . $month ."<br>";
            echo "<b> Delayed # </b>: " . $delayed_count . "<br> <hr>";
         }
      }
   }
}
else if (isset($_POST["query6"]))
{
   echo '<h3> Flight Cancelled count for all months in 2015 and 2016 for airlines from [New York, NY >>>>>>>>>> to >>>>>>>>>> ' . $airport . '] </h3>';

   foreach ($months as $year => $details)
   {
      foreach ($details as $month => $data)
      {
         $cancelled_count = 0;

         for ($i = 0; $i < count ($data); $i++)
         {
	    if ($data[$i]['CANCELLED'] != 0)
	    {
	       $cancelled_count += 1;
	    }
         }

         if ((($_POST['greater'] == NULL) && ($_POST['less'] == NULL)) ||
	     (($_POST['greater'] == NULL) && ($cancelled_count < $_POST['less'])) ||
	     (($_POST['less'] == NULL) && ($cancelled_count > $_POST['greater'])) || 
	     ($cancelled_count > $_POST['greater'] && $cancelled_count < $_POST['less']))
         {
            echo "<b> Year </b>: " . $year . "<br>";
            echo "<b> Month </b>: " . $month ."<br>";
            echo "<b> Cancelled # </b>: " . $cancelled_count . "<br> <hr>";
         }
      }
   }
}
else if (isset($_POST["query7"]))
{
   echo '<h3> Flight Diverted count for all months in 2015 and 2016 for airlines from [New York, NY >>>>>>>>>> to >>>>>>>>>> ' . $airport . '] </h3>';

   foreach ($months as $year => $details)
   {
      foreach ($details as $month => $data)
      {
         $diverted_count = 0;

         for ($i = 0; $i < count ($data); $i++)
         {
	    if ($data[$i]['DIV_ARR_DELAY'] != NULL)
	    {
	       $diverted_count += 1;
	    }
         }

         if ((($_POST['greater'] == NULL) && ($_POST['less'] == NULL)) ||
	     (($_POST['greater'] == NULL) && ($diverted_count < $_POST['less'])) ||
	     (($_POST['less'] == NULL) && ($diverted_count > $_POST['greater'])) || 
	     ($diverted_count > $_POST['greater'] && $diverted_count < $_POST['less']))
         {
            echo "<b> Year </b>: " . $year . "<br>";
            echo "<b> Month </b>: " . $month ."<br>";
            echo "<b> Diverted # </b>: " . $diverted_count . "<br> <hr>";
         }
      }
   }
}

echo '<form method = "POST" action="'. $_SESSION['previous_page_1'] .'">';
echo '<input type="hidden" name="home" value= "home">';
echo '<input type = "Submit" name = "submit" value="HOME"> <br>';

echo "<br>";

echo "</body>
      </html>";

?>
