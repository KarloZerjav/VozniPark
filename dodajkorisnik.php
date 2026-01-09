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



if (isset($_POST['insert'])) {
    $vozaciQuery = "INSERT INTO vozac 
                    (vozac_korisnicko_ime, vozac_lozinka, 
                    vozac_ime, vozac_prezime, vozac_adresa, 
                    vozac_datum_rod)
                    VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($dbc, $vozaciQuery);
    mysqli_stmt_bind_param($stmt, "ssssss", $vozac_korisnicko_ime, 
                            $vozac_lozinka, $vozac_ime, $vozac_prezime, 
                            $vozac_adresa, $vozac_datum);

    $vozac_korisnicko_ime = $_POST['vozac_korisnicko_ime'];
    $vozac_lozinka = $_POST['vozac_lozinka'];
    $vozac_ime = $_POST['vozac_ime'];
    $vozac_prezime = $_POST['vozac_prezime'];
    $vozac_adresa = $_POST['vozac_adresa'];
    if (!empty($_POST['vozac_datum'])) {
        $vozac_datum = $_POST['vozac_datum'];
    } else {
        $vozac_datum = null;
    }
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/plug-ins/1.10.25/i18n/Croatian.json"></script>
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
                            <a href="odabir.php"><button type="button" class="btn btn-outline-dark gumb"><img style="width: 30px;" src="img/natrag.png" alt=""></button></a>
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
                <h2 style="margin-bottom: 30px;">DODAJ VOZAČA</h2>
            </div>
            <form enctype="multipart/form-data" action="" method="POST">
                <div class="form-group forma_servis row">
                    <label for="vozac_korisnicko_ime" class="col-sm-3">Korisničko ime:</label>
                    <div class="col-sm-9">
                        <input type="text" name="vozac_korisnicko_ime" id="vozac_korisnicko_ime" class="form-control forma_input" required>
                    </div>
                </div>
                <div class="form-group forma_servis row">
                    <label for="vozac_lozinka" class="col-sm-3">Lozinka:</label>
                    <div class="col-sm-9">
                        <input type="password" name="vozac_lozinka" id="vozac_lozinka" class="form-control forma_input" required>
                    </div>
                </div>
                <div class="form-group forma_servis row">
                    <label for="vozac_ime" class="col-sm-3">Ime:</label>
                    <div class="col-sm-9">
                        <input type="text" name="vozac_ime" id="vozac_ime" class="form-control forma_input" required>
                    </div>
                </div>
                <div class="form-group forma_servis row">
                    <label for="vozac_prezime" class="col-sm-3">Prezime:</label>
                    <div class="col-sm-9">
                        <input type="text" name="vozac_prezime" id="vozac_prezime" class="form-control forma_input" required>
                    </div>
                </div>
                <div class="form-group forma_servis row">
                    <label for="vozac_datum" class="col-sm-3">Datum rođenja:</label>
                    <div class="col-sm-9">
                        <input type="date" name="vozac_datum" id="vozac_datum" class="form-control forma_input" required>
                    </div>
                </div>
                <div class="form-group forma_servis row">
                    <label for="vozac_adresa" class="col-sm-3">Adresa:</label>
                    <div class="col-sm-9">
                        <input type="text" name="vozac_adresa" id="vozac_adresa" class="form-control forma_input" required>
                    </div>
                </div>
                <div class="sredina_povijest">
                    <button type="submit" name="insert" value="Unesi" class="btn btn-outline-dark gumb"><img style="width: 30px;" src="img/add-file.png" alt=""></button>
                </div>
            </form>
        </div>
    </div>


    <div class="footer_boja">
        <p>Karlo Žerjav</p>
        <p>Organizacija i informatizacija ureda, završni rad</p>
    </div>
</body>

</html>
