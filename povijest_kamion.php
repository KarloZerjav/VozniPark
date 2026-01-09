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
$row = array('id_kamion' => '', 'registracijska_oznaka_kam' => '');

$povijestRowsKamion = array(); // Array for storing repair history of trucks
$servisRowsServis = array(); // Array for storing service data of trucks

if (isset($_GET['id_kamion'])) {
    $registrationId = $_GET['id_kamion'];

    // Fetch registration details from the database using the $registrationId
    $query = "SELECT id_kamion, registracijska_oznaka_kam FROM kamioni WHERE id_kamion = $registrationId";
    $result = mysqli_query($dbc, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $id_kamion = $row['id_kamion'];

        // Fetch repair history for the selected truck
        $queryPovijestKamion = "SELECT pk.id_vrste, pk.kilometri, pk.datum, vp.naziv_popravka, vp.opis 
                                    FROM popravak_kamion pk 
                                    INNER JOIN vrste_popravka vp ON pk.id_vrste = vp.id_vrste WHERE pk.id_kamion = $id_kamion";
        $resultPovijestKamion = mysqli_query($dbc, $queryPovijestKamion);

        if ($resultPovijestKamion && mysqli_num_rows($resultPovijestKamion) > 0) {
            while ($row = mysqli_fetch_assoc($resultPovijestKamion)) {
                $povijestRowsKamion[] = $row; // Add each repair row to the array
            }
        }

        // Fetch service data for the selected truck
        $queryServisKamion = "SELECT * FROM servisi_kam WHERE id_kamion = $id_kamion";
        $resultServisKamion = mysqli_query($dbc, $queryServisKamion);

        if ($resultServisKamion && mysqli_num_rows($resultServisKamion) > 0) {
            while ($row = mysqli_fetch_assoc($resultServisKamion)) {
                $servisRowsServis[] = $row; // Add each service row to the array
            }
        }
    }
}

$query = "SELECT id_kamion, registracijska_oznaka_kam FROM kamioni WHERE id_kamion = $registrationId";
$result = mysqli_query($dbc, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    $id_kamion = $row['id_kamion'];
}

if (isset($_POST['insert_servis'])) {
    $datum_servis = $_POST['datum_servis'];
    $kilometri_servis = $_POST['kilometri_servis'];
    $ulje_motor = $_POST['ulje_motora'];
    $filter_ulja = isset($_POST['filter_ulja']) ? 1 : 0;
    $filter_zraka = isset($_POST['filter_zraka']) ? 1 : 0;
    $filter_kabine = isset($_POST['filter_kabine']) ? 1 : 0;
    $filter_goriva = isset($_POST['filter_goriva']) ? 1 : 0;
    $susac = isset($_POST['susac']) ? 1 : 0;
    $ostalo = $_POST['ostalo'];
    $sljedeci = $_POST['sljedeci_servis'];

    // Insert data into the database using prepared statement
    $insertQueryServis = "INSERT INTO servisi_kam (id_kamion, datum_servis, kilometri_servis, ulje_motor, filter_ulja, filter_zraka, filter_kabine, filter_goriva, susac, ostalo, sljedeci_servis) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmtInsertServis = mysqli_prepare($dbc, $insertQueryServis);
    mysqli_stmt_bind_param($stmtInsertServis, "isssiiiiiss", $id_kamion, $datum_servis, $kilometri_servis, $ulje_motor, $filter_ulja, $filter_zraka, $filter_kabine, $filter_goriva, $susac, $ostalo, $sljedeci);

    $insertResultServis = mysqli_stmt_execute($stmtInsertServis);

    if ($insertResultServis) {
        header("Refresh:0");
        exit();
    } else {
        echo "Error: " . mysqli_error($dbc);
    }
}

