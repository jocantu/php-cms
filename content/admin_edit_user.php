<?php include("../includes/init.php");?>
<?php 
    if(logged_in()) {
        $username=$_SESSION['username'];
        if (!verify_user_group($pdo, $username, "Admin")){
            set_message("Este usuario no tiene acceso a este contenido");
            redirect('../index.php');
        }
    } else {
        set_message('Inicia sesion e intenta de nuevo');
        redirect('../index.php');
    }
?>
<?php 
    if(isset($_GET['id'])){
        $user_id = $_GET['id'];
        if(count_field_val($pdo, "users", "id", $user_id) >0 ){
            $row=return_field_data($pdo, "users", "id", $user_id);
            $fname = $row['firstname'];
            $lname = $row['lastname'];
            $uname = $row['username'];
            $comments = $row['comments'];
        } else {
            redirect('admin.php');
        };
       


    } else {
        redirect('admin.php');
    }

    if($_SERVER['REQUEST_METHOD']=="POST") {
        $fname = $_POST['firstname'];
        $lname = $_POST['lastname'];
        $comments = $_POST['comments'];

        if(strlen($fname)<3) {
            $error[] = "El apellido no puede tener menos de tres letras";
        }
        if(strlen($uname)<6) {
            $error[] = "El nombre de usuario debe te tener por lo menos 6 caracteres";
        }

        if (!isset($error)) {
            try {
                $sql = "UPDATE users SET firstname=:firstname, lastname=:lastname,  comments=:comments WHERE id=:id";
                $stmnt = $pdo->prepare($sql);
                $user_data = [':firstname'=>$fname,':lastname'=>$lname,':comments'=>$comments, ':id'=>$user_id];
                $stmnt->execute($user_data);
                redirect('admin.php');
                } catch (PDOException $e) {
                echo "ERROR: ". $e->getMessage();
            }
        } 
    } 
?>

<!DOCTYPE html>
<html lang="en">
    <?php include "../includes/header.php" ?>
    <body>
        <?php include "../includes/nav.php" ?>

        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-lg-offset-3">
                    <?php 
                        if(isset($error)) {
                            foreach($error as $msg) {
                                echo "<h4 class='bg-danger text-center'>{$msg}</h4>";
                            }
                        }
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
                                            <input type="text" name="firstname" id="firstname" tabindex="1" class="form-control" placeholder="First Name" value="<?php echo $fname;?>" required >
                                        </div>
                                        <div class="form-group">
                                            <input type="text" name="lastname" id="lastname" tabindex="2" class="form-control" placeholder="Last Name" value="<?php echo $lname;?>" required >
                                        </div>
                                        <div class="form-group">
                                            <textarea name="comments" id="comments" tabindex="7" class="form-control" placeholder="Comments" ><?php echo $comments;?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-6 col-sm-offset-3">
                                                    <input type="submit" name="update-submit" id="update-submit" tabindex="4" class="form-control btn btn-custom" value="Guardar Usuario">
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
        <?php include "../includes/footer.php" ?>
    </body>
</html>