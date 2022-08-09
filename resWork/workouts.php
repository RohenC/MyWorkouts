<?php
require 'config/config.php';

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if($mysqli->connect_errno) {
        echo $mysqli->connect_error;
        exit();
    }

//User will use get request to visit the page via link
//if the user is trying to use the submit button it will use a post request

//big if statement: if the user IS logged in do the normal things, else redirect them away
if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]) {
    //ok so now we knowt that the user is infact logged in and they clicked on the SW page
    
    //now we gotta check if they submitted the form to delete a song
    if (isset($_GET['index']) && isset($_GET['id']))
    {
        //and they aren't empy
        if (!empty($_GET['index']) && !empty($_GET['id'])) 
        {
            $email = $_SESSION["email"];
            $id = $_GET['id'];

            //now we good to delete from db
            $statement = $mysqli->prepare("DELETE FROM saved_workouts WHERE email = ? AND id = ?");
            $statement->bind_param("si", $email, $id);
    
            $executed = $statement->execute();
            if(!$executed) {
                echo $mysqli->error;
            }
            $statement->close();
        }
    }
    
    
    //we should now do a sql query to find their liked exercises
    $sql = "SELECT * FROM saved_workouts WHERE email= '" . $_SESSION["email"] . "';";
    $results = $mysqli->query($sql);

    if ( $results == false ) { 
        echo $mysqli->error;
        exit();
    }
    $numrows = $results->num_rows;
    if ($numrows == 0) {
        $empty = '<h2 class="text">You currently have no Saved Workouts</h2><h2 class="textSmaller">Search and heart exercises to add them here!</h2>';
    }

    //then loop through all those results using php in the html to dipslay results
    $mysqli->close();

    if ( isset($_GET['index']) && !empty($_GET['index']) ) {
        $index = $_GET['index'] - 1; //should return whatevers in the value
        //create a form
        $form = "<form action='' method=''><input type = 'hidden' id = 'index' value = '" . $index . "'></form>";
      }
    else {
    //just add like a default value
        $form = "<form action='' method=''><input type = 'hidden' id = 'index' value = '1'></form>";
    }
}
else {
  //redirect to homepage
  header("Location: login.php");
}
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
    <link rel="stylesheet" href="resWork.css">
</head>
<body>
    <!-- first item: navbar -->
    <?php include 'nav.php'; ?>

    <?php echo $form ?>
    
    <div class="slideshow-container">
      
        <?php if ( isset($empty) && !empty($empty) ) : ?>
            <?php echo $empty; ?></div>
            <?php unset($empty); ?>
        <?php endif; ?>
        
        <?php $i = 1; ?>
        <?php while ( $row = $results->fetch_assoc() ) : ?>
            <?php $form =  '<form id = "' . $i . 100 . '" action = "workouts.php" method = "GET">'
				. '<input type = "hidden" name = "id" value = "' . $row['id'] . '">'
                . '<input type = "hidden" name = "index" value = "' . $i . '">'
                . '<a id="' . $i . '" href="#/" onclick="likeSong(' . $i . ')"><i class="fa-solid fa-heart"></i></a>'
                . '</form>';
            ?>
                <div class="mySlides fade">
                <div class="numbertext"><?php echo $i . " / " . $numrows; ?></div>
                <div class='heart'><?php echo $form; ?></div>  
                <div>
                    <h2 class="text"><?php echo $row['name']; ?></h2>
                    <?php echo $row['description']; ?>
                    <p>Muscles trained: <?php echo $row['muscles']; ?></p>
                    <p>Equipment needed: <?php echo $row['equipment']; ?></p>
                </div>
            </div>

            <?php $i = $i + 1; ?>
        <?php endwhile; ?>

        <a class="prev" onclick="plusSlides(-1)">❮</a>
        <a class="next" onclick="plusSlides(1)">❯</a>
     
    </div>

    <div id="search">
        <h2 class="textSmaller">Return to the Search Page</h2>
        <button type="button" id="sB" class="btn btn-default btn-xl mt-2 mb-2">Search <i class="fa-solid fa-magnifying-glass"></i></button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- JS for button (rn at least) -->
    <script>

    //for search return button
    let searchButton = document.querySelector('#sB')
    searchButton.onclick = function() {
        window.location.href = 'search.php'
    }

    //fix this
      let slideIndex = parseInt(document.querySelector('#index').value); //getting this value from the form
      showSlides(slideIndex);

      function plusSlides(n) {
        showSlides(slideIndex += n);
      }

      function currentSlide(n) {
        showSlides(slideIndex = n);
      }

      function showSlides(n) {
        let i;
        let slides = document.getElementsByClassName("mySlides");
        if (n > slides.length) {slideIndex = 1}    
        if (n < 1) {slideIndex = slides.length}
        for (i = 0; i < slides.length; i++) {
          slides[i].style.display = "none";  
        }
        slides[slideIndex-1].style.display = "block";  
      }

      function likeSong(i) {
            let toggle = document.getElementById(i);
            console.log(i)
            console.log(document.getElementById(i))
            let display = toggle.innerHTML;
            console.log(toggle);
            console.log(toggle.innerHTML);
            console.log(display === '<i class="fa-regular fa-heart"></i>')
            if (display === '<i class="fa-regular fa-heart"></i>') {
                document.getElementById(i).innerHTML = "<i class='fa-solid fa-heart'></i>";
                console.log("in if")
            }
            else {
                document.getElementById(i).innerHTML = '<i class="fa-regular fa-heart"></i>';
                console.log("in else")
            }
            document.getElementById(i+"100").submit();
            //this submits the form to results.php using GET
    }
</script>
</body>
</html>
