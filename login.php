<?php
session_start();
 
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: home.php");
    exit;
}
 
require_once "config.php";
 
$email = $password = "";
$email_err = $password_err = $login_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter email.";
    } else{
        $email = trim($_POST["email"]);
    }
    
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    if(empty($email_err) && empty($password_err)){
        $sql = "SELECT id, email, password FROM users WHERE email = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            $param_email = $email;
            
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    mysqli_stmt_bind_result($stmt, $id, $email, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["email"] = $email;                            
                            
                            // Redirect user to home page
                            header("location: home.php");
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid email or password.";
                        }
                    }
                } else{
                    // email doesn't exist, display a generic error message
                    $login_err = "Invalid email or password.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;500;600&family=Raleway:wght@300;400;500;600&display=swap" rel="stylesheet">
    <title>Document</title>
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <h1>Company</h1>
            </div>
            <ul class="nav-menu">
                <li>
                    <a href="index.php">Home</a>
                </li>
                <li>
                    <a href="#!">Products <span>+</span></a>
                    <ul class="sub-menu">
                        <li>
                            <a href="products.php">All products</a>
                        </li>
                        <li>
                            <a href="single-product.php">Single product</a>
                        </li>
                        <li>
                            <a href="coming-soon.php">Coming Soon</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="contact.php">Contact</a>
                </li>
            </ul>
            <div class="authentication">
                <ul>
                    <li>
                        <a href="login.php">Login</a>
                    </li>
                    <li>
                        <a href="logout.php">Logout</a>
                    </li>
                    <li>
                        <a href="register.php">Register</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <main>
        <section class="registration e-padding">
            <div class="container">
                <div class="title">
                    <h1>Login</h1>
                </div>
                <div class="row">
                    <div class="wrapper">
                        <?php 
                        if(!empty($login_err)){
                            echo '<div class="alert alert-danger">' . $login_err . '</div>';
                        }        
                        ?>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="input-control">
                                <label for="email">Email Address</label>
                                <input type="email" name="email" id="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                                <span class="invalid-feedback"><?php echo $email_err; ?></span>
                            </div>    
                            <div class="input-control">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                                <span class="invalid-feedback"><?php echo $password_err; ?></span>
                            </div>
                            <div class="input-control">
                                <button type="submit">Login</button>
                            </div>
                            <div class="register-redirect">
                                <a href="register.html">Don't have an account?</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <footer class="e-padding">
        <div class="container">
            <div class="row">
                <article>
                    <h2>Company</h2>
                    <ul>
                        <li>
                            <p>About Us</p>
                        </li>
                        <li>
                            <p>Our Team</p>
                        </li>
                        <li>
                            <p>Blogs</p>
                        </li>
                        <li>
                            <p>Gift Cards</p>
                        </li>
                    </ul>
                </article>
                <article>
                    <h2>Services</h2>
                    <ul>
                        <li>
                            <p>Shipping</p>
                        </li>
                        <li>
                            <p>Tracking</p>
                        </li>
                        <li>
                            <p>Returns</p>
                        </li>
                    </ul>
                </article>
                <article>
                    <h2>Contact</h2>
                    <ul>
                        <li>
                            <p>Email: info@company.com</p>
                        </li>
                        <li>
                            <p>Phone: +181 65 87 66 33 15</p>
                        </li>
                        <li>
                            <p>Address: New York, Queens, 21st</p>
                        </li>
                    </ul>
                </article>
                <article class="social-media">
                    <h2>Follow Us</h2>
                    <ul>
                        <li>
                            <a href="#!">
                                <ion-icon name="logo-facebook"></ion-icon>
                            </a>
                        </li>
                        <li>
                            <a href="#!">
                                <ion-icon name="logo-twitter"></ion-icon>
                            </a>
                        </li>
                        <li>
                            <a href="#!">
                                <ion-icon name="logo-instagram"></ion-icon>
                            </a>
                        </li>
                        <li>
                            <a href="#!">
                                <ion-icon name="logo-linkedin"></ion-icon>
                            </a>
                        </li>
                    </ul>
                </article>
            </div>
        </div>
    </footer>
    <script src="assets/js/script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>