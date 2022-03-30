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
        $row=return_field_data($pdo, "users", "id", $user_id);
        if ($row['active']==false){
            $active='true';
        } else {
            $active='false';
        }
        try {
            $stmnt=$pdo->prepare("UPDATE users SET active={$active} WHERE id=:id");
            $stmnt->execute([':id'=>$user_id]);
            redirect('admin.php');
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    } else {
        redirect('admin.php');
    }
?>