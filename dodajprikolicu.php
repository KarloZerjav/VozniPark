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
$row = array('id' => '', 'slika' => '', 'reg_kamion' => '', 'reg_prikolica' => '', 'registracija_kamion' => '', 'registracija_prikolica' => '');

function zadnjaPrik()
{
    global $dbc; // Make sure $dbc is available in this function

    $query = "SELECT MAX(id_prikolice) AS maxID FROM prikolice";
    $result = mysqli_query($dbc, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['maxID'];
    } else {
        return 0; // If there are no records, return 0
    }
}

// Function to get the last ID from the reg_kam table
function zadnjaReg()
{
    global $dbc; // Make sure $dbc is available in this function

    $query = "SELECT MAX(id_prikolice) AS maxID FROM prikolice";
    $result = mysqli_query($dbc, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['maxID'];
    } else {
        return 0; // If there are no records, return 0
    }
}

// Use getLastKamionID() and getLastRegKamID() to set the default value for 'id_kamion' and 'id_rk'
$idPrik = zadnjaPrik() + 1;
$idReg_prik = zadnjaReg() + 1;

if (isset($_POST['insert'])) {
    $registracijska_oznaka_prik = $_POST['registracijska_oznaka_prik'];
    $prik_vrijedi_do = $_POST['prik_vrijedi_do'];
    $sasija_prikolica = $_POST['sasija_prikolica'];
    $proizvodac_prikolica = $_POST['proizvodac_prikolica'];
    $model_prikolica = $_POST['model_prikolica'];
    $godina_prikolica = $_POST['godina_prikolica'];
    $visina_prikolica = $_POST['visina_prikolica'];
    $nosivost = $_POST['nosivost'];
    $prik_vrijedi_do = !empty($_POST['prik_vrijedi_do']) ? $_POST['prik_vrijedi_do'] : null;

    $prikolicaQuery = "INSERT INTO prikolice (id_prikolice, registracijska_oznaka_prik, sasija_prikolica, proizvodac_prikolica, model_prikolica, godina_prikolica, visina_prikolica, nosivost)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmtPrikolica = mysqli_prepare($dbc, $prikolicaQuery);
    mysqli_stmt_bind_param($stmtPrikolica, "isssssss", $idPrik, $registracijska_oznaka_prik, $sasija_prikolica, $proizvodac_prikolica, $model_prikolica, $godina_prikolica, $visina_prikolica, $nosivost);
    mysqli_stmt_execute($stmtPrikolica);

    $insertQueryReg_prik = "INSERT INTO reg_prik(id_rp, id_prikolice, prik_vrijedi_do) 
    VALUES (?, ?, ?)";
    $stmtReg_prik = mysqli_prepare($dbc, $insertQueryReg_prik);
    mysqli_stmt_bind_param($stmtReg_prik, "iis", $idReg_prik, $idPrik, $prik_vrijedi_do);
    mysqli_stmt_execute($stmtReg_prik);

    $idPrik++;
    $idReg_prik++;

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
                            <a href="dodajvozilo.php"><button type="button" class="btn btn-outline-dark gumb"><img style="width: 30px;" src="img/natrag.png" alt=""></button></a>
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
            <div class="row">
                <div class="col">
                    <div class="naslovi">
                        <h2 style="margin-bottom: 30px;">DODAJ PRIKOLICU</h2>
                    </div>
                    <form enctype="multipart/form-data" action="" method="POST">
                        <div class="form-group forma_servis row">
                            <label for="registracijska_oznaka_prik" class="col-sm-3">Reg. oznaka prikolice:</label>
                            <div class="col-sm-9">
                                <input type="text" name="registracijska_oznaka_prik" id="rregistracijska_oznaka_prik" class="form-control forma_input" required>
                            </div>
                        </div>
                        <div class="form-group forma_servis row">
                            <label for="prik_vrijedi_do" class="col-sm-3">Reg. oznaka vrijedi do:</label>
                            <div class="col-sm-9">
                                <input type="date" name="prik_vrijedi_do" id="prik_vrijedi_do" class="form-control forma_input" required>
                            </div>
                        </div>
                        <div class="form-group forma_servis row">
                            <label for="sasija_prikolica" class="col-sm-3">Broj šasije:</label>
                            <div class="col-sm-9">
                                <input type="text" name="sasija_prikolica" id="sasija_prikolica" class="form-control forma_input" required>
                            </div>
                        </div>
                        <div class="form-group forma_servis row">
                            <label for="proizvodac_prikolica" class="col-sm-3">Proizvođač:</label>
                            <div class="col-sm-9">
                                <input type="text" name="proizvodac_prikolica" id="proizvodac_prikolica" class="form-control forma_input" required>
                            </div>
                        </div>
                        <div class="form-group forma_servis row">
                            <label for="model_prikolica" class="col-sm-3">Model:</label>
                            <div class="col-sm-9">
                                <input type="text" name="model_prikolica" id="model_prikolica" class="form-control forma_input" required>
                            </div>
                        </div>
                        <div class="form-group forma_servis row">
                            <label for="godina_prikolica" class="col-sm-3">Godina:</label>
                            <div class="col-sm-9">
                                <input type="text" name="godina_prikolica" id="godina_prikolica" class="form-control forma_input" required>
                            </div>
                        </div>
                        <div class="form-group forma_servis row">
                            <label for="visina_prikolica" class="col-sm-3">Visina:</label>
                            <div class="col-sm-9">
                                <select name="visina_prikolica" class="form-control forma_input" onchange="dropdownVisinaPrikolica()" required>
                                    <option value="" selected></option>
                                    <option value="2.90m">2.90m</option>
                                    <option value="2.85m">2.85m</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group forma_servis row">
                            <label for="nosivost" class="col-sm-3">Nosivost:</label>
                            <div class="col-sm-9">
                                <select name="nosivost" class="form-control forma_input" onchange="dropdownNosivost()" required>
                                    <option value="" selected></option>
                                    <option value="24000kg">24000kg</option>
                                    <option value="23000kg">23000kg</option>
                                </select>
                            </div>
                        </div>
                        <div class="sredina_povijest">
                            <button type="submit" name="insert" value="Unesi" class="btn btn-outline-dark gumb"><img style="width: 30px;" src="img/add-file.png" alt=""></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class=" footer_boja">
        <p>Karlo Žerjav</p>
        <p>Organizacija i informatizacija ureda, završni rad</p>
    </div>
</body>

<script>
    function dropdownNosivost() {
        var dropdown = document.querySelector('select[name="nosivost"]');
        var selectedOption = dropdown.options[dropdown.selectedIndex].value;
        var inputField = document.querySelector('input[name="nosivost"]');
        inputField.value = selectedOption;
    }

    function dropdownVisinaPrikolica() {
        var dropdown = document.querySelector('select[name="visina_prikolica"]');
        var selectedOption = dropdown.options[dropdown.selectedIndex].value;
        var inputField = document.querySelector('input[name="visina_prikolica"]');
        inputField.value = selectedOption;
    }
</script>




</html>