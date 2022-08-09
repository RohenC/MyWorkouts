<nav class="navbar navbar-expand-md navbar-light" style="background-color: #183446;">
    <div class="container-fluid">
        <a class="navbar-brand fancy" id="color" href="index.php">MyWorkouts!  <i class="fa-solid fa-dumbbell"></i></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
            <div class="navbar-nav ms-auto">
                <!-- home always there -->
                <a class="nav-link" href="index.php">Home</a>

                <!-- want different results based on if they are logged in or not -->
                <!-- why tf is this saying i'm logged in -->
                <?php if (!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) :?>
                    <!-- if they aren't logged in show them the login button -->
                    <a class="nav-link" href="login.php">Login/Register</a>
                <?php else: ?>
                    <!-- if they are logged in -->
                    <div class="color my-auto">Welcome <?php echo $_SESSION["name"]?>!</div>
                    <!-- add a logout button here too -->
                    <a class="nav-link" href="logout.php">Logout</a>
			    <?php endif; ?>

                <!-- always there -->
                <a class='nav-link' href='workouts.php'>Saved Workouts</a>
            </div>
        </div>
    </div>
</nav>