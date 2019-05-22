<?php
require_once "config.php";

$name = $username = $password = $confirm_password = "";
$username_err = $password_err = "";

if($_SERVER["REQUEST_METHOD"]=='POST'){
    
    $sql = "SELECT id FROM users WHERE username = :username";
    
    if($statement = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $param_username = trim($_POST["username"]);
            $statement->bindParam(":username", $param_username, PDO::PARAM_STR);
        
        if($statement->execute()){
                // checking if there's already this username in the table
                if($statement->rowCount() == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        unset($statement);
    
    $name = trim($_POST["name"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);
    
    // validate password
    if($password != $confirm_password){
            $password_err = "Password did not match.";
        }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (name, username, password) VALUES (:name, :username, :password)";
         
        if($statement = $pdo->prepare($sql)){
            $param_name = $name;
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            $statement->bindParam(":name", $param_name, PDO::PARAM_STR);
            $statement->bindParam(":username", $param_username, PDO::PARAM_STR);
            $statement->bindParam(":password", $param_password, PDO::PARAM_STR);
            
            //tries to enter the info into the database
            if($statement->execute()){
                setStat();
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        unset($statement);
    }
    
    // Close connection
    unset($pdo);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" type="text/css" href="login.styles.css">
</head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control" pattern="[a-zA-Z ]{6,30}" value="<?php echo $name; ?>">
            </div>
            
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" pattern="[a-zA-Z0-9_]{5,10}" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div> 
            
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" pattern="(?=.*\d)(?=.*[\W])(?=.*[a-z])(?=.*[A-Z]).{8,20}" value="">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" pattern="(?=.*\d)(?=.*[\W])(?=.*[a-z])(?=.*[A-Z]).{8,20}" value="">
            </div>
            
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>    
</body>
</html>