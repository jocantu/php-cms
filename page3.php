<?php include "includes/init.php" ?>

<!DOCTYPE html>
<html lang="en">
    <?php include "includes/header.php" ?>
    <body>
        <?php include "includes/nav.php" ?>

        <div class="container">
            <?php 
                show_message();
            ?>
            <h1 class="text-center">Page 3</h1>
            <p>"Nulla metus metus, ullamcorper vel, tincidunt sed, euismod in, nibh. Quisque volutpat condimentum velit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nam nec ante. Sed lacinia, urna non tincidunt mattis, tortor neque adipiscing diam, a cursus ipsum ante quis turpis. Nulla facilisi. Ut fringilla. Suspendisse potenti. Nunc feugiat mi a tellus consequat imperdiet. Vestibulum sapien. Proin quam. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."</p>
        <?php 
            echo generate_token()."<br>";
            echo count_field_val($pdo, "users", "username", "arodriguez")."<br>";
        ?>
        <?php 
            try {
                    $result = $pdo->query("SELECT firstname, lastname, username, password FROM users");
                if ($result ->rowCount() > 0 ) {
                    echo "<table class='table'>";
                    echo "<tr><th>Nombre</th><th>Apellido</th><th>Usuario</th><th>Contrasena</th></tr>";
                    foreach ($result as $row) {
                        echo "<tr><td>{$row['firstname']}</td><td>{$row['lastname']}</td><td>{$row['username']}</td><td>{$row['password']}</td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "No hay usuarios en la tabla \'usuarios\'";
                }
            } catch (PDOException $e) {
                echo "Hubo un error <br><br>".$e->getMessage();
            }
            
        ?> 
        </div> <!--Container-->
        
        <?php include "includes/footer.php" ?>
    </body>
</html>