<?php
include '../includes/db.php';

// Most rented cars
$mostRented = $conn->query("
  SELECT cars.name, COUNT(*) AS total_rentals
  FROM rentals
  JOIN cars ON rentals.car_id = cars.id
  GROUP BY cars.name
  ORDER BY total_rentals DESC
");
$mostRentedLabels = [];
$mostRentedData = [];
while ($row = $mostRented->fetch_assoc()) {
  $mostRentedLabels[] = $row['name'];
  $mostRentedData[] = $row['total_rentals'];
}

// Profit per brand
$profitPerBrand = $conn->query("
  SELECT cars.brand, SUM(rentals.total_price) AS total_profit
  FROM rentals
  JOIN cars ON rentals.car_id = cars.id
  GROUP BY cars.brand
");
$profitBrandLabels = [];
$profitBrandData = [];
while ($row = $profitPerBrand->fetch_assoc()) {
  $profitBrandLabels[] = $row['brand'];
  $profitBrandData[] = $row['total_profit'];
}

// Monthly revenue
$monthlyRevenue = $conn->query("
  SELECT DATE_FORMAT(start_date, '%Y-%m') AS month, SUM(total_price) AS revenue
  FROM rentals
  GROUP BY month
  ORDER BY month
");
$monthlyRevenueLabels = [];
$monthlyRevenueData = [];
while ($row = $monthlyRevenue->fetch_assoc()) {
  $monthlyRevenueLabels[] = $row['month'];
  $monthlyRevenueData[] = $row['revenue'];
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode([
  'mostRented' => [
    'labels' => $mostRentedLabels,
    'data' => $mostRentedData
  ],
  'profitPerBrand' => [
    'labels' => $profitBrandLabels,
    'data' => $profitBrandData
  ],
  'monthlyRevenue' => [
    'labels' => $monthlyRevenueLabels,
    'data' => $monthlyRevenueData
  ]
]);
?>
