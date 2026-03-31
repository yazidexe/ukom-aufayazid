<?php
include 'config/database.php';

$query = mysqli_query($conn, "
    SELECT 
        id,
        customer_name,
        total_price,
        created_at,
        (
            SELECT SUM(quantity) 
            FROM transactions 
            WHERE transactions.sale_id = sales.id
        ) AS total_quantity
    FROM sales
    ORDER BY id DESC
");
?>
