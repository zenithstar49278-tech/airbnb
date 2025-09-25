<?php
// listings.php - Property listing page with search results, sort, filters
include 'db.php';

// Get search params from GET
$location = $_GET['location'] ?? '';
$check_in = $_GET['check_in'] ?? '';
$check_out = $_GET['check_out'] ?? '';
$price_min = $_GET['price_min'] ?? 0;
$price_max = $_GET['price_max'] ?? PHP_INT_MAX;
$property_type = $_GET['property_type'] ?? '';
$amenities = $_GET['amenities'] ?? '';
$sort = $_GET['sort'] ?? 'price_asc';

// Build SQL query (pro level with prepared statements)
$sql = "SELECT * FROM properties WHERE location LIKE ? AND price >= ? AND price <= ?";
$params = ["%$location%", $price_min, $price_max];
$types = "sdd";

if ($property_type) {
    $sql .= " AND property_type = ?";
    $params[] = $property_type;
    $types .= "s";
}
if ($amenities) {
    $amenities_array = explode(',', $amenities);
    foreach ($amenities_array as $amenity) {
        $sql .= " AND amenities LIKE ?";
        $params[] = "%$amenity%";
        $types .= "s";
    }
}
// Add availability check (simple: no overlapping bookings)
if ($check_in && $check_out) {
    $sql .= " AND id NOT IN (
        SELECT property_id FROM bookings 
        WHERE (check_in <= ? AND check_out >= ?) 
        OR (check_in <= ? AND check_out >= ?) 
        OR (check_in >= ? AND check_out <= ?)
    )";
    $params = array_merge($params, [$check_out, $check_in, $check_in, $check_out, $check_in, $check_out]);
    $types .= "ssssss";
}

// Sort
if ($sort == 'price_asc') $sql .= " ORDER BY price ASC";
elseif ($sort == 'price_desc') $sql .= " ORDER BY price DESC";
elseif ($sort == 'rating_desc') $sql .= " ORDER BY rating DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
$properties = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Listings</title>
    <style>
        /* Internal CSS - Matching amazing style */
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f4f4f4; color: #333; }
        header { background: #ff385c; color: white; padding: 20px; text-align: center; }
        .listings { max-width: 1200px; margin: 20px auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        .property-card { background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 8px rgba(0,0,0,0.1); transition: transform 0.3s; }
        .property-card:hover { transform: scale(1.05); }
        .property-card img { width: 100%; height: 200px; object-fit: cover; }
        .property-card h3 { margin: 10px; }
        .property-card p { margin: 5px 10px; color: #666; }
        .sort { text-align: center; margin: 10px; }
        .sort select { padding: 10px; border-radius: 5px; }
        footer { background: #222; color: white; text-align: center; padding: 10px; position: fixed; bottom: 0; width: 100%; }
        @media (max-width: 768px) { .listings { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <header>
        <h1>Available Properties</h1>
    </header>
    <div class="sort">
        <label>Sort by: </label>
        <select id="sortSelect" onchange="sortProperties()">
            <option value="price_asc" <?php if($sort=='price_asc') echo 'selected'; ?>>Price Low to High</option>
            <option value="price_desc" <?php if($sort=='price_desc') echo 'selected'; ?>>Price High to Low</option>
            <option value="rating_desc" <?php if($sort=='rating_desc') echo 'selected'; ?>>Best Rated</option>
        </select>
    </div>
    <section class="listings">
        <?php foreach ($properties as $prop): ?>
            <div class="property-card" onclick="viewProperty(<?php echo $prop['id']; ?>)">
                <img src="<?php echo explode(',', $prop['images'])[0]; ?>" alt="<?php echo $prop['title']; ?>">
                <h3><?php echo $prop['title']; ?></h3>
                <p>Location: <?php echo $prop['location']; ?></p>
                <p>Price: $<?php echo $prop['price']; ?>/night</p>
                <p>Rating: <?php echo $prop['rating']; ?> (<?php echo $prop['reviews_count']; ?> reviews)</p>
            </div>
        <?php endforeach; ?>
    </section>
    <footer>&copy; 2025 Airbnb Clone</footer>
    <script>
        // Inline JS for sort and view
        function sortProperties() {
            const sort = document.getElementById('sortSelect').value;
            const url = new URL(window.location.href);
            url.searchParams.set('sort', sort);
            window.location.href = url.toString();
        }
        function viewProperty(id) {
            window.location.href = 'property.php?id=' + id;
        }
    </script>
</body>
</html>
