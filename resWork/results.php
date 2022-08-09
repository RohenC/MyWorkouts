<?php
  
  require "config/config.php";

  $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if($mysqli->connect_errno) {
        echo $mysqli->connect_error;
        exit();
    }

  //start building a hidden form string
  $formBuilder = "<form action='' method=''> ";   	

  //get the submitted form inputs here and create a hidden form input string
  if ( isset($_GET['muscles']) && !empty($_GET['muscles']) ) {
    $muscles = $_GET['muscles']; //should return whatevers in the value
    //add it to the form
    $formBuilder = $formBuilder . "<input type = 'hidden' id = 'muscles' value = '" . $muscles . "'>";
  }
  else {
    //just add like a default all value
    $formBuilder = $formBuilder . "<input type = 'hidden' id = 'muscles' value = ''>";
  }

  if ( isset($_GET['equipment']) && !empty($_GET['equipment']) ) {
    $equipment = $_GET['equipment']; //should return whatevers in the value
    //add it to the form
    $formBuilder = $formBuilder . "<input type = 'hidden' id = 'equipment' value = '" . $equipment . "'>";
  }
  else {
    //just add like a default all value
    $formBuilder = $formBuilder . "<input type = 'hidden' id = 'equipment' value = ''>";
  }

  if ( isset($_GET['index']) && !empty($_GET['index']) ) {
    $index = $_GET['index']; //should return whatevers in the value
    //add it to the form
    $formBuilder = $formBuilder . "<input type = 'hidden' id = 'index' value = '" . $index . "'>";
  }
  else {
    //just add like a default value
    $formBuilder = $formBuilder . "<input type = 'hidden' id = 'index' value = '1'>";
  }

  //now we wanna insert/delete from the db based on whether it already exists or not
  //check if terms exist
  if ( isset($_GET['name']) && isset($_GET['desc']) && isset($_GET['musc']) && isset($_GET['equip']) && isset($_GET['id']) )
  {
      //now check if any of them are empty (php side validation)
      if (empty($_GET['name']) || empty($_GET['desc']) || empty($_GET['musc']) || empty($_GET['equip']) || empty($_GET['id']) ) {
        $response = "Loading Results...";
    }
    //and now check here if the user is actually logged in (can only like songs if logged in)
    if (!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) {
        header("Location: login.php");
    }
    //otherwise we good to go
    else 
    {
        //either insert/delete from db whether id exists in db for that corresponding user or not
        //get user's email
        $email = $_SESSION["email"];
        $id = $_GET['id'];

        //select query
        $sql = "SELECT * FROM saved_workouts WHERE email= '" . $email . "' AND id= '" . $id . "';";
        $results = $mysqli->query($sql);

        if ( $results == false ) {
            echo $mysqli->error;
            exit();
        }

        $numrows = $results->num_rows; //should either be 1 or 0


        if ($numrows == 1) {
            //if the email and workout exist in the db then we should delete it          
            $statement = $mysqli->prepare("DELETE FROM saved_workouts WHERE email = ? AND id = ?");
            $statement->bind_param("si", $email, $id);
    
            $executed = $statement->execute();
            if(!$executed) {
                echo $mysqli->error;
            }
            $statement->close();

            $response = "Removing from Saved Workouts...";
        }
        else {
            //otherwise we can insert it
            $statement = $mysqli->prepare("INSERT INTO saved_workouts (id, name, description, equipment, muscles, email) VALUES (?, ?, ?, ?, ?, ?)");
            $statement->bind_param("isssss", $id, $_GET["name"], $_GET['desc'], $_GET['equip'], $_GET['musc'], $email);
    
            $executed = $statement->execute();
            if(!$executed) {
                echo $mysqli->error;
            }
            $statement->close();

            $response = "Adding to Saved Workouts...";
        }
    }
  }
  else {
      $response = "Loading Results...";
  }

  //now after we do all of that we wanna first check if the user is logged in
  if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"])
  {
    $liked = '';
    //then we can get there email and loop through the returned sql results
    $sql = "SELECT * FROM saved_workouts WHERE email= '" . $_SESSION["email"] . "';";
    $results = $mysqli->query($sql);

    if ( $results == false ) {
        echo $mysqli->error;
        exit();
    }

    //now loop through the results
    while ( $row = $results->fetch_assoc() )
    {
        $liked = $liked . $row['id'] . ' ';
    }

    //store all the ids in the db in a big string and pass that into the form
    $formBuilder = $formBuilder . "<input type = 'hidden' id = 'liked' value = '" . $liked . "'>";

  }
  else {
    //if they're not logged in then they can't have any liked exercises so just pass the empty string to the form ("")
    $formBuilder = $formBuilder . "<input type = 'hidden' id = 'liked' value = ''>";
  }
  
  //end the form
  $formBuilder = $formBuilder . "</form>";
  $mysqli->close();

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
    
    <?php echo $formBuilder ?>

    <div class="slideshow-container">
      
        <!-- each of these mfs are the children -->
        <h2 class="text"><?php echo $response ?></h2>
        <!-- <button type="button" id="rB" class="btn btn-default btn-xl mt-2 mb-2">Results <i class="fa-solid fa-dumbbell"></i></button> -->
     
    </div>

    <div id="search">
        <h2 class="textSmaller">Return to the Search Page</h2>
        <button type="button" id="sB" class="btn btn-default btn-xl mt-2 mb-2">Search <i class="fa-solid fa-magnifying-glass"></i></button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- jquery stuff -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
  	integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
	  crossorigin="anonymous"></script>
    <script>

        //for search return button
        let searchButton = document.querySelector('#sB')
        searchButton.onclick = function() {
            window.location.href = 'search.php'
        }

        // let resultsButton = document.querySelector('#rB')
        // resultsButton.onclick = function() {
        //     console.log("here")
        //     // showSlides(1);
        // }

        //testing form
        let muscles = document.querySelector('#muscles').value;
        console.log(muscles);

        let equipment = document.querySelector('#equipment').value;
        console.log(equipment);

        let liked = document.querySelector("#liked").value;
        console.log(liked)

        //ok so now we have the 2 values we wanna insert into our api endpoint
        //now to actually get the api results (somehow)

        //sample endpoint: https://wger.de/api/v2/exercise/?equipment=1&format=json&language=2&muscles=9%2C12
        //so needa replace commas with '%2C' (or maybe not?)

        //now we needa make a map/dict of #'s --> muscles/equipment
        //and "Not given" to "Not given" cause why not

        //ig i'll try using ajax first
        $.ajax({
        method: "GET",
        url: 'https://wger.de/api/v2/exercise', //do i need a / or ?

        //parameters sent separately
        data: {
            equipment: equipment,
            language: "2",
            muscles: muscles,
            format: "json",
        }
        })
        .done(function(results) {
            //function will run when we get a result from itunes
            console.log(results)
            displayResults(results)
        })
        .fail(function(results) {
            console.log('ERRRRRORR!')
        })

        //display results function
        function displayResults(resultsJS) {

            //i think it's already a json object so i don't need to json parse it
            //let's check here
            console.log(resultsJS.results.length)
            //yayyy

            //ok now we gotta figure out how to extract the right info and insert it into the html(php whatevs)

            numResults = resultsJS.results.length;

            // Clear the previous search result (but also need to remember to add the 2 slide things at the bottom)
            document.querySelector(".slideshow-container").replaceChildren();

            if (numResults == 0) {
                let sorryString = '<h2 class="text">Sorry! There are no Excercises That Match These Search Criteria</h2>'       
                sorryString += '<h2 class="textSmaller">Please try another search</h2>'         
                document.querySelector(".slideshow-container").innerHTML = sorryString;
            }

            // may need to make this its own slide
            // document.querySelector(".slideshow-container").innerHTML = 
            // `<h2 class="text">Click To Display Results!</h2>
            // <button type="button" id="rB" class="btn btn-default btn-xl mt-2 mb-2">Results <i class="fa-solid fa-dumbbell"></i></button>`
            // ;

            //now we needa make a map/dict of #'s --> muscles/equipment
            const mapM = new Map();
            mapM.set('1', 'Biceps Brachii')
            mapM.set('2', 'Anterior Deltoid')
            mapM.set('4', 'Pectoralis Major')
            mapM.set('5', 'Triceps Brachii')
            mapM.set('6', 'Rectus Abdominis')
            mapM.set('7', 'Gastrocnemius')
            mapM.set('8', 'Gluteus Maximus')
            mapM.set('9', 'Trapezius')
            mapM.set('10', 'Quadriceps Femoris')
            mapM.set('11', 'Hamstrings')
            mapM.set('12', 'Latissimus Dorsi')
            mapM.set('13', 'Brachialis')
            mapM.set('14', 'Obliques')
            mapM.set('15', 'Soleus')

            console.log(mapM)
            // console.log(mapM.get(1))
            console.log(mapM.get('1'))

            const mapE = new Map();
            mapE.set('1', 'Barbell')
            mapE.set('2', 'SZ-Bar')
            mapE.set('3', 'Dumbbell')
            mapE.set('4', 'Gym mat')
            mapE.set('6', 'Pull-up bar')
            mapE.set('7', 'No equipment')
            mapE.set('8', 'Bench')
            mapE.set('9', 'Incline bench')
            mapE.set('10', 'Kettlebell')

            console.log(mapE)

            for(let i = 0; i < numResults; i++) {

                let name = resultsJS.results[i].name
                console.log(resultsJS.results[i].name)
                name = name.replaceAll(`"`, `'`)

                let desc = resultsJS.results[i].description
                console.log(resultsJS.results[i].description)
                desc = desc.replaceAll(`"`, `'`)

                let id = resultsJS.results[i].id

                //use the map we made when making the string builders

                let equip = ""
                //dealing with muscles and equipment arrays
                if (resultsJS.results[i].equipment.length == 0) {
                    console.log("Not given")
                    equip = "Not given"
                }
                else {
                    let eBuilder = ""
                    for (let j = 0; j < resultsJS.results[i].equipment.length; j++) {
                        eBuilder += mapE.get(resultsJS.results[i].equipment[j].toString())
                        if (j != resultsJS.results[i].equipment.length -1) {
                        eBuilder += ", "
                        }
                    }
                    console.log(eBuilder);
                    equip = eBuilder
                }

                let musc = ""
                if (resultsJS.results[i].muscles.length == 0) {
                    console.log("Not given")
                    musc = "Not given"
                }
                else {
                    let mBuilder = ""
                    for (let j = 0; j < resultsJS.results[i].muscles.length; j++) {
                        mBuilder += mapM.get(resultsJS.results[i].muscles[j].toString())
                        if (j != resultsJS.results[i].muscles.length -1) {
                        mBuilder += ", "
                        }
                    }
                    console.log(mBuilder);
                    musc = mBuilder
                }

                let index = i + 1;

                //build the heart form here
                    //we want to include all the exercise info that we needa display
                    //and the same search terms that brought us to the page (so we can get back to it)
                    //and the current slide index we're on so we can jump to that one
                let form = `<form id = "` + i+100 + `" action = "results.php" method = "GET">`
            	+ `<input type = "hidden" id = "name" name = "name" value = "` + name + `">`
				+ `<input type = "hidden" id = "desc" name = "desc" value = "` + desc + `">`
				+ `<input type = "hidden" id = "musc" name = "musc" value = "` + musc + `">`
                + `<input type = "hidden" id = "equip" name = "equip" value = "` + equip + `">`
				+ `<input type = "hidden" name = "id" value = "` + id + `">`
                + `<input type = "hidden" name = "index" value = "` + index + `">`
				+ `<input type = "hidden" name = "muscles" value = "` + muscles + `">`
                + `<input type = "hidden" name = "equipment" value = "` + equipment + `">`;

                if (liked.includes(id)) {
                    //then make it have the solid heart
                    form += `<a id="` + i + `" href="#/" onclick="likeSong(` + i + `)"><i class="fa-solid fa-heart"></i></a>`
                }
                else {
                    form += `<a id="` + i + `" href="#/" onclick="likeSong(` + i + `)"><i class="fa-regular fa-heart"></i></a>`
                }
                form += `</form>`;

                //put all the info the api has gathered for me in the slideshow template
                //also make a form for liking the song
                let htmlString = `
                    <div class="mySlides fade">
                        <div class="numbertext">${i+1} / ${numResults}</div>
                        <div class='heart'>${form}</div>  
                        <div>
                            <h2 class="text">${name}</h2>
                            ${desc}
                            <p>Muscles trained: ${musc}</p>
                            <p>Equipment needed: ${equip}</p>
                        </div>
                    </div>
                `;

                document.querySelector(".slideshow-container").innerHTML += htmlString;
            }
            //at the very end add the 2 extra slide things
            let newString = `<a class="prev" onclick="plusSlides(-1)">❮</a>
                             <a class="next" onclick="plusSlides(1)">❯</a>`;
            document.querySelector(".slideshow-container").innerHTML += newString;
            flag = true;
        }

        //I think it's skipping to this too fast before the api results get back
        //need to make sure to wait for the results to come back before doing any of this

        console.log("waiting")

        let slideIndex = parseInt(document.querySelector('#index').value); //getting this value from the form
        //making this part wait
        setTimeout(function(){
            showSlides(slideIndex);
            console.log("done")
        },2500);

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
