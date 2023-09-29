<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

<script LANGUAGE="javascript">

function VerifyBooking( data ) 
{
	var DATA_DELIMITER='~';
	
	if( data.Name.value == "" )
	{
		alert('Please type in a name.');
		return false;
	}
	
	myString = new String(data.Name.value);
	if( myString.search( DATA_DELIMITER ) >= 0 )
	{
		alert('Character not allowed('+DATA_DELIMITER+').');
		return false;
	}
	
		
	return confirm(' The cottage will be booked for ' + 
												data.Name.value + ' on the dates:\n\n  ' + 
												data.StartMonth.value + ' ' + data.StartDay.value + ' ' +
												data.StartYear.value + ' - ' + data.EndMonth.value + ' ' +
												data.EndDay.value + ' ' + data.EndYear.value + 
												'\n\nContinue?' );
}


function DeleteBooking (index) 
{
	var where_to = confirm('Are you sure you want to delete this booking?\n' );
 	if (where_to == true)
 	{
 		window.location="CottageBook.php?index=" + index;
 	}
 	else
 	{
 		window.location="CottageBook.php";
 	}
}



function popitup(url) {
	newwindow=window.open(url,'PreviousBookings','height=600,width=450,scrollbars=1');
	if (window.focus) {newwindow.focus()}
	return false;
}



</script>



<?php

/* Christian: enable to debug php */
/*
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
*/
		
	define( "BOOKING_DATA_FILE",		"BookingData" );
	define( "DATA_DELIMITER",				"~"); 
	
	$months = array ("Jan"=>1, "Feb"=>2, "Mar"=>3, "Apr"=>4, "May"=>5, "Jun"=>6, "Jul"=>7, 
										"Aug"=>8, "Sep"=>9, "Oct"=>10, "Nov"=>11, "Dec"=>12); 
	
	$DAYS_IN_YEAR = 12 * 31;									  
  
  /* Process the input into this page. */
	
	$name 			= $_POST['Name'];
	$startMonth = $_POST['StartMonth'];
	$startDay 	= $_POST['StartDay'];
	$startYear 	= $_POST['StartYear'];
	$endMonth 	= $_POST['EndMonth'];
	$endDay 		= $_POST['EndDay'];
	$endYear 		= $_POST['EndYear'];
	$deleteIndex = $_GET['index'];
	
	$today = explode( " ",date("M d Y"));
	$todayNumber = ($months[$today[0]] * 31) + $today[1] + ($today[2] * $DAYS_IN_YEAR)-1;
	
		
	if( $name != "" )
	{	
		/* If name is filled in, a request was made to book the cottage. */
		
		$datesAreValid = TRUE;
		$startNumber = ($months[$startMonth] * 31) + $startDay + ($startYear * $DAYS_IN_YEAR);
		$endNumber = ($months[$endMonth] * 31) + $endDay + ($endYear * $DAYS_IN_YEAR);
	
		if( $startNumber > $endNumber )
		{
			echo "<script>alert('Invalid date. Start date is before the end date.')</script>";
		}
		else if( $startNumber < $todayNumber )
		{
			echo "<script>alert('Invalid date. Start date is before todays date.')</script>";
		}
		else
		{
			$fileData = file(BOOKING_DATA_FILE);
			$size = count($fileData);

			$insertIndex = $size;
				
			for( $line=0; $line<$size; $line++ )
			{
				$lineData = explode( DATA_DELIMITER, $fileData[$line] );
					
				$testStartNumber = ($months[$lineData[1]] * 31) + $lineData[2] + ($lineData[3] * $DAYS_IN_YEAR);
				$testEndNumber = ($months[$lineData[4]] * 31) + $lineData[5] + ($lineData[6] * $DAYS_IN_YEAR);
					
				if( (($startNumber >= $testStartNumber) && ($startNumber <= $testEndNumber)) ||
						(($endNumber >= $testStartNumber) && ($endNumber <= $testEndNumber)) ||
						(($startNumber <= $testStartNumber) && ($endNumber >= $testEndNumber)) )
				{
					$lineString = $lineData[1]." ".$lineData[2].", ".$lineData[3].
		 										" - ".$lineData[4]." ".$lineData[5].", ".chop($lineData[6]);			
		 							
		 			echo "<script>alert('Dates conflict with the following previous booking:\\n\\n   ".
		 							$lineString."' )</script>";
		 			$datesAreValid = FALSE;	
		 			break;
				}	
					
				/* Find the line in the file to insert this booking into. */
				if(( $startNumber > $testStartNumber ) && ( $insertIndex == $size ))
				{
					$insertIndex = $line;
				}
			}
				
			if( $datesAreValid == TRUE )
			{
				$fp = fopen(BOOKING_DATA_FILE, 'w'); 
				if(!$fp) 
				{ 
					echo "<script>alert('Error:Could not open the file.')</script>";
				} 
				else 
				{
					for( $line=0; $line<=$size; $line++ )
					{
						if( $line == $insertIndex )
						{
							fwrite( $fp, "$name".DATA_DELIMITER."$startMonth".DATA_DELIMITER."$startDay".
										DATA_DELIMITER."$startYear".DATA_DELIMITER."$endMonth".DATA_DELIMITER."$endDay".
										DATA_DELIMITER."$endYear\n" );
						}
						
						if( $line < $size )
						{
							fputs( $fp, $fileData[$line] );
						}
					}
					
					fclose($fp);
			 	}
			}
		}
	}
	
	else if( $deleteIndex != "" )
	{
		/* If an index was supplied, a request was made to delete a booking. */
		
		$fileData = file(BOOKING_DATA_FILE);
		$size = count($fileData);
		
		$fp = fopen(BOOKING_DATA_FILE, 'w'); 
		if(!$fp) 
		{ 
			echo "<script>alert('Error:Could not open the file.')</script>";
		} 
		else 
		{
			for( $line=0; $line<$size; $line++ )
			{
				if( $line != $deleteIndex )
				{
					fputs( $fp, $fileData[$line] );
				}
			}
		 	
		 	fclose($fp);
		}
	}
	
	
