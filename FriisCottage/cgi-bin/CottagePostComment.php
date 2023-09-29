<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

<SCRIPT LANGUAGE="javascript">
function VerifyComment( data ) 
{
	var DATA_DELIMITER='~';
	
	if( data.Name.value == "" )
	{
		alert('Must type in a name.');
		return false;
	}
	
	myString = new String(data.Name.value);
	if( myString.search( DATA_DELIMITER ) >= 0 )
	{
		alert('Invalid character in name('+DATA_DELIMITER+').');
		return false;
	}
	
	if( data.Comments.value == "" )
	{
		alert('Must type in some comments to post.');
		return false;
	}
	
	myString = new String(data.Comments.value);
	if( myString.search( DATA_DELIMITER ) >= 0 )
	{
		alert('Invalid character in comment('+DATA_DELIMITER+').');
		return false;
	}
	
	return true;
}
</SCRIPT>


<h2>Post a Comment </h2>
<br>

Here you can post a general comment on the main page for everyone to see.<br>
Just enter your name and comment, then select Post.
<br><br><hr><br>

<form name="PostComment" method="post" action="CottageMain.php" 
			onsubmit="return VerifyComment(this);">

Name:<input name="Name">
<br><br>

<textarea name="Comments" ROWS="10" COLS="70" wrap="virtual">
</textarea>
<br>       
  
<input type="submit" value="Post">

</form>

<br>
<button onClick='window.location.href="CottageMain.php"'>Cancel</button>
<br><br><br>


</html>
