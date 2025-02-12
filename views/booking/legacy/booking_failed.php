<!-- 
    !!          !!          !!          !!          !!          !!          !!          !!
    !! This PHP file is no longer used after implementing AJAX for booking handling     !!
    !!          !!          !!          !!          !!          !!          !!          !!
-->
<?php include('../../includes/header.php')?>

<main class="container-fluied w-100">
    <section class="vh-100 bg-w d-flex  align-items-center">
        <div class="row container-fluid gx-0 gy-0 d-flex justify-content-center w-100">
            <div class="col-12 d-flex justify-content-center booking-result-img">
                <img src="/assets/imgs/failed_img.png" id="booking-result-img" class="booking-result-img" alt="Failed Booking img">
            </div>
            <div class="col-12 d-flex justify-content-center pt-2">
                <p class=" fs-2 txt-b">Booking Failed!</p>
            </div>
            <div class="col-12 d-flex justify-content-center">
            <a href="/index.php" class="btn bg-d text-light btn-outline-light fw-bold ">Back to Main Page</a>
            </div>
        </div>
    </section>
</main>

<?php include('../../includes/footer.php')?>