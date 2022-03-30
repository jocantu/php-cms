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
    if(isset($_GET['id'])&&isset($_GET['tbl'])){
        $id = $_GET['id'];
        $table = $_GET['tbl'];
        try {
            $stmnt=$pdo->prepare("DELETE FROM {$table} WHERE id=:id");
            $stmnt->execute([':id'=>$id]);
            if ($table=="users") {
                $stmnt=$pdo->prepare("DELETE FROM user_group_link WHERE user_id=:id");
                $stmnt->execute([':id'=>$id]);
            }
            if ($table=="groups") {
                $stmnt=$pdo->prepare("DELETE FROM user_group_link WHERE group_id=:id");
                $stmnt->execute([':id'=>$id]);
                $stmnt=$pdo->prepare("DELETE FROM pages WHERE group_id=:id");
                $stmnt->execute([':id'=>$id]);
            }
            if ($table == "user_group_link") {
                if (isset($_GET['group'])) {
                    $group_id = $_GET['group'];
                    redirect("admin_manage_users.php?id={$group_id}");
                } else {
                    redirect("admin.php?tab=group");
                }
            } else {
                redirect("admin.php?tab={$table}");
            }

            
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    } else {
        redirect('admin.php');
    }
?>