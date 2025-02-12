<!-- 
    !!          !!          !!          !!          !!          !!          !!          !!
    !! This PHP file is no longer used after implementing AJAX for booking handling     !!
    !!          !!          !!          !!          !!          !!          !!          !!
-->
<?php
    require_once('../../storage/cars.php');
    require_once('../../storage/storage.php');
    $carManeger = new CarManager();
    $bookingsManeger = new Storage(new JsonIO('../../storage/JSONfiles/bookings.json'));
    $carId = isset($_GET['id']) ?$_GET['id'] : null;
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $userId = $_SESSION['user']['id'] ?? null;
    $startDate = isset($_GET['startDate']) ?$_GET['startDate'] : null;
    $endDate = isset($_GET['endDate']) ?$_GET['endDate'] : null;
    $errors = [];
    $car = $carManeger->findCarById($carId);
    if ($carId && $userId && $startDate && $endDate) {
        $bookingsManeger->add([
            "carId" => $carId,
            "userId" => $userId,
            "startDate" => $startDate,
            "endDate" => $endDate
        ]);
        header('Location: booking_success.php?id=' . $carId . '&startDate=' . $startDate . '&endDate=' . $endDate);
        exit;
    } else {
        header('Location: booking_failed.php');
        exit;
    }
    

?>
