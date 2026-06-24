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
    $naziv = $_POST['naziv'];

    $stmt = $conn->prepare("INSERT INTO countries (name) VALUES (?)");
    $stmt->bind_param("s", $naziv);

    if ($stmt->execute()) {
        $poruka = "Država uspješno dodana!";
    } else {
        $poruka = "Greška: " . $stmt->error;
    }

    $stmt->close();
}

$countries = $conn->query("SELECT name FROM countries");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dodaj državu</title>
</head>
<body>

<h1>Dodaj novu državu</h1>

<?php if ($poruka): ?>
    <p><?php echo htmlspecialchars($poruka); ?></p>
<?php endif; ?>

<form method="post" action="">
    <label>Naziv države:</label><br>
    <input type="text" name="naziv" required><br><br>

    <button type="submit">Dodaj državu</button>
</form>

<h2>Postojeće države</h2>
<ul>
<?php while ($row = $countries->fetch_assoc()): ?>
    <li><?php echo htmlspecialchars($row['name']); ?></li>
<?php endwhile; ?>
</ul>

<p><a href="vj18_prikaz.php">Povratak na prikaz korisnika</a></p>

</body>
</html>

<?php $conn->close(); ?>