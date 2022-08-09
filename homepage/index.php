<?php
	//call session_start at the very beginning
	session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mochiy+Pop+P+One&family=Sedgwick+Ave&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Indie+Flower&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">
    <title>MyWorkouts!</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/70b040b9ff.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="nav.css">
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <!-- first item: navbar -->
    <?php include 'nav.php'; ?>
    <div id="header">
          <h1 id = 'title'>Welcome to <span class='fancy'>MyWorkouts!  <i class="fa-solid fa-dumbbell"></i></span></h1>
          <h2>Discover new workouts and pursue your goals</h2>
          <h1>Press Search to Get Started</h1>
          <button type="button" class="btn btn-default btn-xl mt-3 mb-4" href="#">Search <i class="fa-solid fa-magnifying-glass"></i></button>
    </div>

    <div id="review-box">
        <div id="stars">
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
        </div>
        <div id="review-text">
            "One of the best websites I've come across. Not only do I love using it but its applications are perfectly catered to my needs."
        </div>
        <div id="review-auth">
            - Rohen Chawla (avid MyWorkouts! <i class="fa-solid fa-dumbbell"></i> user)
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- JS for button (rn at least) -->
    <script>
        let searchButton = document.querySelector('.btn')
        searchButton.onclick = function() {
            window.location.href = 'search.php'
        }
    </script>
</body>
</html>
