<?php
global $conn;
include 'pdf/config.php';
$query = "SELECT * FROM persons";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INSPINIA | Dashboard v.3</title>
    <link href="../../../Users/User/PhpstormProjects/InternLB/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../../Users/User/PhpstormProjects/InternLB/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../../../Users/User/PhpstormProjects/InternLB/css/animate.css" rel="stylesheet">
    <link href="../../../Users/User/PhpstormProjects/InternLB/css/style.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" rel="stylesheet">
</head>

<body class="fixed-navigation">
<div id="wrapper">
    <?php include 'include/sidebar.php'; ?>

    <div id="page-wrapper" class="gray-bg sidebar-content">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                    <form role="search" class="navbar-form-custom" action="search_results.html">
                        <div class="form-group">
                            <input type="text" placeholder="Search for something..." class="form-control" name="top-search" id="top-search">
                        </div>
                    </form>
                </div>
            </nav>
        </div>

        <div class="container-fluid">
            <div class="row mt-5">
                <div class="col">
                    <div class="card mt-5">
                        <div class="card-header">
                            <h2 class="display-6 text-center">Database Table</h2>
                            <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">Add User</button>
                        </div>
                        <div class="card-body">
                            <table id="example" class="table table-bordered text-center">
                                <thead>
                                <tr class="bg-light text-dark">
                                    <th>User ID</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Birthday</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php while($row = mysqli_fetch_assoc($result)) { ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo $row['name']; ?></td>
                                        <td><?php echo $row['last_name']; ?></td>
                                        <td><?php echo $row['birthday']; ?></td>
                                        <td><?php echo $row['email']; ?></td>
                                        <td>
                                            <?php
                                            if($row['role'] == "0") {
                                                echo "User";
                                            } elseif($row['role'] == "1") {
                                                echo "Admin";
                                            } else {
                                                echo "Invalid User";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-primary edit_data"
                                               data-id="<?php echo $row['id']; ?>"
                                               data-name="<?php echo $row['name']; ?>"
                                               data-lastname="<?php echo $row['last_name']; ?>"
                                               data-birthday="<?php echo $row['birthday']; ?>"
                                               data-email="<?php echo $row['email']; ?>">Edit</a>
                                        </td>
                                        <td><a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include 'include/footer.php'; ?>
    </div>
</div>

<!-- Modal for edit user -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" id="editUserId" name="id">
                    <div class="form-group">
                        <label for="name" class="col-form-label">Name:</label>
                        <input type="text" id="editName" name="name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="lastname" class="col-form-label">Last Name:</label>
                        <input type="text" id="editLastName" name="lastname" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="birthday" class="col-form-label">Birthday:</label>
                        <input type="date" id="editBirthday" name="birthday" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="email" class="col-form-label">Email:</label>
                        <input type="email" id="editEmail" name="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-form-label">Password:</label>
                        <input type="password" id="editPassword" name="password" class="form-control" placeholder="Password">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="updateUser">Update</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for add user -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addUserForm">
                    <div class="form-group">
                        <label for="name" class="col-form-label">Name:</label>
                        <input type="text" id="name" name="name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="lastname" class="col-form-label">Last Name:</label>
                        <input type="text" id="lastname" name="lastname" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="birthday" class="col-form-label">Birthday:</label>
                        <input type="date" id="birthday" name="birthday" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="email" class="col-form-label">Email:</label>
                        <input type="email" id="email" name="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-form-label">Password:</label>
                        <input type="password" id="password" name="password" class="form-control">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="addUser">Add</button>
            </div>
        </div>
    </div>
</div>

<!-- Mainly scripts -->
<script src="../../../Users/User/PhpstormProjects/InternLB/js/jquery-3.1.1.min.js"></script>
<script src="../../../Users/User/PhpstormProjects/InternLB/js/popper.min.js"></script>
<script src="../../../Users/User/PhpstormProjects/InternLB/js/bootstrap.js"></script>
<script src="../../../Users/User/PhpstormProjects/InternLB/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="../../../Users/User/PhpstormProjects/InternLB/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="../../../Users/User/PhpstormProjects/InternLB/js/inspinia.js"></script>
<script src="../../../Users/User/PhpstormProjects/InternLB/js/plugins/pace/pace.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/fixedcolumns/5.0.1/js/dataTables.fixedColumns.js"></script>
<script src="https://cdn.datatables.net/fixedcolumns/5.0.1/js/fixedColumns.dataTables.js"></script>

<script>
        $(document).ready(function() {
        // Initialize DataTable
        var table = $('#example').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "fetch_users.php",
        "columns": [
    {"data": 0, "title": "User ID"},
    {"data": 1, "title": "First Name"},
    {"data": 2, "title": "Last Name"},
    {"data": 3, "title": "Birthday"},
    {"data": 4, "title": "Email"},
    {"data": 5, "title": "Role"},
    {"data": 6, "title": "Edit", "orderable": false, "searchable": false},
    {"data": 7, "title": "Delete", "orderable": false, "searchable": false}
        ]
    });

        // Custom search input
        $('#searchInput').on('keyup', function () {
        table.search(this.value, false, false).draw(); // Disable smart search and regex
    });

        // Delegate event listener for edit buttons to handle dynamically loaded data
        $('#example tbody').on('click', '.edit_data', function () {
        var data = table.row($(this).parents('tr')).data();
        $('#editUserId').val(data[0]);
        $('#editName').val(data[1]);
        $('#editLastName').val(data[2]);
        $('#editBirthday').val(data[3]);
        $('#editEmail').val(data[4]);
        $('#exampleModal').modal('show');
    });

        // Update user
        $('#updateUser').on('click', function () {
        var formData = $('#editForm').serialize();
        $.ajax({
        url: 'update_user.php',
        method: 'POST',
        data: formData,
        success: function (response) {
        $('#exampleModal').modal('hide');
        table.ajax.reload();
    }
    });
    });

        // Add user modal
        $('#addUser').on('click', function () {
        var formData = $('#addUserForm').serialize();
        $.ajax({
        url: 'add_user.php',
        method: 'POST',
        data: formData,
        success: function (response) {
        $('#addUserModal').modal('hide');
        table.ajax.reload();
    }
    });
    });
    });


</script>

</body>
</html>
