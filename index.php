<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>regstration form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <?php
        require_once "db_ops.php";
        if (isset($_POST["submit"])) {
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
            $errors = array();
            if (empty($fullname) or empty($username) or empty($birth_date) or empty($phone) or empty($address) or empty($email) or empty($password) or empty($repeated_password)) {
                array_push($errors, "all fields are required");
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                array_push($errors, "the email is not valid");
            }
            if (strlen($password) < 8 or !$number or !$specialChars) {
                array_push($errors, "Password should be at least 8 characters in length and include one number, and one special character");
            }
            if ($password !== $repeated_password) {
                array_push($errors, "the password does not match");
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
                        echo "<div class='alert alert-danger'>userName already exists!</div>";
                    } else {
                        $sql = "INSERT INTO users (fullName, userName, password, email, address, phone, birthDate) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
                        $stmt = mysqli_stmt_init($conn);
                        $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
                        if ($prepareStmt) {
                            mysqli_stmt_bind_param($stmt, "sssssss", $fullname, $username, $password, $email, $address, $phone, $birth_date);
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
        ?>

        <form action="index.php" method="post">
            <div class="form-element">
                <input type="text" class="form-control" name="fullname" placeholder="Full Name:">
            </div>
            <div class="form-element">
                <input type="text" class="form-control" name="username" placeholder="User Name:">
            </div>
            <div class="form-element">
                <input type="date" class="form-control" name="birth-date" placeholder="Birth Date:">
            </div>
            <div class="form-element">
                <input type="number" class="form-control" name="phone" placeholder="Phone:">
            </div>
            <div class="form-element">
                <input type="text" class="form-control" name="address" placeholder="Address:">
            </div>
            <div class="form-element">
                <input type="email" class="form-control" name="email" placeholder="Email:">
            </div>
            <div class="form-element">
                <input type="password" class="form-control" name="password" placeholder="Password:">
            </div>
            <div class="form-element">
                <input type="password" class="form-control" name="repeated-password" placeholder="Repeated Password:">
            </div>

            <div class="form-element">
                <input type="submit" class="btn-primarly" value="register" name="submit">
            </div>
        </form>
    </div>

</body>

</html>