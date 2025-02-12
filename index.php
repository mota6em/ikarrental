<?php
    $isAdmin = false;
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
      $isAdmin = false;
    }
    $user = null;
    if(isset($_SESSION['user'])){
      $user = $_SESSION['user'];
      if($user['role'] === 'admin'){
        $isAdmin = true;
      }
    }
    include './includes/header.php';
    require_once('./storage/cars.php');
    require_once('./storage/storage.php');
    $carManager = new CarManager();
    $bookingsManager = new Storage(new JsonIO('./storage/JSONfiles/bookings.json'));
    $bookings = $bookingsManager->findAll(); 
    $cars = $carManager->getAllCars();
    $errors = [];
    $seats = isset($_GET['seats']) ? (int)$_GET['seats'] : 2;
    $transmission = isset($_GET['transmission']) ? $_GET['transmission'] : 'all';
    $fuelType = isset($_GET['fuelType']) ? $_GET['fuelType'] : 'all';
    $priceFrom = isset($_GET['priceFrom']) ? (int)$_GET['priceFrom'] : 1500;
    $priceTo = isset($_GET['priceTo']) ? (int)$_GET['priceTo'] : 450000;
    $dateFrom = isset($_GET['dateFrom']) ? $_GET['dateFrom'] : date('Y-m-d');
    $dateTo = isset($_GET['dateTo']) ? $_GET['dateTo'] : date('Y-m-d');
    if(strtotime($dateFrom) > strtotime($dateTo)){
      $errors['dateTo'] = 'End date should come after start date!';
    }
    $allSeats = false; 
    $allDates = false; 
    if (isset($_GET['allSeats']) && $_GET['allSeats'] === '1') {
        $allSeats = true;
    }
    if (isset($_GET['allDates']) && $_GET['allDates'] === '1') {
        $allDates = true;
    }else{
      $allDates = false;
    }

    function formatPrice($price){
      $formattedPrice = number_format($price,0,'',' ');
      return $formattedPrice . " Ft";
    }
    
    $filteredCars = $cars;
    $carsDisplied = 'All Cars';
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET)) {
      $filteredCars = array_filter($cars, function($car) use ($allSeats,$allDates,$dateFrom,$dateTo,$bookings,$seats, $transmission, $fuelType, $priceFrom, $priceTo) {
        $isAvailable = isAvailable($car['id'], $bookings, $dateFrom,$dateTo);
        return ($allSeats || !$seats || strtolower($car['passengers']) == ($seats)) &&
               ($transmission == 'all' || strtolower($car['transmission']) == strtolower($transmission)) &&
               ($fuelType == 'all' || strtolower($car['fuel_type']) == strtolower($fuelType)) &&
               ( $car['daily_price_huf'] >= $priceFrom && $car['daily_price_huf'] <= $priceTo) &&
               ($allDates || $isAvailable);
      });
      if(isset($_GET['filter'])){
        $carsDisplied = 'Only Filtered Cars';
        if($_GET['filter'] === 'reset'){
          $filteredCars = $cars;
          $carsDisplied = 'All Cars';
        }
      }

    }
    function isAvailable($id ,$bookings,$dateFrom,$dateTo) {
      if(empty($dateFrom) || empty($dateTo)){
        return true;
      }
      foreach($bookings as $booking){
        if($booking['carId'] === $id){
          $bookingStart = strtotime($booking['startDate']);
          $bookingEnd = strtotime($booking['endDate']);
          $filterStart = strtotime($dateFrom);
          $filterEnd = strtotime($dateTo);
          if($filterStart <= $bookingEnd && $filterEnd >= $bookingStart){
            return false;
          }
        }
      }
      return true;
    }
  
