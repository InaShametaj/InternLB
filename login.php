<?php
global $conn;
include 'pdf/config.php';

$emailErr = $passwordErr = "";
$email = $password = "";
$errors = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["email"])) {
        $emailErr = "Please enter your email";
        $errors = true;
    } else {
        $email = test_input($_POST["email"]);
    }

    if (empty($_POST["password"])) {
        $passwordErr = "Please enter your password";
        $errors = true;
    } else {
        $password = test_input($_POST["password"]);
    }

    if (!$errors) {
        $email = mysqli_real_escape_string($conn, $email);
        $password = mysqli_real_escape_string($conn, $password);
        $query = "SELECT * FROM persons WHERE email='$email'";
        $result = $conn->query($query);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['email'] = $email;
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['role'] = $row['role'];
                header("Location: profile.php");
                exit();
            } else {
                $passwordErr = "Incorrect password";
            }
        } else {
            $emailErr = "Email not found";
        }
    }
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

    <title>INSPINIA | Login</title>

    <link href="../../../Users/User/PhpstormProjects/InternLB/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../../Users/User/PhpstormProjects/InternLB/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="../../../Users/User/PhpstormProjects/InternLB/css/animate.css" rel="stylesheet">
    <link href="../../../Users/User/PhpstormProjects/InternLB/css/style.css" rel="stylesheet">

</head>

<body class="gray-bg">

<div class="middle-box text-center loginscreen animated fadeInDown">
    <div>
        </p>
        <p>Login in.</p>
        <form class="m-t" role="form" action="" method="post">
            <div class="form-group">
                <input type="text" id="email" name="email" class="form-control" placeholder="Email">
                <div><?php echo $emailErr ?></div>
            </div>
            <div class="form-group">
                <input type="password" id="password" name="password" class="form-control" placeholder="Password">
                <div><?php echo $passwordErr ?></div>
            </div>
            <button type="submit" name="submit" class="btn btn-primary block full-width m-b">Login</button>

            <p class="text-muted text-center"><small>Do not have an account?</small></p>
            <a class="btn btn-sm btn-white btn-block" href="register.php">Create an account</a>
        </form>
    </div>
</div>

<!-- Mainly scripts -->
<script src="../../../Users/User/PhpstormProjects/InternLB/js/jquery-3.1.1.min.js"></script>
<script src="../../../Users/User/PhpstormProjects/InternLB/js/bootstrap.min.js"></script>


</body>
</html>