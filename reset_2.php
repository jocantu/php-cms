<?php include "includes/init.php"; ?>
<?php
    if($_GET['user']) {
        $username = $_GET['user'];
        $vcode = $_GET['code'];
        if(count_field_val($pdo, "users", "username", $username)>0) {
            $row=return_field_data($pdo, "users", "username", $username);
            if ($vcode!=$row['validationcode']) {
                set_message("El codigo no es valido");
                redirect('index.php');
            }
        } else {
            set_message("Usuario no encontrado");
                redirect('index.php');
        }
    } else {
        set_message("Solicitud sin usuario  ");
        redirect('index.php');
    }
    if ($_SERVER['REQUEST_METHOD']=='POST') {
        try {
            $password = $_POST['password'];
            $password_confirm = $_POST['password_confirm'];
            echo $password_confirm;
            echo "<br>".$password;
            if($password==$password_confirm){
                $password=$_POST['password'];
                $stmnt = $pdo->prepare("UPDATE users SET password=:password WHERE username=:username");
                $user_data=[':password'=>password_hash($password, PASSWORD_BCRYPT), ':username'=>$username];
                $stmnt->execute($user_data);
                set_message("Contrasena restablecida, favor de iniciar sesion", "success");
                redirect('login.php');
            } else {
                set_message("La contrasena no coincide, internar de nuevo");
            }
            
        } catch (PDOEXCEPTION $e) {
            echo "Error: ". $e->getMessage();
        }
    }   
?>
<!DOCTYPE html>
<html lang="en">
    <?php include "includes/header.php" ?>
    <body>
        <?php include "includes/nav.php" ?>

        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-lg-offset-3">
                    <?php 
                        show_message(); 
                    ?>

                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="panel panel-login">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form id="register-form" method="post" role="form" >
                                        <div class="form-group">
                                            <input type="password" name="password" id="password" tabindex="5" class="form-control" placeholder="Contrasena"  required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="password_confirm" id="confirm-password" tabindex="6" class="form-control" placeholder="Confirmar contrasena"  required>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-6 col-sm-offset-3">
                                                    <input type="submit" name="reset-submit" id="register-submit" tabindex="4" class="form-control btn btn-custom" value="Cambiar contrasena">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include "includes/footer.php" ?>
    </body>
</html>