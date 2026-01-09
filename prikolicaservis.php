<?php
session_start();
if (isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] > 900) {
    session_unset();
    session_destroy();
    header("Location: index.php");
   }
   $_SESSION['last_activity'] = time();
// Set the username from the session, if it exists
$user = isset($_SESSION['username']) ? $_SESSION['username'] : '';

if (empty($user)) {
    header("Location: index.php");
}

include 'connect.php';
$msg = ""; // Message variable for successful registration message

if (isset($_GET['logout'])) {
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session
    header("Location: index.php"); // Redirect to the index page
    exit();
}

// Initialize $row array to avoid errors
$row = array('id' => '', 'slika' => '', 'reg_kamion' => '', 'reg_prikolica' => '', 'registracija_kamion' => '', 'registracija_prikolica' => '');

$povijestRows = array();
$povijestRowsPrikolica = array();

if (isset($_GET['id']) && isset($_GET['stavka'])) {
    $registrationId = $_GET['id'];
    $stavka = $_GET['stavka'];

    $query = "SELECT id, reg_kamion, reg_prikolica, registracija_kamion, registracija_prikolica, slika FROM popis WHERE id = $registrationId";
    $result = mysqli_query($dbc, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Store additional values in variables if needed

        $id = $row['id'];
    }
    $queryPovijestPrikolica = "SELECT stavka, vozilo, datum, kilometri, naslov, opis FROM povijest WHERE stavka = $stavka AND vozilo = 'Prikolica'";
    $resultPovijestPrikolica = mysqli_query($dbc, $queryPovijestPrikolica);

    if ($resultPovijestPrikolica && mysqli_num_rows($resultPovijestPrikolica) > 0) {
        while ($row = mysqli_fetch_assoc($resultPovijestPrikolica)) {
            $povijestRowsPrikolica[] = $row; // Add each servis row to the array
        }
    }
}

$query = "SELECT id, reg_kamion, reg_prikolica, registracija_kamion, registracija_prikolica, slika FROM popis WHERE id = $registrationId";
$result = mysqli_query($dbc, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    $id = $row['id'];
}

if (isset($_POST['update'])) {
    $registrationId = $_GET['id'];
    $datum = $_POST['datum_prikolica'];
    $kilometri = $_POST['kilometri_prikolica'];
    $naslov = $_POST['naslov'];
    $opis = $_POST['opis_prikolica'];

    // Assuming $registrationId is already defined earlier in your code
    $updateQuery = "UPDATE povijest SET datum=?, kilometri=?, naslov=?, opis=? WHERE stavka=?";
    $stmtUpdate = mysqli_prepare($dbc, $updateQuery);
    mysqli_stmt_bind_param($stmtUpdate, 'ssssi', $datum, $kilometri, $naslov, $opis, $stavka);

    // Check if the statement executed successfully
    if (mysqli_stmt_execute($stmtUpdate)) {
        // Update successful
    } else {
        echo "Error updating record: " . mysqli_error($dbc);
    }

    mysqli_stmt_close($stmtUpdate);
    header("Refresh:0");
    exit();
}

