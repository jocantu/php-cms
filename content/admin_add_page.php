<?php include("../includes/init.php");?>
<?php 
    if($_SERVER['REQUEST_METHOD']=='POST') {
        $name = $_POST['name'];
        $url = $_POST['url'];
        $group_id = $_POST['group_id'];
        $descr = $_POST['descr'];

        if(strlen($name)<3) {
            $error[]="El nombre del grupo debe tener por lo menos 3 caracteres.";
        }
        if(strlen($url)<3) {
            $error[]="La URL debe tener por lo menos 3 caracteres.";
        }
        if(count_field_val($pdo, "groups", "id", $group_id)==0) {
            $error[]="ID de grupo no encontrada en la tabla de grupos";
        }
        if(!isset($error)) {
            try {
                $stmnt=$pdo->prepare("INSERT INTO pages (name, url, group_id, descr) VALUES (:name, :url, :group_id, :descr)");
                $stmnt->execute([":name"=>$name, ":url"=>$url, ":group_id"=>$group_id, ":descr"=>$descr]);
                set_message("La pagina {$name} fue creada","success");
                redirect("admin.php?tab=pages");
            } catch (PDOException $e) {
                echo "Error: ".$e->getMessage();
            }
        }

    } else {
        $name = "";
        $url = "";
        $group_id = "";
        $descr = "";
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
                                            <input type="text" name="name" id="name" tabindex="1" class="form-control" placeholder="Nombre de la Pagina" value="<?php echo $name ?>" required >
                                        </div>
                                        <div class="form-group">
                                            <input type="text" name="url" id="url" tabindex="2" class="form-control" placeholder="URL (ejemplo.php)" value="<?php echo $url ?>" required >
                                        </div>
                                        <div class="form-group">
                                            <select name='group_id' id='group_id' class='form-control' required>
                                                <?php 
                                                    try {
                                                        $res = $pdo->query("SELECT id, name FROM groups ORDER BY name");
                                                        foreach($res as $row) {
                                                            echo "<option value='{$row['id']}'>{$row['name']}</option>";
                                                        }
                                                    } catch (PDOException $e) {
                                                        echo "Error: ". $e->getMessage();
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <textarea name="descr" id="descr" tabindex="8" class="form-control" placeholder="Descripcion"><?php echo $descr ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-6 col-sm-offset-3">
                                                    <input type="submit" name="register-submit" id="register-submit" tabindex="4" class="form-control btn btn-custom" value="Agregar Pagina">
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
