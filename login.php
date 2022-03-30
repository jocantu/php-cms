<?php include "includes/init.php" ?>
<?php 
    if($_SERVER['REQUEST_METHOD']=='POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];
        if (isset($_POST['remember'])) {
            $remember = "on";
        } else {
            $remember = "off";
        }
        if (count_field_val($pdo, "users", "username", $username)>0) {
            set_message("Usuario encontrado");
            $user_data = return_field_data($pdo, "users", "username", $username);
            if ($user_data['active']=='true') {
                if (password_verify($password, $user_data['password'])) {
                    set_message("Inicio de sesion exitoso","success");
                    update_login_date($pdo, $username);
                    $_SESSION['username']=$username;
                    if ($remember == "on") {
                        setcookie("username", $username, time()+86400, "/", null, false, true);
                    }
                    redirect("mycontent.php");
                } else {
                    set_message("Contrasena incorrecta");
                }
            } else {
                set_message("Cuenta no activada");
            }
        } else {
            set_message("Datos incorrectos");
        }

    } else {
        $username="";
        $password="";
    }
?>

<!DOCTYPE html>
<html lang="en">
    <?php include "includes/header.php" ?>
    <body>
        <?php include "includes/nav.php" ?>
        <div class="container">
    	    <div class="row">
			    <div class="col-md-6 col-md-offset-3">
                    <?php 
                        show_message();
                    ?>
				    <div class="panel panel-login">
					    <div class="panel-body">
						    <div class="row">
							    <div class="col-lg-12">
								    <form id="login-form"  method="post" role="form" style="display: block;">
									    <div class="form-group">
										    <input type="text" name="username" id="username" tabindex="1" class="form-control" placeholder="Nombre de usuario" value="<?php echo $username; ?>"required>
									    </div>
									    <div class="form-group">
										    <input type="password" name="password" id="login-
										password" tabindex="2" class="form-control" placeholder="Contrasena" value="<?php echo $password; ?>" required>
                                        </div>
                                        <div class="form-group text-center">
                                            <input type="checkbox" tabindex="3" class="" name="remember" id="remember">
                                            <label for="remember">Recordar datos</label>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-6 col-sm-offset-3">
                                                    <input type="submit" name="login-submit" id="login-submit" tabindex="4" class="form-control btn btn-custom" value="Iniciar Sesion">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="text-center">
                                                        <a href="reset_1.php" tabindex="5" class="forgot-password">Restablecer conrasena</a>
                                                    </div>
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