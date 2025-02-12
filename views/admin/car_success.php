<?php 
include('../../includes/header.php');
require_once('../../storage/cars.php');
if(!isset($_SESSION['user'])){
    header('Location: /views/auth/login.php');
}else if($_SESSION['user']['role'] === 'user'){
    header('Location: /');
}
$carManager = new CarManager();
if(isset($_GET['carId'])){
    $car = $carManager->findCarById($_GET['carId']);

}else{
    die('car not found!');
}

$type = $_GET['type'];
function formatPrice($price){
    $formattedPrice = number_format($price,0,'',' ');
    return $formattedPrice . " Ft";
  }
?>


<main class="container-fluied w-100">
    <section class="vh-100 bg-w d-flex  align-items-center pt-4">
        <div class="row container-fluid gx-0 gy-0 d-flex justify-content-center w-100">
            <div class="col-12 d-flex justify-content-center p-5 pb-3">
                <?php if($type === 'add'):?>
                    <p class=" fs-3 txt-g text-center">
                        The car <span class="fw-bold txt-b"><?= $car['brand'] . ' ' . $car['model'] ?></span> has been successfully added to your inventory.
                    </p>
                <?php endif;?>
                <?php if($type === 'update'):?>
                    <p class=" fs-3 txt-g text-center">
                        The car <span class="fw-bold txt-b"><?= $car['brand'] . ' ' . $car['model'] ?></span> has been successfully updated.
                    </p>
                <?php endif;?>
            </div>
            <div class="col-12 mb-2 mb-md-0 col-md-6 fdc car-page-img ">
                <img src="<?= $car['image']?>" class="w-75 rounded " alt="Car img">
            </div>
            <div class="col-12 col-md-6">
                <div class="card bg-d mx-3 text-light p-3 car-details  fs-5">
                    <h3 class="fw-bold text-center fs-1"><?= $car['brand']?> <span class="text-warning"><?= $car['model']?></span></h3>
                    <div class="d-flex justify-content-between mt-2">
                        <p class="mb-0">
                            Fuel Type: <?= $car['fuel_type']?><br>
                        Transmission: <?= $car['transmission']?>
                        </p>
                        <p class="mb-0">
                            Year: <?= $car['year']?><br>
                            Seats: <?= $car['passengers']?>
                        </p>
                    </div>
                    <div class="mt-3">
                        <h4 class="fw-bold text-center"><?= formatPrice($car['daily_price_huf'])?>/day</h4>
                    </div>
                    <div class="d-flex justify-content-between px-md-5 mt-3">
                        <button class="btn fs-5 btn-primary btn-sm" disabled>Select Date</button>
                        <button class="btn fs-5 btn-warning btn-sm text-dark fw-bold" disabled>Book Now</button>
                    </div>
                </div>
            
                <div class="fdc mt-2 mt-md-4">
                    <a href="profile.php" class="btn bg-d text-light btn-outline-light fw-bold w-25">Back To Profile</a>
                </div>
            </div>
        </div>
    </section>
    <script src="/assets/js/navigation.js"></script>
</main>

<?php include('../../includes/footer.php')?>