<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>


<table border="1" align="center">
 
<tr> <th width='200'>Name <th width='100'>Start Date <th width='100'>End Date 

<?php
	
	define( "BOOKING_DATA_FILE",		"BookingData" );
	define( "DATA_DELIMITER",				"~"); 
	
	$DAYS_IN_YEAR = 12 * 31;					

	$months = array ("Jan"=>1, "Feb"=>2, "Mar"=>3, "Apr"=>4, "May"=>5, "Jun"=>6, "Jul"=>7, 
										"Aug"=>8, "Sep"=>9, "Oct"=>10, "Nov"=>11, "Dec"=>12); 
										
	$fileData = file(BOOKING_DATA_FILE);
	$size = count($fileData);
	$today = split( " ",date("M d Y"));
  	
  $todayNumber = ($months[$today[0]] * 31) + $today[1] + ($today[2] * $DAYS_IN_YEAR);
  
  /* List the old bookings in a table. Test the end date in 
  	each entry against todays date minus 1 day and list those entries. */

	for( $line=0; $line<$size; $line++ )
	{
		$lineData = split( DATA_DELIMITER, $fileData[$line] );
		$testEndNumber = ($months[$lineData[4]] * 31) + $lineData[5] + ($lineData[6] * $DAYS_IN_YEAR);
			
		if( $testEndNumber <= ($todayNumber - 1) )
		{
			$lineString = $lineData[0]." ".$lineData[1]." ".$lineData[2].", ".$lineData[3]." - ".
											$lineData[4]." ".$lineData[5].", ".chop($lineData[6]);
					
			echo "<tr><td align=center>".$lineData[0].
						"<td align=center>".$lineData[1]." ".$lineData[2].", ".$lineData[3].
						"<td align=center>".$lineData[4]." ".$lineData[5].", ".$lineData[6];	
		}
	}
	
?>

</table>

</html>




