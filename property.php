<?php
// property.php - Single property view
include 'db.php';

$id = $_GET['id'] ?? 0;
if (!$id) die('Invalid ID');

// Fetch property
$stmt = $conn->prepare("SELECT * FROM properties WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$property = $stmt->get_result()->fetch_assoc();

if (!$property) die('Property not found');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $property['title']; ?></title>
    <style>
        /* Internal CSS - Detailed, amazing view */
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f4f4f4; color: #333; }
        header { background: #ff385c; color: white; padding: 20px; text-align: center; }
        .property-detail { max-width: 800px; margin: 20px auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .property-detail img { width: 100%; height: auto; border-radius: 10px; }
        .property-detail h1 { margin: 10px 0; }
        .property-detail p { margin: 5px 0; }
        .book-btn { background: #ff385c; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; margin-top: 20px; }
        .book-btn:hover { background: #e31c3d; }
        footer { background: #222; color: white; text-align: center; padding: 10px; position: fixed; bottom: 0; width: 100%; }
        @media (max-width: 768px) { .property-detail { padding: 10px; } }
    </style>
</head>
<body>
    <header>
        <h1><?php echo $property['title']; ?></h1>
    </header>
    <div class="property-detail">
        <img src="<?php echo explode(',', $property['images'])[0]; ?>" alt="<?php echo $property['title']; ?>">
        <p><?php echo $property['description']; ?></p>
        <p>Location: <?php echo $property['location']; ?></p>
        <p>Price: $<?php echo $property['price']; ?>/night</p>
        <p>Type: <?php echo $property['property_type']; ?></p>
        <p>Amenities: <?php echo $property['amenities']; ?></p>
        <p>Rating: <?php echo $property['rating']; ?> (<?php echo $property['reviews_count']; ?> reviews)</p>
        <button class="book-btn" onclick="bookProperty(<?php echo $property['id']; ?>)">Book Now</button>
    </div>
    <footer>&copy; 2025 Airbnb Clone</footer>
    <script>
        // Inline JS for booking redirect
        function bookProperty(id) {
            window.location.href = 'book.php?id=' + id;
        }
    </script>
</body>
</html>
