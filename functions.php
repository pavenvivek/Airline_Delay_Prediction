<?php

/*************************************
functions.php

[-] Contains all the necessary functions

**************************************/

//require('/home/pvivekan/things.php');

/*************************************
function contents

[-] Used to display the given array

**************************************/

function contents($x)
{
   echo '<pre>';
   print_r($x);
   echo '</pre>';
}

/*************************************
functions get_destination

[-] Retrieve all the available destinations

**************************************/

function get_destination ($airline_data)
{
   $destinations = array();

   for ($i = 0; $i < count($airline_data); $i++)
   {
      $destinations[] = $airline_data[$i]["DEST_CITY_NAME"];
   }

   return array_values(array_unique($destinations));
}

/*************************************
functions connect_db

[-] Establish the connection to the database

**************************************/

function connect_db ()
{
   $password = 'ptVgs4QC';

   if ($conn = pg_connect("host=db.ils.indiana.edu port=5433 user=pvivekan password=".$password))
   {
      return $conn;
   } 
   else 
   {
      die('PSQL connection could not be completed !!!');
   }
}

/*************************************
functions get_cancelled_counts

[-] Retrieve the # of times given carrier is cancelled

**************************************/

function get_cancelled_counts ($airline_data, $carriers, $org, $dest)
{
   $cancelled_count = array_fill_keys($carriers, 0);

   for ($i = 0; $i < count ($airline_data); $i++)
   {
      for ($j = 0; $j < count ($carriers); $j++)
      {
	 if ($airline_data[$i]['CARRIER'] == $carriers[$j] && $airline_data[$i]['ORIGIN'] == $org && $airline_data[$i]['DEST'] == $dest)
	 {
	    if ($airline_data[$i]['CANCELLED'] == 1)
	    {
	       $cancelled_count[$carriers[$j]] += 1;
	    }

            break;
         }
      }
   }

   return $cancelled_count; 
}

/*************************************
functions get_diverted_counts

[-] Retrieve the # of times given carrier is diverted

**************************************/

function get_diverted_counts ($airline_data, $carriers, $org, $dest)
{
   $diverted_count = array_fill_keys($carriers, 0);

   for ($i = 0; $i < count ($airline_data); $i++)
   {
      for ($j = 0; $j < count ($carriers); $j++)
      {
	 if ($airline_data[$i]['CARRIER'] == $carriers[$j] && $airline_data[$i]['ORIGIN'] == $org && $airline_data[$i]['DEST'] == $dest)
	 {
	    if ($airline_data[$i]['DIV_ARR_DELAY'] != NULL)
	    {
	       $diverted_count[$carriers[$j]] += 1;
	    }

            break;
         }
      }
   }

   return $diverted_count; 
}

/*************************************
functions get_delayed_counts

[-] Retrieve the # of times given carrier is delayed

**************************************/

function get_delayed_counts ($airline_data, $carriers, $org, $dest)
{
   $delayed_count = array_fill_keys($carriers, 0);

   for ($i = 0; $i < count ($airline_data); $i++)
   {
      for ($j = 0; $j < count ($carriers); $j++)
      {
	 if ($airline_data[$i]['CARRIER'] == $carriers[$j] && $airline_data[$i]['ORIGIN'] == $org && $airline_data[$i]['DEST'] == $dest)
	 {
	    if ($airline_data[$i]['DEP_DELAY_NEW'] != 0 || $airline_data[$i]['ARR_DELAY_NEW'] != 0 || $airline_data[$i]['CARRIER_DELAY'] != 0 ||  
		$airline_data[$i]['WEATHER_DELAY'] != 0 || $airline_data[$i]['NAS_DELAY'] != 0 || $airline_data[$i]['SECURITY_DELAY'] != 0 ||
		$airline_data[$i]['LATE_AIRCRAFT_DELAY'] != 0 || $airline_data[$i]['DIV_ARR_DELAY'] != NULL)
	    {
	       $delayed_count[$carriers[$j]] += 1;
	    }

            break;
         }
      }
   }

   return $delayed_count; 
}

/*************************************
functions get_ontime_counts

[-] Retrieve the # of times given carrier is not delayed

**************************************/

