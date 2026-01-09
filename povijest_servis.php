<?php
include 'connect.php';
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

$msg = ""; // Message variable for successful registration message

if (isset($_GET['logout'])) {
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session
    header("Location: index.php"); // Redirect to the index page
    exit();
}
$registrationId = isset($_GET['id_kamion']) ? $_GET['id_kamion'] : '';

$row = array('id_kamion' => '', 'registracijska_oznaka_kam' => '');

$servisRow = array();
$servisRowsServis = array();

if (isset($_GET['id_kamion']) && isset($_GET['id_servis_kam'])) {
    $registrationId = $_GET['id_kamion'];
    $interval = $_GET['id_servis_kam'];

    $query = "SELECT id_kamion, registracijska_oznaka_kam FROM kamioni WHERE id_kamion = $registrationId";
    $result = mysqli_query($dbc, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $id = $row['id_kamion'];
    }
    // Fetch service data for the selected truck
    $queryServisKamion = "SELECT * FROM servisi_kam WHERE id_kamion = $id AND id_servis_kam=$interval";
    $resultServisKamion = mysqli_query($dbc, $queryServisKamion);

    if ($resultServisKamion && mysqli_num_rows($resultServisKamion) > 0) {
        while ($row = mysqli_fetch_assoc($resultServisKamion)) {
            $servisRowsServis[] = $row; // Add each service row to the array
        }
    }
}

$query = "SELECT id_kamion, registracijska_oznaka_kam FROM kamioni WHERE id_kamion = $registrationId";
$result = mysqli_query($dbc, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    $id = $row['id_kamion'];
}

if (isset($_POST['update'])) {
    $id_servis_kam = $_GET['id_servis_kam'];
    $registrationId = $_GET['id_kamion'];
    $datum_servis = $_POST['datum_servis'];
    $stanje_km = $_POST['stanje_km'];
    $ulje_motor = $_POST['ulje_motor'];

    // Checkbox values
    $filter_ulja = isset($_POST['filter_ulja']);
    $filter_zraka = isset($_POST['filter_zraka']);
    $filter_kabine = isset($_POST['filter_kabine']);
    $filter_goriva = isset($_POST['filter_goriva']);
    $susac = isset($_POST['susac']);
    $ostalo = $_POST['ostalo'];
    $sljedeci = $_POST['sljedeci_servis'];



    // Perform the SQL update
    $queryUpdate = "UPDATE servisi_kam SET datum_servis=?, kilometri_servis=?, ulje_motor=?, filter_ulja=?, filter_zraka=?, filter_kabine=?, filter_goriva=?, susac=?, ostalo=?, sljedeci_servis=? WHERE id_servis_kam=?";
    $stmtUpdate = mysqli_prepare($dbc, $queryUpdate);
    mysqli_stmt_bind_param($stmtUpdate, "sssiiiiissi", $datum_servis, $stanje_km, $ulje_motor, $filter_ulja, $filter_zraka, $filter_kabine, $filter_goriva, $susac, $ostalo, $sljedeci, $interval);


    if (mysqli_stmt_execute($stmtUpdate)) {
        $msg = "Podaci su uspješno ažurirani!";
    } else {
        $msg = "Greška prilikom ažuriranja podataka: " . mysqli_error($dbc);
    }

    mysqli_stmt_close($stmtUpdate);

    header("Location: Refresh:0");
    exit();
}

