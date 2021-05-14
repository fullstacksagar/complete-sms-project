<?php
include("../database/db.php");
error_reporting(0);
ob_start();
session_start();
if ((!isset($_SESSION['student_email'])) && (!isset($_SESSION['student_password']))) {
    header('Location: student-login.php');
}

if (isset($_REQUEST['submitsupport'])) {
    $msg = "";

    $student_email = $_REQUEST['student_email'];
    $support = $_REQUEST['support'];
    $sql = "select * from support where (student_email='$student_email');";
    $res = mysqli_query($conn, $sql);
    if (mysqli_num_rows($res) > 0) {
        $msg = '<div class="alert alert-warning alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
</button>
        <strong>Great!</strong> support Already Exist.
      </div>';
    } else {


        $query = "insert into support(`support`,`student_email`) 
    values('$support','$student_email')";
        $result = mysqli_query($conn, $query);
        if ($result) {
            $msg = '<div class="alert alert-success alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
      </button>
        <strong>Success!</strong> support Added
      </div>';
        } else {
            $msg = '<div class="alert alert-danger alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
      </button>
  <strong>Failed!</strong> Something Went Wrong.
</div>';
        }
    }
}

$sql = "SELECT * FROM students WHERE student_email = '$_SESSION[student_email]'";
$result = $conn->query($sql);
if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $student_email = $row["student_email"];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <?php include('includes/header.php');  ?>

    <?php include('includes/nav.php');  ?>
    <?php include('includes/sidebar.php');  ?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Support</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Profile</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>



        <?php echo $msg; ?>

       <div class="container">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="form-group">

                    <input type="hidden" name="student_email" value="<?php if (isset($student_email)) {
                                                                            echo $student_email;
                                                                        }  ?>" class="form-control" readonly>
                </div>
                <div class="form-group">
                   
                    <input type="text" name="support" placeholder="write your support here..." class="form-control">
                </div>


                <input type="submit" value="Submit" name="submitsupport" class="btn btn-primary">

            </form>

            <table class="table table-bordered  table-hover animate__animated animate__fadeIn mt-5">
                <thead class="thead-light">
                    <tr>
                        <th>S.No</th>
                        <th>Your support</th>
                        <th>Action</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <?php

                $query = "SELECT * FROM support WHERE student_email= '$student_email'";
                $query_run = mysqli_query($conn, $query);
                $i = 1;


                while ($row = mysqli_fetch_assoc($query_run)) {

                ?>

                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $row['support']; ?></td>
                        <td>In Pending</td>



                        <td>
                            <button class="btn btn-outline-success" name=""><a href="edit_book.php?bn=<?php echo $row['book_no']; ?>">Edit</a></button>
                            <button class="btn btn-outline-delete" name=""><a href="delete-support.php?supportid=<?php echo $row['support_id']; ?>">Delete</a></button>

                        </td>
                    </tr>
                <?php } ?>
            </table>

            </div>

    <?php include('includes/footer.php');  ?>