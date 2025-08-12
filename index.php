<?php require "config/db.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shop with Filters</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container mt-4">
    <div class="row">
        <!-- Filters -->
        <div class="col-md-3">
            <h5>Categories</h5>
            <?php
            $cat_query = mysqli_query($conn, "SELECT DISTINCT category FROM products");
            while ($cat = mysqli_fetch_assoc($cat_query)) {
                echo "<div><input type='checkbox' class='filter category' value='{$cat['category']}'> {$cat['category']}</div>";
            }
            ?>

            <h5 class="mt-3">Brands</h5>
            <?php
            $brand_query = mysqli_query($conn, "SELECT DISTINCT brand FROM products");
            while ($brand = mysqli_fetch_assoc($brand_query)) {
                echo "<div><input type='radio' name='brand' class='filter brand' value='{$brand['brand']}'> {$brand['brand']}</div>";
            }
            ?>

            <h5 class="mt-3">Price</h5>
            <input type="number" id="min_price" class="form-control mb-2" placeholder="Min Price">
            <input type="number" id="max_price" class="form-control" placeholder="Max Price">
        </div>

        <!-- Products -->
        <div class="col-md-9">
            <div class="row" id="product-list"></div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    function loadProducts(){
        var categories = [];
        $('.category:checked').each(function(){
            categories.push($(this).val());
        });

        var brand = $('.brand:checked').val();
        var min_price = $('#min_price').val();
        var max_price = $('#max_price').val();

        $.post("fetch.php", {
            categories: categories,
            brand: brand,
            min_price: min_price,
            max_price: max_price
        }, function(data){
            $("#product-list").html(data);
        });
    }

    loadProducts();

    $(".filter").change(loadProducts);
    $("#min_price, #max_price").keyup(loadProducts);
});
</script>
</body>
</html>
