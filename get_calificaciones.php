<?php
// Get the item ID from the request
$itemId = $_POST['item_id'];

// Query the database to get the status of the item
$query = "SELECT status FROM items WHERE id = $itemId";
$result = mysqli_query($conn, $query);
$item = mysqli_fetch_assoc($result);

// Return the status as a JSON response
echo json_encode(['status' => $item['status']]);