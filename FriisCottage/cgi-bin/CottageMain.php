<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

<h1 align=center> Welcome to Friis Cottage Country </h1>
<h1 align="center"> <img src="../cottage.jpg" alt="Cottage"> </h1>

<hr><br>
<table border="0">

<tr><td valign="top" width=200>
<br><br><br>
<a href="CottagePostComment.php">Post a Comment</a>
<br><br>
<a href="../CottageDirections.html">Cottage Directions</a>
<br><br>
<a href="CottageBook.php">Book the Cottage</a>
<br><br>
<a href="CottageItems.php">Items needed at the Cottage</a>
<br><br>
<a href="CottageToDo.php">Cottage To Do List</a>
<br><br>
<a href="CottageMaintenance.php">Cottage Maintenance</a>
<br><br>
</td>

<td align="center"><h3>Comments</h3>

<?php
	
/* Christian: enable to debug php */
/*
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
*/

	define( "COMMENT_DATA_FILE",	"CommentData" );
	define( "DATA_DELIMITER",			"~"); 

	$name 			= $_POST['Name'];
	$comments		= $_POST['Comments'];
	
	if( $name != "" )
	{
		/* A comment is being posted with a a name and comments string. */
		
		//Imports old data
		$fp = fopen(COMMENT_DATA_FILE, "r");
		$old_content = fread($fp, filesize(COMMENT_DATA_FILE));
		fclose($fp);

		$fp = fopen(COMMENT_DATA_FILE, 'w'); 
		if(!$fp) 
		{ 
			echo "<script>alert('Error:Could not open the file.')</script>";
		} 
		else 
		{
			/* Write out new data concatenating the old data so the 
				current log appears first in the file. 	*/
			fwrite( $fp, "$name".DATA_DELIMITER.date('g:ia'.DATA_DELIMITER.'M d\, Y').
							DATA_DELIMITER."$comments\n".DATA_DELIMITER.$old_content );
			
			fclose($fp);
		}
	}
		
	/* Display all the comments in the comment file. */
	
	$fileString = chop( file_get_contents(COMMENT_DATA_FILE) );
	$fileData = explode( DATA_DELIMITER, $fileString );
	$size = count($fileData) - 1;		// split always return n+1 items
	
	echo "<br><table border='1'>";

	for( $index=0; $index<$size; $index++ )
	{	
		if( $index%4 == 0 )
		{
			echo "<tr><td width='130' align='center'>";
		}
		else if( $index%4 == 3 )
		{
			echo "<td width='500'>";
		}
		else
		{
			echo "<br>";
		}
		
		echo $fileData[$index];
	}
	echo "</table>";

?>


</table>
<br><br><br><br>

</html>
