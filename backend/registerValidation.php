<?php
global $conn;
include '../pdf/config.php';

$nameErr=$emailErr=$lastnameErr=$passwordErr=$birthdayErr="";
$name=$email=$lastname=$password=$birthday="";
$errors = false;

if($_SERVER["REQUEST_METHOD"]=="POST") {
    if(empty($_POST["name"])) {
        $nameErr="Please enter a valid name";
        $errors = true;
    }
    else {
        $name = test_input($_POST["name"]);
        if (!preg_match("/^[a-zA-Z-']*$/", $name)) {
            $nameErr = "Only letters and white spaces allowed";
            $errors = true;
        }
    }

    if(empty($_POST["lastname"])) {
        $lastnameErr="Please enter a valid last name";
        $errors = true;

    } else{
        $lastname=test_input($_POST["lastname"]);
        if(!preg_match("/^[a-zA-Z-']*$/", $lastname)) {
            $lastnameErr="Only letters and white spaces allowed";
            $errors = true;

        }
    }

if(empty($_POST["email"])) {
    $emailErr="Valid email address";
    $errors = true;

} else {
    $email= test_input($_POST["email"]);
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr="The email address is incorrect";
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

    if (!$errors) {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);  // For debugging purposes only

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO persons (name, last_name, email, birthday, password) VALUES ('$name', '$lastname', '$email', '$birthday', '$hashed_password')";

        if ($conn->query($query) === TRUE) {
            echo "<script>alert('Registration Successful'); </script>";
        } else {

            echo "Error: " . $query . "<br>" . $conn->error;
        }

        /*$result = $conn->query($query);
        if ($result) {
            echo "<script>alert('Registration Successful'); </script>";
        } else {
            echo "<script>alert('Error registering user');</script>";
        }*/
    }
}
function validateDateOfBirth($birthday)
{
    if (time() < strtotime('+18 years', strtotime($birthday))) {
        return 'Not 18';
    }
    return "user is older than 18 years old";
}


function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


?>