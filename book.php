<?php
// book.php - Booking system
include 'db.php';

$id = $_GET['id'] ?? 0;
if (!$id) die('Invalid ID');

// Fetch property for display
$stmt = $conn->prepare("SELECT title, price FROM properties WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$property = $stmt->get_result()->fetch_assoc();

// Handle POST for booking
$confirmation = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    
    // Simple user check/insert (for demo, no password)
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    if (!$user) {
        $stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $email);
        $stmt->execute();
        $user_id = $stmt->insert_id;
    } else {
        $user_id = $user['id'];
    }
    
    // Calculate total price (simple: nights * price)
    $nights = (strtotime($check_out) - strtotime($check_in)) / (60*60*24);
    $total_price = $nights * $property['price'];
    
    // Insert booking
    $stmt = $conn->prepare("INSERT INTO bookings (user_id, property_id, check_in, check_out, total_price, status) VALUES (?, ?, ?, ?, ?, 'Confirmed')");
    $stmt->bind_param("iissd", $user_id, $id, $check_in, $check_out, $total_price);
    if ($stmt->execute()) {
        $confirmation = "Booking confirmed! Total: $$total_price. Thank you!";
    } else {
        $confirmation = "Error: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book <?php echo $property['title']; ?></title>
    <style>
        /* Internal CSS - Form style, amazing */
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f4f4f4; color: #333; }
        header { background: #ff385c; color: white; padding: 20px; text-align: center; }
        .booking-form { max-width: 600px; margin: 20px auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .booking-form input { display: block; width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; }
        .booking-form button { background: #ff385c; color: white; border: none; padding: 10px; border-radius: 5px; cursor: pointer; width: 100%; }
        .booking-form button:hover { background: #e31c3d; }
        .confirmation { color: green; text-align: center; }
        footer { background: #222; color: white; text-align: center; padding: 10px; position: fixed; bottom: 0; width: 100%; }
        @media (max-width: 768px) { .booking-form { padding: 10px; } }
    </style>
</head>
<body>
    <header>
        <h1>Book Your Stay</h1>
    </header>
    <div class="booking-form">
        <form method="POST">
            <input type="text" name="name" placeholder="Your Name" required>
            <input type="email" name="email" placeholder="Your Email" required>
            <input type="date" name="check_in" required>
            <input type="date" name="check_out" required>
            <button type="submit">Confirm Booking</button>
        </form>
        <?php if ($confirmation): ?>
            <p class="confirmation"><?php echo $confirmation; ?></p>
        <?php endif; ?>
    </div>
    <footer>&copy; 2025 Airbnb Clone</footer>
    <script>
        // No JS needed for this page, but can add validation if wanted
    </script>
</body>
</html>