if (isset($_POST['delete'])) {
    $deleteQuery = "DELETE FROM povijest WHERE stavka=?";
    $stmtDelete = mysqli_prepare($dbc, $deleteQuery);
    mysqli_stmt_bind_param($stmtDelete, 'i', $stavka);

    // Check if the statement executed successfully
    if (mysqli_stmt_execute($stmtDelete)) {
        // Deletion successful
    } else {
        echo "Error deleting record: " . mysqli_error($dbc);
    }

    mysqli_stmt_close($stmtDelete);
    header("Location: povijest.php?id=$registrationId");
    exit();
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

<body>
    <div class="adresa">
        <ul class="nav justify-content-center">
            <li class="nav-item">
                <img style="width: 14px;" src="img/lokacija.svg" alt="">
                <a class="a_adresa" rel="noreferrer noopener" href="https://www.google.com/maps/place/Dru%C5%A1kovec+Humski+82-1,+49231,+Dru%C5%A1kovec+Humski/@46.1895199,15.7004897,15.01z/data=!4m5!3m4!1s0x476591ed9ab6a98b:0x6936023145828a2!8m2!3d46.188583!4d15.7004335" target="_blank"> Druškovec Humski 82/1, 49231 Hum na Sutli, Hrvatska</a>&nbsp;&nbsp;&nbsp;&nbsp;
            </li>
            <li class="nav-item">
                <img style="width: 15px;" src="img/mail.svg" alt="">
                e-mail: <a class="a_adresa" href="mailto:info@kompanija-zerjav-transporti.hr">info@kompanija-zerjav-transporti.hr</a>
                &nbsp;&nbsp;&nbsp;&nbsp;
            </li>
            <li class="nav-item">
                <img style="width: 15px;" src="img/mob.svg" alt="">
                tel: <a class="a_adresa" href="tel:+38549340749">+385 49 / 340 - 749</a>
            </li>
        </ul>
    </div>

    <div class="container crta">
        <div class="container">
            <div class="container">
                <div class="container">
                    <div class="responzivno row">
                        <div class="col">
                            <a href="https://kompanija-zerjav-transporti.hr/"><img class="navbar_slika" src="img/LogoZerjav.svg" alt=""></a>
                        </div>

                        <div class="col gumb_pozicija">
                            <a href="povijest.php?id=<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>"><button type="button" class="btn btn-outline-dark gumb"><img style="width: 30px;" src="img/natrag.png" alt=""></button></a>
                            <a href="izbornik.php"><button type="button" class="btn btn-outline-dark gumb"><img style="width: 30px;" src="img/home.png" alt=""></button></a>
                            <?php
                            if (!empty($user)) {
                                echo '<a href="index.php?logout=true"><button type="button" class="btn btn-outline-dark gumb"><img style="width: 30px;" src="img/logout.png" alt=""></button></a>'; // Add the logout link
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="sredina">
            <div class="row">
                <div class="col">
                    <form enctype="multipart/form-data" action="" method="POST">
                        <div class="form-group forma_servis row">
                            <label for="naslov" class="col-sm-3">Naslov:</label>
                            <div class="col-sm-9">
                                <?php foreach ($povijestRowsPrikolica as $povijestRow) : ?>
                                    <input type="text" name="naslov" class="form-control forma_input" value="<?php echo $povijestRow["naslov"]; ?>">
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="form-group forma_servis row">
                            <label for="datum_prikolica" class="col-sm-3">Datum:</label>
                            <div class="col-sm-9">
                                <?php foreach ($povijestRowsPrikolica as $povijestRow) : ?>
                                    <input type="date" name="datum_prikolica" class="form-control forma_input" value="<?php echo $povijestRow["datum"]; ?>">
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="form-group forma_servis row">
                            <label for="kilometri_prikolica" class="col-sm-3">Kilometri:</label>
                            <div class="col-sm-9">
                                <?php foreach ($povijestRowsPrikolica as $povijestRow) : ?>
                                    <input type="text" name="kilometri_prikolica" class="form-control forma_input" value="<?php echo $povijestRow["kilometri"]; ?>">
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="form-group forma_servis row">
                            <label for="opis_prikolica" class="col-sm-3">Opis:</label>
                            <div class="col-sm-9">
                                <?php foreach ($povijestRowsPrikolica as $povijestRow) : ?>
                                    <textarea name="opis_prikolica" class="form-control forma_input" rows="2" cols="70"><?php echo $povijestRow["opis"]; ?></textarea>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="sredina_povijest">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <button type="submit" name="update" value="Prihvati" class="btn btn-outline-dark gumb"><img style="width: 30px;" src="img/save-file.png" alt=""></button>
                                <button type="delete" name="delete" value="Izbriši" class="btn btn-outline-dark gumb" onclick="return confirm('Jeste li sigurni da želite obrisati podatke?')"><img style="width: 30px;" src="img/trash.svg" alt=""></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="footer_boja">
        <p>Karlo Žerjav</p>
        <p>Organizacija i informatizacija ureda, završni rad</p>
    </div>
</body>

</html>