<?php
include 'connect.php';

session_start();
if (isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] > 900) {
    session_unset();
    session_destroy();
    header("Location: index.php");
   }
$_SESSION['last_activity'] = time();

$user = isset($_SESSION['username']) ? $_SESSION['username'] : '';

if (empty($user)) {
    header("Location: index.php");
}

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}


if (isset($_GET['id_kamion'])) {
    $registrationId = $_GET['id_kamion'];
    $query = "SELECT 
        k.id_kamion,
        k.registracijska_oznaka_kam AS kamion_registracija,
        rk.kam_vrijedi_do AS kamion_vrijedi_do,
        p.id_prikolice,
        p.registracijska_oznaka_prik AS prikolica_registracija,
        rp.prik_vrijedi_do AS prikolica_vrijedi_do
        FROM kamioni AS k
        LEFT JOIN reg_kam AS rk ON k.id_kamion = rk.id_kamion
        LEFT JOIN kamion_prikolica AS kp ON k.id_kamion = kp.id_kamion
        LEFT JOIN prikolice AS p ON kp.id_prikolice = p.id_prikolice
        LEFT JOIN reg_prik AS rp ON p.id_prikolice = rp.id_prikolice
        WHERE k.id_kamion = ? OR p.id_prikolice = ?
        UNION
        SELECT 
            k.id_kamion,
            k.registracijska_oznaka_kam AS kamion_registracija,
            rk.kam_vrijedi_do AS kamion_vrijedi_do,
            NULL AS id_prikolice,
            NULL AS prikolica_registracija,
            NULL AS prikolica_vrijedi_do
            FROM kamioni AS k
            LEFT JOIN reg_kam AS rk ON k.id_kamion = rk.id_kamion
            LEFT JOIN kamion_prikolica AS kp ON k.id_kamion = kp.id_kamion
            WHERE kp.id_kamion IS NULL
        UNION
        SELECT 
            NULL AS id_kamion,
            NULL AS kamion_registracija,
            NULL AS kamion_vrijedi_do,
            p.id_prikolice,
            p.registracijska_oznaka_prik AS prikolica_registracija,
            rp.prik_vrijedi_do AS prikolica_vrijedi_do
            FROM prikolice AS p
            LEFT JOIN reg_prik AS rp ON p.id_prikolice = rp.id_prikolice
            LEFT JOIN kamion_prikolica AS kp ON p.id_prikolice = kp.id_prikolice
            WHERE kp.id_prikolice IS NULL";
}


$kamionQuery = "SELECT id_kamion, registracijska_oznaka_kam, sasija_kamion, proizvodac_kamion, model_kamion, godina_kamion, visina_kamion, rezervar, tahograf, aparat_kabine, slika FROM kamioni WHERE id_kamion = ?";
$stmt = mysqli_prepare($dbc, $kamionQuery);
mysqli_stmt_bind_param($stmt, 'i', $registrationId);
mysqli_stmt_execute($stmt);
$kamionResult = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);

$kamionRow = array('id_kamion' => '', 'registracijska_oznaka_kam' => '', 'sasija_kamion' => '', 'proizvodac_kamion' => '', 'model_kamion' => '', 'godina_kamion' => '', 'visina_kamion' => '', 'rezervar' => '', 'tahograf' => '', 'aparat_kabine' => '', 'slika' => '');

if ($kamionResult && mysqli_num_rows($kamionResult) > 0) {
    $kamionRow = mysqli_fetch_assoc($kamionResult);
}

$prikolicaQuery = "SELECT id_prikolice, registracijska_oznaka_prik, sasija_prikolica, proizvodac_prikolica, model_prikolica, godina_prikolica, visina_prikolica, nosivost, aparati FROM prikolice WHERE id_prikolice = ?";
$stmt = mysqli_prepare($dbc, $prikolicaQuery);
mysqli_stmt_bind_param($stmt, 'i', $registrationId);
mysqli_stmt_execute($stmt);
$prikolicaResult = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);

$prikolicaRow = array('id_prikolice' => '', 'registracijska_oznaka_prik' => '', 'sasija_prikolica' => '', 'proizvodac_prikolica' => '', 'model_prikolica' => '', 'godina_prikolica' => '', 'visina_prikolica' => '', 'nosivost' => '', 'aparati' => '');

