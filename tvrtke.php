<?php
session_start();

$inactive_timeout = 15 * 60; // 15 minutes in seconds

// Check if the session variable for last activity exists and check the timeout
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $inactive_timeout)) {
    // Session expired, destroy session and redirect to logout page
    session_unset();
    session_destroy();
    header("Location: logout.php");
    exit();
}

// Update last activity time
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
                            <a href="izbornik.php"><button type="button" class="btn btn-outline-dark gumb"><img style="width: 30px;" src="img/natrag.png" alt=""></button></a>
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
        <div class="naslovi">
            <h2>POSLOVNI PARTNERI</h2>
        </div>
        <div class="row">
            <div class="col">
                <div class="card" style="width: auto; height: 340px; text-align: center; margin-top: 35px;">
                    <img class="card-img-top" src="img/Auto_Hrvatska_logo.png" alt="Card image cap" style="padding: 10px; width:330px; height:100px; margin: 0 auto;">
                    <div class="card-body">
                        <h5 class="card-title">Auto Hrvatska D.D.</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <img style="width: 14px;" src="https://kompanija-zerjav-transporti.hr/wp-content/uploads/2022/07/location-sharp.svg" alt="">Zastavnice 25c, 10257 Brezovica
                            </li>
                            <li class="list-group-item">
                                <img style="width: 15px;" src="https://kompanija-zerjav-transporti.hr/wp-content/uploads/2022/07/call-sharp.svg" alt="">+385 1 6167 666
                            </li>
                            <li class="list-group-item">
                                <img style="width: 15px;" src="https://kompanija-zerjav-transporti.hr/wp-content/uploads/2022/07/mail-sharp.svg" alt="">ah@autohrvatska.hr
                            </li>
                        </ul>
                        <a href="https://www.autohrvatska.hr/"><button type="button" class="btn btn-outline-dark gumb_tvrtke">Auto Hrvatska D.D.</button></a>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card" style="width: auto; height: 340px; text-align: center; margin-top: 35px;">
                    <img class="card-img-top" src="img/schmitz.png" alt="Card image cap" style="padding: 10px; width:330px; height:96px; margin: 0 auto;">
                    <div class="card-body">
                        <h5 class="card-title">Schmitz Cargobull</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <img style="width: 14px;" src="https://kompanija-zerjav-transporti.hr/wp-content/uploads/2022/07/location-sharp.svg" alt="">Ljudevita Posavskog 29, 10360 Sesvete
                            </li>
                            <li class="list-group-item">
                                <img style="width: 15px;" src="https://kompanija-zerjav-transporti.hr/wp-content/uploads/2022/07/call-sharp.svg" alt="">+385 1 2013 914
                            </li>
                            <li class="list-group-item">
                                <img style="width: 15px;" src="https://kompanija-zerjav-transporti.hr/wp-content/uploads/2022/07/mail-sharp.svg" alt="">schmitz.hrvatska@cargobull.com
                            </li>
                        </ul>
                        <a href="https://www.cargobull.com/hr"><button type="button" class="btn btn-outline-dark gumb_tvrtke">Schmitz Cargobull</button></a>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card" style="width: auto; height: 340px; text-align: center; margin-top: 35px;">
                    <img class="card-img-top" src="img/feros.png" alt="Card image cap" style="padding: 10px; width:330px; height:96px; margin: 0 auto;">
                    <div class="card-body">
                        <h5 class="card-title">Feros - rezervni dijelovi</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <img style="width: 14px;" src="https://kompanija-zerjav-transporti.hr/wp-content/uploads/2022/07/location-sharp.svg" alt="">Franje Lučića 32, 10000 Zagreb
                            </li>
                            <li class="list-group-item">
                                <img style="width: 15px;" src="https://kompanija-zerjav-transporti.hr/wp-content/uploads/2022/07/call-sharp.svg" alt="">+385 1 3498-093
                            </li>
                            <li class="list-group-item">
                                <img style="width: 15px;" src="https://kompanija-zerjav-transporti.hr/wp-content/uploads/2022/07/mail-sharp.svg" alt="">feros@feros.hr
                            </li>
                        </ul>
                        <a href="https://feros.hr/"><button type="button" class="btn btn-outline-dark gumb_tvrtke">Feros - rezervni dijelovi</button></a>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card" style="width: auto; height: 340px; text-align: center; margin-top: 35px;">
                    <img class="card-img-top" src="img/tokic.png" alt="Card image cap" style="padding: 10px; width:330px; height:100px; margin: 0 auto;">
                    <div class="card-body">
                        <h5 class="card-title">Autodijelovi - Tokić</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <img style="width: 14px;" src="https://kompanija-zerjav-transporti.hr/wp-content/uploads/2022/07/location-sharp.svg" alt="">Zagrebačka cesta 26c, 49000 Krapina
                            </li>
                            <li class="list-group-item">
                                <img style="width: 15px;" src="https://kompanija-zerjav-transporti.hr/wp-content/uploads/2022/07/call-sharp.svg" alt="">+385 (049) 228 025
                            </li>
                            <li class="list-group-item">
                                <img style="width: 15px;" src="https://kompanija-zerjav-transporti.hr/wp-content/uploads/2022/07/mail-sharp.svg" alt="">krapina1@tokic-partner.hr
                            </li>
                        </ul>
                        <a href="https://www.tokic.hr/"><button type="button" class="btn btn-outline-dark gumb_tvrtke">Autodijelovi - Tokić</button></a>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card" style="width: auto; height: 340px; text-align: center; margin-top: 35px;">
                    <img class="card-img-top" src="img/berner.png" alt="Card image cap" style="padding: 10px; width:330px; height:96px; margin: 0 auto;">
                    <div class="card-body">
                        <h5 class="card-title">Berner Hrvatska</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <img style="width: 14px;" src="https://kompanija-zerjav-transporti.hr/wp-content/uploads/2022/07/location-sharp.svg" alt="">Majstorska 9, 10000 Zagreb
                            </li>
                            <li class="list-group-item">
                                <img style="width: 15px;" src="https://kompanija-zerjav-transporti.hr/wp-content/uploads/2022/07/call-sharp.svg" alt="">+385 1 2499 470
                            </li>
                            <li class="list-group-item">
                                <img style="width: 15px;" src="https://kompanija-zerjav-transporti.hr/wp-content/uploads/2022/07/mail-sharp.svg" alt="">berner@berner.hr
                            </li>
                        </ul>
                        <a href="https://shop.berner.eu/hr-hr/"><button type="button" class="btn btn-outline-dark gumb_tvrtke">Berner Hrvatska</button></a>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card" style="width: auto; height: 340px; text-align: center; margin-top: 35px;">
                    <img class="card-img-top" src="img/wurth.png" alt="Card image cap" style="padding: 10px; width:330px; height:96px; margin: 0 auto;">
                    <div class="card-body">
                        <h5 class="card-title">Würth-Hrvatska d.o.o</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <img style="width: 14px;" src="https://kompanija-zerjav-transporti.hr/wp-content/uploads/2022/07/location-sharp.svg" alt="">Lužec 1, 49214 Veliko Trgovišće
                            </li>
                            <li class="list-group-item">
                                <img style="width: 15px;" src="https://kompanija-zerjav-transporti.hr/wp-content/uploads/2022/07/call-sharp.svg" alt="">+385 (049) 638-300
                            </li>
                            <li class="list-group-item">
                                <img style="width: 15px;" src="https://kompanija-zerjav-transporti.hr/wp-content/uploads/2022/07/mail-sharp.svg" alt="">wuerth@wuerth.com.hr
                            </li>
                        </ul>
                        <a href="https://eshop.wuerth.com.hr/"><button type="button" class="btn btn-outline-dark gumb_tvrtke">Würth-Hrvatska d.o.o</button></a>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card" style="width: auto; height: 340px; text-align: center; margin-top: 35px;">
                    <img class="card-img-top" src="img/intercars.png" alt="Card image cap" style="padding: 10px; width:330px; height:100px; margin: 0 auto;">
                    <div class="card-body">
                        <h5 class="card-title">Inter Cars d.o.o.</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <img style="width: 14px;" src="https://kompanija-zerjav-transporti.hr/wp-content/uploads/2022/07/location-sharp.svg" alt="">Kovinska 9B, 10000 Zagreb
                            </li>
                            <li class="list-group-item">
                                <img style="width: 15px;" src="https://kompanija-zerjav-transporti.hr/wp-content/uploads/2022/07/call-sharp.svg" alt="">+385 1 3492-245
                            </li>
                            <li class="list-group-item">
                                <img style="width: 15px;" src="https://kompanija-zerjav-transporti.hr/wp-content/uploads/2022/07/mail-sharp.svg" alt="">zagreb.zapad@intercars.eu
                            </li>
                        </ul>
                        <a href="https://intercars.hr/"><button type="button" class="btn btn-outline-dark gumb_tvrtke">Inter Cars d.o.o.</button></a>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card" style="width: auto; height: 340px; text-align: center; margin-top: 35px;">
                    <img class="card-img-top" src="img/skuba.jpg" alt="Card image cap" style="padding: 10px; width:330px; height:100px; margin: 0 auto;">
                    <div class="card-body">
                        <h5 class="card-title">Skuba Hrvatska</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <img style="width: 14px;" src="https://kompanija-zerjav-transporti.hr/wp-content/uploads/2022/07/location-sharp.svg" alt="">Kovinska 28A, 10000 Zagreb
                            </li>
                            <li class="list-group-item">
                                <img style="width: 15px;" src="https://kompanija-zerjav-transporti.hr/wp-content/uploads/2022/07/call-sharp.svg" alt="">+385 1 5582 444
                            </li>
                            <li class="list-group-item">
                                <img style="width: 15px;" src="https://kompanija-zerjav-transporti.hr/wp-content/uploads/2022/07/mail-sharp.svg" alt="">zagreb@skuba.eu
                            </li>
                        </ul>
                        <a href="https://skuba.hr/"><button type="button" class="btn btn-outline-dark gumb_tvrtke">Skuba Hrvatska</button></a>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card" style="width: auto; height: 340px; text-align: center; margin-top: 35px;">
                    <img class="card-img-top" src="img/ciak.jpg" alt="Card image cap" style="padding: 10px; width:330px; height:100px; margin: 0 auto;">
                    <div class="card-body">
                        <h5 class="card-title">CIAK Auto</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <img style="width: 14px;" src="https://kompanija-zerjav-transporti.hr/wp-content/uploads/2022/07/location-sharp.svg" alt="">Matije Gupca 128, 49210 Zabok
                            </li>
                            <li class="list-group-item">
                                <img style="width: 15px;" src="https://kompanija-zerjav-transporti.hr/wp-content/uploads/2022/07/call-sharp.svg" alt="">+385 (049) 221-726
                            </li>
                            <li class="list-group-item">
                                <img style="width: 15px;" src="https://kompanija-zerjav-transporti.hr/wp-content/uploads/2022/07/mail-sharp.svg" alt="">maloprodaja.zabok@ciak-auto.hr
                            </li>
                        </ul>
                        <a href="https://ciak-auto.hr/"><button type="button" class="btn btn-outline-dark gumb_tvrtke">CIAK Auto</button></a>
                    </div>
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