if (isset($_POST['insert'])) {
    $naziv_popravka = $_POST['naziv_popravka'];
    $datum = $_POST['datum'];
    $kilometri = $_POST['kilometri'];
    $opis = $_POST['opis'];

    // Insert data into vrste_popravka table
    $insertQueryPopravak = "INSERT INTO vrste_popravka (naziv_popravka, opis) VALUES (?, ?)";
    $stmtInsertPopravak = mysqli_prepare($dbc, $insertQueryPopravak);
    mysqli_stmt_bind_param($stmtInsertPopravak, "ss", $naziv_popravka, $opis);

    $insertResultPopravak = mysqli_stmt_execute($stmtInsertPopravak);

    if ($insertResultPopravak) {
        // Get the last inserted id_vrste
        $id_vrste = mysqli_insert_id($dbc);

        // Insert data into popravak_kamion table
        $insertQueryPopravakKamion = "INSERT INTO popravak_kamion (id_vrste, id_kamion, kilometri, datum) VALUES (?, ?, ?, ?)";
        $stmtInsertPopravakKamion = mysqli_prepare($dbc, $insertQueryPopravakKamion);
        mysqli_stmt_bind_param($stmtInsertPopravakKamion, "iiss", $id_vrste, $id_kamion, $kilometri, $datum);

        $insertResultPopravakKamion = mysqli_stmt_execute($stmtInsertPopravakKamion);

        if ($insertResultPopravakKamion) {
            header("Refresh:0");
            exit();
        }
    }
}

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.cvs-mobile.com/rest/v1/DataAPI/VehicleDataList/xml?AuthToken=EB98F70C-5CB5-41FF-BE93-90C9619ED290',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => '', // Empty string to satisfy the server's requirements
));

$response = curl_exec($curl);

curl_close($curl);

