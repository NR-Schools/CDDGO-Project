<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/services/StudentService.php";
    require_once $_SERVER['DOCUMENT_ROOT'] . '/guards/AuthGuard.php';

    if (!AuthGuard::guard_route(Role::ADMIN)) {
        // Return to root
        // header("Location: /");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Registrations</title>
    <!-- CSS -->
    <link type="text/css" rel="stylesheet" href="../css/admin-manage_registrations.css">
</head>
<body>
    <!-- Include Header -->
    <?php
        require_once $_SERVER['DOCUMENT_ROOT'] . "/components/header.php";
    ?>

    <!-- Start Body -->
    <div class="main-body">
        <p>MANAGE REGISTRATIONS</p>

        <hr />

        <?php

            // Check if the form is submitted
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST['confirm'])) {
                    
                    $studentId = $_POST['studentId'];
                    // Confirm student registration
                    StudentService::confirmStudentRegistration($studentId);
                    header("Location: admin-manage_users.php");
                }elseif (isset($_POST['reject'])) {
                    $studentId = $_POST['studentId'];
                    // Reject student registration
                    StudentService::rejectStudentRegistration($studentId);
                    
                    echo "<script> alert('User Rejected');
                    </script>";

                }
            }

            // Get all students
            $students = StudentService::getAllStudents();

            foreach ($students as $student) {
                assert($student instanceof Student);

                // Check if the student is not verified
                if (!$student->isVerified) {
                    echo <<<EOD

                    <div class="borrow-record-entry">
                        <div>
                            <span>{$student->StudNo}</span>
                            <span>{$student->getFullName()}</span>
                            <span>{$student->Email}</span>
                            <span>{$student->Program}</span>
                        </div>

                        <form action="admin-manage_registrations.php" enctype="multipart/form-data" method="POST">
                            <!-- Hidden input field to store student ID -->
                            <input type="hidden" name="studentId" value="{$student->StudID}">
                            <div class="button-container" text-center>
                                <button type="submit" class="btn" name="confirm" value="confirm">Confirm</button>
                                <button type="submit" class="btn" name="reject" value="reject">Reject</button>
                            </div>
                            
                        </form>
                    </div>
                    EOD;
                }
            }
        ?>
    </div>
    
</body>
</html>