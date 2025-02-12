
<?php
  if (session_status() === PHP_SESSION_NONE) {
      session_start();
  }
  $redirectUrlToProfile = '/';
  if(isset($_SESSION['user'])){
    $user = $_SESSION['user'];
    if($user['role'] === 'admin'){
      $redirectUrlToProfile = '/views/admin/profile.php';
    }else{
      $redirectUrlToProfile = '/views/user/profile.php';
    }
  }else{
    $redirectUrlToProfile = '/views/auth/login.php';
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>iKarREntal</title>
    <link rel="icon" href="../assets/imgs/icon.png" />
    <link rel="icon" href="../../assets/imgs/icon.png" />
    <link rel="stylesheet" href="../../assets/style/style.css" />
    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
      crossorigin="anonymous"
    />
  </head>
  <body>
    <header class="position-fixed w-100 z-3 shadow-lg">
      <div class="d-flex align-items-center justify-content-between">
        <div class="ms-2 ms-lg-5 ps-lg-2 fs-md-0 d-flex fs-2 flex-row">
          <a
            href="../../index.php"
            class="logo text-black link-offset-2 link-underline link-underline-opacity-0"
            >iKarRental</a
          >
        </div>
        <div class="d-flex flex-row">
          <div class="fdc pe-2 me-lg-0 cursor-pointer">
            <i class="bx bxs-moon fs-2" title="Dark Mode" id="bx-moon"></i
            ><i class="bx bxs-sun fs-2" title="Light Mode" id="bx-sun"></i>
          </div>
          <?php if(isset($_SESSION['user'])):?>
            <a href="/views/auth/logout.php" title="Log Out" class="text-decoration-none me-lg-2 text-black fs-2 me-1 d-flex align-items-center"><i class='bx bx-log-out'></i></a>
            <a href="<?= $redirectUrlToProfile ?>" title="My Profile" class="text-decoration-none me-lg-3 text-black fs-2 me-2 d-flex align-items-center"><i class='bx bx-user-circle'></i></a>
          <?php endif; ?>
          <?php if(!isset($_SESSION['user'])):?>
            <a href="/views/auth/login.php?redirect=<?=urlencode($_SERVER['REQUEST_URI'])?>" class="btn btn-sm btn-light me-lg-2 me-1">Log in</a>
            <a href="/views/auth/register.php" class="btn btn-sm btn-dark  ms-2 me-lg-3 me-1">
              Register
            </a>
          <?php endif; ?>
        </div>
      </div>
      <div id="alert-container" class="position-fixed top-0 start-50 translate-middle-x mt-3" style="z-index: 4055; width: 80%; max-width: 500px;"></div>

    </header>