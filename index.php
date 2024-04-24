<?php
require 'function.php';
$select = new Select();
if (!empty($_SESSION["id"])) {
    $user = $select->selectUserById($_SESSION["id"]);
} else {
    header("Location: login.php");
    exit; // Add exit to prevent further execution
}
// sorting the names ASC
$tablename = 'users';
// $sortField = 'first_name'; // Default sort field
// $sortOrder = 'ASC'; // Default sort order
$sortField = isset($_GET['sortField']) ? $_GET['sortField'] : 'first_name';
$sortOrder = isset($_GET['sortOrder']) && in_array($_GET['sortOrder'], ['ASC', 'DESC']) ? $_GET['sortOrder'] : 'ASC';
$sql = "SELECT * FROM `$tablename` ORDER BY `$sortField` $sortOrder";

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="css/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>PHP CRUD Application</title>
    <style>
        .input {
            color: #10421d;
            border: 2px solid #0e4615;
            border-radius: 5px;
            background: transparent;
            max-width: 190px;
        }

        .input:active {
            box-shadow: 2px 2px 15px #0c4f1b inset;
        }
        th{
            cursor: pointer;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-light justify-content-between fs-3 mb-5" style="background-color: #4caf50;">
        <h3 class="mx-3"><?php echo $user["name"]; ?></h3>
        PHP Complete CRUD Application
        <a class="mx-3" href="logout.php"><button class="button">Logout</button></a>
    </nav>
    <div class="container">
        <?php
        if (isset($_GET['msg'])) {
            $msg = $_GET['msg'];
            echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                ' . $msg . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
        }
        ?>
        <div class="d-flex justify-content-between">
            <div>
                <a href="add_new.php" class="btn btn-primary mb-3">Add New</a>
                <a href="generate_pdf.php" class="btn btn-secondary mb-3">PDF</a>
                <a href="export_excel.php" class="btn btn-success mb-3">Export To Excel</a>
                <a href="email.php" class="btn btn-warning mb-3">Send Email</a>
                <!-- Changed to a link -->
            </div>
            <form action="" method="GET">
                <input type="text" name="search" class="input" placeholder="Search..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                <button class="button">Explore</button>
            </form>
        </div>
        <table class="table table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th scope="col" onclick="sortItems('id')">ID</th>
                    <th scope="col" onclick="sortItems('first_name')">First Name</th>
                    <th scope="col" onclick="sortItems('last_name')">Last Name</th>
                    <th scope="col" onclick="sortItems('email')">Email</th>
                    <th scope="col" onclick="sortItems('gender')">Gender</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include "db_conn.php";
                $start_from = 0;
                $row_per_page = 8;
                if (isset($_GET["page"])) {
                    $page = $_GET["page"];
                    $start_from = ($page - 1) * $row_per_page;
                } else {
                    $page = 1;
                }
                $search_term = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
                $sql = "SELECT * FROM `$tablename` WHERE `first_name` LIKE '%$search_term%' OR `last_name` LIKE '%$search_term%' ORDER BY `$sortField` $sortOrder LIMIT $start_from, $row_per_page"; // Updated SQL query
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                ?>
                    <tr>
                        <th><?php echo $row['id']; ?></th>
                        <td><?php echo $row['first_name']; ?></td>
                        <td><?php echo $row['last_name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['gender']; ?></td>
                        <td class="gap-3">
                            <a href="edit.php?id=<?php echo $row['id'] ?>" class="link-dark mx-3"><i class="fa-regular fa-pen-to-square"></i></a>
                            <a href="delete.php?id=<?php echo $row['id'] ?>" class="link-dark"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    <tr>
                    <?php
                }
                    ?>
            </tbody>
        </table>
        <?php
        $sql = "SELECT * FROM `$tablename`";
        $result = mysqli_query($conn, $sql);
        $total_records = mysqli_num_rows($result);
        $total_pages = ceil($total_records / $row_per_page);
        ?>
        <nav class="d-flex justify-content-between" aria-label="Page navigation">
            <ul class="pagination my-2">
                <?php
                for ($i = 1; $i <= $total_pages; $i++) {
                    echo '<li class="page-item"><a class="page-link" href="index.php?page=' . $i . '&sortField=' . $sortField . '&sortOrder=' . $sortOrder . '">' . $i . '</a></li>';
                }
                ?>
            </ul>
            <form action="import_excel.php" method="POST" enctype="multipart/form-data" class="d-flex my-2">
                <input type="file" name="import_file" class="form-control">
                <button type="submit" name="save_data" class="btn btn-outline-info mx-2">Import</button>
            </form>
        </nav>
    </div>
    <script>
        function sortItems(field) {
            let sortOrder = '<?php echo $sortOrder === "ASC" ?>';
        // If the clicked field is already the current sort field, toggle the sort order
        if (field === '<?php echo $sortField; ?>') {
            sortOrder = '<?php echo $sortOrder === "ASC" ? "DESC" : "ASC"; ?>';
        }
            window.location.href = 'index.php?sortField=' + field + '&sortOrder=' + sortOrder;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>