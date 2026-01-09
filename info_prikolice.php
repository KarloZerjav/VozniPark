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

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}

if (isset($_GET['id_prikolice'])) {
    $registrationId = $_GET['id_prikolice'];
    $query = "SELECT
                p.id_prikolice,
                p.registracijska_oznaka_prik AS prikolica_registracija,
                rp.prik_vrijedi_do AS prikolica_vrijedi_do,
                p.sasija_prikolica,
                p.proizvodac_prikolica,
                p.model_prikolica,
                p.godina_prikolica,
                p.visina_prikolica,
                p.nosivost,
                p.aparati
                FROM prikolice AS p
                LEFT JOIN reg_prik AS rp ON p.id_prikolice = rp.id_prikolice";
}


$prikolicaQuery = "SELECT 
                    id_prikolice, 
                    registracijska_oznaka_prik, 
                    sasija_prikolica, 
                    proizvodac_prikolica, 
                    model_prikolica, 
                    godina_prikolica, 
                    visina_prikolica, 
                    nosivost, 
                    aparati 
                    FROM prikolice 
                    WHERE id_prikolice = ?";
$stmt = mysqli_prepare($dbc, $prikolicaQuery);
mysqli_stmt_bind_param($stmt, 'i', $registrationId);
mysqli_stmt_execute($stmt);
$prikolicaResult = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);


$prikolicaRow = array('id_prikolice' => '', 'registracijska_oznaka_prik' => '', 'sasija_prikolica' => '', 'proizvodac_prikolica' => '', 'model_prikolica' => '', 'godina_prikolica' => '', 'visina_prikolica' => '', 'nosivost' => '', 'aparati' => '');

if ($prikolicaResult && mysqli_num_rows($prikolicaResult) > 0) {
    $prikolicaRow = mysqli_fetch_assoc($prikolicaResult);
}

$prikolicaRegQuery = "SELECT prik_vrijedi_do FROM reg_prik WHERE id_prikolice = ?";
$stmt = mysqli_prepare($dbc, $prikolicaRegQuery);
mysqli_stmt_bind_param($stmt, 'i', $registrationId);
mysqli_stmt_execute($stmt);
$prikolicaRegResult = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);

$prikolicaRegRow = mysqli_fetch_assoc($prikolicaRegResult);
$prikolica_vrijedi_do = $prikolicaRegRow ? $prikolicaRegRow['prik_vrijedi_do'] : null;

if (isset($_POST['update'])) {
    $sasija_prikolica = $_POST['sasija_prikolica'];
    $registracijska_oznaka_prik = $_POST['registracijska_oznaka_prik'];
    $prik_vrijedi_do = $_POST['prik_vrijedi_do'];
    $aparati = $_POST['aparati'];
    $proizvodac_prikolica = $_POST['proizvodac_prikolica'];
    $model_prikolica = $_POST['model_prikolica'];
    $godina_prikolica = $_POST['godina_prikolica'];
    $visina_prikolica = $_POST['visina_prikolica'];
    $nosivost = $_POST['nosivost'];

    $updateQuery1 = "UPDATE prikolice 
                        SET sasija_prikolica=?, 
                        registracijska_oznaka_prik=?, 
                        proizvodac_prikolica=?, 
                        model_prikolica=?, 
                        godina_prikolica=?, 
                        visina_prikolica=?, 
                        nosivost=?, aparati=? 
                        WHERE id_prikolice=?";
    $stmt1 = mysqli_prepare($dbc, $updateQuery1);
    mysqli_stmt_bind_param($stmt1, 'ssssssssi', $sasija_prikolica, 
                            $registracijska_oznaka_prik, $proizvodac_prikolica, 
                            $model_prikolica, $godina_prikolica, $visina_prikolica, 
                            $nosivost, $aparati, $registrationId);
    mysqli_stmt_execute($stmt1);
    mysqli_stmt_close($stmt1);

    $updateQuery2 = "UPDATE reg_prik SET prik_vrijedi_do=? WHERE id_prikolice=?";
    $stmt2 = mysqli_prepare($dbc, $updateQuery2);
    mysqli_stmt_bind_param($stmt2, 'si', $prik_vrijedi_do, $registrationId);
    mysqli_stmt_execute($stmt2);
    mysqli_stmt_close($stmt2);

    header("Location: popis.php");
    exit();
}