?>


<h2> Cottage Booking Tool </h2>
<br>

Here you can book the cottage. Just enter your name and the date you want to book it for<br>
and the date you will be leaving. You can also delete previous bookings.
<br><br>

<h4 align="center"> Upcoming Bookings </h4>
<table border="1" align="center">
 
<tr> <th width='200'>Name <th width='100'>Start Date <th width='100'>End Date 
<th>Delete Booking


<?php
	
	$DAYS_IN_YEAR = 12 * 31;		

	$fileData = file(BOOKING_DATA_FILE);
	$size = count($fileData);
  
  /* List the upcoming bookings in a table. Test the end dates in 
  	each entry against todays date minus 1 day and list those entries. */
	for( $line=($size-1); $line>=0; $line-- )
	{
		$lineData = explode( DATA_DELIMITER, $fileData[$line] );
		$testEndNumber = ($months[$lineData[4]] * 31) + $lineData[5] + ($lineData[6] * $DAYS_IN_YEAR);
			
		if( $testEndNumber > ($todayNumber - 1) )
		{
			$lineString = $lineData[0]." ".$lineData[1]." ".$lineData[2].", ".$lineData[3]." - ".
											$lineData[4]." ".$lineData[5].", ".chop($lineData[6]);
					
			echo "<tr><td align=center>".$lineData[0].
						"<td align=center>".$lineData[1]." ".$lineData[2].", ".$lineData[3].
						"<td align=center>".$lineData[4]." ".$lineData[5].", ".$lineData[6].
						"<td align=center> <button onClick='DeleteBooking($line)'>Delete</button>";	
		}
	}
	
?>

</table>


<br><br>
<a href="CottagePreviousBookings.php" onclick="return popitup('CottagePreviousBookings.php')">
 Previous bookings </a>
	

<br><hr>
<h4> Book the Cottage </h4>