if (isset($_POST['delete'])) {
    // Perform the SQL delete
    $queryDelete = "DELETE FROM servisi_kam WHERE id_servis_kam=?";
    $stmtDelete = mysqli_prepare($dbc, $queryDelete);
    mysqli_stmt_bind_param($stmtDelete, "i", $interval);

    if (mysqli_stmt_execute($stmtDelete)) {
    } else {
        $msg = "Greška prilikom brisanja podataka: " . mysqli_error($dbc);
    }

    mysqli_stmt_close($stmtDelete);

    header("Location: povijest_kamion.php?id_kamion=$registrationId");
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
                            <a href="povijest_kamion.php?id_kamion=<?php echo isset($_GET['id_kamion']) ? $_GET['id_kamion'] : ''; ?>"><button type="button" class="btn btn-outline-dark gumb"><img style="width: 30px;" src="img/natrag.png" alt=""></button></a>
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
                        <div class="form-group forma_servis_naslov">
                            <img class="navbar_slika" src="img/LogoZerjav.svg" alt="">
                            <label style="display: block; font-size:25px;">SERVISNI INTERVAL</label>
                        </div>
                        <div class="form-group forma_servis row">
                            <label for="reg_br" class="col-sm-3">REG. BROJ:</label>
                            <div class="col-sm-9">
                                <?php foreach ($servisRowsServis as $servisRow) : ?>
                                    <input type="text" class="form-control forma_input" id="reg_br" value="<?php echo $row["registracijska_oznaka_kam"]; ?>" readonly>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="form-group forma_servis row">
                            <label for="datum" class="col-sm-3">DATUM:</label>
                            <div class="col-sm-9">
                                <?php foreach ($servisRowsServis as $servisRow) : ?>
                                    <input type="date" class="form-control forma_input" id="datum" name="datum_servis" value="<?php echo $servisRow["datum_servis"]; ?>">
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="form-group forma_servis row">
                            <label for="stanje_km" class="col-sm-3">STANJE km:</label>
                            <div class="col-sm-9">
                                <?php foreach ($servisRowsServis as $servisRow) : ?>
                                    <input type="text" class="form-control forma_input" id="stanje_km" name="stanje_km" value="<?php echo $servisRow["kilometri_servis"]; ?>">
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="form-group forma_servis row">
                            <label for="ulje_motor" class="col-sm-3">ULJE MOTORA:</label>
                            <div class="col-sm-9">
                                <?php foreach ($servisRowsServis as $servisRow) : ?>
                                    <select name="ulje_motor" class="form-control forma_input" id="ulje_motor">
                                        <option value=""></option>
                                        <option value="5W-30" <?php echo ($servisRow["ulje_motor"] == "5W-30") ? "selected" : ""; ?>>5W-30</option>
                                        <option value="10W-40" <?php echo ($servisRow["ulje_motor"] == "10W-40") ? "selected" : ""; ?>>10W-40</option>
                                    </select>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="form-group forma_servis row">
                            <label class="col-sm-3">FILTER:</label>
                            <div class="col-sm-9">
                                <label class="form-check-label" for="filter_ulja">ULJE:</label>
                                <input type="checkbox" name="filter_ulja" <?php echo ($servisRow["filter_ulja"] == 1) ? "checked" : ""; ?>>
                                <label class="form-check-label" for="filter_zraka">ZRAK:</label>
                                <input type="checkbox" name="filter_zraka" <?php echo ($servisRow["filter_zraka"] == 1) ? "checked" : ""; ?>>
                                <label class="form-check-label" for="filter_kabine">KABINA:</label>
                                <input type="checkbox" name="filter_kabine" <?php echo ($servisRow["filter_kabine"] == 1) ? "checked" : ""; ?>>
                                <label class="form-check-label" for="filter_goriva">GORIVO:</label>
                                <input type="checkbox" name="filter_goriva" <?php echo ($servisRow["filter_goriva"] == 1) ? "checked" : ""; ?>>
                                <label class="form-check-label" for="susac">SUŠAČ ZRAKA:</label>
                                <input type="checkbox" name="susac" <?php echo ($servisRow["susac"] == 1) ? "checked" : ""; ?>>
                            </div>
                        </div>
                        <div class="form-group forma_servis row">
                            <label for="ostalo" class="col-sm-3">OSTALO:</label>
                            <div class="col-sm-9">
                                <?php foreach ($servisRowsServis as $servisRow) : ?>
                                    <input type="text" class="form-control forma_input" id="ostalo" name="ostalo" value="<?php echo $servisRow["ostalo"]; ?>">
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="form-group forma_servis row">
                            <label for="slj_servis" class="col-sm-3">SLJEDEĆI SERVIS:</label>
                            <div class="col-sm-9">
                                <?php foreach ($servisRowsServis as $servisRow) : ?>
                                    <input type="text" class="form-control forma_input" id="slj_servis" name="sljedeci_servis" value="<?php echo $servisRow["sljedeci_servis"]; ?>">
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