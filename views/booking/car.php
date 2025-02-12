<?php
    include '../../includes/header.php'; 
    require_once('../../storage/cars.php');
    $carManeger = new CarManager();
    $carId = isset($_GET['id']) ?$_GET['id'] : null;
    $errors = [];
    if(!isset($_SESSION['user']['id'])){
        $errors['general'] = 'Please login first!';
    }
    if ($carId) {
        $car = $carManeger->findCarById($carId);
        if (!$car) {
            die('Car Not Found!');
        }
    } else {
        die('Car Not Found!');
    }
    $bookedDates = [
        ["from" => "2025-01-16", "to" => "2025-01-19"],
        ["from" => "2025-02-01", "to" => "2025-02-05"]
    ];
    
    // This PHP code for handling booking via POST is no longer used 
    // after implementing AJAX for booking management.

        // if($_SERVER['REQUEST_METHOD'] === 'POST'){
        //     $startDate = $endDate = null;
        //     if(empty($_POST['startDate'])){
        //         $errors['startDate'] = 'Please enter the start date!';
        //         $errors['general'] = 'Please enter a valid date!';
        //     }
        //     if(empty($_POST['endDate'])){
        //         $errors['endDate'] = 'Please enter the end date!';
        //         $errors['general'] = 'Please enter a valid date!';
        //     }        
        //     if (!empty($_POST['startDate']) && !empty($_POST['endDate'])) {
        //         $startDate = $_POST['startDate'];
        //         $endDate = $_POST['endDate'];
        //         if (strtotime($startDate) >= strtotime($endDate)) {
        //             $errors['endDate'] = 'End date must be after start date!';
        //             $errors['general'] = 'Please enter a valid date!';
        //         }
        //     }
    
        //     if(empty($errors)){
        //         header('Location: booking-handler.php?id=' . $carId . '&startDate=' . $startDate . '&endDate=' . $endDate);
        //         exit;
        //     }
        // }
    function formatPrice($price){
        $formattedPrice = number_format($price,0,'',' ');
        return $formattedPrice . " Ft";
    }
?>

<main class="bg-w">
    <div class="container pt-4 pb-2">
        <div class="row d-flex align-items-md-center justify-content-center min-vh-100">
            <div class="col-12 mb-2 mb-md-0 col-md-6 fdc car-page-img ">
                <img src="<?= $car['image']?>" class="w-100 rounded " alt="Car img">
            </div>
            <div class="col-12 col-md-6">
                <div class="card bg-d text-light p-3 car-details  fs-5">
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
                    <div class="d-flex flex-column justify-content-center px-md-5 mt-3">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#dateModal" id="select-date-bx-btn" class="btn fs-5 btn-primary btn-sm" <?= isset($_SESSION['user']) ? '' : 'disabled' ?>>Select Date to Book</button>
                        <?php if(isset($errors['general'])):?>
                            <span class="text-danger"><?= $errors['general']?></span>
                        <?php endif;?>
                    </div>  
                </div>
                <div class="modal fade" id="dateModal" tabindex="-1" aria-labelledby="dateModalLabel" aria-hidden="true">
                    <div class="modal-dialog bg-w txt-b">
                        <div class="modal-content bg-w txt-b">
                            <div class="modal-header">
                                <h5 class="modal-title" id="dateModalLabel">Select Rental Dates</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div>
                                <div class="modal-body">
                                    <input type="hidden" name="carId" value="<?= $carId ?>">
                                    <div class="mb-3">
                                        <label for="startDate" class="form-label">Start Date</label>
                                        <input type="text" class="form-control" id="startDate" name="startDate" placeholder="Select start date" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="endDate" class="form-label">End Date</label>
                                        <input type="text" class="form-control" id="endDate" name="endDate" placeholder="Select end date" readonly>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button id="confirm-dates" data-id="confirm-dates" data-bs-dismiss="modal" data-car-id="<?= $carId ?>" class="btn btn-success">Confirm Dates</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="fdc mt-2 mt-md-4">
                    <button id="car-back-btn" class="btn bg-d text-light btn-outline-light fw-bold w-25">Back</button>
                </div>
            </div>
        </div>
    </div>
    <script src="/assets/js/navigation.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="/assets/js/book_car.js"></script>
</main>
<?php include('../../includes/footer.php')?>