<form name="CottageBook" method="post" action="CottageBook.php" 
			onsubmit="return VerifyBooking(this);">

<table border="0">
<TR align="left"><TH> Name: <TD> <input name="Name">
<TR align="left"><TH> Start Date: 

<TD> <select name="StartMonth">  
 <option value="Jan"> Jan
 <option value="Feb"> Feb
 <option value="Mar"> Mar
 <option value="Apr"> Apr
 <option value="May"> May
 <option value="Jun"> Jun
 <option value="Jul"> Jul
 <option value="Aug"> Aug
 <option value="Sep"> Sep
 <option value="Oct"> Oct
 <option value="Nov"> Nov
 <option value="Dec"> Dec
</select>
<select name="StartDay">  
 <option value="1"> 1
 <option value="2"> 2
 <option value="3"> 3
 <option value="4"> 4
 <option value="5"> 5
 <option value="6"> 6
 <option value="7"> 7
 <option value="8"> 8
 <option value="9"> 9
 <option value="10"> 10
 <option value="11"> 11
 <option value="12"> 12
 <option value="13"> 13
 <option value="14"> 14
 <option value="15"> 15
 <option value="16"> 16
 <option value="17"> 17
 <option value="18"> 18
 <option value="19"> 19
 <option value="20"> 20
 <option value="21"> 21
 <option value="22"> 22
 <option value="23"> 23
 <option value="24"> 24
 <option value="25"> 25
 <option value="26"> 26
 <option value="27"> 27
 <option value="28"> 28
 <option value="29"> 29
 <option value="30"> 30
 <option value="31"> 31
</select>
<select name="StartYear">  
 <option value="2018"> 2018
 <option value="2019"> 2019
 <option value="2020"> 2020
 <option value="2021"> 2021
 <option value="2022"> 2022
 <option value="2023"> 2023
 <option value="2024"> 2024
 <option value="2025"> 2025
 <option value="2026"> 2026
 <option value="2027"> 2027
</select>

<TR align="left"><TH> End Date: 

<TD> <select name="EndMonth">  
 <option value="Jan"> Jan
 <option value="Feb"> Feb
 <option value="Mar"> Mar
 <option value="Apr"> Apr
 <option value="May"> May
 <option value="Jun"> Jun
 <option value="Jul"> Jul
 <option value="Aug"> Aug
 <option value="Sep"> Sep
 <option value="Oct"> Oct
 <option value="Nov"> Nov
 <option value="Dec"> Dec
</select>
<select name="EndDay">  
 <option value="1"> 1
 <option value="2"> 2
 <option value="3"> 3
 <option value="4"> 4
 <option value="5"> 5
 <option value="6"> 6
 <option value="7"> 7
 <option value="8"> 8
 <option value="9"> 9
 <option value="10"> 10
 <option value="11"> 11
 <option value="12"> 12
 <option value="13"> 13
 <option value="14"> 14
 <option value="15"> 15
 <option value="16"> 16
 <option value="17"> 17
 <option value="18"> 18
 <option value="19"> 19
 <option value="20"> 20
 <option value="21"> 21
 <option value="22"> 22
 <option value="23"> 23
 <option value="24"> 24
 <option value="25"> 25
 <option value="26"> 26
 <option value="27"> 27
 <option value="28"> 28
 <option value="29"> 29
 <option value="30"> 30
 <option value="31"> 31
</select>
<select name="EndYear">  
 <option value="2018"> 2018
 <option value="2019"> 2019
 <option value="2020"> 2020
 <option value="2021"> 2021
 <option value="2022"> 2022
 <option value="2023"> 2023
 <option value="2024"> 2024
 <option value="2025"> 2025
 <option value="2026"> 2026
 <option value="2027"> 2027
</select>

		
</table>
	
<br>
<input type="Submit" value="Submit Booking">
	
</form>

<br>
<button onClick='window.location.href="CottageMain.php"'>Back to Main Page </button>
<br><br><br>


</html>




