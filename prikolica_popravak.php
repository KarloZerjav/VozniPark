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
$row = array('id_prikolice' => '', 'registracijska_oznaka_prik' => '');

$povijestRows = array();
$povijestRowsPrikolica = array();

if (isset($_GET['id_prikolice']) && isset($_GET['id_vrste'])) {
    $registrationId = $_GET['id_prikolice'];
    $id_vrste = $_GET['id_vrste']; // Assuming you're getting id_vrste from the URL

    // Fetch registration details from the database using the $registrationId
    $query = "SELECT id_prikolice, registracijska_oznaka_prik FROM prikolice WHERE id_prikolice = $registrationId";
    $result = mysqli_query($dbc, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $id_prikolice = $row['id_prikolice'];
    }

    // Fetch repair details with the specified id_vrste
    $queryPovijestPrikolica = "SELECT pp.id_vrste, pp.datum, vp.naziv_popravka, vp.opis 
                            FROM popravak_prikolica pp 
                            INNER JOIN vrste_popravka vp ON pp.id_vrste = vp.id_vrste 
                            WHERE pp.id_prikolice = $id_prikolice AND pp.id_vrste = $id_vrste"; // Add condition for id_vrste
    $resultPovijestPrikolica = mysqli_query($dbc, $queryPovijestPrikolica);

    if ($resultPovijestPrikolica && mysqli_num_rows($resultPovijestPrikolica) > 0) {
        while ($row = mysqli_fetch_assoc($resultPovijestPrikolica)) {
            $povijestRowsPrikolica[] = $row; // Add each repair row to the array
        }
    }
}

$query = "SELECT id_prikolice, registracijska_oznaka_prik FROM prikolice WHERE id_prikolice = $registrationId";
$result = mysqli_query($dbc, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    $id_prikolice = $row['id_prikolice'];
}

if (isset($_POST['update'])) {
    $registrationId = $_GET['id_prikolice'];
    $id_vrste = $_GET['id_vrste']; // Assuming you're getting id_vrste from the URL
    $datum = $_POST['datum'];
    $naziv_popravka = $_POST['naziv_popravka']; // Assuming 'naziv_popravka' comes from the 'naslov' input field
    $opis = $_POST['opis'];

    // Update data in 'popravak_kamion' table
    $updateQuery1 = "UPDATE popravak_prikolica SET datum=? WHERE id_prikolice=? AND id_vrste=?";
    $stmtUpdate1 = mysqli_prepare($dbc, $updateQuery1);
    mysqli_stmt_bind_param($stmtUpdate1, 'sii', $datum, $registrationId, $id_vrste);

    // Check if the statement executed successfully
    if (mysqli_stmt_execute($stmtUpdate1)) {
        // Update successful
    } else {
        echo "Error updating record in 'popravak_kamion' table: " . mysqli_error($dbc);
    }

    mysqli_stmt_close($stmtUpdate1);

    // Update data in 'vrste_popravka' table
    $updateQuery2 = "UPDATE vrste_popravka SET naziv_popravka=?, opis=? WHERE id_vrste=?";
    $stmtUpdate2 = mysqli_prepare($dbc, $updateQuery2);
    mysqli_stmt_bind_param($stmtUpdate2, 'ssi', $naziv_popravka, $opis, $id_vrste);

    // Check if the statement executed successfully
    if (mysqli_stmt_execute($stmtUpdate2)) {
        // Update successful
    } else {
        echo "Error updating record in 'vrste_popravka' table: " . mysqli_error($dbc);
    }

    mysqli_stmt_close($stmtUpdate2);

    header("Refresh:0");
    exit();
}




if (isset($_POST['delete'])) {
    $id_vrste = $_GET['id_vrste'];
    $id_prikolice = $_GET['id_prikolice'];

    // First, delete the record from the popravak_kamion table
    $deleteQueryPopravakPrikolice = "DELETE FROM popravak_prikolica WHERE id_vrste=? AND id_prikolice=?";
    $stmtDeletePopravakPrikolice = mysqli_prepare($dbc, $deleteQueryPopravakPrikolice);
    mysqli_stmt_bind_param($stmtDeletePopravakPrikolice, 'ii', $id_vrste, $id_prikolice);

    // Check if the statement executed successfully
    if (mysqli_stmt_execute($stmtDeletePopravakPrikolice)) {
        mysqli_stmt_close($stmtDeletePopravakPrikolice);

        // Now, delete the corresponding record from the vrste_popravka table
        $deleteQueryVrstePopravka = "DELETE FROM vrste_popravka WHERE id_vrste=?";
        $stmtDeleteVrstePopravka = mysqli_prepare($dbc, $deleteQueryVrstePopravka);
        mysqli_stmt_bind_param($stmtDeleteVrstePopravka, 'i', $id_vrste);

        // Check if the statement executed successfully
        if (mysqli_stmt_execute($stmtDeleteVrstePopravka)) {
            mysqli_stmt_close($stmtDeleteVrstePopravka);
            header("Location: povijest_prikolica.php?id_prikolice=$registrationId");
            exit();
        }
    }
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
                            <a href="povijest_prikolica.php?id_prikolice=<?php echo isset($_GET['id_prikolice']) ? $_GET['id_prikolice'] : ''; ?>"><button type="button" class="btn btn-outline-dark gumb"><img style="width: 30px;" src="img/natrag.png" alt=""></button></a>
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
                                    <input type="text" name="naziv_popravka" class="form-control forma_input" value="<?php echo $povijestRow["naziv_popravka"]; ?>">
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="form-group forma_servis row">
                            <label for="datum_kamion" class="col-sm-3">Datum:</label>
                            <div class="col-sm-9">
                                <?php foreach ($povijestRowsPrikolica as $povijestRow) : ?>
                                    <input type="date" name="datum" class="form-control forma_input" value="<?php echo $povijestRow["datum"]; ?>">
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="form-group forma_servis row">
                            <label for="opis_kamion" class="col-sm-3">Opis:</label>
                            <div class="col-sm-9">
                                <?php foreach ($povijestRowsPrikolica as $povijestRow) : ?>
                                    <textarea name="opis" class="form-control forma_input" rows="2" cols="70"><?php echo $povijestRow["opis"]; ?></textarea>
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