if ($prikolicaResult && mysqli_num_rows($prikolicaResult) > 0) {
    $prikolicaRow = mysqli_fetch_assoc($prikolicaResult);
}

$kamionRegQuery = "SELECT kam_vrijedi_do FROM reg_kam WHERE id_kamion = ?";
$stmt = mysqli_prepare($dbc, $kamionRegQuery);
mysqli_stmt_bind_param($stmt, 'i', $registrationId);
mysqli_stmt_execute($stmt);
$kamionRegResult = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);

$kamionRegRow = mysqli_fetch_assoc($kamionRegResult);
$kamion_vrijedi_do = $kamionRegRow ? $kamionRegRow['kam_vrijedi_do'] : null;

$prikolicaRegQuery = "SELECT prik_vrijedi_do FROM reg_prik WHERE id_prikolice = ?";
$stmt = mysqli_prepare($dbc, $prikolicaRegQuery);
mysqli_stmt_bind_param($stmt, 'i', $registrationId);
mysqli_stmt_execute($stmt);
$prikolicaRegResult = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);

$prikolicaRegRow = mysqli_fetch_assoc($prikolicaRegResult);
$prikolica_vrijedi_do = $prikolicaRegRow ? $prikolicaRegRow['prik_vrijedi_do'] : null;

$idVozac = null; // Initialize $idVozac to null

// Check if there is a current ID kamion in the kamion_prikolica table
$checkKamionPrikolicaQuery = "SELECT id_vozac FROM kamion_prikolica WHERE id_kamion = ?";
$stmt = mysqli_prepare($dbc, $checkKamionPrikolicaQuery);
mysqli_stmt_bind_param($stmt, 'i', $registrationId);
mysqli_stmt_execute($stmt);
$idVozacResult = mysqli_stmt_get_result($stmt);
$idVozacRow = mysqli_fetch_assoc($idVozacResult); // Fetching id_vozac from kamion_prikolica

if ($idVozacRow) {
    $idVozac = $idVozacRow['id_vozac']; // Storing the fetched id_vozac if it exists
}

mysqli_stmt_close($stmt);

// Check if $idVozac is not null before proceeding to fetch driver information
if ($idVozac !== null) {
    // Query to fetch driver information based on the fetched id_vozac
    $vozacQuery = "SELECT * FROM vozac WHERE id_vozac = ?";
    $stmt = mysqli_prepare($dbc, $vozacQuery);
    mysqli_stmt_bind_param($stmt, 'i', $idVozac);
    mysqli_stmt_execute($stmt);
    $vozacResult = mysqli_stmt_get_result($stmt);
    $vozacRow = mysqli_fetch_assoc($vozacResult); // Fetching driver information
    mysqli_stmt_close($stmt);
}