// Load the XML response
$xml = simplexml_load_string($response);
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
                            <a href="info_kamion.php?id_kamion=<?php echo isset($_GET['id_kamion']) ? $_GET['id_kamion'] : ''; ?>"><button type="button" class="btn btn-outline-dark gumb"><img style="width: 30px;" src="img/natrag.png" alt=""></button></a>
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
                        <div class="accordion" id="accordionPanelsStayOpenExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button style="font-weight: bold;" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                        SERVISI
                                    </button>
                                </h2>
                                <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse">
                                    <div class="accordion-body">
                                        <div class="form-group forma_servis_naslov">
                                            <img class="navbar_slika" src="img/LogoZerjav.svg" alt="">
                                            <label style="display: block; font-size:25px;">SERVISNI INTERVAL</label>
                                        </div>
                                        <div class="form-group forma_servis row">
                                            <label for="reg_br" class="col-sm-3">REG. BROJ:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control forma_input" id="reg_br" value="<?php echo $row["registracijska_oznaka_kam"]; ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group forma_servis row">
                                            <label for="datum" class="col-sm-3">DATUM:</label>
                                            <div class="col-sm-9">
                                                <input type="date" class="form-control forma_input" id="datum" name="datum_servis" required>
                                            </div>
                                        </div>
                                        <div class="form-group forma_servis row">
                                            <label for="stanje_km" class="col-sm-3">STANJE km:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control forma_input" id="stanje_km" name="kilometri_servis" required>
                                            </div>
                                        </div>
                                        <div class="form-group forma_servis row">
                                            <label for="ulje_motor" class="col-sm-3">ULJE MOTORA:</label>
                                            <div class="col-sm-9">
                                                <select name="ulje_motora" class="form-control forma_input" id="ulje_motor" required>
                                                    <option value="" disabled selected></option>
                                                    <option value="5W-30">5W-30</option>
                                                    <option value="10W-40">10W-40</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group forma_servis row">
                                            <label class="col-sm-3">FILTER:</label>
                                            <div class="col-sm-9">
                                                <label class="form-check-label" for="exampleFormControlSelect2">ULJE:</label>
                                                <input type="checkbox" name="filter_ulja">
                                                <label class="form-check-label" for="exampleFormControlSelect2">ZRAK:</label>
                                                <input type="checkbox" name="filter_zraka">
                                                <label class="form-check-label" for="exampleFormControlSelect2">KABINA:</label>
                                                <input type="checkbox" name="filter_kabine">
                                                <label class="form-check-label" for="exampleFormControlSelect2">GORIVO:</label>
                                                <input type="checkbox" name="filter_goriva">
                                                <label class="form-check-label" for="exampleFormControlSelect2">SUŠAČ ZRAKA</label>
                                                <input type="checkbox" name="susac">
                                            </div>
                                        </div>

                                        <div class="form-group forma_servis row">
                                            <label for="ostalo" class="col-sm-3">OSTALO:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control forma_input" id="ostalo" name="ostalo">
                                            </div>
                                        </div>

                                        <div class="form-group forma_servis row">
                                            <label for="sljedeci_servis" class="col-sm-3">SLJEDEĆI SERVIS:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control forma_input" id="sljedeci_servis" name="sljedeci_servis" required>
                                            </div>
                                        </div>
                                        <div class="sredina_povijest">
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <button type="submit" name="insert_servis" value="Unesi" class="btn btn-outline-dark gumb"><img style="width: 30px;" src="img/add-file.png" alt=""></button>
                                            </div>
                                        </div>
                                        <div class="list-group text-center">
                                            <h3>POVIJEST SERVISA</h3>
                                            <?php
                                            foreach ($servisRowsServis as $index => $rowServis) {
                                                $currentId = $row['id_kamion'];
                                                $currentInterval = $rowServis['id_servis_kam'];
                                                $kilometri_servis = $rowServis['kilometri_servis'];

                                                // Handle case where $odometer is not set, empty, or not numeric
                                                echo '<a href="povijest_servis.php?id_kamion=' . $currentId . '&id_servis_kam=' . $currentInterval . '" style="text-decoration: none;">';
                                                echo '<button type="button" class="list-group-item list-group-item-action">';
                                                echo '<div class="d-flex justify-content-between align-items-center">'; // Use flexbox for layout
                                                echo '<span>Servis odrađen:<br> ' . date('d.m.Y', strtotime($rowServis['datum_servis'])) . '</span>';
                                                echo '<span>' . $rowServis['ostalo'] . '</span>';
                                                echo '<span>na:<br>' . $kilometri_servis . '</span>';
                                                echo '</div>';
                                                echo '</button>';
                                                echo '</a>';
                                            }

                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <form enctype="multipart/form-data" action="" method="POST">
                        <div class="accordion" id="accordionPanelsStayOpenExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button style="font-weight: bold;" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="true" aria-controls="panelsStayOpen-collapseThree">
                                        POPRAVCI
                                    </button>
                                </h2>
                                <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse">
                                    <div class="accordion-body">
                                        <div class="list-group">
                                            <?php
                                            foreach ($povijestRowsKamion as $rowServis) {
                                                $currentId = $row['id_kamion'];
                                                $currentStavka = $rowServis['id_vrste'];

                                                echo '<a href="kamion_popravak.php?id_kamion=' . $currentId . '&id_vrste=' . $currentStavka . '" style="text-decoration: none;">';
                                                echo '<button type="button" class="list-group-item list-group-item-action">';
                                                echo '<div class="d-flex justify-content-between align-items-center">'; // Use flexbox for layout
                                                echo '<span>' . $rowServis['naziv_popravka'] . '<br>' . date('d.m.Y', strtotime($rowServis['datum'])) . '</span>';
                                                echo '<span>' . $rowServis['kilometri'] . '</span>';
                                                echo '</div>';
                                                echo '</button>';
                                                echo '</a>';
                                            }
                                            ?>

                                        </div>

                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button style="font-weight: bold;" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseSeven" aria-expanded="true" aria-controls="panelsStayOpen-collapseSeven">
                                                    DODAJ POPRAVAK&nbsp;<img style="width: 30px;" src="img/tool.png" alt="">
                                                </button>
                                            </h2>
                                            <div id="panelsStayOpen-collapseSeven" class="accordion-collapse collapse">
                                                <div class="accordion-body">
                                                    <div class="form-group forma_servis row">
                                                        <label for="naslov" class="col-sm-3">Naslov:</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control forma_input" id="naslov" name="naziv_popravka" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group forma_servis row">
                                                        <label for="datum" class="col-sm-3">Datum:</label>
                                                        <div class="col-sm-9">
                                                            <input type="date" class="form-control forma_input" id="datum" name="datum" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group forma_servis row">
                                                        <label for="kilometri" class="col-sm-3">Kilometri:</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control forma_input" id="kilometri" name="kilometri" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group forma_servis row">
                                                        <label for="opis" class="col-sm-3">Opis:</label>
                                                        <div class="col-sm-9">
                                                            <textarea name="opis" id="opis" class="form-control forma_input" rows="2" cols="70" required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="sredina_povijest">
                                                        <button type="submit" name="insert" value="Unesi" class="btn btn-outline-dark gumb"><img style="width: 30px;" src="img/add-file.png" alt=""></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

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

<script>
    document.getElementById('stanje_km').addEventListener('input', function() {
        var stanjeKm = parseInt(this.value);
        if (!isNaN(stanjeKm)) {
            var sljedeciServis = stanjeKm + 60000;
            document.getElementById('sljedeci_servis').value = sljedeciServis + "km";
        }
    });
</script>


</html>