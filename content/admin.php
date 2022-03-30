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
<!DOCTYPE html>
<html lang="en">
    <?php include "../includes/header.php" ?>
    <body>
        <?php include "../includes/nav.php" ?>

        <div class="container">
            <?php 
                show_message();
            ?>
            <h1 class="text-center">Admin</h1>
            <ul class="nav nav-tabs">
                  <li id="users" class="tab-label active"><a href="#">Users</a></li>
                  <li id="groups" class="tab-label"><a href="#">Groups</a></li>
                  <li id="pages" class="tab-label"><a href="#">Pages</a></li>
            </ul>
            <div id='tab-users' class='tab-content'>
            <?php 
                try {
                            $result = $pdo->query("SELECT id, firstname, lastname, username, email, active, joined, last_login FROM users ORDER BY firstname");
                        if ($result ->rowCount() > 0 ) {
                            echo "<table class='table'>";
                            echo "<tr><th>Nombre</th><th>Apellido</th><th>Usuario</th><th>Email</th><th>Activado</th><th>Fecha registro</th><th>Ultima sesion</th><tr>";
                            foreach ($result as $row) {
                                if ($row['active']) {
                                    $active = 'Si';
                                    $action = 'Desactivar';
                                } else {
                                    $active = 'No';
                                    $action='Activar';
                                }
                                echo "<tr><td>{$row['firstname']}</td><td>{$row['lastname']}</td><td>{$row['username']}</td><td>{$row['email']}</td><td>{$active}</td><td>{$row['joined']}</td><td>{$row['last_login']}</td><td><a href='admin_deactivate_user.php?id={$row['id']}'>{$action}</a></td><td><a href='admin_edit_user.php?id={$row['id']}'>Editar</a></td></td><td><a class='confirm-delete' href='admin_delete.php?id={$row['id']}&tbl=users'>Eliminar</a></td></tr>";
                            }
                            echo "</table>";
                        } else {
                            echo "No hay usuarios en la tabla \'usuarios\'";
                        }
                    } catch (PDOException $e) {
                        echo "Hubo un error <br><br>".$e->getMessage();
                    }
                ?> 
            </div>
            <div id='tab-groups' class='tab-content'>
                <?php 
                    try {
                        $result = $pdo->query("SELECT id, name, descr FROM groups ORDER BY name");
                        if ($result ->rowCount() > 0 ) {
                            echo "<table class='table'>";
                            echo "<tr><th>ID</th><th>Nombre</th><th># de usuarios</th><th># de paginas</th><th>Descripcion</th><tr>";
                            foreach ($result as $row) {
                                $user_count =  count_field_val($pdo, "user_group_link", "group_id", $row['id']);
                                $page_count =  count_field_val($pdo, "pages", "group_id", $row['id']);
                                $row = clean_array($row);
                                echo "<tr><td>{$row['id']}</td><td>{$row['name']}</td><td>{$user_count}</td><td>{$page_count}</td><td>{$row['descr']}</td><td><a href='admin_manage_users.php?id={$row['id']}'>Manage Users</a></td><td><a class='confirm-delete' href='admin_delete.php?id={$row['id']}&tbl=groups'>Eliminar</a></td></td><td><a href='admin_edit_group.php?id={$row['id']}'>Editar</a></td></tr>";
                            }
                            echo "</table>";
                        } else {
                            echo "Aun no hay grupos creados<br>";
                        }
                    } catch (PDOException $e) {
                        echo "Hubo un error <br><br>".$e->getMessage();
                    }
                ?> 
                <a href='admin_add_group.php' class="btn btn-success">Add Group</a>
            </div>
            <div id='tab-pages' class='tab-content'>
                <?php 
                    try {
                        $result = $pdo->query("SELECT id, name, url, group_id, descr FROM pages ORDER BY name");
                        if ($result ->rowCount() > 0 ) {
                            echo "<table class='table'>";
                            echo "<tr><th>ID</th><th>Nombre</th><th>URL</th><th>Grupo</th><th>Descripcion</th><tr>";
                            foreach ($result as $row) {
                                $group_row = return_field_data($pdo, "groups", "id", $row['group_id']);
                                echo "<tr><td>{$row['id']}</td><td>{$row['name']}</td><td>{$row['url']}</td><td>{$group_row['name']}</td><td>{$row['descr']}</td><td><a class='confirm-delete' href='admin_delete.php?id={$row['id']}&tbl=pages'>Eliminar</a></td><td><a href='admin_edit_page.php?id={$row['id']}'>Editar</a></td></tr>";
                            }
                            echo "</table>";
                        } else {
                            echo "Aun no hay paginas creadas<br>";
                        }
                    } catch (PDOException $e) {
                        echo "Hubo un error <br><br>".$e->getMessage();
                    }
                ?> 
                <a href='admin_add_page.php' class="btn btn-success">Add Page</a>
            </div>
        </div> <!--Container-->
        <?php include "../includes/footer.php" ?>
        <script>
            $(".confirm-delete").click(function(e){
                if(!confirm("Confirma accion de 'Borrar'")){
                    e.preventDefault();
                }
            })
            if(getParameterByName('tab')) {
                gotoTab(getParameterByName('tab'));
            } else {
                gotoTab("users");
            }
            $(".tab-label").click(function(){
                gotoTab($(this).attr('id'));
            });
            function gotoTab(label){
                var current_tab="#tab-"+label;
                console.log("'"+current_tab+"'");
                $(".tab-content").hide();
                $(".tab-label").removeClass("active");
                $(current_tab).show();
                $("#"+label).addClass("active");
            }
        </script>
    </body>
</html>