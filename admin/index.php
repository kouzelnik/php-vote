<?php
error_reporting(3);
session_start();
?>
<!DOCTYPE html>
<head>
<meta charset="UTF-8">
<?php
// Work around missing functions in old php
if (phpversion() < 5.5)
{
	require_once ('../passwordLib.php');
}

include('../functions.php');
$user = new user($_SESSION["user_username"], 1);
$voting = new voting($_SESSION["user_username"], 1);

if (!isset($_SESSION["user_username"]))
{
	die("Neautorizovaný přístup!!!");
}

if (!isset($_GET['sub']))
{
	$_GET['sub'] = "uvod";
}

if (isset($_POST['username_logout']))
{
	$user->logout(1);
}
if (isset($_POST["voting_name"]))
{
	$voting->create_voting($_POST["voting_name"]);
}

if (isset($_POST["question_name"]))
{
	// Determine number of possibilities
	$j = 1;
	while($i != 1)
	{
		$name = "possibility_" . $j;
		if(isset($_POST[$name]))
		{
			$j = $j + 1;
		}
		else
		{
			$i = 1;
		}
	}
	// Include all posted possibilities into single array
	$i = 1;
	while($i<=$j)
	{
		$name = "possibility_" . $i;
		$possibilities[] = array($_POST[$name]);
		$i = $i + 1;
	}
	$voting->add_question($_GET["voting_edit"], $_POST["question_name"], $possibilities);
}

if (isset($_GET["voting_remove"]))
{
	$voting->delete_voting($_GET["voting_remove"]);
}

if (isset($_POST["username_register"]))
{
	register($_POST["username_register"], $_POST["username_password"], $_POST["username_mail"], $_POST["username_level"]);
}
?>

<link rel="shortcut icon" href="favicon.gif" />
<title>php-vote - Administrace</title>
<meta name="robots" content="noindex,nofollow">
<link rel="stylesheet" type="text/css" href="styles.css"/>
<link rel="stylesheet" type="text/css" href="../css/style.css"/>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
</head>
<body>
<div class="left_menu">
<form method="post">
<input class="tlacitko" name="username_logout" type="submit" value="Odhlásit se" />
</form>
<a href="../"><strong>Přejít na web</strong></a>

<br/>
<hr>
<h3<?php if ($_GET['sub'] == "uvod") echo " id=\"active\" "?>><a href="index.php">Úvod</a></h3>
<h3<?php if ($user->get_level($user->get_cur_username()) == 3) {if ($_GET['sub'] == "uzivatele") echo " id=\"active\" "?>><a href="index.php?sub=uzivatele">Uživatelé</a></h3>
<h3<?php if ($_GET['sub'] == "nastaveni") echo " id=\"active\" "?>><a href="index.php?sub=nastaveni">Nastavení</a><?php } ?></h3>
</div>

<div class="admin">
<?php

switch ($_GET['sub']){
	case "uzivatele":
		include('uzivatele.php');
		break;
	case "nastaveni":
		include('nastaveni.php');
		break;
	default:
		include('uvod.php');

}
?>
</div>
<!--<div class="bottom_panel"></div>-->
</div>
</body>
</html>
