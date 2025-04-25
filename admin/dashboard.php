<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard | RetroRevive</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
body {
  margin: 0;
  padding: 0;
  font-family: 'Segoe UI', sans-serif;
}

.background-wrapper {
  position: relative;
  background-image: url('../Images/admin-back.png'); 
  background-size: cover;
  background-position: center;
  height: 100vh;
  color: white;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

/* This is the dark overlay */
.background-wrapper::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  height: 100%;
  width: 100%;
  background: rgba(0, 0, 0, 0.5); /* Adjust darkness: 0.3 is light, 0.6+ is darker */
  z-index: 1;
}


.dashboard-content {
  position: relative;
  z-index: 2;
  text-align: center;
  padding: 30px;
}

.dashboard-content h1 {
  font-size: 3rem;
  font-weight: 700;
}

.dashboard-content p {
  font-size: 1.2rem;
  margin-bottom: 30px;
}

.card-boxes {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  justify-content: center;
}

.card-boxes .box {
  padding: 20px;
  border-radius: 10px;
  background-color: rgba(255, 255, 255, 0.1); 
  backdrop-filter: blur(5px);
  width: 200px;
  text-align: center;
}
</style>

</head>

<body class="d-flex flex-column min-vh-100">

<?php include("includes/admin-header.php"); ?>


 
<div class="background-wrapper">
  <div class="dashboard-content">
    <h1>Welcome, Artjol</h1>
    <p>This is your dashboard where you can manage cars, listings, bookings, and users.</p>

    <div class="card-boxes">
      <div class="box">Total Cars<br>--</div>
      <div class="box">Active Listings<br>--</div>
      <div class="box">Bookings<br>--</div>
      <div class="box">Users<br>--</div>
    </div>
  </div>
</div>



<?php include("includes/admin-footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
