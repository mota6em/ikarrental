<?php 
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }
    if(!isset($_SESSION['user'])){
        header('Location: /views/auth/login.php');
    }else{
        $user = $_SESSION['user'];
        if($user['role'] === 'user'){
            header('Location: /views/user/profile.php');
        }
    }
    include('../../includes/header.php');
    require_once('../../storage/storage.php');
    $bookingsManeger = new Storage(new JsonIO('../../storage/JSONfiles/bookings.json'));
    $allBookedCars = $bookingsManeger->findAll();
    require_once('../../storage/cars.php');
    $carManeger = new CarManager();
    $allCars = $carManeger->getAllCars();
?>


<main class=" w-100">
    <section class="pt-5  min-vh-100 bg-w d-flex  align-items-center">
        <div class="pt-5 row w-100 container-fluid gx-0 gy-0 d-flex justify-content-center w-100">
            <div class="col-12 col-md-6 d-flex justify-content-center booking-result-img">
                <img src="/assets/imgs/user.png" class="profile-img" alt="Profile img">
            </div>
            <div class="col-12 col-md-6 d-flex jcc pt-2">
                <div class="txt-b p-4 rounded shadow w-auto">
                    <h3 class="fw-bold text-center mb-3">Admin Information</h3>
                    <p><strong>Name:</strong> <?= $user['name'] ?></p>
                    <p><strong>ID:</strong> <?= $user['id']?></p>
                    <p><strong>Email:</strong> <?= $user['email']?></p>
                </div>
            </div>
            <div class="container-fluid gy-5">
                <div class="col-12 w-100 d-flex flex-column txt-b align-items-center  justify-content-center jcc pt-2">
                    <a href="add_car.php" class="btn btn-primary">Add New Car</a>
                </div>
            </div>
            <div id="alert-container" class="position-fixed top-0 start-50 translate-middle-x mt-3" style="z-index: 1055; width: 80%; max-width: 500px;"></div>
            <div class="container-fluid border-top gy-4">
                <div class="col-12 w-100 d-flex gy-4 flex-column txt-b align-items-center  justify-content-center jcc pt-2">
                    <h2>All Booked Cars</h2>
                    <div class="row w-100 row-cols-auto">
                        <?php if (empty($allBookedCars)): ?>
                            <div class="d-flex justify-content-center align-items-center w-100 my-5">
                                <h3 class="text-danger fs-5">No Booked Cars.</h3>
                            </div>

                        <?php endif; ?>
                        <?php foreach($allBookedCars as $bookedCar):?>
                            <?php 
                                $currentCar = $carManeger->findCarById($bookedCar['carId']);
                            ?>
                            <div
                            class="card card-light gx-0 profile-card-admin"
                            style="width: 15rem; height: 25rem; margin: 1rem auto"
                            >
                                <div class="position-relative">
                                    <button class="btn btn-danger del-btn position-absolute" data-type="booking" data-id="<?= $bookedCar['id'] ?>">delete</button>
                                    <img
                                    src="<?= $currentCar['image'] ?>"
                                    class="card-img-top profile-card-img"
                                    alt="Car Image"
                                    />
                                    <div
                                    class="position-absolute bottom-0 bg-black end-0 bg-opacity-75 text-white px-3 py-0 my-2 rounded fw-bold"                            >
                                    <?= $bookedCar['startDate']?> / <?= $bookedCar['endDate']?>
                                    </div>
                                </div>                        
                                <div class="card-body text-center">
                                    <h4 class="card-title mb-1; txt-b"><?= $currentCar['brand'] . ' ' . $currentCar['model']?></h4>
                                    <p class="card-text mb-2 txt-g"><?= $currentCar['passengers'] . ' seats - ' . $currentCar['transmission'] . ' - ' . $currentCar['fuel_type']?></p>
                                    <p class="card-title mb-1; txt-b">Renter ID: <?= $bookedCar['userId']?></p>
                                </div>
                            </div>
                        <?php endforeach;?>
                    </div>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-center my-5">
                <a href="/index.php" class="btn ms-2 text-black btn-warning fw-bold ">Main Page</a>
            </div>
        </div>
    </section>
    <script src="/assets/js/delete_entity.js"></script>
</main>

<?php include('../../includes/footer.php')?>