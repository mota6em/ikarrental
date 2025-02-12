<?php 
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }   
    include('../../includes/header.php');
    require_once('../../storage/storage.php');
    $io = new JsonIO('../../storage/JSONfiles/users.json');
    $usersStorage = new Storage($io);
    $password = $email = '';
    $errors = [];
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        if(!isset($_POST['email']) || trim($_POST['email']) === ''){
            $errors['email'] = 'PLease enter your email!';
        }elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            $errors['email'] = 'Please enter a valid email address!';
        }
        if(!isset($_POST['password']) || trim($_POST['password']) === ''){
            $errors['password'] = 'PLease enter the password!';
        }
        $email = $_POST['email'];
        $password = $_POST['password'];

        if(empty($errors)){
            $users = $usersStorage->findAll();
            $user = array_filter($users, function($user) use($email, $password){
                return $user['email'] === $email && $password=== $user['password'];
            });
            if(!empty($user)){
                $loggedInUser = reset($user);
                $_SESSION['user'] = [
                    'id' => $loggedInUser['id'],
                    'email' => $loggedInUser['email'],
                    'name' => $loggedInUser['name'],
                    'role' => $loggedInUser['role']
                ];                
                $redirectUrl = isset($_GET['redirect']) ? $_GET['redirect'] : '/';
                header('Location: ' . $redirectUrl);
                exit();
            }else{
                $errors['general'] = 'Invalid email or password!';
            }
        }
    }    
?>
<main class="w-100">
    <section class="min-vh-100 bg-w d-flex flex-column align-items-center pt-4" >
        <div class="row w-100 container-fluid gx-0 gy-0 d-flex align-items-center w-100 pt-5 py-5">
            <div class="col-12">
                <h1 class="text-center txt-b mb-4 fw-bold">Log in</h1>
            </div>
            <?php if(isset($_GET['id'])):?>
                <div class="col-12 d-flex align-items-center justify-content-center">
                    <div class="alert alert-success text-center fw-bold w-75" role="alert">
                        Registration successful! Please log in to continue.
                    </div>
                </div>
            <?php endif;?>
            <div class="col-12 col-md-6 d-flex justify-content-center">
                <img src="../../assets/imgs/login.png" class="register-img" alt="Register icon">
            </div>
            <div class="col-12 col-md-6 d-flex jcc mt-5" >
                <form method="POST" action="login.php" class="w-50">
                    <div class="mb-3">
                        <p class="fs-4 txt-b  fw-bold">Email</p>
                        
                        <input type="text" value="<?= $email ?>" name="email" class="form-control" placeholder="Your Email" >
                        <?php if(isset($errors['email'])): ?>
                            <span class="text-danger"><?= $errors['email'] ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <p class="fs-4 txt-b fw-bold">Password</p>
                        <input type="password" name="password" value="<?= $password ?>" class="form-control" placeholder="Password" >
                        <?php if(isset($errors['password'])): ?>
                            <span class="text-danger"><?= $errors['password'] ?></span>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
            </div>
            <?php if(isset($errors['general'])):?>
                <div class="col-12 d-flex mt-3 align-items-center justify-content-center">
                    <div class="alert alert-danger text-center fw-bold w-75" role="alert">
                        <?= $errors['general'] ?>
                    </div>
                </div>
            <?php endif;?>
            <div class="col-12 d-flex justify-content-center my-3 mt-lg-5">
                <!-- <button  class="btn back-btn bg-d me-2 text-light btn-outline-light fw-bold ">Back</button> -->
                <a href="/index.php" class="btn ms-2 text-black btn-warning fw-bold ">Main Page</a>
            </div>
        </div>
    </section>

</main>
<?php include('../../includes/footer.php')?>