<!-- 
    !!          !!          !!          !!          !!          !!          !!          !!
    !! This PHP file is no longer used after implementing AJAX for booking handling     !!
    !!          !!          !!          !!          !!          !!          !!          !!
-->
<?php
    if(session_status() === PHP_SESSION_ACTIVE){
        session_start();
    }
    include('../../includes/header.php');
    $carId = $_GET['id'] ?? null;
    require_once('../../storage/cars.php');
    $carManeger = new CarManager();
    $car = $carManeger->findCarById($carId);
    $startDate = $_GET['startDate'] ?? null;
    $endDate = $_GET['endDate'] ?? null;
    $redirectUrl = $_SESSION['user']['role'] === 'user' ? '../user/profile.php' : '/views/admin/profile.php'; 
?>


<main class="container-fluied w-100">
    <section class="vh-100 bg-w d-flex  align-items-center pt-4">
        <div class="row container-fluid gx-0 gy-0 d-flex justify-content-center w-100">
            <div class="col-12 d-flex justify-content-center booking-result-img">
                <img src="../../assets/imgs/success_img.png" id="booking-result-img" class="booking-result-img" alt="Success Booking img">
            </div>
            <div class="col-12 d-flex justify-content-center p-5 pb-3">
                <p class=" fs-5 txt-b text-center">
                    Your booking for the <span class="fw-bold txt-g"><?= $car['brand'] . ' ' . $car['model']?></span> has been successfully confirmed 
                    for the period<span class="fw-bold num-font"> <?=$startDate?> – <?=$endDate?></span>.
                    <br />
                    You can track the status of your booking on your profile page.
                </p>
            </div>
            <div class="col-12 d-flex justify-content-center">
                <button onclick="history.back();" class="btn bg-d text-light btn-outline-light fw-bold me-5">Back</button>
                <a href="<?= $redirectUrl ?>" class="btn btn-success text-light btn-outline-light fw-bold ">My Profile</a>
            </div>
        </div>
    </section>
</main>

<?php include('../footer.php')?>