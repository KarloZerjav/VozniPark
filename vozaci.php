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

// Set the username and permission level from the session, if they exist
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

$sql = "SELECT 
            k.registracijska_oznaka_kam AS registracijska_oznaka_kam, 
            p.registracijska_oznaka_prik AS registracijska_oznaka_prik
            FROM kamioni k
            JOIN kamion_prikolica kp ON k.id_kamion = kp.id_kamion
            JOIN prikolice p ON kp.id_prikolice = p.id_prikolice
            JOIN vozac v ON kp.id_vozac = v.id_vozac
            WHERE v.vozac_korisnicko_ime = '$user'";


$result = mysqli_query($dbc, $sql);



if (isset($_POST['insert'])) {
    // Retrieve form data
    $opis = $_POST['opis'];

    // Construct the SQL query to insert data into the obavijest table
    $sql_insert = "INSERT INTO obavijest (id_vozac, opis, kreirano) 
                   VALUES ((SELECT id_vozac FROM vozac WHERE vozac_korisnicko_ime = '$user'), '$opis', NOW())";

    // Execute the SQL query
    if (mysqli_query($dbc, $sql_insert)) {
        // Insertion successful
        $msg = "Obavijest uspješno poslana.";
    } else {
        // Insertion failed
        $msg = "Error: " . mysqli_error($dbc);
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
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Kompanija Žerjav transporti d.o.o.</label>
                    <select name="registracija" class="form-control forma_input" required>
                        <option value="" selected disabled>Odaberite registracijske oznake:</option>
                        <?php
                        // Display options based on user permission level
                        if ($permission_level === 1) {
                            // Display all kamion and prikolica for administrators
                            while ($row = mysqli_fetch_assoc($result)) {
                                $spojeno = $row['registracijska_oznaka_kam'] . ' - ' . $row['registracijska_oznaka_prik'];
                        ?>
                                <option value="<?php echo $spojeno; ?>"><?php echo $spojeno; ?></option>
                                <?php
                            }
                        } else {
                            // Display kamion and prikolica for normal users
                            while ($row = mysqli_fetch_assoc($result)) {
                                $spojeno = $row['registracijska_oznaka_kam'] . ' - ' . $row['registracijska_oznaka_prik'];
                                if ($row['registracijska_oznaka_kam']) {
                                ?>
                                    <option value="<?php echo $spojeno; ?>"><?php echo $spojeno; ?></option>
                        <?php
                                }
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlTextarea1" class="form-label">Potrebno popraviti:</label>
                    <textarea class="form-control forma_input" id="exampleFormControlTextarea1" name="opis" rows="5" required></textarea>
                </div>
                <div class="sredina_povijest">
                    <button type="submit" name="insert" value="Unesi" class="btn btn-outline-dark gumb">POŠALJI</button>
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