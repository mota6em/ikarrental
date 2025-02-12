<?php 
    include('../../includes/header.php');
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if(!isset($_SESSION['user'])){
        header('Location: /views/auth/login.php');
    }else if($_SESSION['user']['role'] === 'admin'){
        header('Location: /');
    }
    $user = $_SESSION['user'];
    $userId = $user['id'];
    require_once('../../storage/cars.php');
    $carManeger = new CarManager();
    $allCars = $carManeger->getAllCars();
    require_once('../../storage/storage.php');
    $bookingsManeger = new Storage(new JsonIO('../../storage/JSONfiles/bookings.json'));
    $allBookedCars = $bookingsManeger->findAll();
    $bookedCarsByUser = [];
    foreach($allBookedCars as $bookedCar){
        if($bookedCar['userId'] === $userId){
            array_push($bookedCarsByUser,
                 ["carId" => $bookedCar['carId'], "startDate" => $bookedCar['startDate'], "endDate" => $bookedCar['endDate']]);
        }
    }
    $numOfBookings = count($bookedCarsByUser);
?>

<main class=" w-100">
    <section class="pt-5  min-vh-100 bg-w d-flex  align-items-center">
        <div class="pt-5 row w-100 container-fluid gx-0 gy-0 d-flex justify-content-center w-100">
            <div class="col-12 col-md-6 d-flex justify-content-center booking-result-img">
                <img src="../../assets/imgs/user.png" class="profile-img" alt="Profile img">
            </div>
            <div class="col-12 col-md-6 d-flex jcc pt-2">
                <div class="txt-b p-4 rounded shadow w-auto">
                    <h3 class="fw-bold text-center mb-3">User Information</h3>
                    <p><strong>Name:</strong> <span class="text-uppercase txt-g"><?= $user['name']?></span></p>
                    <p><strong>ID:</strong> <span class="txt-g"><?= $user['id']?></span></p>
                    <p><strong>Email:</strong> <span class=" txt-g"><?= $user['email']?></span></p>
                    <p><strong>Number of Bookings:</strong> <span class="txt-g"><?= $numOfBookings ?></span></p>
                </div>
            </div>
            <div class="col-12 pt-5 d-flex justify-content-center pt-2">
                <p class=" fs-2 txt-b">My Bookings</p>
            </div>
            <div class="container col-12 pt-1">
                <div class="row row-cols-auto">
                    <?php foreach($bookedCarsByUser as $bookedCar):?>
                        <?php 
                            $currentCar = $carManeger->findCarById($bookedCar['carId']);
                        ?>
                        <div
                        class="card card-light gx-0 profile-card"
                        style="width: 15rem; height: 20rem; margin: 1rem auto"
                        >
                        <div class="position-relative">
                            <img
                            src="<?= $currentCar['image'] ?>"
                            class="card-img-top profile-card-img"
                            alt="Car Image"
                            />
                            <div
                            class="position-absolute bg-black bottom-0 end-0 fs-5 bg-opacity-50 text-white px-3 py-0 my-2 rounded fw-bold"                            >
                            <?= $bookedCar['startDate']?> / <?= $bookedCar['endDate']?>
                            </div>
                        </div>                        
                        <div class="card-body; font-size: 3rem text-center">
                            <h4 class="card-title mb-1; txt-b"><?= $currentCar['brand'] . ' ' . $currentCar['model']?></h4>
                            <p class="card-text mb-2 txt-g"><?= $currentCar['passengers'] . ' seats - ' . $currentCar['transmission'] . ' - ' . $currentCar['fuel_type']?></p>
                        </div>
                    </div>
                    <?php endforeach;?>
                </div>
                <div class="d-flex justify-content-center align-items-center fs-4 txt-g">
                    <?php if($numOfBookings === 0):?>
                        <div class="">
                            <p>No bookings yet. <a href="/index.php#filter-sec">BOOK NOW!</a></p>
                        </div>    
                    <?php endif?>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-center my-5">
                <!-- <button class="btn bg-d me-2 text-light btn-outline-light fw-bold back-btn">Back</button> -->
                <a href="../../index.php" class="btn ms-2 text-black btn-warning fw-bold ">Main Page</a>
            </div>
        </div>
    </section>
</main>
<?php include('../../includes/footer.php')?>