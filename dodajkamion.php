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
$row = array('id_kamion' => '', 'registracijska_oznaka_kam' => '', 'kam_vrijedi_do' => '');

// Function to get the last ID from the kamioni table
function zadnjiKamion()
{
    global $dbc; // Make sure $dbc is available in this function

    $query = "SELECT MAX(id_kamion) AS maxID FROM kamioni";
    $result = mysqli_query($dbc, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['maxID'];
    } else {
        return 0; // If there are no records, return 0
    }
}

// Function to get the last ID from the reg_kam table
function zadnjiReg()
{
    global $dbc; // Make sure $dbc is available in this function

    $query = "SELECT MAX(id_kamion) AS maxID FROM kamioni";
    $result = mysqli_query($dbc, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['maxID'];
    } else {
        return 0; // If there are no records, return 0
    }
}

// Use getLastKamionID() and getLastRegKamID() to set the default value for 'id_kamion' and 'id_rk'
$idKamioni = zadnjiKamion() + 1;
$idReg_kam = zadnjiReg() + 1;

if (isset($_POST['insert'])) {
    $registracijska_oznaka_kam = $_POST['registracijska_oznaka_kam'];
    $kam_vrijedi_do = $_POST['kam_vrijedi_do'];
    $sasija_kamion = $_POST['sasija_kamion'];
    $proizvodac_kamion = $_POST['proizvodac_kamion'];
    $model_kamion = $_POST['model_kamion'];
    $godina_kamion = $_POST['godina_kamion'];
    $visina_kamion = $_POST['visina_kamion'];
    $rezervar = $_POST['rezervar'];

    $kamionQuery = "INSERT INTO kamioni (id_kamion, registracijska_oznaka_kam, sasija_kamion, proizvodac_kamion, model_kamion, godina_kamion, visina_kamion, rezervar)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmtKamion = mysqli_prepare($dbc, $kamionQuery);
    mysqli_stmt_bind_param($stmtKamion, "isssssss", $idKamioni, $registracijska_oznaka_kam, $sasija_kamion, $proizvodac_kamion, $model_kamion, $godina_kamion, $visina_kamion, $rezervar);
    mysqli_stmt_execute($stmtKamion);

    // Insert data into the reg_kam table with the incremented ID
    $insertQueryReg_kam = "INSERT INTO reg_kam (id_rk, id_kamion, kam_vrijedi_do) 
    VALUES (?, ?, ?)";
    $stmtReg_kam = mysqli_prepare($dbc, $insertQueryReg_kam);
    mysqli_stmt_bind_param($stmtReg_kam, "iis", $idReg_kam, $idKamioni, $kam_vrijedi_do);
    mysqli_stmt_execute($stmtReg_kam);

    // Increment the default ID values for the next insertion
    $idKamioni++;
    $idReg_kam++;

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
                        <h2 style="margin-bottom: 30px;">DODAJ KAMION</h2>
                    </div>
                    <form enctype="multipart/form-data" action="" method="POST">
                        <div class="form-group forma_servis row" hidden>
                            <label for="id_kamion" class="col-sm-3" hidden>Br:</label>
                            <div class="col-sm-9" hidden>
                                <input type="number" name="id_kamion" id="id_kamion" class="form-control forma_input" value="<?php echo $defaultBr; ?>" hidden>
                            </div>
                        </div>
                        <div class="form-group forma_servis row">
                            <label for="registracijska_oznaka_kam" class="col-sm-3">Reg. oznaka kamiona:</label>
                            <div class="col-sm-9">
                                <input type="text" name="registracijska_oznaka_kam" id="registracijska_oznaka_kam" class="form-control forma_input" required>
                            </div>
                        </div>
                        <div class="form-group forma_servis row">
                            <label for="kam_vrijedi_do" class="col-sm-3">Reg. oznaka vrijedi do:</label>
                            <div class="col-sm-9">
                                <input type="date" name="kam_vrijedi_do" id="kam_vrijedi_do" class="form-control forma_input" required>
                            </div>
                        </div>
                        <div class="form-group forma_servis row">
                            <label for="sasija_kamion" class="col-sm-3">Broj šasije:</label>
                            <div class="col-sm-9">
                                <input type="text" name="sasija_kamion" id="sasija_kamion" class="form-control forma_input" required>
                            </div>
                        </div>
                        <div class="form-group forma_servis row">
                            <label for="proizvodac_kamion" class="col-sm-3">Proizvođač:</label>
                            <div class="col-sm-9">
                                <input type="text" name="proizvodac_kamion" id="proizvodac_kamion" class="form-control forma_input" required>
                            </div>
                        </div>
                        <div class="form-group forma_servis row">
                            <label for="model_kamion" class="col-sm-3">Model:</label>
                            <div class="col-sm-9">
                                <input type="text" name="model_kamion" id="model_kamion" class="form-control forma_input" required>
                            </div>
                        </div>
                        <div class="form-group forma_servis row">
                            <label for="godina_kamion" class="col-sm-3">Godina:</label>
                            <div class="col-sm-9">
                                <input type="text" name="godina_kamion" id="godina_kamion" class="form-control forma_input" required>
                            </div>
                        </div>
                        <div class="form-group forma_servis row">
                            <label for="visina_kamion" class="col-sm-3">Visina:</label>
                            <div class="col-sm-9">
                                <select name="visina_kamion" class="form-control forma_input" onchange="dropdownVisinaKamion()" required>
                                    <option value="" selected disabled></option>
                                    <option value="Standard">Standard</option>
                                    <option value="Mega">Mega</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group forma_servis row">
                            <label for="rezervar" class="col-sm-3">Rezervar:</label>
                            <div class="col-sm-9">
                                <select name="rezervar" class="form-control forma_input" required>
                                    <option value="" selected disabled></option>
                                    <option value="960L">960L</option>
                                    <option value="870L">870L</option>
                                    <option value="1160L">1160L</option>
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
    function dropdownVisinaKamion() {
        var dropdownKamion = document.querySelector('select[name="visina_kamion"]');
        var selectedOptionKamion = dropdownKamion.options[dropdownKamion.selectedIndex].value;

        var dropdownRezervar = document.querySelector('select[name="rezervar"]');
        // Set default selected value based on the selected option in visina_kamion dropdown
        if (selectedOptionKamion === "Mega") {
            dropdownRezervar.value = "960L";
        } else {
            dropdownRezervar.value = ""; // Set to default value or leave it empty if there is no default.
        }
    }
</script>


</html>