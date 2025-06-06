<?php
$host = 'mysql';
$user = 'root';
$password = 'admin';
$database = 'proiectdb';

// Conectare la baza de date
$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Conexiune eșuată: " . $conn->connect_error);
}

if (isset($_POST['register'])) {
    // Preluare date din formular Register
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $passwordPlain = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'user';

    if (!$name || !$email || !$passwordPlain || !$role) {
        die("Toate câmpurile trebuie completate!");
    }

    $passwordHash = password_hash($passwordPlain, PASSWORD_DEFAULT);

    // Inserare utilizator nou
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $passwordHash, $role);

    if ($stmt->execute()) {
        echo "Utilizatorul a fost adăugat cu succes!";
    } else {
        echo "Eroare la adăugare: " . $stmt->error;
    }

    $stmt->close();
}

if (isset($_POST['login'])) {
    // Preluare date din formular Login
    $email = $_POST['email'] ?? '';
    $passwordPlain = $_POST['password'] ?? '';

    if (!$email || !$passwordPlain) {
        die("Trebuie să completați email și parola!");
    }

    // Căutare utilizator după email
    $stmt = $conn->prepare("SELECT password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($passwordHash, $role);
    if ($stmt->fetch()) {
        // Verificare parolă
        if (password_verify($passwordPlain, $passwordHash)) {
            echo "Login reușit! Rol: $role";
            // Aici poți începe sesiunea, etc.
        } else {
            echo "Parolă incorectă!";
        }
    } else {
        echo "Utilizator inexistent!";
    }
    $stmt->close();
}

$conn->close();
?>
