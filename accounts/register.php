<?php
require 'config/config.php';

//User will use get request to visit the page via link
//if the user is trying to use the submit button it will use a post request

//big if statement: if the user isn't logged in do the normal things, else redirect them away

if (!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) {

// input validation using the post method so you know it's only after they try to submit the form
//secondary validation with PHP
    //only enter if they all exist first
    if ( isset($_POST['email']) && isset($_POST['name']) && isset($_POST['password']) ) 
    {
        //now check if any of them are empty (php side validation)
        if (empty($_POST['email']) || empty($_POST['name']) || empty($_POST['password']) ) {
            $error = "Please fill out all required fields.";
        }       
        //otherwise we good to go
        else {

            $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            if($mysqli->connect_errno) {
                echo $mysqli->connect_error;
                exit();
            }

            //hash the password
            $passwordInput = hash("sha256", $_POST["password"]);

            //now we wanna check if the email already exists in the db
            $statement_registered = $mysqli->prepare("SELECT * FROM users WHERE email=?");
            $statement_registered->bind_param("s", $_POST["email"]);
            $executed_registered = $statement_registered->execute();

            if(!$executed_registered) {
                echo $mysqli->error;
            }

            //with prepared stmnts once you get results call store_result() to get the results
            $statement_registered->store_result();
            $numrows = $statement_registered->num_rows; //we want this to be 0 to create the account

            $statement_registered->close();

            if ($numrows > 0) {
                //user/email already exists
                $error = "Email already registered. Please login or register with a different account";
            }
            else {

                // echo $_POST["name"] . $_POST["email"] . $passwordInput;

                //then we want to insert there info into the db
                $statement = $mysqli->prepare("INSERT INTO users (userName, email, password) VALUES (?, ?, ?)");
                $statement->bind_param("sss", $_POST["name"], $_POST["email"], $passwordInput);
        
                $executed = $statement->execute();
                if(!$executed) {
                    echo $mysqli->error;
                }
                $statement->close();

                //now we want to make the session say we logged in and redirect to the homapgae

                //log in (save their info with sessions, stored in key val pairs ig)
                $_SESSION["logged_in"] = true;
                $_SESSION["name"] = $_POST["name"];
                $_SESSION["email"] = $_POST["email"];
                //redirect to the home page
                // header() to make GET request and pass in the pathin
                header("Location: index.php");
            }

            $mysqli->close();
        }
    }
}
else {
	//redirect to homepage
	header("Location: index.php");
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
    <title>Sign in</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/70b040b9ff.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="nav.css">
    <link rel="stylesheet" href="logRes.css">
</head>
<body>
    <!-- first item: navbar -->
    <?php include 'nav.php'; ?>
    <!-- login and register stuff -->

    <!-- submitting the form to itself using the POST method -->
	<form action="register.php" method="POST">
        <div class="container">
            <?php if ( isset($error) && !empty($error) ) : ?>
                <?php ob_start(); ?> 
                <div class="text-danger error" id="clearMe"><?php echo $error; ?></div>
                <?php unset($error); ?>
            <?php endif; ?>
            <div class="row">
                <div class="col-12 col-md-6 p-4">
                <h2><strong>Register</strong></h2>
                    <label for="email">Email</label>
                    <br>
                    <div class="hi">
                        <input class="form-control size" type="email" name="email" id="email">
                        <small id="email-error" class="invalid-feedback">*Email is required.</small>
                    </div>

                    <label for="name">Name</label>
                    <br>
                    <div class="hi">
                        <input class="form-control size" type="text" name="name" id = "name">
                        <small id="name-error" class="invalid-feedback">*Name is required.</small>
                    </div>

                    <label for="password">Password</label>
                    <br>
                    <div class="hi">
                        <input class="form-control size" type="password" name="password" id = "password">
                        <small id="password-error" class="invalid-feedback">*Password is required.</small>
                    </div>

                    <button type="submit" class="redBack wide"><i class="fa-solid fa-user-plus"></i> Create Account</button>      
                </div>
                <div class="col-12 col-md-6 p-4 my-auto">
                    <h2><strong>Already have an account?</strong></h2>
                    <p>Click the button below to log in</p>
                    <button type="button" class="redBack2 wide"><i class="fa-solid fa-arrow-right-to-bracket"></i> Sign in</button>
                    <h2><strong>Forgot your Password?</strong></h2>
                    <button type="button" class="blueBack wide"><i class="fa-solid fa-key"></i> Change password</button>
                </div>
            </div>
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- JS for button (rn at least) -->
    <script>
        let loginButton = document.querySelector('.redBack2')
        loginButton.onclick = function() {
            window.location.href = 'login.php'
        }

        let passButton = document.querySelector('.blueBack')
        passButton.onclick = function() {
            window.location.href = 'password.php'
        }

        // JS to do server side user-validation
		document.querySelector('form').onsubmit = function(){

            //clear the echoed statement here (if it  exists)
            console.log(document.querySelector("#clearMe"))
            if (document.querySelector("#clearMe") == null)
            {
                console.log("no existy")
            }
            else {
                console.log("it must existy")
                document.querySelector("#clearMe").innerHTML = "";
            }

            // if(!document.querySelector("#clearMe"))
            // {
            //     document.querySelector("#clearMe").innerHTML = "";
            // }

			if ( document.querySelector('#email').value.trim().length == 0 ) {
				document.querySelector('#email').classList.add('is-invalid');
			} else {
				document.querySelector('#email').classList.remove('is-invalid');
			}

			if ( document.querySelector('#name').value.trim().length == 0 ) {
				document.querySelector('#name').classList.add('is-invalid');
			} else {
				document.querySelector('#name').classList.remove('is-invalid');
			}

			if ( document.querySelector('#password').value.trim().length == 0 ) {
				document.querySelector('#password').classList.add('is-invalid');
			} else {
				document.querySelector('#password').classList.remove('is-invalid');
			}

			return ( !document.querySelectorAll('.is-invalid').length > 0 );
		}
    </script>
</body>
</html>
