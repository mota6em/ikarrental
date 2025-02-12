<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once('../../storage/storage.php');
    $carManager = new Storage(new JsonIO('../../storage/JSONfiles/cars.json'));
    $bookingsManager = new Storage(new JsonIO('../../storage/JSONfiles/bookings.json'));
    $usersManager = new Storage(new JsonIO('../../storage/JSONfiles/users.json'));
    $input = json_decode(file_get_contents('php://input'), true);
    $startDate = $input['startDate'] ?? null;
    $endDate = $input['endDate'] ?? null;
    $carId = $input['carId'] ?? null;
    $userId = $_SESSION['user']['id'] ?? null;
    $userEmail = $usersManager->findById($userId)['email'];
    $car = $carManager->findById($carId);

    if ($startDate && $endDate && $carId && $userId) {
        if(strtotime($startDate) > strtotime($endDate)) {
            echo json_encode(['success' => false, 'message' => 'The end date must be later than the start date.']);
            return;
        }
        $bookingsManager->add([
            "carId" => $carId,
            "userId" => $userId,
            "userEmail" => $userEmail,
            "startDate" => $startDate,
            "endDate" => $endDate
        ]);
        echo json_encode(['success' => true, 'message' => '<span class="fw-900 fs-5">Your booking for the <span class="fw-bold txt-g">' . $car['brand'] . ' ' . $car['model'] . '</span> car has been successfully confirmed 
                    for the period:<br><span class="fw-bold num-font">' . $startDate . ' / ' . $endDate . '</span>.
                    <br>
                    You can track the status of your booking on your profile page. </span>']);
    } elseif(!$startDate) {
        echo json_encode(['success' => false, 'message' => 'Invalid start date!']);
    } elseif(!$endDate) {
        echo json_encode(['success' => false, 'message' => 'Invalid end date!']);
    }else{
        echo json_encode(['success' => false, 'message' => 'Invalid input dates!']);
    }
}
?>
