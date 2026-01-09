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
$row = array('id_prikolice' => '', 'registracijska_oznaka_prik' => '');


$povijestRowsPrikolica = array();
$servisRowsServis = array();

if (isset($_GET['id_prikolice'])) {
    $registrationId = $_GET['id_prikolice'];

    // Fetch registration details from the database using the $registrationId
    $query = "SELECT id_prikolice, registracijska_oznaka_prik FROM prikolice WHERE id_prikolice = $registrationId";
    $result = mysqli_query($dbc, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $id_prikolice = $row['id_prikolice'];

        $queryPovijestPrikolica = "SELECT pp.id_vrste, pp.datum, vp.naziv_popravka, vp.opis 
                                        FROM popravak_prikolica pp
                                        INNER JOIN vrste_popravka vp ON pp.id_vrste = vp.id_vrste 
                                        WHERE pp.id_prikolice = $id_prikolice";
        $resultPovijestPrikolica = mysqli_query($dbc, $queryPovijestPrikolica);

        if ($resultPovijestPrikolica && mysqli_num_rows($resultPovijestPrikolica) > 0) {
            while ($row = mysqli_fetch_assoc($resultPovijestPrikolica)) {
                $povijestRowsPrikolica[] = $row; // Add each servis row to the array
            }
        }
    }
}

$query = "SELECT id_prikolice, registracijska_oznaka_prik FROM prikolice WHERE id_prikolice = $registrationId";
$result = mysqli_query($dbc, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    $id_prikolice = $row['id_prikolice'];
}

if (isset($_POST['insert'])) {
    $naziv_popravka = $_POST['naziv_popravka'];
    $datum = $_POST['datum'];
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
        $insertQueryPopravakPrikolica = "INSERT INTO popravak_prikolica (id_vrste, id_prikolice, datum) VALUES (?, ?, ?)";
        $stmtInsertPopravakPrikolica = mysqli_prepare($dbc, $insertQueryPopravakPrikolica);
        mysqli_stmt_bind_param($stmtInsertPopravakPrikolica, "iis", $id_vrste, $id_prikolice, $datum);

        $insertResultPopravakPrikolica = mysqli_stmt_execute($stmtInsertPopravakPrikolica);

        if ($insertResultPopravakPrikolica) {
            header("Refresh:0");
            exit();
        }
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
                            <a href="info_prikolice.php?id_prikolice=<?php echo isset($_GET['id_prikolice']) ? $_GET['id_prikolice'] : ''; ?>"><button type="button" class="btn btn-outline-dark gumb"><img style="width: 30px;" src="img/natrag.png" alt=""></button></a>
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
                <h2>POVIJEST POPRAVKA</h2>
            </div>
            <div class="row">
                <div class="col">
                    <form enctype="multipart/form-data" action="" method="POST">
                        <div class="list-group">
                            <?php
                            foreach ($povijestRowsPrikolica as $rowServis) {
                                $currentId = $row['id_prikolice'];
                                $currentStavka = $rowServis['id_vrste'];

                                echo '<a href="prikolica_popravak.php?id_prikolice=' . $currentId . '&id_vrste=' . $currentStavka . '" style="text-decoration: none;">';
                                echo '<button type="button" class="list-group-item list-group-item-action">';
                                echo '<div class="d-flex justify-content-between align-items-center">'; // Use flexbox for layout
                                echo '<span>Naziv popravka:<br>' . $rowServis['naziv_popravka'] . '</span>';
                                echo '<span>Datuma:<br>' . date('d.m.Y', strtotime($rowServis['datum'])) . '</span>';
                                echo '</div>';
                                echo '</button>';
                                echo '</a>';
                            }
                            ?>
                        </div>
                        <div class="accordion" id="accordionPanelsStayOpenExample">
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
            document.getElementById('slj_servis').value = sljedeciServis + "km";
        }
    });
</script>


</html>