// Query to fetch id_prikolica and registracijska_oznaka_prik from kamion_prikolica table based on the current id_kamion
$prikolicaKamion = "SELECT kp.id_prikolice, p.registracijska_oznaka_prik FROM kamion_prikolica kp INNER JOIN prikolice p ON kp.id_prikolice = p.id_prikolice WHERE kp.id_kamion = ?";
$prikKam = mysqli_prepare($dbc, $prikolicaKamion);
mysqli_stmt_bind_param($prikKam, 'i', $registrationId);
mysqli_stmt_execute($prikKam);
$prikolicaKamionResult = mysqli_stmt_get_result($prikKam);


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    // Extract data from the form
    $id_kamion = $_GET['id_kamion'];
    $registracijska_oznaka_kam = $_POST['registracijska_oznaka_kam'];
    $sasija_kamion = $_POST['sasija_kamion'];
    $proizvodac_kamion = $_POST['proizvodac_kamion'];
    $model_kamion = $_POST['model_kamion'];
    $godina_kamion = $_POST['godina_kamion'];
    $visina_kamion = $_POST['visina_kamion'];
    $rezervar = $_POST['rezervar'];
    $tahograf = $_POST['tahograf'];
    $aparat_kabine = $_POST['aparat_kabine'];

    // Update data in the kamion table
    $updateKamionQuery = "UPDATE kamioni SET 
        registracijska_oznaka_kam = ?,
        sasija_kamion = ?,
        proizvodac_kamion = ?,
        model_kamion = ?,
        godina_kamion = ?,
        visina_kamion = ?,
        rezervar = ?,
        tahograf = ?,
        aparat_kabine = ?
        WHERE id_kamion = ?";

    $stmt = mysqli_prepare($dbc, $updateKamionQuery);
    mysqli_stmt_bind_param($stmt, 'sssssssssi', $registracijska_oznaka_kam, $sasija_kamion, $proizvodac_kamion, $model_kamion, $godina_kamion, $visina_kamion, $rezervar, $tahograf, $aparat_kabine, $id_kamion);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Update data in the reg_kam table
    $kam_vrijedi_do = $_POST['kam_vrijedi_do'];
    $updateRegKamQuery = "UPDATE reg_kam SET kam_vrijedi_do = ? WHERE id_kamion = ?";
    $stmt = mysqli_prepare($dbc, $updateRegKamQuery);
    mysqli_stmt_bind_param($stmt, 'si', $kam_vrijedi_do, $id_kamion);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $id_prikolica = $_POST['registracijska_oznaka_prik']; // Get the selected id_prikolica from the form

    // Check if the selected value is 'remove'
    if ($id_prikolica === "remove") {
        // Set the id_prikolice to NULL
        $deleteKamionPrikolicaQuery = "DELETE FROM kamion_prikolica WHERE id_kamion = ? OR id_prikolice = ? OR id_vozac = ?";
        $stmt = mysqli_prepare($dbc, $deleteKamionPrikolicaQuery);
        mysqli_stmt_bind_param($stmt, 'iii', $id_kamion, $id_prikolice, $id_vozac);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } else {
        // Check if there is an existing entry in the kamion_prikolica table for the current truck and trailer
        $checkExistingQuery = "SELECT * FROM kamion_prikolica WHERE id_kamion = ?";
        $stmt = mysqli_prepare($dbc, $checkExistingQuery);
        mysqli_stmt_bind_param($stmt, 'i', $id_kamion);
        mysqli_stmt_execute($stmt);
        $existingResult = mysqli_stmt_get_result($stmt);
        $existingRow = mysqli_fetch_assoc($existingResult);
        mysqli_stmt_close($stmt);

        if ($existingRow) {
            // If there is an existing entry, update it with the selected trailer
            $updateKamionPrikolicaQuery = "UPDATE kamion_prikolica SET id_prikolice = ? WHERE id_kamion = ?";
            $stmt = mysqli_prepare($dbc, $updateKamionPrikolicaQuery);
            mysqli_stmt_bind_param($stmt, 'ii', $id_prikolica, $id_kamion);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } else {
            // If there is no existing entry, insert a new one
            $insertKamionPrikolicaQuery = "INSERT INTO kamion_prikolica (id_kamion, id_prikolice) VALUES (?, ?)";
            $stmt = mysqli_prepare($dbc, $insertKamionPrikolicaQuery);
            mysqli_stmt_bind_param($stmt, 'ii', $id_kamion, $id_prikolica);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    if (isset($_POST['vozac_dropdown'])) {
        $id_vozac = $_POST['vozac_dropdown'];

        if ($id_vozac === "remove") {
            $updateKamionPrikolicaQuery = "UPDATE kamion_prikolica SET id_vozac = NULL WHERE id_kamion = ?";
            $stmt = mysqli_prepare($dbc, $updateKamionPrikolicaQuery);
            mysqli_stmt_bind_param($stmt, 'i', $id_kamion);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } else {
            // Update the driver ID
            $updateKamionPrikolicaQuery = "UPDATE kamion_prikolica SET id_vozac = ? WHERE id_kamion = ?";

            $stmt = mysqli_prepare($dbc, $updateKamionPrikolicaQuery);
            mysqli_stmt_bind_param($stmt, 'ii', $id_vozac, $id_kamion); // Bind both id_vozac and id_kamion parameters
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            // Update data in the vozac table only if any driver field is not empty
            if (!empty($_POST['vozac_korisnicko_ime']) || !empty($_POST['vozac_lozinka']) || !empty($_POST['vozac_ime']) || !empty($_POST['vozac_prezime']) || !empty($_POST['vozac_adresa']) || !empty($_POST['vozac_datum_rod'])) {
                $updateVozacQuery = "UPDATE vozac SET 
                vozac_korisnicko_ime = ?,
                vozac_lozinka = ?,
                vozac_ime = ?,
                vozac_prezime = ?,
                vozac_adresa = ?,
                vozac_datum_rod = ?
                WHERE id_vozac = ?";

                $stmt = mysqli_prepare($dbc, $updateVozacQuery);
                mysqli_stmt_bind_param($stmt, 'ssssssi', $_POST['vozac_korisnicko_ime'], $_POST['vozac_lozinka'], $_POST['vozac_ime'], $_POST['vozac_prezime'], $_POST['vozac_adresa'], $_POST['vozac_datum_rod'], $id_vozac);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
        }
    }
    // Update image data if a new image is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_data = file_get_contents($_FILES['image']['tmp_name']);

        // Update the image data in the database
        $stmt = mysqli_prepare($dbc, "UPDATE kamioni SET slika=? WHERE id_kamion=?");
        mysqli_stmt_bind_param($stmt, 'si', $image_data, $id_kamion);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    // Redirect to a success page or back to the current page
    header("Location: info_kamion.php?id_kamion=$id_kamion"); // Change success.php to your desired success page
    exit();
}


if (isset($_POST['delete'])) {
    $deleteQuery = "DELETE FROM kamioni WHERE id_kamion = ?";
    $stmt = mysqli_prepare($dbc, $deleteQuery);
    mysqli_stmt_bind_param($stmt, 'i', $registrationId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: popis.php");
    exit();
}

$checkKamionPrikolicaQuery = "SELECT id_kamion FROM kamion_prikolica WHERE id_kamion = ?";
$stmt = mysqli_prepare($dbc, $checkKamionPrikolicaQuery);
mysqli_stmt_bind_param($stmt, 'i', $registrationId);
mysqli_stmt_execute($stmt);
$checkKamionPrikolicaResult = mysqli_stmt_get_result($stmt);
$hasKamionPrikolica = mysqli_num_rows($checkKamionPrikolicaResult) > 0;
mysqli_stmt_close($stmt);
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
                            <a href="popis.php"><button type="button" class="btn btn-outline-dark gumb"><img style="width: 30px;" src="img/natrag.png" alt=""></button></a>
                            <a href="povijest_kamion.php?id_kamion=<?php echo isset($_GET['id_kamion']) ? $_GET['id_kamion'] : ''; ?>"><button class="btn btn-outline-dark gumb"><img style="width: 30px;" src="img/servis.png" alt=""></button></a>
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
            <div class="naslovi">
                <h2>INFORMACIJE O <?php echo $kamionRow["registracijska_oznaka_kam"]; ?></h2>
            </div>
            <div class="row">
                <div class="col">
                    <form enctype="multipart/form-data" action="" method="POST">
                        <div class="accordion" id="accordionPanelsStayOpenExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button style="font-weight: bold;" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                        <img style="width: 50px;" src="img/semi-truck.png" alt="">
                                    </button>
                                </h2>
                                <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse">
                                    <div class="accordion-body">
                                        <div class="form-group forma_servis row">
                                            <label for="sasija_kamion" class="col-sm-3">Broj šasije:</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="sasija_kamion" id="sasija_kamion" class="form-control forma_input" value="<?php echo $kamionRow["sasija_kamion"]; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group forma_servis row">
                                            <label for="registracijska_oznaka_kam" class="col-sm-3">Reg. oznaka:</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="registracijska_oznaka_kam" id="registracijska_oznaka_kam" class="form-control forma_input" value="<?php echo $kamionRow["registracijska_oznaka_kam"]; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group forma_servis row">
                                            <label for="kam_vrijedi_do" class="col-sm-3">Registrirano do:</label>
                                            <div class="col-sm-9">
                                                <input type="date" name="kam_vrijedi_do" id="kam_vrijedi_do" class="form-control forma_input" value="<?php echo date('Y-m-d', strtotime($kamion_vrijedi_do)); ?>">
                                            </div>
                                        </div>
                                        <div class="form-group forma_servis row">
                                            <label for="tahograf" class="col-sm-3">Tahograf:</label>
                                            <div class="col-sm-9">
                                                <input type="date" name="tahograf" id="tahograf" class="form-control forma_input" value="<?php echo $kamionRow["tahograf"] !== null ? date('Y-m-d', strtotime($kamionRow["tahograf"])) : ''; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group forma_servis row">
                                            <label for="aparat_kabine" class="col-sm-3">Aparat:</label>
                                            <div class="col-sm-9">
                                                <input type="date" name="aparat_kabine" id="aparat_kabine" class="form-control forma_input" value="<?php echo $kamionRow["aparat_kabine"] !== null ? date('Y-m-d', strtotime($kamionRow["aparat_kabine"])) : ''; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group forma_servis row">
                                            <label for="registracijska_oznaka_prik" class="col-sm-3">Prikolica:</label>
                                            <div class="col-sm-9">
                                                <select class="form-select" name="registracijska_oznaka_prik" id="registracijska_oznaka_prik">
                                                    <?php
                                                    // Check if there are any associated prikolica
                                                    if (mysqli_num_rows($prikolicaKamionResult) > 0) {
                                                        // Fetching and displaying the current truck's trailer (if any)
                                                        while ($row = mysqli_fetch_assoc($prikolicaKamionResult)) {
                                                            echo '<option value="' . $row['id_prikolice'] . '">' . $row['registracijska_oznaka_prik'] . '</option>';
                                                        }
                                                        // Add an option to remove the associated trailer
                                                        echo '<option value="remove">Ukloni prikolicu</option>';
                                                    } else {
                                                        // If no associated prikolica found, display a default option
                                                        echo '<option value="remove" selected>Odaberi prikolicu</option>';
                                                        // Query to fetch trailers not associated with any trucks
                                                        $freePrikolicaQuery = "SELECT id_prikolice, registracijska_oznaka_prik FROM prikolice WHERE id_prikolice NOT IN (SELECT id_prikolice FROM kamion_prikolica)";
                                                        $freePrikolicaResult = mysqli_query($dbc, $freePrikolicaQuery);

                                                        // Fetching and displaying trailers not associated with any trucks
                                                        while ($freePrikolicaRow = mysqli_fetch_assoc($freePrikolicaResult)) {
                                                            echo '<option value="' . $freePrikolicaRow['id_prikolice'] . '">' . $freePrikolicaRow['registracijska_oznaka_prik'] . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>




                                        <div class="accordion" id="accordionPanelsStayOpenExample">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button style="font-weight: bold;" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="true" aria-controls="panelsStayOpen-collapseTwo">
                                                        SPECIFIKACIJE
                                                    </button>
                                                </h2>
                                                <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse">
                                                    <div class="accordion-body">
                                                        <div class="form-group forma_servis row">
                                                            <label for="proizvodac_kamion" class="col-sm-3">Proizvođač:</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" name="proizvodac_kamion" id="proizvodac_kamion" class="form-control forma_input" value="<?php echo $kamionRow["proizvodac_kamion"]; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="form-group forma_servis row">
                                                            <label for="model_kamion" class="col-sm-3">Model:</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" name="model_kamion" id="model_kamion" class="form-control forma_input" value="<?php echo $kamionRow["model_kamion"]; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="form-group forma_servis row">
                                                            <label for="godina_kamion" class="col-sm-3">Godina:</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" name="godina_kamion" id="godina_kamion" class="form-control forma_input" value="<?php echo $kamionRow["godina_kamion"]; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="form-group forma_servis row">
                                                            <label for="visina_kamion" class="col-sm-3">Visina:</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" name="visina_kamion" id="visina_kamion" class="form-control forma_input" value="<?php echo $kamionRow["visina_kamion"]; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="form-group forma_servis row">
                                                            <label for="rezervar" class="col-sm-3">Rezervar:</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" name="rezervar" id="rezervar" class="form-control forma_input" value="<?php echo $kamionRow["rezervar"]; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        if ($hasKamionPrikolica) {
                            // Display the driver information
                        ?>
                            <div class="accordion" id="accordionPanelsStayOpenExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button style="font-weight: bold;" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="true" aria-controls="panelsStayOpen-collapseThree">
                                            <img style="width: 50px;" src="img/driver.png" alt="">
                                        </button>
                                    </h2>
                                    <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse">
                                        <div class="accordion-body">
                                            <?php if (empty($vozacRow)) : ?>
                                                <div class="form-group forma_servis row">
                                                    <label for="vozac_dropdown" class="col-sm-3">Odaberi vozača:</label>
                                                    <div class="col-sm-9">
                                                        <select class="form-select" name="vozac_dropdown" id="vozac_dropdown">
                                                            <option value="remove">Odaberi vozača</option>
                                                            <?php
                                                            // Fetch all available drivers from the database and populate the dropdown list
                                                            $driverQuery = "SELECT id_vozac, CONCAT(vozac_ime, ' ', vozac_prezime) AS full_name FROM vozac";
                                                            $driverResult = mysqli_query($dbc, $driverQuery);
                                                            while ($driverRow = mysqli_fetch_assoc($driverResult)) {
                                                                echo '<option value="' . $driverRow['id_vozac'] . '">' . $driverRow['full_name'] . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            <?php else : ?>
                                                <div class="form-group forma_servis row">
                                                    <label for="vozac_korisnicko_ime" class="col-sm-3">Korisničko ime:</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="vozac_korisnicko_ime" id="vozac_korisnicko_ime" class="form-control forma_input" value="<?php echo $vozacRow['vozac_korisnicko_ime']; ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group forma_servis row">
                                                    <label for="vozac_lozinka" class="col-sm-3">Lozinka:</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="vozac_lozinka" id="vozac_lozinka" class="form-control forma_input" value="<?php echo $vozacRow['vozac_lozinka']; ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group forma_servis row">
                                                    <label for="vozac_ime" class="col-sm-3">Ime:</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="vozac_ime" id="vozac_ime" class="form-control forma_input" value="<?php echo $vozacRow['vozac_ime']; ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group forma_servis row">
                                                    <label for="vozac_prezime" class="col-sm-3">Prezime:</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="vozac_prezime" id="vozac_prezime" class="form-control forma_input" value="<?php echo $vozacRow['vozac_prezime']; ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group forma_servis row">
                                                    <label for="vozac_datum_rod" class="col-sm-3">Datum rođenja:</label>
                                                    <div class="col-sm-9">
                                                        <input type="date" name="vozac_datum_rod" id="vozac_datum_rod" class="form-control forma_input" value="<?php echo date('Y-m-d', strtotime($vozacRow['vozac_datum_rod'])); ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group forma_servis row">
                                                    <label for="vozac_adresa" class="col-sm-3">Adresa:</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="vozac_adresa" id="vozac_adresa" class="form-control forma_input" value="<?php echo $vozacRow['vozac_adresa']; ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group forma_servis row">
                                                    <label for="vozac_dropdown" class="col-sm-3">Promijeni vozača:</label>
                                                    <div class="col-sm-9">
                                                        <select class="form-select" name="vozac_dropdown" id="vozac_dropdown">
                                                            <?php
                                                            // Fetch all available drivers from the database and populate the dropdown list
                                                            $driverQuery = "SELECT id_vozac, CONCAT(vozac_ime, ' ', vozac_prezime) AS full_name FROM vozac";
                                                            $driverResult = mysqli_query($dbc, $driverQuery);
                                                            while ($driverRow = mysqli_fetch_assoc($driverResult)) {
                                                                $selected = ($driverRow['id_vozac'] == $idVozac) ? "selected" : ""; // Check if this driver is the current driver
                                                                echo '<option value="' . $driverRow['id_vozac'] . '" ' . $selected . '>' . $driverRow['full_name'] . '</option>';
                                                            }
                                                            ?>
                                                            <option value="remove">Ukloni vozača</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                        <div class="gumb_uredivanje_podataka">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <button type="reset" value="Poništi" class="btn btn-outline-dark gumb"><img style="width: 30px;" src="img/refresh.png" alt=""></button>
                                <button type="submit" name="update" value="Prihvati" class="btn btn-outline-dark gumb"><img style="width: 30px;" src="img/save-file.png" alt=""></button>
                                <button type="delete" name="delete" value="Izbriši" class="btn btn-outline-dark gumb" onclick="return confirm('Jeste li sigurni da želite obrisati podatke?')"><img style="width: 30px;" src="img/trash.svg" alt=""></button>
                            </div>
                        </div>
                </div>
                <div class="col slika_sredina">
                    <?php
                    if (!empty($kamionRow["slika"])) {
                        echo '<img src="data:image/jpeg;base64,' . base64_encode($kamionRow["slika"]) . '" class="servis_slika img-fluid" alt="Responsive image">';
                    } else {
                        echo '<p>Nema dostupne fotografije!</p>';
                    }
                    ?>
                    <br>
                    <br>
                    <input type="file" name="image" />
                </div>
                </form>
            </div>
        </div>
    </div>

    <div class="footer_boja">
        <p>Karlo Žerjav</p>
        <p>Organizacija i informatizacija ureda, završni rad</p>
    </div>
</body>



</html>