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
		alert('Invalid character in item('+DATA_DELIMITER+').');
		return false;
	}
	
	return true;
}

function DeleteItem( index ) 
{
	var where_to = confirm('Are you sure you want to delete this item?\n\n' );
 	if (where_to == true)
 	{
 		window.location="CottageItems.php?index=" + index;
 	}
 	else
 	{
 		window.location="CottageItems.php";
 	}
}

</SCRIPT>


<h2> Required Items </h2>
<br>

Here you can add or remove various items that are needed at the cottage.<br>
If you find we are out of an item or running low, add the item to this list.<br>
If you are coming up to the cottage, refer to this list and buy the items that are needed.<br><br>

<b>IMPORTANT</b>: Please be sure to delete the item if you bring it to the cottage.<br><br><br>

<table align="center" border="1">
<tr> <th width='200'>Item<th width='100'>Date Added<th>Delete Item


<?php

/* Christian: enable to debug php */
/*
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
*/
		
	define( "ITEMLIST_DATA_FILE",	"ItemListData" );
	define( "DATA_DELIMITER",		"~"); 
	
	$item = $_POST['Item'];
	$deleteIndex = $_GET['index'];
	
	if( $item != "" )
	{
		/* This script was sent an item to add to the list. */
		
		$fp = fopen(ITEMLIST_DATA_FILE, 'a+'); 
		if(!$fp) 
		{ 
			echo "<script>alert('Error:Could not open the file.')</script>";
		} 
		else 
		{
			fwrite( $fp, "$item".DATA_DELIMITER.date('M d\, Y')."\n" );
	
			fclose($fp);
		}
	}
	else if( $deleteIndex != "" )
	{
		/* This script was sent an index of an item to delete. */
		
		$fileData = file(ITEMLIST_DATA_FILE);
		$size = count($fileData);

		$fp = fopen(ITEMLIST_DATA_FILE, 'w'); 
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

	/* Display all the items in the file. */
	$fileData = file( ITEMLIST_DATA_FILE );
	$size = count($fileData);
	for( $line=0; $line<$size; $line++ )
	{
		$lineData = explode( DATA_DELIMITER, $fileData[$line] );

		echo "<tr><td align=center>".$lineData[0]."<td align=center>".$lineData[1].
						"<td align=center> <button onClick='DeleteItem($line)'>Delete</button>";	
	}

?>

</table>


<br><br><hr><br>	
<form name="CottageAddItem" method="post" action="CottageItems.php" 
			onsubmit="return VerifyItem(this);">
			
	Add Item: <TD> <input name="Item">
	<input type="Submit" value="Submit">
	
</form>

<br>
<button onClick='window.location.href="CottageMain.php"'>Back to Main Page </button>
<br><br><br>
	

</html>