function get_ontime_counts ($airline_data, $carriers, $org, $dest)
{
   $ontime_count = array_fill_keys($carriers, 0);

   for ($i = 0; $i < count ($airline_data); $i++)
   {
      for ($j = 0; $j < count ($carriers); $j++)
      {
	 if ($airline_data[$i]['CARRIER'] == $carriers[$j] && $airline_data[$i]['ORIGIN'] == $org && $airline_data[$i]['DEST'] == $dest)
	 {
	    if ($airline_data[$i]['DEP_DELAY_NEW'] == 0 && $airline_data[$i]['ARR_DELAY_NEW'] == 0 && $airline_data[$i]['CARRIER_DELAY'] == 0 &&  
		$airline_data[$i]['WEATHER_DELAY'] == 0 && $airline_data[$i]['NAS_DELAY'] == 0 && $airline_data[$i]['SECURITY_DELAY'] == 0 &&
		$airline_data[$i]['LATE_AIRCRAFT_DELAY'] == 0 && $airline_data[$i]['DIV_ARR_DELAY'] == NULL)
	    {
	       $ontime_count[$carriers[$j]] += 1;
	    }

            break;
         }
      }
   }

   return $ontime_count; 
}


/*************************************
functions get_edited_data

[-] Retrieve the edited data

**************************************/

function get_edited_data ($filename)
{
   $file = explode (".", $filename);
   $filename = $file[0] . "_2.csv";

   //echo "<b> file name is " . $filename . " </b> <br>";

   if (!file_exists($filename))
   {
      return NULL;
   }

   $fp = fopen($filename, 'r');
   $airline_data = array();
   $i = 0;

   while($x = fgetcsv($fp))
   {
      $airline_data[$i] = $x;
      $i++;
   }

   //contents($airline_data);

   if (NULL == $airline_data)
   {
     //echo "Array is NULL <br>";
   }

   fclose($fp);

   return $airline_data;
}


/*************************************
functions get_max_index

[-] Retrieve the max index

**************************************/

function get_max_index ($mod_array)
{
   //contents($mod_array);
   $max = 0;

   if ($mod_array != NULL)
   {

      for ($i = 0; $i < count($mod_array); $i++)
      {
	 if ($mod_array[$i][22] > $max)
	 {
	    $max = $mod_array[$i][22];
	 }
      }
   }

   return $max;
}

/*************************************
functions get_added_array

[-] Retrieve the newly added elements

**************************************/

function get_added_array ($mod_array, $index)
{
   //contents($mod_array);
   $new_a = array();

   if ($mod_array != NULL)
   {
      for ($i = 0; $i < count($mod_array); $i++)
      {
	 if ($mod_array[$i][22] >= $index)
	 {
	    $new_a[] = $mod_array[$i];
	 }
      }
   }

   return $new_a;
}


/*************************************
functions create_db

[-] Create the database tables

**************************************/

function create_db ()
{
   $conn = connect_db();

   $result = pg_query("SELECT * FROM airline_db"); 

   if (NULL == $result)
   {
      //echo "Table does not exists <br>";
      $result = pg_query("CREATE TABLE airline_db (id int,
						   carrier varchar(5), 
						   org_airport varchar(20), 
						   org_city varchar(50), 
						   dest_airport varchar(20),
						   dest_city varchar(50),
						   dep_delay int, 
						   arr_delay int, 
						   carrier_delay int, 
						   weather_delay int, 
						   nas_delay int, 
						   security_delay int, 
						   aircraft_delay int, 
						   diverted_delay int)");

      if (NULL == $result)
      {
	 return -1; //for failure
      }
   }

   pg_close($conn);

   return 0; //for success
}

/*************************************
functions delete_db

[-] Deletes the database tables

**************************************/

function delete_db ()
{
   $conn = connect_db();

   $result = pg_query("SELECT * FROM airline_db"); 

   if (NULL != $result)
   {
      $result = pg_query("DELETE FROM airline_db *");
   }

   pg_close($conn);
} 

/*************************************
functions distribute_db

[-] Populates the database tables

**************************************/

