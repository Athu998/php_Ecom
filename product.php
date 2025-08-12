<?php
require "config/db.php";

$id = intval($_GET['id']); // Always sanitize GET params!
$sql = "SELECT * FROM products WHERE id = $id";
$result = mysqli_query($conn, $sql);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    die("Product not found.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($product['name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="card p-4">
        <div class="row">
            <div class="col-md-4">
                <img src="images/<?php echo htmlspecialchars($product['image']); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>
            <div class="col-md-8">
                <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                <h4>₹<?php echo htmlspecialchars($product['price']); ?></h4>
                <button id="buyNow" class="btn btn-success">Buy Now</button>
            </div>
        </div>
    </div>
</div>

<script>
$('#buyNow').click(function(e){
    e.preventDefault();

    var options = {
        "key": "<?php echo htmlspecialchars($_ENV['RAZORPAY_KEY_ID']); ?>", 
        "amount": "<?php echo $product['price'] * 100; ?>",  
        "currency": "INR",
        "name": "<?php echo htmlspecialchars($product['name']); ?>",
        "description": "Product Purchase",
        "handler": function (response){
            $.post("store_order.php", {
                product_id: "<?php echo $product['id']; ?>",
                product_name: "<?php echo htmlspecialchars($product['name']); ?>",
                amount: "<?php echo $product['price']; ?>",
                payment_id: response.razorpay_payment_id
            }, function(res){
                alert(res);
                window.location.href = "index.php";
            });
        },
        "prefill": {
            "name": "",
            "email": "",
            "contact": ""
        },
        "theme": {
            "color": "#3399cc"
        }
    };

    var rzp1 = new Razorpay(options);
    rzp1.open();
});
</script>

</body>
</html>
