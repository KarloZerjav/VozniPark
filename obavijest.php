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

// Delete selected items
if (isset($_POST['delete'])) {
    // Check if any checkboxes are checked
    if (!isset($_POST['oznaceno'])) {
        $msg = "Niste označili nijedno polje";
    } else {
        // Loop through each selected checkbox
        foreach ($_POST['oznaceno'] as $id_obavijest => $value) {
            // Delete the message with the corresponding id_obavijest
            $deleteQuery = "DELETE FROM obavijest WHERE id_obavijest = ?";
            $stmt = mysqli_prepare($dbc, $deleteQuery);
            mysqli_stmt_bind_param($stmt, "i", $id_obavijest);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
        // Redirect to prevent form resubmission
        header("Location: obavijest.php");
        exit();
    }
}

// Modified SQL query to join kamioni, kamion_prikolica, prikolice, vozac, and obavijest tables
$sql = "SELECT 
            k.registracijska_oznaka_kam AS registracijska_oznaka_kam, 
            p.registracijska_oznaka_prik AS registracijska_oznaka_prik,
            o.id_vozac AS id_vozac,
            o.opis AS opis,
            o.kreirano AS kreirano,
            v.vozac_ime as vozac_ime,
            v.vozac_prezime as vozac_prezime,
            o.id_obavijest as id_obavijest
        FROM 
            kamioni k
            JOIN kamion_prikolica kp ON k.id_kamion = kp.id_kamion
            JOIN prikolice p ON kp.id_prikolice = p.id_prikolice
            JOIN vozac v ON kp.id_vozac = v.id_vozac
            JOIN obavijest o ON v.id_vozac = o.id_vozac";


$result = mysqli_query($dbc, $sql);
if (!$result) {
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
                <h2>POTREBNI POPRAVCI</h2>
            </div>
            <?php if (!empty($msg)) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $msg; ?>
                </div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="list-group">
                    <?php
                    // Check if there are any rows returned from the query
                    if (mysqli_num_rows($result) > 0) {
                        // Fetch data from the query result
                        while ($row = mysqli_fetch_assoc($result)) {
                            $vozac_ime = $row['vozac_ime'];
                            $vozac_prezime = $row['vozac_prezime'];
                            $reg_kamion = $row['registracijska_oznaka_kam'];
                            $reg_prikolica = $row['registracijska_oznaka_prik'];
                            $opis = $row['opis'];
                            $id = $row['id_obavijest'];
                            $kreirano_timestamp = strtotime($row['kreirano']); // Convert to timestamp
                            $kreirano = date('d.m.Y H:i', $kreirano_timestamp);

                            // Display reg_kamion, reg_prikolica, and opis
                            echo '<div class="list-group-item">';
                            echo "<p><strong>Ime i prezime vozača:</strong> $vozac_ime $vozac_prezime</p>";
                            echo "<p><strong>Registracijske oznake:</strong> $reg_kamion - $reg_prikolica</p>";
                            echo "<p><strong>Opis:</strong> $opis</p>";
                            echo "<p><strong>Poslano:</strong> $kreirano</p>";
                            echo "<input type='checkbox' name='oznaceno[$id]' class='mx-auto d-block'>";
                            echo '</div>';
                        }
                        echo '<div class="sredina_povijest">';
                        echo '<button type="submit" name="delete" value="Obriši" class="btn btn-outline-dark gumb">IZBRIŠI</button>';
                        echo '</div>';
                    } else {
                        // If there are no rows returned, display a message
                        echo '<a class="list-group-item list-group-item-action flex-column align-items-start">';
                        echo '<div class="d-flex w-100 justify-content-between">';
                        echo "<h5 class='mb-1'>Nema obavijesti!</h5>";
                        echo '</div>';
                        echo '</a>';
                    }
                    ?>
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