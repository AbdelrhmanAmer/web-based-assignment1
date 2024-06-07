<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>
    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ?>
    <?php include 'header.php'; ?>
    <div class="container">
        <?php
        require_once "db_ops.php";
        $old_values = [
            'fullname' => '',
            'username' => '',
            'birth-date' => '',
            'phone' => '',
            'address' => '',
            'email' => '',
            'password' => '',
            'repeated-password' => ''
        ];
        
        if (isset($_POST["submit"])) {
            foreach ($old_values as $key => $value) {
                $old_values[$key] = $_POST[$key];
            }
            
            $fullname = $_POST["fullname"];
            $username = $_POST["username"];
            $birth_date = $_POST["birth-date"];
            $phone = $_POST["phone"];
            $address = $_POST["address"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $repeated_password = $_POST["repeated-password"];
            $number    = preg_match('@[0-9]@', $password);
            $specialChars = preg_match('@[^\w]@', $password);
            $image = time() . $_FILES["pic"]['name'];
            $picuploaded = 0;
            $errors = array();
            if (move_uploaded_file($_FILES["pic"]['tmp_name'], $_SERVER["DOCUMENT_ROOT"] . '/web-based-assignment1/photo/' . $image)) {
                $target_file = $_SERVER["DOCUMENT_ROOT"] . '/web-based-assignment1/photo/' . $image;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                $picname = basename($_FILES["pic"]['name']);
                $photo = time() . $picname;
            }
            if ($_FILES["pic"]['size'] > 20000000 || !in_array($imageFileType, ["jpg", "jpeg", "png"])) {
        ?>
                <script>
                    alert("Please upload a photo with the extension jpg or jpeg or png");
                </script>
        <?php
            } else {
                $picuploaded = 1;
            }
            if ($picuploaded == 1) {
                if (empty($fullname) or empty($username) or empty($birth_date) or empty($phone) or empty($address) or empty($email) or empty($password) or empty($repeated_password)) {
                    array_push($errors, "All fields are required");
                }
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    array_push($errors, "The email is not valid");
                }
                if (strlen($password) < 8 or !$number or !$specialChars) {
                    array_push($errors, "Password should be at least 8 characters in length and include one number, and one special character");
                }
                if ($password !== $repeated_password) {
                    array_push($errors, "The passwords do not match");
                }
                if (count($errors) > 0) {
                    foreach ($errors as $error) {
                        echo "<div class='alert alert-danger'>$error</div>";
                    }
                } else {
                    // Checking if username already exists
                    $sql = "SELECT * FROM users WHERE userName = ?";
                    $stmt = mysqli_stmt_init($conn);
                    $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
                    if ($prepareStmt) {
                        mysqli_stmt_bind_param($stmt, "s", $username);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_store_result($stmt);
                        $rowCount = mysqli_stmt_num_rows($stmt);
                        if ($rowCount > 0) {
                            echo "<div class='alert alert-danger'>Username already exists!</div>";
                        } else {
                            $sql = "INSERT INTO users (fullName, userName, password, email, address, phone, birthDate, image) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                            $stmt = mysqli_stmt_init($conn);
                            $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
                            if ($prepareStmt) {
                                mysqli_stmt_bind_param($stmt, "ssssssss", $fullname, $username, $password, $email, $address, $phone, $birth_date, $image);
                                mysqli_stmt_execute($stmt);
                                echo "<div class='alert alert-success'>You are registered successfully.</div>";
                            } else {
                                echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
                            }
                        }
                    } else {
                        echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
                    }
                }
            }
        }
        ?>

        <form action="index.php" method="post" enctype="multipart/form-data">
            <div class="form-element">
                <input type="text" class="form-control" name="fullname" placeholder="Full Name" value="<?php echo htmlspecialchars($old_values['fullname']); ?>">
            </div>
            <div class="form-element">
                <input type="text" class="form-control" name="username" placeholder="Username" value="<?php echo htmlspecialchars($old_values['username']); ?>">
            </div>
            <div class="date-element">
                <input type="date" class="form-control" name="birth-date" id="birth-date" placeholder="Birth Date" value="<?php echo htmlspecialchars($old_values['birth-date']); ?>">
                <button type="button" class="check-button" id="check-actors">Check Actors</button>
            </div>
            <div class="form-element">
                <input type="number" class="form-control" name="phone" placeholder="Phone" value="<?php echo htmlspecialchars($old_values['phone']); ?>">
            </div>
            <div class="form-element">
                <input type="text" class="form-control" name="address" placeholder="Address" value="<?php echo htmlspecialchars($old_values['address']); ?>">
            </div>
            <div class="form-element">
                <input type="email" class="form-control" name="email" placeholder="Email" value="<?php echo htmlspecialchars($old_values['email']); ?>">
            </div>
            <div class="form-element">
                <input type="password" class="form-control" name="password" placeholder="Password" value="<?php echo htmlspecialchars($old_values['password']); ?>">
            </div>
            <div class="form-element">
                <input type="password" class="form-control" name="repeated-password" placeholder="Repeated Password" value="<?php echo htmlspecialchars($old_values['repeated-password']); ?>">
            </div>
            <div class="form-element">
                <input type="file" id="pic" required class="form-control" name="pic" placeholder="Upload Image">
            </div>

            <div class="submit-button">
                <input type="submit" class="btn-primarly" value="Register" name="submit">
            </div>
        </form>
    </div>
    <script>
        $(document).ready(function() {
            $('#check-actors').click(function() {
                var birthdate = $('#birth-date').val();
                var parts = birthdate.split('-');
                var month = parts[1];
                var day = parts[2];
                birthdate = month + '-' + day;

                $.ajax({
                    url: "api_ops.php",
                    type: 'POST',
                    data: {
                        action: 'getActorsBornOnDate',
                        birthdate: birthdate
                    },
                    success: function(response) {
                        var data = JSON.parse(response);

                        if (data.hasOwnProperty('error')) {
                            swal({
                                title: "Error",
                                text: "An error occurred: " + data.error,
                                icon: 'error'
                            });
                        } else {
                            var actorsIds = data.actors;
                            if (actorsIds.length > 0) {
                                var actorNames = [];

                                actorsIds.forEach(function(actorId) {
                                    $.ajax({
                                        url: "api_ops.php",
                                        type: "POST",
                                        data: {
                                            action: 'getActorName',
                                            actorId: actorId
                                        },
                                        success: function(nameResponse) {
                                            var actorData = JSON.parse(nameResponse);

                                            if (actorData) {
                                                if (actorData.name) {
                                                    actorNames.push(actorData.name);
                                                }
                                            } else {
                                                swal({
                                                    title: "ERROR",
                                                    icon: 'error'
                                                })
                                            }
                                            swal({
                                                title: "Actors Born on " + birthdate,
                                                text: actorNames.join(", "),
                                                icon: 'info'
                                            });
                                        },
                                        error: function(xhr, status, error) {
                                            swal({
                                                title: "Error",
                                                text: "An error occurred: " + error,
                                                icon: 'error'
                                            });
                                        }
                                    })
                                });
                            } else {
                                swal({
                                    title: "No actors born on " + birthdate,
                                    icon: 'info'
                                });
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        swal({
                            title: "Error",
                            text: "An error occurred: " + error,
                            icon: 'error'
                        });
                    }
                });
            });
        });

    </script>
    <?php include 'footer.php'; ?>
</body>
</html>