<?php
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "vjezba17";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
    die("Konekcija neuspješna: " . $conn->connect_error);
}

$sql = "SELECT users.ime, users.prezime, countries.name AS drzava
        FROM users
        JOIN countries ON users.country_id = countries.id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Prikaz korisnika</title>
</head>
<body>

<h1>Prikaz korisnika</h1>
<ul>
<?php while ($row = $result->fetch_assoc()): ?>
    <li><?php echo htmlspecialchars($row['ime']) . ' ' . htmlspecialchars($row['prezime']) . ' (' . htmlspecialchars($row['drzava']) . ')'; ?></li>
<?php endwhile; ?>
</ul>

</body>
</html>

<?php $conn->close(); ?>