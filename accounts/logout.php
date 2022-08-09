<?php
//when user clicks logout they will come to this page
//this page should destroy the session

session_start(); //first start the session
session_destroy(); //then destroy it 

//now redirect to home page
header("Location: index.php");

?>

<?php
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Logout</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
</body>
</html>
