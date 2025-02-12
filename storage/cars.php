<?php
require_once 'storage.php';

class CarManager {
    private $storage;

    public function __construct() {
        $io = new JsonIO(__DIR__ . '/JSONfiles/cars.json');
        $this->storage = new Storage($io);
    }

    public function getAllCars() {
        return $this->storage->findAll();
    }

    public function addCar($carData) {
        return $this->storage->add($carData);
    }

    public function findCarById($id) {
        $cars = $this->getAllCars();
        foreach ($cars as $car) {
            if ($car['id'] == $id) { 
                return $car;
            }
        }
        return null;
    }
    

    public function updateCar($id, $carData) {
        $carData['id'] = $id; 
        $this->storage->update($id, $carData); 
    }
    

    public function deleteCar($id) {
        $this->storage->delete($id);
    }

    public function filterCarsByBrand($brand) {
        return $this->storage->findAll(['brand' => $brand]);
    }
}
