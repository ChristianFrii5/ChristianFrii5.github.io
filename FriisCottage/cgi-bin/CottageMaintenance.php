<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

<style type="text/css">

input {
    margin: 5px;
}

input[type="checkbox"] {
  transform:scale(1.5, 1.5);
}
</style>


<h2>Cottage Maintenance</h2>

This is a list of regular maintenance items that need to be done throughout the year.<br> Check off the items as they are completed. Remember to submit any changes to the list.<br><br>


<form name='CottageMaintList' method='POST' action='CottageMaintenance.php'>

  <input type="Submit" value="Submit Changes" />

  <button type='button' onClick='window.location.href="CottageMain.php"'>Back to Main Page </button>


<?php

/* Christian: enable to debug php */
/*
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
*/

  define( "MAINT_DATA_FILE",	"MaintData" );
  define( "DATA_DELIMITER",	"~"); 

  $StringArray=array(
    "Winter",
    "Place the marker at the well head to warn the snow plow",
    "In December pour two containers of anti freeze into the laundry tub and flush with plenty of water",
    "In January pour two containers of anti freeze into the laundry tub and flush with plenty of water",
    "In February pour two containers of anti freeze into the laundry tub and flush with plenty of water",
    "At the start of a cold spell turn heat up to 20 degrees in ping-pong room and work shop and close the door to the hallway. If it's a severe cold spell turn on a trickle of water in the laundry tub and make sure nothing can fall into the tub and block the drain. Reverse when the cold spell is over",
    "Fill green container in the outdoor storage area with kindling",
    "Clean sand filter (see instructions by the hot water tank)",
    "Spring",
    "Launch boats",
    "Remove ropes that secure the dock",
    "Remove the chains and replace the bolts that connect the gangway to the dock",
    "Set up patio furniture",
    "Remove the marker by the well head",
    "Put out outdoor mats",
    "Sweep / clean patios",
    "Fill green container in the outdoor storage area with kindling",
    "Clean sand filter (see instructions by the hot water tank)",
    "Summer",
    "Fill green container in the outdoor storage area with kindling",
    "Clean sand filter (see instructions by the hot water tank)",
    "Fall",
    "Take out boats and store",
    "Secure the dock with ropes from corners to land",
    "Remove the bolts that connect the gangway to the dock and replace with chains",
    "Sweep / clean patios",
    "Turn patio table upside down before snowfall",
    "Store outdoor mats in the outdoor storage area",
    "Fill green container in the outdoor storage area with kindling",
    "Clean sand filter (see instructions by the hot water tank)",
    "Misc",
    "Clean up septic weeping bed i.e. remove debris and tall weeds and saplings"
   );

  $fileString = chop( file_get_contents(MAINT_DATA_FILE) );
  $fileData = explode( DATA_DELIMITER, $fileString );
  $size = count($fileData) - 1;	// explode always return n+1 items

  $check=$_POST['check'];
  $submitted=$_POST['submitted'];


  if( isset($submitted) && !isset($check) )
  {

    #echo "submitted with empty set<br>";


    $fp = fopen(MAINT_DATA_FILE, 'w'); 
    if(!$fp) 
    { 
      echo "<script>alert('Error:Could not open the file.')</script>"; 
    }
    else
    {
      for( $i=0; $i<count($StringArray); $i++ )
      {
        fwrite( $fp, "0".DATA_DELIMITER ); 
        $fileData[$i]=0;
      }
      fclose($fp);
    }
  }

  else if( isset($check) )
  {

    #echo "submitted with checkboxes<br>";


    $fp = fopen(MAINT_DATA_FILE, 'w'); 
    if(!$fp) 
    { 
      echo "<script>alert('Error:Could not open the file.')</script>"; 
    }
    else
    {

      for ($i=0; $i<$size; $i++)
      {
        $fileData[$i] = 0;

        for($j=0; $j<count($check); $j++)
         {
          if( $check[$j] == $i+1 )
          {
            $fileData[$i] = 1;
            break;
          }
        }

        fwrite( $fp, $fileData[$i].DATA_DELIMITER ); 
      }

      fclose($fp);
    }
  }

  $fileString = chop( file_get_contents(MAINT_DATA_FILE) );
  #echo $fileString."<br>";

  $index=1;

  echo "<input type='hidden' name='submitted' value='1'/>";

  foreach( $StringArray as $item )
  {
    if( ($item == "Winter") || ($item == "Spring") || ($item == "Summer") || ($item == "Fall") || ($item == "Misc"))
    {
      echo "<h4>".$item."</h4>";
    }
    else
    {
      echo "<input type='checkbox' name='check[]' value=".$index." ".(($fileData[$index-1]==1)?'checked':'')." /> ".$index.". ".$item."<br>";
      $index++;
    }
  } 

?>

</form>

<br><br><br><br>

</html>
