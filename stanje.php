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

// Modified SQL query to fetch truck data
$sqlKam = "SELECT 
            k.id_kamion,
            k.registracijska_oznaka_kam
        FROM kamioni AS k";
$resultKam = mysqli_query($dbc, $sqlKam);

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.cvs-mobile.com/rest/v1/DataAPI/VehicleDataList/xml?AuthToken=',
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

// Extract vehicle data
$vehicles = $xml->xpath('//Vehicle');
$vehicle_data = [];
foreach ($vehicles as $vehicle) {
    $plateNumber = preg_replace('/[\s-]+/', '', (string)$vehicle['VehiclePlateNumber']);
    $odometer = (string)$vehicle['Odometer'];
    $vehicle_data[$plateNumber] = $odometer;
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
                            <a href="popis.php"><button type="button" class="btn btn-outline-dark gumb"><img style="width: 30px;" src="img/natrag.png" alt=""></button></a>
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
            <div class="naslovi">
                <h2>Podaci o vozilima</h2>
            </div>
            <table class="table table-hover" id="popis">
                <thead class="thead-dark">
                    <tr class="table-dark">
                        <th scope="col">Br.</th>
                        <th scope="col">Registracijska oznaka</th>
                        <th scope="col">Trenutni kilometri</th>
                        <th scope="col">Servis</th>
                    </tr>
                </thead>
                <tbody class="font_mob" style="cursor: pointer;">
                    <?php
                    $brojac = 1;
                    if ($resultKam) {
                        while ($row = mysqli_fetch_assoc($resultKam)) {
                            $registracijska_oznaka_kam = preg_replace('/[\s-]+/', '', $row["registracijska_oznaka_kam"]); // Normalize plate number format
                            $odometer = isset($vehicle_data[$registracijska_oznaka_kam]) ? $vehicle_data[$registracijska_oznaka_kam] : '';

                            // Check if odometer data is available and numeric
                            if (isset($odometer) && $odometer !== "" && is_numeric($odometer)) {
                                // Fetch last service data for the truck
                                $id_kamion = $row['id_kamion'];
                                $queryServisKamion = "SELECT * FROM servisi_kam WHERE id_kamion = $id_kamion ORDER BY id_servis_kam DESC LIMIT 1";
                                $resultServisKamion = mysqli_query($dbc, $queryServisKamion);
                                $lastServis = mysqli_fetch_assoc($resultServisKamion);

                                // Calculate kilometers remaining for next service
                                $service_info = 'N/A';
                                if ($lastServis) {
                                    $kilometri_servis = $lastServis['kilometri_servis'];
                                    $odometerNumeric = floatval(preg_replace('/[^0-9.]/', '', $odometer));
                                    $difference = $odometerNumeric - (float)$kilometri_servis;
                                    $potrebno_km = 60000 - $difference;
                                    if ($potrebno_km < 0) {
                                        $service_info = 'prekoračio je za ' . number_format(abs($potrebno_km), 2) . 'km';
                                    } else {
                                        $service_info = 'potreban za ' . number_format($potrebno_km, 2) . 'km';
                                    }
                                }

                                echo '<tr class="' . ($potrebno_km < -10000 || strpos($service_info, 'prekoračio je za') !== false ? 'table-danger' : ($potrebno_km < 10000 ? 'table-warning' : '')) . '">';
                                echo '<td>' . $brojac++ . '</td>';
                                echo '<td>' . $row["registracijska_oznaka_kam"] . '</td>';
                                echo '<td>' . $odometer . 'km</td>';
                                echo '<td>' . $service_info . '</td>';
                                echo '</tr>';
                            } else {
                                // Odometer data is not available or not numeric
                                echo '<tr>';
                                echo '<td>' . $brojac++ . '</td>';
                                echo '<td>' . $row["registracijska_oznaka_kam"] . '</td>';
                                echo '<td>Nije dostupno</td>';
                                echo '<td>Nije dostupno</td>';
                                echo '</tr>';
                            }
                        }
                    } else {
                        echo '<tr><td colspan="4">Nema dostupnih podataka</td></tr>';
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