<?php
    include('../../includes/header.php');   
    require_once('../../storage/cars.php');
    if(!isset($_SESSION['user'])){
        header('Location: /views/auth/login.php');
    }else if($_SESSION['user']['role'] === 'user'){
        header('Location: /');
    }
    $carManager = new CarManager();
    $carId = $_GET['id'];
    $oldCarId = $_GET['id'];
    $car = $carManager->findCarById($carId);
    $brand = $car['brand'];
    $model = $car['model'];
    $transmission = $car['transmission'];
    $fueltype = $car['fuel_type'];
    $imageURL = $car['image'];
    $seats = $car['passengers'];
    $price = $car['daily_price_huf'];
    $year = $car['year'];
    $errors = [];
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        if (empty(trim($_POST['brand']))) {
            $errors['brand'] = 'The car brand is required.';
        }
        if (empty(trim($_POST['model']))) {
            $errors['model'] = 'The car model is required.';
        }
        if (empty(trim($_POST['seats'])) || !ctype_digit($_POST['seats'])) {
            $errors['seats'] = 'Please enter a valid number of seats.';
        } elseif ((int)$_POST['seats'] < 1 || (int)$_POST['seats'] > 10) {
            $errors['seats'] = 'Seats must be between 1 and 10.';
    }
    if (empty(trim($_POST['year'])) || !ctype_digit($_POST['year'])) {
        $errors['year'] = 'Please enter a valid year.';
    } elseif ((int)$_POST['year'] < 1900 || (int)$_POST['year'] > date('Y')) {
        $errors['year'] = 'Please enter a year between 1900 and the current year.';
    }
    if (empty(trim($_POST['transmission']))) {
        $errors['transmission'] = 'Please select a transmission type.';
    }
    if (empty(trim($_POST['fueltype']))) {
        $errors['fueltype'] = 'Please select a fuel type.';
    }
    if (empty(trim($_POST['price'])) || !ctype_digit($_POST['price'])) {
        $errors['price'] = 'Please enter a valid price.';
    } elseif ((int)$_POST['price'] < 1000) {
        $errors['price'] = 'The price must be at least 1000 Ft.';
    }
    if (empty(trim($_POST['imageURL'])) || !filter_var($_POST['imageURL'], FILTER_VALIDATE_URL)) {
        $errors['imageURL'] = 'Please enter a valid image URL.';
    }
    
    $brand = trim($_POST['brand']);
    $model = trim($_POST['model']);
    $seats = (int)$_POST['seats'];
    $year = (int)$_POST['year'];
    $transmission = $_POST['transmission'];
    $fueltype = $_POST['fueltype'];
    $price = (int)$_POST['price'];
    $imageURL = trim($_POST['imageURL']);

    if (empty($errors)) {
        $carData = [
            'id' => $carId,
            'brand' => $brand,
            'model' => $model,
            'passengers' => $seats,
            'transmission' => $transmission,
            'fuel_type' => $fueltype,
            'daily_price_huf' => $price,
            'year' => $year,
            'image' => $imageURL
        ];
        
        $carManager->updateCar($carId, $carData);
        header('Location: /views/admin/car_success.php?carId=' . $carId . '&type=' . 'update');
        exit;
    }
}
?>
<main class="bg-w">
    <div class="container w-100 px-md-5 px-3 py-5 txt-b">
        <h1 class="text-center mt-4 mb-4">Edit Car</h1>
        <form action="edit_car.php?id=<?= $carId ?>" method="POST" class="border py-2 px-2 p-md-5 shadow rounded">
            <div class="mb-3">
                <label for="brand" class="form-label fs-5 fw-bold">Car Brand</label>
                <input type="text" class="form-control <?= isset($errors['brand']) ? 'bg-danger-subtle' : '' ?>" id="brand" name="brand" value="<?= htmlspecialchars($brand) ?>" >
                <?php if(isset($errors['brand'])): ?>
                    <span class="text-danger"><?= $errors['brand'] ?></span>
                <?php endif;?>
            </div>
            <div class="mb-3">
                <label for="model" class="form-label fs-5 fw-bold">Car Model</label>
                <input type="text" class="form-control <?= isset($errors['model']) ? 'bg-danger-subtle' : '' ?>" id="model" name="model" value="<?= htmlspecialchars($model) ?>" >
                <?php if(isset($errors['model'])): ?>
                    <span class="text-danger"><?= $errors['model'] ?></span>
                <?php endif;?>
            </div>
            <div class="mb-3">
                <label for="year" class="form-label fs-5 fw-bold">Number of year</label>
                <input type="number" class="form-control <?= isset($errors['year']) ? 'bg-danger-subtle' : '' ?>" id="year" name="year" value="<?= htmlspecialchars($year) ?>">
                <?php if(isset($errors['year'])): ?>
                    <span class="text-danger"><?= $errors['year'] ?></span>
                <?php endif;?>
            </div>
            <div class="mb-3">
                <label for="seats" class="form-label fs-5 fw-bold">Number of Seats</label>
                    <input type="number" class="form-control <?= isset($errors['seats']) ? 'bg-danger-subtle' : '' ?>" id="seats" name="seats" value="<?= htmlspecialchars($seats) ?>">
                <?php if(isset($errors['seats'])): ?>
                    <span class="text-danger"><?= $errors['seats'] ?></span>
                <?php endif;?>
            </div>
            <div class="mb-3">
                <label for="transmission" class="form-label fs-5 fw-bold" >Transmission</label>
                <select class="form-select <?= isset($errors['transmission']) ? 'bg-danger-subtle' : '' ?>" id="transmission" name="transmission" >
                    <option value="manual" <?= $transmission === 'manual' ? 'selected' : '' ?>>Manual</option>
                    <option value="automatic" <?= $transmission === 'automatic' ? 'selected' : '' ?>>Automatic</option>
                </select>
                <?php if(isset($errors['transmission'])): ?>
                    <span class="text-danger"><?= $errors['transmission'] ?></span>
                <?php endif;?>
            </div>
            <div class="mb-3">
                <label for="fueltype" class="form-label fs-5 fw-bold">Fuel Type</label>
                <select class="form-select <?= isset($errors['fueltype']) ? 'bg-danger-subtle' : '' ?>" id="fueltype" name="fueltype" >
                    <option value="petrol" <?= $fueltype === 'petrol' ? 'selected' : '' ?>>Petrol</option>
                    <option value="diesel" <?= $fueltype === 'diesel' ? 'selected' : '' ?>>Diesel</option>
                    <option value="electric" <?= $fueltype === 'electric' ? 'selected' : '' ?>>Electric</option>
                </select>
                <?php if(isset($errors['fueltype'])): ?>
                    <span class="text-danger"><?= $errors['fueltype'] ?></span>
                <?php endif;?>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label fs-5 fw-bold">Daily Price (Ft)</label>
                <input type="number" class="form-control <?= isset($errors['price']) ? 'bg-danger-subtle' : '' ?>" id="price" name="price" value="<?= htmlspecialchars($price) ?>" >
                <?php if(isset($errors['price'])): ?>
                    <span class="text-danger"><?= $errors['price'] ?></span>
                <?php endif;?>
            </div>
            <div class="mb-3">
                <label for="imageURL" class="form-label fs-5 fw-bold">Car Image URL</label>
                <input type="url" class="form-control <?= isset($errors['imageURL']) ? 'bg-danger-subtle' : '' ?>" id="imageURL" name="imageURL" value="<?= htmlspecialchars($imageURL) ?>" placeholder="Enter image URL" />
                <?php if(isset($errors['imageURL'])): ?>
                    <span class="text-danger"><?= $errors['imageURL'] ?></span>
                <?php endif;?>
            </div>
            <button type="submit" class="btn btn-primary w-100">Update Car</button>
        </form>
    </div>
</main>
<main class="bg-w">
    <div class="container w-100 px-md-5 px-3 py-5 txt-b d-flex align-items-center justify-content-center flex-column">
        <div class="border py-2 px-2 p-md-5 shadow rounded d-flex align-items-center justify-content-center flex-column">
            <h1 class="text-center mt-4 mb-4">Delete All Bookings of This Car</h1>
            <button class="btn px-4 py-2 del-btn btn-danger" data-id="<?= $carId?>" data-type="del-bookings">Delete Bookings</button>
        </div>    
    </div>
</main>
<div class="bg-w">
    <div class="contaner col-12 d-flex justify-content-center">
        <a href="/index.php" class="btn ms-2 text-black btn-warning fw-bold my-2">Main Page</a>
    </div>
</div>
<script src="../../assets/js/delete_entity.js"></script>
<?php include('../../includes/footer.php')?>