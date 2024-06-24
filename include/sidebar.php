<?php
global $conn;


if (isset($_SESSION['email'])) {
    $currentUser = $_SESSION['email'];
    $sql = "SELECT * FROM persons WHERE email='$currentUser'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $role = $user['role'];
    } else {
        echo "Error fetching user data.";
        exit;
    }
} else {
    echo "User is not logged in.";
    exit;
}

echo "<script>console.log('User role: " . $role . "');</script>";
?>

<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <span>
                       <img alt="image" class="img-circle" src="uploads/<?php echo htmlspecialchars($user['userImage']); ?>" />
                    </span>
                </div>
                <div class="logo-element">
                    IN+
                </div>
            </li>
            <li>
                <a href="index.html"><i class="fa fa-th-large"></i> <span class="nav-label">Options</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    <li><a href="profile.php">Profile</a></li>
                    <?php if ($_SESSION['role']) { ?>
                        <li><a href="dashboard.php">Dashboard</a></li>
                    <?php } ?>

                    <li><a href="login.php">Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>



