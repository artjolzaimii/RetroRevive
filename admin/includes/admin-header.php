<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel | RetroRevive</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f8f9fa;
    }
    .admin-header {
      background-color: #212529;
      color: white;
      padding: 20px;
      text-align: center;
    }
    .admin-navbar {
      background-color: #343a40;
    }
    .admin-navbar .nav-link {
      color: white;
    }
    .admin-navbar .nav-link:hover {
      color: #ffc107;
    }
    .main-content {
      padding: 30px;
    }
  </style>
</head>
<body>

<div class="admin-header">
  <h1>RetroRevive Admin Panel</h1>
</div>

<?php include("admin-navbar.php"); ?>
