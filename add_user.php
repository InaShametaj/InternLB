<?php
global $conn;
require 'pdf/config.php';

$response = [
    'success' => false,
    'errors' => [],
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $lastname = trim($_POST['lastname']);
    $birthday = trim($_POST['birthday']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate name
    if (empty($name)) {
        $response['errors']['name'] = "Please enter a valid name";
    } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
        $response['errors']['name'] = "Only letters and white spaces allowed";
    }

    // Validate last name
    if (empty($lastname)) {
        $response['errors']['lastname'] = "Please enter a valid last name";
    } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $lastname)) {
        $response['errors']['lastname'] = "Only letters and white spaces allowed";
    }

    // Validate email
    if (empty($email)) {
        $response['errors']['email'] = "Please enter a valid email address";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['errors']['email'] = "Invalid email format";
    }

    // Validate birthday
    if (empty($birthday)) {
        $response['errors']['birthday'] = "Please enter a valid birthday";
    } elseif (validateDateOfBirth($birthday) !== true) {
        $response['errors']['birthday'] = "You must be at least 18 years old";
    }

    // Validate password
    if (empty($password)) {
        $response['errors']['password'] = "Password cannot be empty";
    } elseif (strlen($password) < 8) {
        $response['errors']['password'] = "Password must be at least 8 characters long";
    }

    if (empty($response['errors'])) {
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO persons (name, last_name, birthday, email, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('sssss', $name, $lastname, $birthday, $email, $passwordHash);

        if ($stmt->execute()) {
            $response['success'] = true;
        } else {
            $response['errors']['general'] = "Database error: " . $stmt->error;
        }

        $stmt->close();
    }
}

echo json_encode($response);
exit;

function validateDateOfBirth($birthday) {
    if (time() < strtotime('+18 years', strtotime($birthday))) {
        return 'Not 18';
    }
    return true;
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

