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
        $group_id = $_GET['id'];
    } else {
        redirect('admin.php');
    }
    if($_SERVER['REQUEST_METHOD']=='POST') {
        $user_id = $_POST['user_id'];
        
        try {
            $stmnt=$pdo->prepare("INSERT INTO user_group_link (user_id, group_id) VALUES (:user_id, :group_id)");
            $stmnt->execute([":user_id"=>$user_id, ":group_id"=>$group_id]);
            set_message("El usuario '{$user_id}' fue agregado al grupo}","success");
            // redirect("admin.php?tab=groups");
        } catch (PDOException $e) {
            echo "Error: ".$e->getMessage();
        }


    }
?>
<!DOCTYPE html>
<html lang="en">
    <?php include "../includes/header.php" ?>
    <body>
        <?php include "../includes/nav.php" ?>

        <div class="container">
            <?php 
                show_message();
            ?>
            <div class="row">
                <div class="col-lg-10 col-lg-offset-1">
                    <?php
                        try {
                                $result = $pdo->query("SELECT id, user_id FROM user_group_link WHERE group_id = {$group_id}");
                            if ($result ->rowCount() > 0 ) {
                                echo "<table class='table'>";
                                echo "<tr><th>ID</th><th>Nombre de usuario</th><th>Nombre</th><tr>";
                                foreach ($result as $row) {
                                    $user_row = return_field_data($pdo, "users", "id", $row['user_id']);
                                    echo "<tr><td>{$user_row['id']}</td><td>{$user_row['username']}</td><td>{$user_row['firstname']}</td></td><td><a class='confirm-delete' href='admin_delete.php?id={$row['id']}&tbl=user_group_link&group={$group_id}'>Eliminar</a></td></tr>";

                                }
                                echo "</table>";
                            } else {
                                echo "No hay usuarios en este grupo \'usuarios\'";
                            }
                        } catch (PDOException $e) {
                            echo "Hubo un error <br><br>".$e->getMessage();
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
                                            <select name="user_id" id="user_id" tabindex="8" class="form-control">
                                                <?php 
                                                    try {
                                                        $res = $pdo->query("SELECT id, username FROM users ORDER BY username");
                                                        foreach($res as $row) {
                                                            $user_row=return_field_data($pdo, "users", "id", $row['id']);
                                                            $group_row=return_field_data($pdo, "groups", "id", $group_id);
                                                            if (!verify_user_group($pdo, $user_row['username'], $group_row['name'])){
                                                                echo "<option value={$row['id']}>{$row['username']}</option>";
                                                            }
                                                            
                                                        }
                                                    } catch (PDOException $e) {
                                                        echo "Error: ". $e->getMessage();
                                                    }
                                                ?>
                                            </Select>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-6 col-sm-offset-3">
                                                    <input type="submit" name="manage-submit" id="manage-submit" tabindex="4" class="form-control btn btn-custom" value="Agregar Usuario a Grupo">
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