function distribute_db ($airline_data, $destination)
{
   $cnt = count ($airline_data);
   $result = delete_db();
   $conn = connect_db();

   //echo 'destination is ' . $destination . '<br> <br>';
   $index = 0;
   for ($i = 0; $i < $cnt; $i++)
   {
      if (($airline_data[$i]['DEST_CITY_NAME'] == $destination) && ($airline_data[$i]['CARRIER_DELAY'] != 0 || $airline_data[$i]['DIV_ARR_DELAY'] != 0))
      {
	 if ($airline_data[$i]['DIV_ARR_DELAY'] != 0)
	 {
	    if ($airline_data[$i]['DEP_DELAY_NEW'] == NULL)
	    {
	       $airline_data[$i]['DEP_DELAY_NEW'] = 0;
	    }

	    if ($airline_data[$i]['ARR_DELAY_NEW'] == NULL)
	    {
	       $airline_data[$i]['ARR_DELAY_NEW'] = 0;
	    }

	    if ($airline_data[$i]['CARRIER_DELAY'] == NULL)
	    {
	       $airline_data[$i]['CARRIER_DELAY'] = 0;
	    }
	    if ($airline_data[$i]['WEATHER_DELAY'] == NULL)
	    {
	       $airline_data[$i]['WEATHER_DELAY'] = 0;
	    }
	    if ($airline_data[$i]['NAS_DELAY'] == NULL)
	    {
	       $airline_data[$i]['NAS_DELAY'] = 0;
	    }
	    if ($airline_data[$i]['SECURITY_DELAY'] == NULL)
	    {
	       $airline_data[$i]['SECURITY_DELAY'] = 0;
	    }
	    if ($airline_data[$i]['LATE_AIRCRAFT_DELAY'] == NULL)
	    {
	       $airline_data[$i]['LATE_AIRCRAFT_DELAY'] = 0;
	    }
	    if ($airline_data[$i]['DIV_ARR_DELAY'] == NULL)
	    {
	       $airline_data[$i]['DIV_ARR_DELAY'] = 0;
	    }
	 }
	 else if ($airline_data[$i]['DIV_ARR_DELAY'] == NULL)
	 {
	    $airline_data[$i]['DIV_ARR_DELAY'] = 0;
	 }

	 $db_entry = array('id' => $index,
			   'carrier' => $airline_data[$i]['CARRIER'],
			   'org_airport' => $airline_data[$i]['ORIGIN'],
			   'org_city' => $airline_data[$i]['ORIGIN_CITY_NAME'],
			   'dest_airport' => $airline_data[$i]['DEST'],
			   'dest_city' => $airline_data[$i]['DEST_CITY_NAME'],
			   'dep_delay' => $airline_data[$i]['DEP_DELAY_NEW'],
			   'arr_delay' => $airline_data[$i]['ARR_DELAY_NEW'],
			   'carrier_delay' => $airline_data[$i]['CARRIER_DELAY'],
			   'weather_delay' => $airline_data[$i]['WEATHER_DELAY'],
			   'nas_delay' => $airline_data[$i]['NAS_DELAY'],
			   'security_delay' => $airline_data[$i]['SECURITY_DELAY'],
			   'aircraft_delay' => $airline_data[$i]['LATE_AIRCRAFT_DELAY'],
			   'diverted_delay' => $airline_data[$i]['DIV_ARR_DELAY']);

	 $result = pg_insert($conn, 'airline_db', $db_entry);

	 if (NULL == $result)
	 {
	    echo "Database insertion failed <br>";
	 }

	 $index++;
      }
   }

   pg_close($conn);
}


/*************************************
functions create_db

[-] Create the database temp tables

**************************************/

function create_temp_table ()
{
   $conn = connect_db();

   $result = pg_query("SELECT * FROM temp"); 

   if (NULL == $result)
   {
      //echo "Table does not exists <br>";
      $result = pg_query("CREATE TABLE temp (year int, month int, carrier varchar(10))");

      if (NULL == $result)
      {
	 return -1; //for failure
      }
   }

   pg_close($conn);

   return 0; //for success
}

