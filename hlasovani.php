<?php
if(!isset($_POST["voting_code"]))
{
	// Somebody tried to load file directly, die in pain!
	die();
}

if ((isset($_POST["voting_code"])) AND ($voting->voting_exists($_POST["voting_code"]) != 1))
{
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?stranka=kod">';
	// Header won't work here, 
	die(); // And this is ugly, AJAX should be better in this case
}

// Check if somebody voted
if(isset($_GET["vote"]))
{
	$voting->write_vote($_POST["voting_user"], $_POST["voting_code"], $_GET["vote"]);

}

else
{
	$header = $voting->view_voting($_POST["voting_code"]);
	echo "<h2>" . $header . "</h2>";
	$id = $_POST["voting_code"];
	$file_name = "voting/" . $id;
	$file = file($file_name);
	$i = 1;
	while($i < sizeof($file))
	{
		$possibilities[] = array($file[$i]);
		$i = $i + 1;
	}
	echo print_r($possibilities);
	$i = 1;
	foreach ($voting->get_possibilities($_POST["voting_code"]) as $pos)
	{
		echo "
		<a href=\"index.php?stranka=hlasovani&vote=" . $i . "\"><div value=\"" . $i . "\" id=\"Poll_1\">
	<span>" . $pos[0] . "</span>
	</div></a>
		";
		$i = $i + 1;
	}
}
?>