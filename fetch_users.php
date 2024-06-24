<?php
include 'pdf/config.php';

$request = $_REQUEST;

$columns = [
    0 => 'id',
    1 => 'name',
    2 => 'last_name',
    3 => 'birthday',
    4 => 'email',
    5 => 'role'
];

// Total records
$query = "SELECT COUNT(*) as total FROM persons";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$totalData = $row['total'];

// Total records with filter
$query = "SELECT COUNT(*) as total FROM persons WHERE 1=1";

if (!empty($request['search']['value'])) {
    $query .= " AND (name LIKE '%" . $request['search']['value'] . "%' ";
    $query .= " OR last_name LIKE '%" . $request['search']['value'] . "%' ";
    $query .= " OR email LIKE '%" . $request['search']['value'] . "%' ";
    $query .= " OR role LIKE '%" . $request['search']['value'] . "%')";
}

$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$totalFiltered = $row['total'];

// Fetch records
$query = "SELECT * FROM persons WHERE 1=1";

if (!empty($request['search']['value'])) {
    $query .= " AND (name LIKE '%" . $request['search']['value'] . "%' ";
    $query .= " OR last_name LIKE '%" . $request['search']['value'] . "%' ";
    $query .= " OR email LIKE '%" . $request['search']['value'] . "%' ";
    $query .= " OR role LIKE '%" . $request['search']['value'] . "%')";
}

$query .= " ORDER BY " . $columns[$request['order'][0]['column']] . " " . $request['order'][0]['dir'] . " LIMIT " . $request['start'] . " ," . $request['length'];

$result = mysqli_query($conn, $query);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $nestedData = [];
    $nestedData[] = $row['id'];
    $nestedData[] = $row['name'];
    $nestedData[] = $row['last_name'];
    $nestedData[] = $row['birthday'];
    $nestedData[] = $row['email'];
    $nestedData[] = ($row['role'] == "0") ? "User" : "Admin";
    $nestedData[] = '<a href="#" class="btn btn-primary edit_data" data-id="' . $row['id'] . '">Edit</a>';
    $nestedData[] = '<a href="delete.php?id=' . $row['id'] . '" class="btn btn-danger">Delete</a>';

    $data[] = $nestedData;
}

$json_data = [
    "draw" => intval($request['draw']),
    "recordsTotal" => intval($totalData),
    "recordsFiltered" => intval($totalFiltered),
    "data" => $data
];

echo json_encode($json_data);
?>
