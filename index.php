<?php
// index.php - Homepage with search bar, featured listings, filters
include 'db.php'; // Include DB connection

// Sample featured properties (in real, fetch from DB)
$featured = [
    ['id' => 1, 'title' => 'Cozy Apartment', 'location' => 'New York', 'price' => 100, 'image' => 'https://example.com/img1.jpg'],
    // Add more as needed
];

// Handle search form submission (use JS for redirect as per instruction)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Airbnb Clone - Homepage</title>
    <style>
        /* Internal CSS - Amazing, real-looking, professional design */
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f4f4f4; color: #333; }
        header { background: #ff385c; color: white; padding: 20px; text-align: center; }
        .search-bar { max-width: 800px; margin: 20px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .search-bar form { display: flex; justify-content: space-between; }
        .search-bar input, .search-bar select { padding: 10px; border: 1px solid #ddd; border-radius: 5px; flex: 1; margin: 0 5px; }
        .search-bar button { background: #ff385c; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; }
        .search-bar button:hover { background: #e31c3d; }
        .filters { display: flex; justify-content: center; margin: 10px 0; }
        .filters label { margin: 0 10px; }
        .featured { max-width: 1200px; margin: 20px auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        .property-card { background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 8px rgba(0,0,0,0.1); transition: transform 0.3s; }
        .property-card:hover { transform: scale(1.05); }
        .property-card img { width: 100%; height: 200px; object-fit: cover; }
        .property-card h3 { margin: 10px; }
        .property-card p { margin: 5px 10px; color: #666; }
        footer { background: #222; color: white; text-align: center; padding: 10px; position: fixed; bottom: 0; width: 100%; }
        @media (max-width: 768px) { .search-bar form { flex-direction: column; } .search-bar input, .search-bar select, .search-bar button { margin: 5px 0; } }
    </style>
</head>
<body>
    <header>
        <h1>Welcome to Airbnb Clone</h1>
    </header>
    <div class="search-bar">
        <form id="searchForm">
            <input type="text" id="location" placeholder="Destination" required>
            <input type="date" id="check_in" required>
            <input type="date" id="check_out" required>
            <div class="filters">
                <label>Price Min: <input type="number" id="price_min"></label>
                <label>Price Max: <input type="number" id="price_max"></label>
                <label>Type: <select id="property_type"><option value="">Any</option><option value="Apartment">Apartment</option><option value="House">House</option></select></label>
                <label>Amenities: <input type="text" id="amenities" placeholder="WiFi,Pool"></label>
            </div>
            <button type="button" onclick="searchProperties()">Search</button>
        </form>
    </div>
    <section class="featured">
        <h2>Featured Listings</h2>
        <?php foreach ($featured as $prop): ?>
            <div class="property-card" onclick="viewProperty(<?php echo $prop['id']; ?>)">
                <img src="<?php echo $prop['image']; ?>" alt="<?php echo $prop['title']; ?>">
                <h3><?php echo $prop['title']; ?></h3>
                <p>Location: <?php echo $prop['location']; ?></p>
                <p>Price: $<?php echo $prop['price']; ?>/night</p>
            </div>
        <?php endforeach; ?>
    </section>
    <footer>&copy; 2025 Airbnb Clone</footer>
    <script>
        // Inline JS for redirection as per instruction
        function searchProperties() {
            const params = new URLSearchParams({
                location: document.getElementById('location').value,
                check_in: document.getElementById('check_in').value,
                check_out: document.getElementById('check_out').value,
                price_min: document.getElementById('price_min').value,
                price_max: document.getElementById('price_max').value,
                property_type: document.getElementById('property_type').value,
                amenities: document.getElementById('amenities').value
            });
            window.location.href = 'listings.php?' + params.toString();
        }
        function viewProperty(id) {
            window.location.href = 'property.php?id=' + id;
        }
    </script>
</body>
</html>
