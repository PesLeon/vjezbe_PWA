<?php
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "vjezba17";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
    die("Konekcija neuspješna: " . $conn->connect_error);
}

$poruka = "";

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = (int)$_POST['id'];
    $ime = $_POST['ime'];
    $prezime = $_POST['prezime'];
    $country_id = $_POST['country_id'];

    $stmt = $conn->prepare("UPDATE users SET ime = ?, prezime = ?, country_id = ? WHERE id = ?");
    $stmt->bind_param("ssii", $ime, $prezime, $country_id, $id);

    if ($stmt->execute()) {
        $poruka = "Korisnik uspješno ažuriran!";
    } else {
        $poruka = "Greška: " . $stmt->error;
    }

    $stmt->close();
}

$stmt = $conn->prepare("SELECT ime, prezime, country_id FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$korisnik = $result->fetch_assoc();
$stmt->close();

$countries = $conn->query("SELECT id, name FROM countries");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit korisnika</title>
</head>
<body>

<h1>Edit korisnika</h1>

<?php if ($poruka): ?>
    <p><?php echo htmlspecialchars($poruka); ?></p>
<?php endif; ?>

<form method="post" action="">
    <input type="hidden" name="id" value="<?php echo $id; ?>">

    <label>Ime:</label><br>
    <input type="text" name="ime" value="<?php echo htmlspecialchars($korisnik['ime']); ?>" required><br><br>

    <label>Prezime:</label><br>
    <input type="text" name="prezime" value="<?php echo htmlspecialchars($korisnik['prezime']); ?>" required><br><br>

    <label>Država:</label><br>
    <select name="country_id" required>
        <?php while ($row = $countries->fetch_assoc()): ?>
            <option value="<?php echo $row['id']; ?>" <?php echo ($row['id'] == $korisnik['country_id']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($row['name']); ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>

    <button type="submit">Spremi promjene</button>
</form>

<p><a href="vj18_prikaz.php">Povratak na prikaz korisnika</a></p>

</body>
</html>

<?php $conn->close(); ?>