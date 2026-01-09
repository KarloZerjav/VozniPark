<?php
session_start();
$user = isset($_SESSION['username']) ? $_SESSION['username'] : '';
if (isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] > 900) {
    session_unset();
    session_destroy();
    header("Location: index.php");
   }
   $_SESSION['last_activity'] = time();
include 'connect.php';
$msg = ""; // Message variable for successful registration message
if (empty($user)) {
    header("Location: index.php");
}
if (isset($_GET['logout'])) {
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session
    header("Location: index.php"); // Redirect to the index page
    exit();
}

$sqlKam = "SELECT 
            k.id_kamion,
            k.registracijska_oznaka_kam,
            k.tahograf,
            k.aparat_kabine
        FROM kamioni AS k";

$sqlPrik = "SELECT 
            p.id_prikolice,
            p.registracijska_oznaka_prik,
            p.aparati
        FROM prikolice AS p";



$resultKam = mysqli_query($dbc, $sqlKam);
$resultPrik = mysqli_query($dbc, $sqlPrik);

if (!$resultKam || !$resultPrik) {
    die("Error: " . mysqli_error($dbc));
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
        <div class="sredina">
            <div class="naslovi">
                <h2>POTREBNO OBNOVITI</h2>
            </div>
            <?php
            $alert_counter = 0; // Initialize the alert counter

            // Loop through the result set for kamioni (trucks)
            if ($resultKam) {
                while ($row = mysqli_fetch_assoc($resultKam)) {
                    $tahograf = strtotime($row["tahograf"]);
                    $aparat_kabine = strtotime($row["aparat_kabine"]);

                    $today = strtotime('today');
                    $last_day_of_current_month = strtotime(date('Y-m-t'));
                    $days_until_tahograf = ($tahograf - $today) / (60 * 60 * 24);
                    $days_until_aparat_kabine = ($aparat_kabine - $today) / (60 * 60 * 24);

                    if (($tahograf >= $today && $tahograf <= $last_day_of_current_month) || $tahograf < $today) {
                        $expiration_date = date('d.m.Y', $tahograf);
                        if ($tahograf == $today) {
                            echo '<div class="alert alert-primary" style="font-weight: bold;" role="alert">';
                            echo 'Tahograf ' . htmlspecialchars($row["registracijska_oznaka_kam"]) . ' ističe danas (' . $expiration_date . ').';
                            echo '</div>';
                        } elseif ($tahograf < $today) {
                            $days_expired = abs($days_until_tahograf);
                            echo '<div class="alert alert-primary" style="font-weight: bold;" role="alert">';
                            echo 'Tahograf ' . htmlspecialchars($row["registracijska_oznaka_kam"]) . ' je istekao prije ' . $days_expired . ' dana (' . $expiration_date . ').';
                            echo '</div>';
                        } else {
                            echo '<div class="alert alert-primary" style="font-weight: bold;" role="alert">';
                            echo 'Tahograf ' . htmlspecialchars($row["registracijska_oznaka_kam"]) . ' ističe za ' . abs($days_until_tahograf) . ' dana (' . $expiration_date . ').';
                            echo '</div>';
                        }
                        $alert_counter++;
                    }

                    if (($aparat_kabine >= $today && $aparat_kabine <= $last_day_of_current_month) || $aparat_kabine < $today) {
                        $expiration_date = date('d.m.Y', $aparat_kabine);
                        if ($aparat_kabine == $today) {
                            echo '<div class="alert alert-primary" style="font-weight: bold;" role="alert">';
                            echo 'Aparat kabine ' . htmlspecialchars($row["registracijska_oznaka_kam"]) . ' ističe danas (' . $expiration_date . ').';
                            echo '</div>';
                        } elseif ($aparat_kabine < $today) {
                            $days_expired = abs($days_until_aparat_kabine);
                            echo '<div class="alert alert-primary" style="font-weight: bold;" role="alert">';
                            echo 'Aparat kabine ' . htmlspecialchars($row["registracijska_oznaka_kam"]) . ' je istekao prije ' . $days_expired . ' dana (' . $expiration_date . ').';
                            echo '</div>';
                        } else {
                            echo '<div class="alert alert-primary" style="font-weight: bold;" role="alert">';
                            echo 'Aparat kabine ' . htmlspecialchars($row["registracijska_oznaka_kam"]) . ' ističe za ' . abs($days_until_aparat_kabine) . ' dana (' . $expiration_date . ').';
                            echo '</div>';
                        }
                        $alert_counter++;
                    }
                }
            }

            // Loop through the result set for prikolice (trailers)
            if ($resultPrik) {
                while ($row = mysqli_fetch_assoc($resultPrik)) {
                    $aparati = strtotime($row["aparati"]);

                    $today = strtotime('today');
                    $last_day_of_current_month = strtotime(date('Y-m-t'));
                    $days_until_aparati = ($aparati - $today) / (60 * 60 * 24);

                    if (($aparati >= $today && $aparati <= $last_day_of_current_month) || $aparati < $today) {
                        $expiration_date = date('d.m.Y', $aparati);
                        if ($aparati == $today) {
                            echo '<div class="alert alert-primary" style="font-weight: bold;" role="alert">';
                            echo 'Aparati na prikolici ' . htmlspecialchars($row["registracijska_oznaka_prik"]) . ' ističu danas (' . $expiration_date . ').';
                            echo '</div>';
                        } elseif ($aparati < $today) {
                            $days_expired = abs($days_until_aparati);
                            echo '<div class="alert alert-primary" style="font-weight: bold;" role="alert">';
                            echo 'Aparati na prikolici ' . htmlspecialchars($row["registracijska_oznaka_prik"]) . ' su istekli prije ' . $days_expired . ' dana (' . $expiration_date . ').';
                            echo '</div>';
                        } else {
                            echo '<div class="alert alert-primary" style="font-weight: bold;" role="alert">';
                            echo 'Aparati na prikolici ' . htmlspecialchars($row["registracijska_oznaka_prik"]) . ' ističu za ' . abs($days_until_aparati) . ' dana (' . $expiration_date . ').';
                            echo '</div>';
                        }
                        $alert_counter++;
                    }
                }
            }

            // Check if both result sets are empty
            if (!$resultKam && !$resultPrik) {
                echo '<div class="alert alert-info" role="alert">';
                echo 'Sve je u redu!';
                echo '</div>';
            }
            ?>

        </div>
    </div>




    <?php
    $_SESSION['alert_counter'] = $alert_counter;
    ?>
    <div class="footer_boja">
        <p>Karlo Žerjav</p>
        <p>Organizacija i informatizacija ureda, završni rad</p>
    </div>
</body>

</html>