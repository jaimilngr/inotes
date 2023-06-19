<?php
// Connect to the Database
$servername = "localhost";
$username = "root";
$password = "";
$database = "notes";
$limit = 5;

// Create a connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Die if the connection was not successful
if (!$conn) {
    die("Sorry, we failed to connect: " . mysqli_connect_error());
}

// Handle delete request
if (isset($_POST['delete'])) {
    $sno = $_POST['delete'];
    $delete_sql = "DELETE FROM `note` WHERE `sno` = '$sno'";
    if (mysqli_query($conn, $delete_sql)) {
        echo "success";
        exit();
    } else {
        echo "error";
        exit();
    }
}

// Handle edit request
if (isset($_POST['edit_sno']) && isset($_POST['edit_title']) && isset($_POST['edit_description'])) {
    $sno = $_POST['edit_sno'];
    $title = $_POST['edit_title'];
    $description = $_POST['edit_description'];
    $edit_sql = "UPDATE `note` SET `title` = '$title', `description` = '$description' WHERE `sno` = '$sno'";
    if (mysqli_query($conn, $edit_sql)) {
        echo "success";
        exit();
    } else {
        echo "error";
        exit();
    }
}

$records_per_page = 5;
$page = isset($_POST["page_no"]) ? $_POST["page_no"] : 1;
$output = '';

$start_from = ($page - 1) * $records_per_page;
$sql = "SELECT * FROM `note` LIMIT $start_from, $records_per_page";
$result = mysqli_query($conn, $sql);

$output .= '
    <table class="table" id="myTable">
        <thead>
            <tr>
                <th scope="col">S.No</th>
                <th scope="col">Title</th>
                <th scope="col">Description</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>';

$sno = ($page - 1) * $records_per_page;

while ($row = mysqli_fetch_assoc($result)) {
    $sno++;
    $output .= '
        <tr>
            <th scope="row">' . $sno . '</th>
            <td>' . $row['title'] . '</td>
            <td>' . $row['description'] . '</td>
            <td>
                <button class="edit btn btn-sm btn-primary" id="' . $row['sno'] . '">Edit</button>
                <button class="delete btn btn-sm btn-primary" id="' . $row['sno'] . '">Delete</button>
            </td>
        </tr>';
}

$output .= '</tbody></table>';

$sql = "SELECT COUNT(*) AS total FROM `note`";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$total_records = $row['total'];
$total_pages = ceil($total_records / $records_per_page);
$output .= '<div id="pagination" class="mt-4">';
$output .= '<nav aria-label="Page navigation example">';
$output .= '<ul class="pagination justify-content-center">';

$output .= '<li class="page-item ' . ($page == 1 ? 'disabled' : '') . '">';
$output .= '<a class="page-link" href="#" data-page-no="' . ($page - 1) . '">Previous</a>';
$output .= '</li>';

for ($i = 1; $i <= $total_pages; $i++) {
    $output .= '<li class="page-item ' . ($page == $i ? 'active' : '') . '">';
    $output .= '<a class="page-link" href="#" data-page-no="' . $i . '">' . $i . '</a>';
    $output .= '</li>';
}

$output .= '<li class="page-item ' . ($page == $total_pages ? 'disabled' : '') . '">';
$output .= '<a class="page-link" href="#" data-page-no="' . ($page + 1) . '">Next</a>';
$output .= '</li>';

$output .= '</ul>';
$output .= '</nav>';
$output .= '</div>';

echo $output;
?>

<script>
    edits = document.getElementsByClassName('edit');
    Array.from(edits).forEach((element) => {
        element.addEventListener("click", (e) => {
            console.log("edit ");
            tr = e.target.parentNode.parentNode;
            title = tr.getElementsByTagName("td")[0].innerText;
            description = tr.getElementsByTagName("td")[1].innerText;
            console.log(title, description);
            titleEdit.value = title;
            descriptionEdit.value = description;
            snoEdit.value = e.target.id;
            console.log(e.target.id)
            $('#editModal').modal('toggle');
        })
    })

 
</script>
