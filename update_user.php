<?php
require 'pdf/config.php';

$response = [
    'success' => false,
    'errors' => [],
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_POST['id'];
    $name = trim($_POST['name']);
    $lastname = trim($_POST['lastname']);
    $birthday = trim($_POST['birthday']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);


    if (empty($name)) {
        $response['errors']['name'] = "Please enter a valid name";
    } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
        $response['errors']['name'] = "Only letters and white spaces allowed";
    }


    if (empty($lastname)) {
        $response['errors']['lastname'] = "Please enter a valid last name";
    } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $lastname)) {
        $response['errors']['lastname'] = "Only letters and white spaces allowed";
    }


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


    if (empty($password)) {
        $response['errors']['password'] = "Password cannot be empty";
    } elseif (strlen($password) < 6) {
        $response['errors']['password'] = "Password must be at least 6 characters long";
    }

    if (empty($response['errors'])) {

        $passwordHash = !empty($password) ? password_hash($password, PASSWORD_BCRYPT) : null;


        $query = "UPDATE persons SET name = ?, last_name = ?, birthday = ?, email = ?";
        $params = [$name, $lastname, $birthday, $email];

        if ($passwordHash) {
            $query .= ", password = ?";
            $params[] = $passwordHash;
        }

        $query .= " WHERE id = ?";
        $params[] = $userId;

        $stmt = $conn->prepare($query);
        $stmt->bind_param(str_repeat('s', count($params) - 1) . 'i', ...$params);

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
?>

<?php
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
