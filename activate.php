<?php include "includes/init.php" ?>

<?php 
    if(isset($_GET['user'])) {
        $user = $_GET['user'];
        if(isset($_GET['code'])){
            $code = $_GET['code'];
            $db_code = get_validationcode($user, $pdo);
            if ($code == $db_code) {
                try {
                    $sql="UPDATE users SET active='true' WHERE username=:username";
                    $stmnt = $pdo->prepare($sql);
                    $stmnt->execute([':username'=>$user]);
                    set_message("Cuenta activada, favor de iniciar sesion","success");
                    redirect('index.php');
                } catch (PDOException $e) {
                    return $e->getMessage();
                }
            } else {
                set_message("Codigo de validacion incorrecto");
                redirect('index.php');
            }
        } else {
            set_message("Enlace de activacion no valido.");
        }
    } else {
        set_message("Enlace de activacion no valido.");
        redirect('index.php');
    }
?>