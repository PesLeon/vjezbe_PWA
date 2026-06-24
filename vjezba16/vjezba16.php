<?php
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "vjezba16";

$poruka = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $country = $_POST['country'];

    if (strlen($username) < 5 || strlen($username) > 10) {
        $poruka = "Username mora imati između 5 i 10 znakova.";
    } elseif (strlen($password) < 4) {
        $poruka = "Password mora imati minimalno 4 znaka.";
    } else {
        $conn = new mysqli($servername, $username_db, $password_db, $dbname);

        if ($conn->connect_error) {
            die("Konekcija neuspješna: " . $conn->connect_error);
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO registration (first_name, last_name, email, username, password, country) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $first_name, $last_name, $email, $username, $hashed_password, $country);

        if ($stmt->execute()) {
            $poruka = "Registracija uspješna!";
        } else {
            $poruka = "Greška: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Form</title>
</head>
<body>

<h2>Registration Form</h2>

<?php if ($poruka): ?>
    <p><?php echo htmlspecialchars($poruka); ?></p>
<?php endif; ?>

<form method="post" action="">
    <label>First Name *</label><br>
    <input type="text" name="first_name" placeholder="Your name.." required><br><br>

    <label>Last Name *</label><br>
    <input type="text" name="last_name" placeholder="Your last name.." required><br><br>

    <label>Your E-mail *</label><br>
    <input type="email" name="email" placeholder="Your e-mail.." required><br><br>

    <label>Username:* (Username must have min 5 and max 10 char)</label><br>
    <input type="text" name="username" placeholder="Username.." minlength="5" maxlength="10" required><br><br>

    <label>Password:* (Password must have min 4 char)</label><br>
    <input type="password" name="password" placeholder="Password.." minlength="4" required><br><br>

    <label>Country:</label><br>
    <select name="country">
        <option value="">molimo odaberite</option>
        <option value="HR">Croatia</option>
        <option value="BE">Belgium</option>
        <option value="LU">Luxembourg</option>
        <option value="HU">Hungary</option>
    </select><br><br>

    <button type="submit">Submit</button>
</form>

</body>
</html>