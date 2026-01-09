<?php
session_start();
include "connect.php";

$message = ""; // Initialize an empty message
$sessionName = isset($_SESSION['name']) ? $_SESSION['name'] : '';
$sessionUsername = isset($_SESSION['username']) ? $_SESSION['username'] : '';

if (isset($_GET['logout'])) {
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session
    header("Location: index.php"); // Redirect to the login page
    exit();
}

if (isset($_POST["submit"])) {
    $user = $_POST["user"];
    $password = $_POST["password"];

    $userSelectVozac = "SELECT * FROM vozac WHERE vozac_korisnicko_ime = ?";

    // Prepare the statement
    $stmtVozac = mysqli_prepare($dbc, $userSelectVozac);

    if ($stmtVozac) {
        // Bind parameters and execute
        mysqli_stmt_bind_param($stmtVozac, 's', $user);
        mysqli_stmt_execute($stmtVozac);

        // Get result and fetch row
        $resultVozac = mysqli_stmt_get_result($stmtVozac);
        $rowVozac = mysqli_fetch_array($resultVozac);

        // Check if the user exists
        if ($rowVozac) {
            // Verify the password
            if (password_verify($password, $rowVozac["vozac_lozinka"]) || $password === $rowVozac["vozac_lozinka"]) {
                $_SESSION['username'] = $user;
                header('Location: vozaci.php');
                exit();
            } else {
                $message = "Pogrešno korisničko ime ili lozinka.";
            }
        } else {
            $message = "Korisnik ne postoji.";
        }

        // Close the statement
        mysqli_stmt_close($stmtVozac);
    }

    mysqli_close($dbc);
}
?>

<!DOCTYPE html>
<html lang="hr">

<head>
    <title>Kompanija Žerjav transporti d.o.o.</title>
    <link rel="icon" href="https://kompanija-zerjav-transporti.hr/wp-content/uploads/2022/07/cropped-zerjav-logo2-print-1-1-32x32.jpg" sizes="32x32">
    <link rel="icon" href="https://kompanija-zerjav-transporti.hr/wp-content/uploads/2022/07/cropped-zerjav-logo2-print-1-1-192x192.jpg" sizes="192x192">
    <link rel="apple-touch-icon" href="https://kompanija-zerjav-transporti.hr/wp-content/uploads/2022/07/cropped-zerjav-logo2-print-1-1-180x180.jpg">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="style.css">
</head>

<body class="login_stranica">
    <div class="forma_parent">
        <form method="POST" action="">
            <div class="login_forma forma">
                <h6>Prijavljujete se kao vozač</h6>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingInput" name="user" placeholder="Korisničko ime" required>
                    <label for="user">Korisničko ime</label>
                </div>

                <div class="form-floating">
                    <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Lozinka" required>
                    <label for="password">Lozinka</label>
                </div>
                <button name="submit" type="submit" class="btn btn-outline-dark gumb_forma">PRIJAVI SE</button>
                <?php
                echo '<p class="pogresna_lozinka">' . $message . '</p>';
                ?>
            </div>
        </form>
    </div>
</body>

</html>
