<?php
    include('../../includes/header.php');
    require_once('../../storage/storage.php');
    $io = new JsonIO('../../storage/JSONfiles/users.json');
    $usersStorage = new Storage($io);
    $name = $password = $repeatPassword= $email = '';
    $errors = [];
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        if(!isset($_POST['name']) || trim($_POST['name']) ===''){
            $errors['name'] = 'PLease enter your name!';
        }elseif(str_word_count($_POST['name']) < 2){
            $errors['name'] = 'A name must contain at least two words!'; 
        }
        if(!isset($_POST['email']) || trim($_POST['email']) === ''){
            $errors['email'] = 'PLease enter your email!';
        }elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            $errors['email'] = 'Please enter a valid email address!';
        }
        if(!isset($_POST['password']) || trim($_POST['password']) === ''){
            $errors['password'] = 'PLease enter a password!';
        }
        if(!isset($_POST['repeatPassword']) || trim($_POST['repeatPassword']) === ''){
            $errors['repeatPassword'] = 'PLease enter the password again!';
        }elseif($_POST['repeatPassword'] !== $_POST['password']){
            $errors['repeatPassword'] = 'The repeated password does not match!';
        }
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $repeatPassword = $_POST['repeatPassword'];

        if(empty($errors)){
            $userId = $usersStorage->add([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'role' => 'user'
            ]);
            header('Location: login.php?id=' . $userId);
        }
    }
?>
<main class="w-100">
    <section class="min-vh-100 bg-w d-flex align-items-center pt-4" >
        <div class="row w-100 container-fluid gx-0 gy-0 d-flex align-items-center w-100 pt-5 py-5">
            <div class="col-12">
                <h1 class="text-center txt-b mb-4 fw-bold">Sign up</h1>
            </div>
            <div class="col-12 col-md-6 d-flex justify-content-center">
                <img src="/assets/imgs/register.png" class="register-img" alt="Register icon">
            </div>
            <div class="col-12 col-md-6 d-flex jcc">
                <form action="register.php" method="post" class="w-75">
                    <!-- Name -->
                    <div class="mb-3">
                        <p class="fs-4 txt-b fw-bold">Name</p>
                        <input type="text" name="name" class="form-control" value="<?= $name ?>" placeholder="Your Name">
                        <?php if(isset($errors['name'])): ?>
                            <span class="text-danger"><?= $errors['name'] ?></span>
                        <?php endif; ?>
                    </div>
                    <!-- Email -->
                    <div class="mb-3">
                        <p class="fs-4 txt-b fw-bold">Email</p>
                        <input type="text" name="email" class="form-control" value="<?= $email ?>" placeholder="Your Email">
                        <?php if(isset($errors['email'])): ?>
                            <span class="text-danger"><?= $errors['email'] ?></span>
                        <?php endif; ?>
                    </div>
                    <!-- Password -->
                    <div class="mb-3">
                        <p class="fs-4 txt-b fw-bold">Password</p>
                        <input type="password" name="password" class="form-control" value="<?= $password ?>" placeholder="Password">
                        <?php if(isset($errors['password'])): ?>
                            <span class="text-danger"><?= $errors['password'] ?></span>
                        <?php endif; ?>
                    </div>
                    <!-- Repeat Password -->
                    <div class="mb-3">
                        <p class="fs-4 txt-b fw-bold">Repeat Password</p>
                        <input type="password" name="repeatPassword" class="form-control" value="<?= $repeatPassword ?>" placeholder="Repeat your password">
                        <?php if(isset($errors['repeatPassword'])): ?>
                            <span class="text-danger"><?= $errors['repeatPassword'] ?></span>
                        <?php endif; ?>
                    </div>
               
                    <!-- Submit -->
                    <button type="submit" class="btn btn-primary w-100">Register</button>
                </form>
            </div>
            <div class="col-12 d-flex justify-content-center my-3 mt-lg-5">
                <!-- <button  class="btn bg-d me-2 back-btn text-light btn-outline-light fw-bold ">Back</button> -->
                <a href="/index.php" class="btn ms-2 text-black btn-warning fw-bold ">Main Page</a>
            </div>
        </div>
    </section>

</main>
<?php include('../../includes/footer.php')?>