?>
<main class="bg-w">
      <section
        class="main-sec d-flex justify-content-center align-items-center min-vh-100"
      >
        <div
          class="main container-fluid text-white text-center d-flex flex-column align-items-center justify-content-center"
        >
          <h1 class="display-1 fw-bold">iKarRental</h1>
          <!-- <?php if(isset($_SESSION['user'])):?>
            <h3>Wellcome <span class="text-warning fw-bold"><?= $_SESSION['user']['name']?></span>!</h3>
          <?php endif;?> -->
          <p class="h2 mt-1">Rent a Car with Ease!</p>
          <p class="lead mt-1 w-50 ts">
            Book now and drive away with <span class="text-warning ff-cfv fw-bold">confidence!</span>
          </p>
          <!-- <p class="lead mt-2 w-50 ts">
            Explore a wide selection of vehicles, tailored for your needs, at
            competitive prices. Book now and drive away with confidence!
          </p> -->
          <a href="#filter-sec" class="btn btn-warning btn-lg mt-2"
            >Browse Cars</a
          >
        </div>
      </section>
      <section id="filter-sec">
        <div class="container mt-4 mb-2">
          <h1 class="display-5 fw-bold">
            <span class="goldenrod" id="id">Find Your Ride</span>
            <span class="txt-g"> with Ease</span>
          </h1>
          <p class="lead txt-g">
            Explore, filter, and choose the car that suits your journey
            perfectly.
          </p>
        </div>
        <div
          class="container px-5 mt-3 mb-3 d-flex align-items-center bg-d text-white rounded p-2"
        >
          <form action="index.php">
            <div class="row row-cols-auto g-3 justify-content-right mt-3">
              <div class="col-12 mb-2 mx-0 d-flex align-items-center">
                <div class="d-flex align-items-center">
                  <div class="row">
                    <div class="col-12 col-md-6 mb-1">
                      <div class="form-check ">
                        <input
                        type="checkbox"
                        class="form-check-input"
                        id="allSeats"
                        name="allSeats"
                        value="1"
                        <?= $allSeats ? 'checked' : '' ?>
                        />
                        <label class="form-check-label fw-bold" for="allSeats">Select all seat options.</label>
                      </div>
                    </div>
                    <div class="col-12 col-md-6  d-flex align-items-center">
                      <span>Or</span>
                      <span class="ps-1">specify</span>
                      <span class="ps-1">seats:</span>
                      <div class="btn ms-1 text-white ms-2  btn-outline-secondary" id="subtractOne">-</div>
                      <input
                        type="number"
                        class="form-control mx-2 text-center"
                        value="<?= $seats ?>"
                        max="10"
                        min="1"
                        id="seatsNum"
                        name="seats"
                        style="width:60px"
                        readonly
                      />
                      <div class="btn text-white btn-outline-secondary" id="addOne">+</div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12 mb-3 mx-0">
                <div class="d-flex align-items-center">
                  <div class="row">
                    <div class="col-md-4 col-12">
                      <div class="form-check mx-md-0 d-flex flex-column">
                        <div>
                          <input
                          type="checkbox"
                          class="form-check-input"
                          id="allDates"
                          name="allDates"
                          value="1"
                          <?= isset($_GET['allDates']) ? 'checked' : '' ?>
                          />
                          <label class="form-check-label" for="allDates"><span class="fw-bold me-1">Select all date options.</span></label>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4 col-12 ms-md-0 my-2 my-md-0">
                        <div class="col-12 col-md-6 ms-3  w-100 gx-0">
                          <span class="me-2 w-100"><span class="fw-bold">Or specify:</span> from</span>
                          <input type="date" id="dateFrom" value="<?= htmlspecialchars($dateFrom) ?>" name="dateFrom" class="form-control w-auto" />
                        </div>
                    </div>
                    <div class="col-md-4 col-12"> 
                      <div class="col-12 col-md-6 ms-3  ms-md-0">
                        <span class="ms-lg-2 me-2">to</span>
                        <input type="date" id="dateTo" value="<?= htmlspecialchars($dateTo) ?>" name="dateTo" class="form-control w-auto" />
                        <?php if(isset($errors['dateTo'])):?>
                          <span class="text-danger w-100"><?= $errors['dateTo']?></span>
                        <?php endif;?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col mb-3 mx-1 d-flex align-items-center">
                <div class="d-flex align-items-center">
                  <label for="transmission" name="transmission" class="form-label me-2">Transmission</label>
                  <select class="form-select" name="transmission" id="transmission">
                    <option value="all" <?= $transmission === 'all' ? 'Selected' : ''?> >All</option>
                    <option value="Automatic" <?= $transmission === 'Automatic' ? 'selected' : ''?>>Automatic</option>
                    <option value="Manual" <?= $transmission === 'Manual' ? 'selected' : ''?>>Manual</option>
                  </select>
                </div>
              </div>
              <div class="col mb-3 mx-1 d-flex align-items-center">
                <div class="d-flex align-items-center">
                  <label for="fuelType" class="form-label me-2">Fuel Type</label>
                  <select class="form-select" name="fuelType" id="fuelType">
                    <option value="all" <?= $fuelType === 'all' ? 'selected' : ''?> >All</option>
                    <option value="Petrol" <?= $fuelType === 'Petrol' ? 'selected' : ''?>>Petrol</option>
                    <option value="Diesel" <?= $fuelType === 'Diesel' ? 'selected' : ''?>>Diesel</option>
                    <option value="Electric" <?= $fuelType === 'Electric' ? 'selected' : ''?>>Electric</option>
                  </select>
                </div>
              </div>
              <div class="col mb-3 mx-1">
                <div class="d-flex align-items-center">
                  <div class="row">
                    <div class="col-6 col-md-6">
                      <span class="me-1">Price: </span>
                      <span class="me-1">from</span>
                      <input
                        type="number"
                        class="form-control w-auto"
                        value="<?=$priceFrom?>"
                        name="priceFrom"
                        id="priceFrom"
                      />
                    </div>
                    <div class="col-12 col-md-6">
                      <span class="me-1 ms-3">to</span>
                      <span class="mx-1">(prices are in Ft)</span>
                      <input
                        type="number"
                        class="form-control w-auto"
                        value="<?= $priceTo?>"
                        name="priceTo"
                        id="priceTo"
                      />
                    </div>
                  </div>
                </div>
              </div>
              <div class="col mb-3 mx-1 d-flex">
                <div class="d-flex justify-content-center">
                  <button
                    type="submit"
                    name="filter"
                    value="apply"
                    class="btn filter-btns btn-md fw-bold btn-warning ms-lg-5 me-3 me-lg-3 rounded shadow"
                  >
                    Apply Filter
                  </button>
                  <button
                    id="reset-filter-btn"
                    type="submit"
                    name="filter"
                    value="reset"
                    class="btn filter-btns btn-md fw-bold btn-secondary ms-lg-3  rounded shadow"
                  >
                    All Cars
                  </button>
                </div>
              </div>
            </div>
          </form>
          <script src="/assets/js/index-php.js"></script>
        </div>

      </section>
      <section id="cars-sec" class="mt-4 pt-2">
        <div class="container text-center">
          <div class="row txt-g mb-2">
            <h4 class="fs-2"><span class="txt-b fw-bold border-bottom border-black"><?= $carsDisplied ?></span> Are Displayed</h4>
          </div>
          <div class="row row-cols-auto gx-0 ">
            <?php foreach($filteredCars as $car):?>
              <div
              class="card card-light gx-0 border border-dark-subtle card-dimensions"
              >
                <!-- Car Image -->
                <div class="position-relative">
                  <img
                  src="<?= $car['image']?>"
                  class="card-img-top card-img"
                  alt="Car Image"
                  />
                  <div
                  class="position-absolute bottom-0 end-0 bg-b text-white px-2 py-1 my-2 me-1 rounded fw-bold fs-5"
                  >
                  <?= formatPrice($car['daily_price_huf']) ?>
                </div>
                <?php if($isAdmin):?>
                  <div class="btn btn-danger del-btn position-absolute" data-type="car" data-id="<?= $car['id']?>">delete</div>
                  <div class="btn btn-info edit-btn me-5 px-2 position-absolute"><a href="/views/admin/edit_car.php?id=<?= $car['id'] ?>">edit</a></div>
                  <?php endif;?>
                </div>
                <a class="text-decoration-none" href="./views/booking/car.php?id=<?= $car['id']?>">
                  <!-- Card Body -->
                  <div class="card-body">
                    <h4 class="card-title brand-name mb-1 txt-b"><?= $car['brand']?><span class="txt-g"> <?= $car['model']?></span></h4>
                    <p class="card-text mb-2 txt-g"><?= $car['passengers'] ?> seats - <?= $car['transmission'] ?> - <?= $car['fuel_type'] ?></p>
                    <a href="./views/booking/car.php?id=<?= $car['id']?>" class="btn btn-warning fw-bold">View Details to Book</a></a>
                  </div>
                </a>
              </div>
            <?php endforeach;?>
          </div>
        </div>
        <div class="container text-center p-3">
          <i class="bx bx-sad txt-b fs-4"></i>
          <p class="fs-3 fw-light txt-b"><?= count($filteredCars) === 0 ? 'No items found.' : 'All items have been displayed.'?></p>
        </div>
      </section>
    </main>
  <script src="/assets/js/delete_entity.js"></script>
<?php include './includes/footer.php' ?>