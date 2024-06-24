<?php

global $conn;
include 'pdf/config.php';

$nameErr = $emailErr = $lastnameErr = $passwordErr = $birthdayErr = $roleErr= "";
$name = $email = $lastname = $password = $birthday = $role= "";
$errors = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["name"])) {
        $nameErr = "Please enter a valid name";
        $errors = true;
    } else {
        $name = test_input($_POST["name"]);
        if (!preg_match("/^[a-zA-Z-']*$/", $name)) {
            $nameErr = "Only letters and white spaces allowed";
            $errors = true;
        }
    }

    if (empty($_POST["lastname"])) {
        $lastnameErr = "Please enter a valid last name";
        $errors = true;

    } else {
        $lastname = test_input($_POST["lastname"]);
        if (!preg_match("/^[a-zA-Z-']*$/", $lastname)) {
            $lastnameErr = "Only letters and white spaces allowed";
            $errors = true;
        }
    }

    if (empty($_POST["email"])) {
        $emailErr = "Valid email address";
        $errors = true;

    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "The email address is incorrect";
            $errors = true;
        }
    }

    if (empty($_POST["birthday"])) {
        $birthdayErr = "Please enter your date of birth";
        $errors = true;

    } else {
        $birthday = test_input($_POST["birthday"]);
        if (validateDateOfBirth($birthday) === 'Not 18') {
            $birthdayErr = "You must be at least 18 years old";
            $errors = true;

        }
    }

    $password = $_POST['password'];
    $number = preg_match('@[0-9]@', $password);
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);

    if (strlen($password) < 8 || !$number || !$uppercase || !$lowercase || !$specialChars) {
        $passwordErr = "Password must be at least 8 characters and must contain at least one number, uppercase letter, lowercase letter, and special character";
        $errors = true;

    }

    if (empty($_POST["role"])) {
        $roleErr = "Please select a role";
        $errors = true;
    } else {
        $role = test_input($_POST["role"]);
    }



        error_reporting(E_ALL);
        ini_set('display_errors', 1);  // For debugging purposes only

        $checkEmail = "SELECT * FROM persons WHERE email = '$email'";
        $result = $conn->query($checkEmail);

        if ($result->num_rows > 0) {
            $emailErr = "Email already exists";
            $errors = true;
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO persons (name, last_name, email, birthday, password, role) VALUES ('$name', '$lastname', '$email', '$birthday', '$hashed_password', '$role')";

            if ($conn->query($query) === TRUE) {
                echo "<script>alert('Registration Successful'); </script>";

            } else {

                echo "Error: " . $query . "<br>" . $conn->error;
            }

        }

}
function validateDateOfBirth($birthday)
{
    if (time() < strtotime('+18 years', strtotime($birthday))) {
        return 'Not 18';
    }
    return "user is older than 18 years old";
}


function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>INSPINIA | Register</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

</head>

<body class="gray-bg">

    <div class="middle-box text-center loginscreen   animated fadeInDown">
        <div>

            <p>Create account </p>
            <form class="m-t" role="form" action="" method="post">
                <div class="form-group">
                    <input type="text" id="name" name="name" class="form-control" placeholder="Name">
                    <div><?php echo $nameErr ?></div>
                </div>
                <div class="form-group">
                    <input type="text" id="lastname" name="lastname" class="form-control" placeholder="LastName">
                    <div><?php echo $lastnameErr ?> </div>
                </div>
                <div class="form-group">
                    <input type="date" id="birthday" name="birthday" class="form-control" placeholder="Birthday">
                    <div><?php echo $birthdayErr ?> </div>
                </div>
                <div class="form-group">
                    <input type="email" id="email" name="email" class="form-control" placeholder="Email">
                    <div><?php echo $emailErr ?> </div>
                </div>
                <div class="form-group">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Password">
                    <div><?php echo $passwordErr ?> </div>
                </div>
                <div class="form-group">
                    <label for="">Give Role</label>
                    <select id="role" name="role" class="form-control" required>
                        <option value="">Select</option>
                        <option value="0">User</option>
                        <option value="1">Admin</option>
                    </select>
                </div>

                <div class="form-group">
                        <div class="checkbox i-checks"><label> <input type="checkbox"><i></i> Agree the terms and policy </label></div>
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">Register</button>

                <p class="text-muted text-center"><small>Already have an account?</small></p>
                <a class="btn btn-sm btn-white btn-block" href="login.php">Login</a>
            </form>
        </div>
    </div>



    <!-- Mainly scripts -->
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- iCheck -->
    <script src="js/plugins/iCheck/icheck.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
        });
    </script>

</body>
</html>
