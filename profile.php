<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Profile</title>

    <link href="../../../Users/User/PhpstormProjects/InternLB/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../../Users/User/PhpstormProjects/InternLB/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="../../../Users/User/PhpstormProjects/InternLB/css/animate.css" rel="stylesheet">
    <link href="../../../Users/User/PhpstormProjects/InternLB/css/style.css" rel="stylesheet">
    <style>
        .profile-image{
            width: 150px;
            height: 150px;
            object-fit: cover;
        }
    </style>

</head>

<body>

<div id="wrapper">
    <?php
    include 'pdf/config.php';
    global $conn;
    include 'include/sidebar.php'; ?>


    <?php

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    $userId = $_SESSION['user_id'];

    $emailErr = $passwordErr = $nameErr = $lastnameErr = $birthdayErr = $userImageErr = "";
    $name = $email = $lastname = $password = $birthday = $userImage = "";
    $errors = false;

    function validateDateOfBirth($birthday) {
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

    // Fetch user data
    $query = "SELECT * FROM persons WHERE id='$userId'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $role = $user['role'];
        $_SESSION['role'] = $role;
    } else {
        echo "User not found.";
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["name"])) {
            $nameErr = "Please enter a valid name";
            $errors = true;
        } else {
            $name = test_input($_POST["name"]);
            if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
                $nameErr = "Only letters and white spaces allowed";
                $errors = true;
            }
        }

        if (empty($_POST["lastname"])) {
            $lastnameErr = "Please enter a valid last name";
            $errors = true;
        } else {
            $lastname = test_input($_POST["lastname"]);
            if (!preg_match("/^[a-zA-Z-' ]*$/", $lastname)) {
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

        if (!empty($_POST['password'])) {
            $password = $_POST['password'];
            $number = preg_match('@[0-9]@', $password);
            $uppercase = preg_match('@[A-Z]@', $password);
            $lowercase = preg_match('@[a-z]@', $password);
            $specialChars = preg_match('@[^\w]@', $password);

            if (strlen($password) < 8 || !$number || !$uppercase || !$lowercase || !$specialChars) {
                $passwordErr = "Password must be at least 8 characters and must contain at least one number, uppercase letter, lowercase letter, and special character";
                $errors = true;
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            }
        }

        if (!$errors) {
            $userImage = $_FILES['userImage']['name'];
            $updateImage = "";

            if ($userImage) {
                $target_dir = "uploads/";
                $target_file = $target_dir . basename($userImage);
                move_uploaded_file($_FILES['userImage']['tmp_name'], $target_file);
                $updateImage = ", userImage='$userImage'";
            }

            $updateSql = "UPDATE persons SET name='$name', last_name='$lastname', birthday='$birthday', email='$email', role='$role'";
            if (!empty($hashed_password)) {
                $updateSql .= ", password='$hashed_password'";
            }
            $updateSql .= "$updateImage WHERE id='$userId'";

            if (mysqli_query($conn, $updateSql)) {
                echo "Profile updated successfully.";
            } else {
                echo "Error updating profile: " . mysqli_error($conn);
            }
        }
    }
    ?>


    <div id="page-wrapper" class="gray-bg">
        <?php include 'include/navbar.php'; ?>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <form class="form-control" role="form" action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <input type="text" id="name" name="name" class="form-control" placeholder="Name" value="<?php echo $user['name']; ?>">
                        <div><?php echo $nameErr ?></div>
                    </div>
                    <div class="form-group">
                        <input type="text" id="lastname" name="lastname" class="form-control" placeholder="LastName" value="<?php echo $user['last_name']; ?>">
                        <div><?php echo $lastnameErr ?> </div>
                    </div>
                    <div class="form-group">
                        <input type="date" id="birthday" name="birthday" class="form-control" value="<?php echo $user['birthday']; ?>">
                        <div><?php echo $birthdayErr ?> </div>
                    </div>
                    <div class="form-group">
                        <input type="email" id="email" name="email" class="form-control" placeholder="Email" value="<?php echo $user['email']; ?>">
                        <div><?php echo $emailErr ?> </div>
                    </div>
                    <div class="form-group">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Password">
                        <div><?php echo $passwordErr ?> </div>
                    </div>
                    <div class="form-group">
                        <div class="form-group">
                        <input type="file" id="userImage" name="userImage" class="form-control" value="<?php echo $user['userImage']; ?>">
                        <div><?php echo $userImageErr ?> </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary block full-width m-b">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php include 'include/footer.php'; ?>
</div>

<!-- Mainly scripts -->
<script src="../../../Users/User/PhpstormProjects/InternLB/js/jquery-3.1.1.min.js"></script>
<script src="../../../Users/User/PhpstormProjects/InternLB/js/bootstrap.min.js"></script>
<script src="../../../Users/User/PhpstormProjects/InternLB/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="../../../Users/User/PhpstormProjects/InternLB/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Custom and plugin javascript -->
<script src="../../../Users/User/PhpstormProjects/InternLB/js/inspinia.js"></script>
<script src="../../../Users/User/PhpstormProjects/InternLB/js/plugins/pace/pace.min.js"></script>

</body>

</html>
