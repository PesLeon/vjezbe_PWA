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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ime = $_POST['ime'];
    $prezime = $_POST['prezime'];
    $country_id = $_POST['country_id'];

    $stmt = $conn->prepare("INSERT INTO users (ime, prezime, country_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $ime, $prezime, $country_id);

    if ($stmt->execute()) {
        $poruka = "Korisnik uspješno dodan!";
    } else {
        $poruka = "Greška: " . $stmt->error;
    }

    $stmt->close();
}

$countries = $conn->query("SELECT id, name FROM countries");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dodaj korisnika</title>
</head>
<body>

<h1>Dodaj novog korisnika</h1>

<?php if ($poruka): ?>
    <p><?php echo htmlspecialchars($poruka); ?></p>
<?php endif; ?>

<form method="post" action="">
    <label>Ime:</label><br>
    <input type="text" name="ime" required><br><br>

    <label>Prezime:</label><br>
    <input type="text" name="prezime" required><br><br>

    <label>Država:</label><br>
    <select name="country_id" required>
        <option value="">odaberite</option>
        <?php while ($row = $countries->fetch_assoc()): ?>
            <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <button type="submit">Dodaj korisnika</button>
</form>

<p><a href="vjezba17.php">Povratak na prikaz korisnika</a></p>

</body>
</html>

<?php $conn->close(); ?>