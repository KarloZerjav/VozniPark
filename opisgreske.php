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

//prikazi pomocu id-a sve podatke
if (isset($_GET['id_greske'])) {
    $Id = $_GET['id_greske'];
    $query = "SELECT id_greske, kod_greske, opis_greske FROM greske WHERE id_greske = ?";
    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, 'i', $registrationId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $row['id_greske'], $row['kod_greske'], $row['opis_greske']);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

// Fetch data from the `greske` table based on the provided ID
$greskeQuery = "SELECT id_greske, kod_greske, opis_greske FROM greske WHERE id_greske = ?";
$stmt = mysqli_prepare($dbc, $greskeQuery);
mysqli_stmt_bind_param($stmt, 'i', $Id); // Assuming $registrationId is the ID you want to fetch
mysqli_stmt_execute($stmt);
$greskeResult = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);

$greskeRow = array('id_greske' => '', 'kod_greske' => '', 'opis_greske' => ''); // Initialize an array to hold the fetched row data

if ($greskeResult && mysqli_num_rows($greskeResult) > 0) {
    $greskeRow = mysqli_fetch_assoc($greskeResult); // Fetch the row as an associative array
}


if (isset($_POST['update'])) {
    $kod = $_POST['kod_greske'];
    $opis = $_POST['opis_greske'];
    $greskeQuery = "UPDATE greske SET kod_greske=?, opis_greske=? WHERE id_greske=?";
    $stmt = mysqli_prepare($dbc, $greskeQuery);
    mysqli_stmt_bind_param($stmt, 'ssi', $kod, $opis, $Id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Redirect after update
    header("Location: greske.php");
    exit();
}

if (isset($_POST['delete'])) {
    $Id = $_GET['id_greske'];
    $greskeQuery = "DELETE FROM greske WHERE id_greske=?";

    // Execute the queries
    $stmt = mysqli_prepare($dbc, $greskeQuery);
    mysqli_stmt_bind_param($stmt, 'i', $Id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Check for errors
    if (mysqli_errno($dbc)) {
        die('Error: ' . mysqli_error($dbc));
    }

    // Redirect after successful deletion
    header("Location: greske.php");
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
                            <a href="greske.php"><button type="button" class="btn btn-outline-dark gumb"><img style="width: 30px;" src="img/natrag.png" alt=""></button></a>
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
                <h2 style="margin-bottom: 30px;">OPIS GREŠKE</h2>
            </div>
            <form enctype="multipart/form-data" action="" method="POST">
                <div class="form-group forma_servis row">
                    <label for="kod_greske" class="col-sm-3">Kod:</label>
                    <div class="col-sm-9">
                        <input type="text" name="kod_greske" id="kod_greske" class="form-control forma_input" value="<?php echo $greskeRow["kod_greske"]; ?>" required>
                    </div>
                </div>
                <div class="form-group forma_servis row">
                    <label for="opis_greske" class="col-sm-3">Opis:</label>
                    <div class="col-sm-9">
                        <textarea name="opis_greske" id="opis_greske" class="form-control forma_input" rows="4" cols="25" required><?php echo $greskeRow["opis_greske"]; ?></textarea>
                    </div>
                </div>
                <div class="sredina_povijest">
                    <button type="submit" name="update" value="Prihvati" class="btn btn-outline-dark gumb"><img style="width: 30px;" src="img/save-file.png" alt=""></button>
                    <button type="delete" name="delete" value="Izbriši" class="btn btn-outline-dark gumb" onclick="return confirm('Jeste li sigurni da želite obrisati podatke?')"><img style="width: 30px;" src="img/trash.svg" alt=""></button>
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

<script>
    $(document).ready(function() {
        $('#greska').DataTable({
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Croatian.json"
            },
            "paging": false, // Disable pagination for better responsiveness
            "ordering": false, // Enable sorting
            "info": false, // Disable table information
            "searching": false, // Disable search box
        });
    });
</script>