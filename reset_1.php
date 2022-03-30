<?php include "includes/init.php"; ?>
<?php
    if($_SERVER['REQUEST_METHOD']=='POST') {
        $username = $_POST['username'];
        if(count_field_val($pdo, "users", "username", $username)>0) {
            $row = return_field_data($pdo, "users","username", $username);
            $body = "Para reestablecer tu contrasena accede al siguiente enlace.\r\n
                http://{$_SERVER['SERVER_NAME']}/{$root_directory}/reset_2.php?user={$username}&code={$row['validationcode']}";
            send_email($row['email'], "Restablecer contrasena", $body, $from_email, $reply_email);
        } else {
            set_message("El usuario '{$username}' no existe");
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
			    <div class="col-md-6 col-md-offset-3">
                    <?php 
                        show_message();
                    ?>
				    <div class="panel panel-login">
					    <div class="panel-body">
						    <div class="row">
							    <div class="col-lg-12">
                                    <h3 class="text-center">Restablecer contrasena</h3>
								    <form id="login-form"  method="post" role="form" style="display: block;">
									    <div class="form-group">
										    <input type="text" name="username" id="username" tabindex="1" class="form-control" placeholder="Nombre de usuario" required>
									    </div>
									    
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-6 col-sm-offset-3">
                                                    <input type="submit" name="reset-submit" id="login-submit" tabindex="4" class="form-control btn btn-custom" value="Restablecer">
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