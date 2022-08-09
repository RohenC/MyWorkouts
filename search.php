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
    <link rel="stylesheet" href="search.css">
</head>
<body>
    <!-- first item: navbar -->
    <?php include 'nav.php'; ?>

    <!-- div box with the inputs and the search stuff -->
    <!-- hardcode for now -->
    <!-- put whole thing in a form too -->
    <form action="results.php?" method="GET">
        <div class="container">
            <!-- top row containing the two search inputs -->
            <div class="row">
                <div class="col-12 col-md-6 p-4">
                    <h2><strong>Input which equipment you would like to use </strong></h2>
                    <!-- dropdown menu -->
                    <select name="equipment" class="size form-control">
                        <option value="">All</option>
                        <option value="7">No equipment</option>
                        <option value="1">Barbell</option>
                        <option value="2">SZ-Bar</option>
                        <option value="3">Dumbbell</option>
                        <option value="4">Gym mat</option>
                        <option value="6">Pull-up bar</option>
                        <option value="8">Bench</option>
                        <option value="9">Incline bench</option>
                        <option value="10">Kettlebell</option>
                    </select>
                </div>
                <div class="col-12 col-md-6 p-4">
                    <h2><strong>Which muscle groups would you like to train</strong></h2>
                    <select name="muscles" class="size form-control">
                        <option value="">All</option>
                        <option value="1,13">Biceps</option>
                        <option value="5">Triceps</option>
                        <option value="2">Shoulders</option>
                        <option value="4">Chest</option>
                        <option value="9,12">Back</option>
                        <option value="6,14">Abs</option>
                        <option value="8">Glutes</option>
                        <option value="10">Quads</option>
                        <option value="11">Hamstrings</option>
                        <option value="7,15">Calves</option>
                    </select>
                </div>
            </div>
                <!-- This one has the search button -->
                <button type="submit" class="btn btn-default btn-xl mt-4 mb-2">Search <i class="fa-solid fa-magnifying-glass"></i></button>
        </div>
    </form>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>
</html>