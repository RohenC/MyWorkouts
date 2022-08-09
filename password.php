<?php
    require 'config/config.php';

    //User will use get request to visit the page via link
    //if the user is trying to use the submit button it will use a post request

    // input validation using the post method so you know it's only after they try to submit the form
    //secondary validation with PHP
    //only enter if they all exist first
    if ( isset($_POST['email']) && isset($_POST['oldPass']) && isset($_POST['newPass']) ) 
    {
        //now check if any of them are empty (php side validation)
        if (empty($_POST['email']) || empty($_POST['oldPass']) || empty($_POST['newPass']) ) {
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
            $oldPass = hash("sha256", $_POST["oldPass"]);
            $newPass = hash("sha256", $_POST["newPass"]);

            //now we wanna check if the email and password exist in the db
            $sql = "SELECT * FROM users WHERE email= '" . $_POST["email"] . "' AND password= '" . $oldPass . "';";
            $results = $mysqli->query($sql);

            if ( $results == false ) {
                echo $mysqli->error;
                exit();
            }

            $numrows = $results->num_rows; //we want this to be 1 to login

            if ($numrows == 1) {
                //if the email and password exist in the db then we can change the password
                //so sql query to update the password for that specific username to the new one
                $row = $results->fetch_assoc();
                $user = $row['userName'];

                $statement = $mysqli->prepare("UPDATE users SET password = ? WHERE email = ?");
                $statement->bind_param("ss", $newPass, $_POST["email"]);
        
                $executed = $statement->execute();
                if(!$executed) {
                    echo $mysqli->error;
                }
                $statement->close();
            }
            else {
                //otherwise the user isn't registerd so we need to display an error message
                $error = "Incorrect email or password. Please input valid credentials or create an account.";
            }

            $mysqli->close();
        }
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
	<form action="password.php" method="POST">
        <div class="container">
                <?php if ( isset($error) && !empty($error) ) : ?>
					<div class="text-danger error clearMe"><?php echo $error; ?></div>
                <?php endif; ?>
				<?php if (isset($user) && !empty($user)): ?>
					<div class="text-success error clearMe"><?php echo "Password for " . $user . " was succesfully changed" ?> </div>
				<?php endif; ?>
            <div class="row">
                <div class="col-12 col-md-6 p-4">
                <h2><strong>Change your password</strong></h2>
                    <label for="email">Email</label>
                    <br>
                    <div class="hi">
                        <input class="form-control" type="email" name="email" id="email">
                        <small class="invalid-feedback">*Email is required.</small>
                    </div>

                    <label for="oldPass">Old Password</label>
                    <br>
                    <div class="hi">
                        <input class="form-control" type="password" name="oldPass" id = "oldPass">
                        <small class="invalid-feedback">*Old password is required.</small>
                    </div>

                    <label for="newPass">New Password</label>
                    <br>
                    <div class="hi">
                        <input class="form-control" type="password" name="newPass" id = "newPass">
                        <small class="invalid-feedback">*New password is required.</small>
                    </div>

                    <button type="submit" class="blueBack wide" onclick="return confirm('Are you sure you want to modify your password?');"><i class="fa-solid fa-key"></i> Change password</button>     
                </div>
                <div class="col-12 col-md-6 p-4 my-auto">
                    <h2><strong>Already have an account?</strong></h2>
                    <p>Click the button below to log in</p>
                    <button type="button" class="redBack2 wide"><i class="fa-solid fa-arrow-right-to-bracket"></i> Sign in</button>
                    <h2><strong>Want to create an account?</strong></h2>
                    <button type="button" id="help" class="blueBack wide"><i class="fa-solid fa-user-plus"></i> Create Account</button>
                </div>
            </div>
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- JS for button (rn at least) -->
    <script>
        let loginButton = document.querySelector('.redBack2')
        loginButton.onclick = function() {
            console.log("login btn")
            window.location.href = 'login.php'
        }

        let registerButton = document.querySelector('#help')
        registerButton.onclick = function() {
            console.log('in here')
            window.location.href = 'register.php'
        }

        // JS to do server side user-validation
		document.querySelector('form').onsubmit = function(){

            //clear the echoed statement here (if it  exists)
            console.log(document.querySelector(".clearMe"))
            if (document.querySelector(".clearMe") == null)
            {
                console.log("no existy")
            }
            else {
                console.log("it must existy")
                document.querySelector(".clearMe").innerHTML = "";
            }

			if ( document.querySelector('#email').value.trim().length == 0 ) {
                console.log("added em")
				document.querySelector('#email').classList.add('is-invalid');
			} else {
                console.log("removed em")
				document.querySelector('#email').classList.remove('is-invalid');
			}

			if ( document.querySelector('#oldPass').value.trim().length == 0 ) {
                console.log("added op")
				document.querySelector('#oldPass').classList.add('is-invalid');
			} else {
                console.log("removed op")
				document.querySelector('#oldPass').classList.remove('is-invalid');
			}

			if ( document.querySelector('#newPass').value.trim().length == 0 ) {
                console.log("added np")
				document.querySelector('#newPass').classList.add('is-invalid');
			} else {
                console.log("removed np")
				document.querySelector('#newPass').classList.remove('is-invalid');
			}

			return ( !document.querySelectorAll('.is-invalid').length > 0 );
		}
    </script>
</body>
</html>