/*************************************
functions delete_db

[-] Deletes the database temp tables

**************************************/

function delete_temp ()
{
   $conn = connect_db();

   $result = pg_query("SELECT * FROM temp"); 

   if (NULL != $result)
   {
      $result = pg_query("DELETE FROM temp *");
   }

   pg_close($conn);
} 

/*************************************
functions create_db

[-] Create the database temp tables

**************************************/

function create_user_table ()
{
   $conn = connect_db();

   $result = pg_query("SELECT * FROM users"); 

   if (NULL == $result)
   {
      //echo "Table does not exists <br>";
      $result = pg_query("CREATE TABLE users (username varchar(10), access varchar(5))");

      if (NULL == $result)
      {
	 echo "Users table creation failed <br>";
	 return -1; //for failure
      }
   }

   pg_close($conn);

   return 0; //for success
}

/*************************************
functions delete_db

[-] Deletes the database temp tables

**************************************/

function delete_user ()
{
   $conn = connect_db();

   $result = pg_query("SELECT * FROM users"); 

   if (NULL != $result)
   {
      $result = pg_query("DELETE FROM users *");
   }

   pg_close($conn);
} 


/*************************************
functions check_valid_dest

[-] Check whether the given destination is valid

**************************************/

function check_valid_dest ($dest, $destinations)
{
  if (NULL != array_search($dest, $destinations))
  {
    return 0;
  }

  return -1;
}


//THIS FUNCTION GETS THE CURRENT URL
function curPageURL()
{
   $pageURL = 'http';

   if (isset($_SERVER["HTTPS"]) && ($_SERVER["HTTPS"] == "on")) 
   {
      $pageURL .= "s://";
      if ($_SERVER["SERVER_PORT"] != "443") 
      {
         $pageURL .= $_SERVER["HTTP_HOST"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
      } 
      else 
      {
         $pageURL .= $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
      }
   } 
   else 
   {
      $pageURL .= "://";
      if ($_SERVER["SERVER_PORT"] != "80") 
      {
         $pageURL .= $_SERVER["HTTP_HOST"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
      } 
      else 
      {
         $pageURL .= $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
      }
   }
   return $pageURL;
}
 
//THIS FUNCTION SENDS THE USER TO CAS AND THEN BACK
function cas_authenticate()
{
 
   $sid = SID;
 
   //if the last session was over 15 minutes ago
   if (isset($_SESSION['LAST_SESSION']) && (time() - $_SESSION['LAST_SESSION'] > 900)) 
   {
      $_SESSION['CAS'] = false; // set the CAS session to false
   }
 
   $authenticated = $_SESSION['CAS'];
   $casurl = curPageURL();
 
   //send user to CAS login if not authenticated
   if (!$authenticated) 
   {
      $_SESSION['LAST_SESSION'] = time();
      $_SESSION['CAS'] = true;
      echo '<META HTTP-EQUIV="Refresh" Content="0; URL=https://cas.iu.edu/cas/login?cassvc=IU&casurl='.$casurl.'">';
      exit;
   }
 
   if ($authenticated) 
   {
      if (isset($_GET["casticket"])) 
      {
         $_url = 'https://cas.iu.edu/cas/validate';
         $cassvc = 'IU'; 
 
         $params = "cassvc=$cassvc&casticket=$_GET[casticket]&casurl=$casurl";
         $urlNew = "$_url?$params";
 
         $ch = curl_init();
         $timeout = 5;
         curl_setopt ($ch, CURLOPT_URL, $urlNew);
         curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

         ob_start();
         curl_exec($ch);
         curl_close($ch);
         $cas_answer = ob_get_contents();
         ob_end_clean();

         list($access,$user) = split("\n",$cas_answer,2);
         $access = trim($access);
         $user = trim($user);

         if ($access == "yes") 
         {
            $_SESSION['user'] = $user;
         }//END SESSION USER
      } 
      else if (!isset($_SESSION['user'])) 
      {
         echo '<META HTTP-EQUIV="Refresh" Content="0; URL=https://cas.iu.edu/cas/login?cassvc=IU&casurl='.$casurl.'">';
      }
   }
}



?>
