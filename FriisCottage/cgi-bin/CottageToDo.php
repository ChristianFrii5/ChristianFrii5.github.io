<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

<SCRIPT LANGUAGE="javascript">

function VerifyItem( data ) 
{
	var DATA_DELIMITER='~';
	
	if( data.Item.value == "" )
	{
		alert('Must type in something.');
		return false;
	}
	
	myString = new String(data.Item.value);
	if( myString.search( DATA_DELIMITER ) >= 0 )
	{
		alert('Invalid character in description('+DATA_DELIMITER+').');
		return false;
	}
	
	return true;
}

function DeleteItem( index ) 
{
	var where_to = confirm('Are you sure you want to delete this item?' );
 	if (where_to == true)
 	{
 		window.location="CottageToDo.php?index=" + index;
 	}
 	else
 	{
 		window.location="CottageToDo.php";
 	}
}

</SCRIPT>


<h2> Cottage ToDo List </h2>
<br>

Here you can add to-do items at the cottage. This might include fixing something <br>
or cleaning something that hasen't been cleaned in awhile. <br>
Remember to delete the items as they are completed.
<br><br>


<?php
		
/* Christian: enable to debug php */
/*
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
*/

	define( "TODOLIST_DATA_FILE",	"ToDoListData" );
	define( "DATA_DELIMITER",			"~"); 

	$item = $_POST['Item'];
	$deleteIndex = $_GET['index'];
	
	if( $item != "" )
	{
		/* This script was sent an item to add to the list. */
		
		$fp = fopen(TODOLIST_DATA_FILE, 'a+'); 
		if(!$fp) 
		{ 
			echo "<script>alert('Error:Could not open the file.')</script>";
		} 
		else 
		{
			fwrite( $fp, "$item".DATA_DELIMITER );
	
			fclose($fp);
		}
	}
	else if( $deleteIndex != "" )
	{
		/* This script was sent an index of an item to delete. */
		
		$fileString = chop( file_get_contents(TODOLIST_DATA_FILE) );
		$fileData = explode( DATA_DELIMITER, $fileString );
		$size = count($fileData) - 1;		// explode always return n+1 items
		
		$fp = fopen(TODOLIST_DATA_FILE, 'w'); 
		if(!$fp) 
		{ 
			echo "<script>alert('Error:Could not open the file.')</script>"; 
		} 
		else 
		{	
			for( $index=0; $index<$size; $index++ )
			{	
				if( $index != $deleteIndex )
				{			
					fputs( $fp, $fileData[$index].DATA_DELIMITER );
				}
			}
		}
	}


	/* Display all the items in the file. */
	echo "<table align='center' border='1'>";
	
	$fileString = chop( file_get_contents(TODOLIST_DATA_FILE) );
	$fileData = explode( DATA_DELIMITER, $fileString );
	$size = count($fileData) - 1;		// explode always return n+1 items
	for( $index=0; $index<$size; $index++ )
	{	
		$lineData = $fileData[$index];
		echo "<tr><td width='300'>".$lineData."<td align='center'> ".
					"<button onClick='DeleteItem($index)'>Delete</button>";	
	}

	echo "</table>";

?>



<br><br><hr><br>	
<form name="CottageAddItem" method="post" action="CottageToDo.php" 
			onsubmit="return VerifyItem(this);">
			
Add to-do item:<br>
<textarea name="Item" ROWS="3" COLS="30" wrap="virtual"></textarea>
<br><br>
	
	<input type="Submit" value="Submit">
	
</form>

<br>
<button onClick='window.location.href="CottageMain.php"'>Back to Main Page </button>
<br><br><br>
	

</html>