if (isset($_POST['delete'])) {
    
    $deleteQuery2 = "DELETE FROM reg_prik WHERE id_prikolice = ?";
    $stmt2 = mysqli_prepare($dbc, $deleteQuery2);
    mysqli_stmt_bind_param($stmt2, 'i', $registrationId);
    mysqli_stmt_execute($stmt2);
    mysqli_stmt_close($stmt2);

    $deleteQuery4 = "DELETE FROM prikolice WHERE id_prikolice = ?";
    $stmt4 = mysqli_prepare($dbc, $deleteQuery4);
    mysqli_stmt_bind_param($stmt4, 'i', $registrationId);
    mysqli_stmt_execute($stmt4);
    mysqli_stmt_close($stmt4);

    header("Location: popis.php");
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
                            <a href="popis.php"><button type="button" class="btn btn-outline-dark gumb"><img style="width: 30px;" src="img/natrag.png" alt=""></button></a>
                            <a href="povijest_prikolica.php?id_prikolice=<?php echo isset($_GET['id_prikolice']) ? $_GET['id_prikolice'] : ''; ?>"><button class="btn btn-outline-dark gumb"><img style="width: 30px;" src="img/servis.png" alt=""></button></a>
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
                <h2>INFORMACIJE</h2>
                <h2><img style="width: 55px; transform: scale(-1, 1);" src="img/trailer.png" alt="">&nbsp;&nbsp;<?php echo $prikolicaRow["registracijska_oznaka_prik"]; ?></h2>

            </div>
            <div class="row">
                <div class="col">
                    <form enctype="multipart/form-data" action="" method="POST">
                        <div class="form-group forma_servis row">
                            <label for="sasija_prikolica" class="col-sm-3">Broj šasije:</label>
                            <div class="col-sm-9">
                                <input type="text" name="sasija_prikolica" id="sasija_prikolica" class="form-control forma_input" value="<?php echo $prikolicaRow["sasija_prikolica"]; ?>">
                            </div>
                        </div>
                        <div class="form-group forma_servis row">
                            <label for="registracijska_oznaka_prik" class="col-sm-3">Reg. oznaka:</label>
                            <div class="col-sm-9">
                                <input type="text" name="registracijska_oznaka_prik" id="registracijska_oznaka_prik" class="form-control forma_input" value="<?php echo $prikolicaRow["registracijska_oznaka_prik"]; ?>">
                            </div>
                        </div>
                        <div class="form-group forma_servis row">
                            <label for="prik_vrijedi_do" class="col-sm-3">Registrirano do:</label>
                            <div class="col-sm-9">
                                <input type="date" name="prik_vrijedi_do" id="prik_vrijedi_do" class="form-control forma_input" value="<?php echo date('Y-m-d', strtotime($prikolica_vrijedi_do)); ?>">
                            </div>
                        </div>
                        <div class="form-group forma_servis row">
                            <label for="aparati" class="col-sm-3">Aparati:</label>
                            <div class="col-sm-9">
                                <input type="date" name="aparati" id="aparati" class="form-control forma_input" value="<?php echo $prikolicaRow["aparati"] !== null ? date('Y-m-d', strtotime($prikolicaRow["aparati"])) : ''; ?>">
                            </div>
                        </div>
                        <div class="form-group forma_servis row">
                            <label for="proizvodac_prikolica" class="col-sm-3">Proizvođač:</label>
                            <div class="col-sm-9">
                                <input type="text" name="proizvodac_prikolica" id="proizvodac_prikolica" class="form-control forma_input" value="<?php echo $prikolicaRow["proizvodac_prikolica"]; ?>">
                            </div>
                        </div>
                        <div class="form-group forma_servis row">
                            <label for="model_prikolica" class="col-sm-3">Model:</label>
                            <div class="col-sm-9">
                                <input type="text" name="model_prikolica" id="model_prikolica" class="form-control forma_input" value="<?php echo $prikolicaRow["model_prikolica"]; ?>">
                            </div>
                        </div>
                        <div class="form-group forma_servis row">
                            <label for="godina_prikolica" class="col-sm-3">Godina:</label>
                            <div class="col-sm-9">
                                <input type="text" name="godina_prikolica" id="godina_prikolica" class="form-control forma_input" value="<?php echo $prikolicaRow["godina_prikolica"]; ?>">
                            </div>
                        </div>
                        <div class="form-group forma_servis row">
                            <label for="visina_prikolica" class="col-sm-3">Visina:</label>
                            <div class="col-sm-9">
                                <input type="text" name="visina_prikolica" id="visina_prikolica" class="form-control forma_input" value="<?php echo $prikolicaRow["visina_prikolica"]; ?>">
                            </div>
                        </div>
                        <div class="form-group forma_servis row">
                            <label for="nosivost" class="col-sm-3">Nosivost:</label>
                            <div class="col-sm-9">
                                <input type="text" name="nosivost" id="nosivost" class="form-control forma_input" value="<?php echo $prikolicaRow["nosivost"]; ?>">
                            </div>
                        </div>

                        <div class="gumb_uredivanje_podataka">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <button type="reset" value="Poništi" class="btn btn-outline-dark gumb"><img style="width: 30px;" src="img/refresh.png" alt=""></button>
                                <button type="submit" name="update" value="Prihvati" class="btn btn-outline-dark gumb"><img style="width: 30px;" src="img/save-file.png" alt=""></button>
                                <button type="delete" name="delete" value="Izbriši" class="btn btn-outline-dark gumb" onclick="return confirm('Jeste li sigurni da želite obrisati podatke?')"><img style="width: 30px;" src="img/trash.svg" alt=""></button>
                            </div>
                        </div>
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