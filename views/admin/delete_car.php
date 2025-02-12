<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once('../../storage/storage.php');
    $bookingsManager = new Storage(new JsonIO('../../storage/JSONfiles/bookings.json'));
    $carsManager = new Storage(new JsonIO('../../storage/JSONfiles/cars.json'));
    $bookings = $bookingsManager->findAll();
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'] ?? null;
    $type = $input['type'] ?? null;

    if ($id && $type) {
        if($type === 'car'){
            $carsManager->delete($id);
            echo json_encode(['success' => true, 'message' => 'Car deleted successfully.']);
        }elseif($type === 'booking'){
            $bookingsManager->delete($id);
            echo json_encode(['success' => true, 'message' => 'Booking deleted successfully.']);
        }elseif($type === 'del-bookings'){
            foreach($bookings as $booking){
                if($booking['carId'] === $id){
                    $bookingsManager->delete($booking['id']);
                }
            }
            echo json_encode(['success' => true, 'message' => 'All Bookings of The Car are deleted successfully.']);
        }
        else{
            echo json_encode(['success' => false, 'message' => 'Invalid type specified.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid input data.']);
    }
}
?>
