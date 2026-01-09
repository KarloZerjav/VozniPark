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

$sql = "SELECT 
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

$result = mysqli_query($dbc, $sql);

$brojac = 1;
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
                            <a href="stanje.php"><button type="button" class="btn btn-outline-dark gumb"><img style="width: 30px;" src="img/speedometer.png" alt=""></button></a>
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
                <h2>POPIS VOZILA</h2>
            </div>
            <table class="table table-hover" id="popis">
                <thead class="thead-dark">
                    <tr class="table-dark">
                        <th scope="col">Br.</th>
                        <th scope="col">Reg. oznaka kamiona</th>
                        <th scope="col">Vrijedi do</th>
                        <th scope="col">Reg. oznaka prikolice</th>
                        <th scope="col">Vrijedi do</th>
                    </tr>
                </thead>
                <tbody class="font_mob" style="cursor: pointer;">
                    <?php
                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $registracija_kamion_datum = $row["kamion_vrijedi_do"] ? strtotime($row["kamion_vrijedi_do"]) : null;
                            $registracija_prikolica_datum = $row["prikolica_vrijedi_do"] ? strtotime($row["prikolica_vrijedi_do"]) : null;

                            // Check if the date is within 10 days before today
                            $deset_dana_prije = strtotime('+14 days', strtotime('today'));

                            // Check if the date has passed
                            $danas = strtotime('today');

                            // Initialize classes and style for bold text
                            $registracija_kamion_class = '';
                            $registracija_prikolica_class = '';
                            $reg_kamion_class = '';
                            $reg_prikolica_class = '';
                            $tekst_bold = 'font-weight: normal;'; // Default style

                            // Set class for registracija_kamion
                            if ($registracija_kamion_datum && $registracija_kamion_datum <= $danas) {
                                $registracija_kamion_class = 'table-danger';
                                $reg_kamion_class = 'table-danger';
                            } elseif ($registracija_kamion_datum && $registracija_kamion_datum <= $deset_dana_prije) {
                                $registracija_kamion_class = 'table-warning';
                                $reg_kamion_class = 'table-warning';
                            }

                            // Set class for registracija_prikolica
                            if ($registracija_prikolica_datum && $registracija_prikolica_datum <= $danas) {
                                $registracija_prikolica_class = 'table-danger';
                                $reg_prikolica_class = 'table-danger';
                            } elseif ($registracija_prikolica_datum && $registracija_prikolica_datum <= $deset_dana_prije) {
                                $registracija_prikolica_class = 'table-warning';
                                $reg_prikolica_class = 'table-warning';
                            }

                            // Format dates in "dd/mm/yyyy" format for Kamion registriran do and Prikolica registrirana do
                            $formatted_reg_kamion_date = $registracija_kamion_datum ? date('d.m.Y', $registracija_kamion_datum) : '-';
                            $formatted_reg_prikolica_date = $registracija_prikolica_datum ? date('d.m.Y', $registracija_prikolica_datum) : '-';

                            echo '<tr>';
                            echo '<th scope="row">' . $brojac++ . '</th>';
                            echo '<td class="' . $reg_kamion_class . '" style="' . $tekst_bold . '"';
                            if ($row["kamion_registracija"]) {
                                echo ' onclick="location.href=\'info_kamion.php?id_kamion=' . $row["id_kamion"] . '\';" style="cursor: pointer;"';
                            }
                            echo '>' . htmlspecialchars($row["kamion_registracija"]) . '</td>';
                            echo '<td class="' . $registracija_kamion_class . '" style="' . $tekst_bold . '"';
                            if ($formatted_reg_kamion_date != '-') {
                                echo ' onclick="location.href=\'info_kamion.php?id_kamion=' . $row["id_kamion"] . '\';" style="cursor: pointer;"';
                            }
                            echo '>' . $formatted_reg_kamion_date . '</td>';
                            echo '<td class="' . $reg_prikolica_class . '" style="' . $tekst_bold . '"';
                            if ($row["prikolica_registracija"]) {
                                echo ' onclick="location.href=\'info_prikolice.php?id_prikolice=' . $row["id_prikolice"] . '\';" style="cursor: pointer;"';
                            }
                            echo '>' . htmlspecialchars($row["prikolica_registracija"]) . '</td>';
                            echo '<td class="' . $registracija_prikolica_class . '" style="' . $tekst_bold . '"';
                            if ($formatted_reg_prikolica_date != '-') {
                                echo ' onclick="location.href=\'info_prikolice.php?id_prikolice=' . $row["id_prikolice"] . '\';" style="cursor: pointer;"';
                            }
                            echo '>' . $formatted_reg_prikolica_date . '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="5">Nema dostupnih podataka</td></tr>';
                    }
                    ?>


                </tbody>
            </table>
        </div>
    </div>


    <div class="footer_boja">
        <p>Karlo Žerjav</p>
        <p>Organizacija i informatizacija ureda, završni rad</p>
    </div>
</body>

</html>

<script>
    $(document).ready(function() {
        $('#popis').DataTable({
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Croatian.json"
            },
            "paging": true,
            "ordering": false,
            "info": true,
            "searching": true,
        });
    });
</script>