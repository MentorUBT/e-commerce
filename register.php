<?php

require_once "config.php";
 

$email = $password = $confirm_password = "";
$email_err = $password_err = $confirm_password_err = "";
 

if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter a email.";
    } elseif(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
        $email_err = "Please enter a valid enter";
    } else{
        $sql = "SELECT id FROM users WHERE email = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            $param_email = trim($_POST["email"]);
            
            if(mysqli_stmt_execute($stmt)){

                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email_err = "This email is already taken.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            mysqli_stmt_close($stmt);
        }
    }
    
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    if(empty($email_err) && empty($password_err) && empty($confirm_password_err)){
        
        $sql = "INSERT INTO users (email, password) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "ss", $param_email, $param_password);
            
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); 

            if(mysqli_stmt_execute($stmt)){
                header("location: login.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            mysqli_stmt_close($stmt);
        }
    }
    
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
    <link href="https://fonts.googleapis.com/css2?family=Lora&family=Raleway:wght@300;400;500;600&display=swap" rel="stylesheet">
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
                    <h1>Create a new Account</h1>
                </div>
                <div class="row">
                    <div class="wrapper">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="form" method="post">
                            <div id="error"></div>
                            <div class="input-control">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                                <span class="invalid-feedback"><?php echo $email_err; ?></span>
                            </div>    
                            <div class="input-control">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                                <span class="invalid-feedback"><?php echo $password_err; ?></span>
                            </div>
                            <div class="input-control">
                                <label for="confirm_password">Confirm Password</label>
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                            </div>
                            <div class="input-control">
                                <button type="submit">Register</button>
                            </div>
                            <div class="register-redirect">
                                <a href="login.php">Already have an account? Login here.</a>
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