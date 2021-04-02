<!DOCTYPE HTML>
<html>
    <head>
        <title>Epico!</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
        <!-- Custom css -->
        <style>
            .m-r-1em{ margin-right:1em; }
            .m-b-1em{ margin-bottom:1em; }
            .m-l-1em{ margin-left:1em; }
            .mt0{ margin-top:0; }
        </style>
    </head>
    <body>
        <div class="container">

            <div class="pb-2 mt-4 mb-2 border-bottom">
                <h2>Leer ítems</h2>
            </div>

            <!-- PHP Code -->
            <?php
                //Database connection
                include 'config/database.php';
                 
                $action = isset($_GET['action']) ? $_GET['action'] : "";
                 
                //If it was redirected from delete.php
                if($action=='deleted'){
                    echo "<div class='alert alert-success'>Ítem eliminado satisfactoriamente.</div>";
                }
                 
                //Select all data
                $query = "SELECT id, name, category, cost_price, unit_price, pic_filename FROM epico_items ORDER BY id DESC";
                $stmt = $con->prepare($query);
                $stmt->execute();
                 
                //This is how to get number of rows returned
                $num = $stmt->rowCount();
                 
                //Link to create record form
                echo "<a href='create.php' class='btn btn-primary m-b-1em'>Crear item</a>";
                 
                //Check if more than 0 record found
                if($num>0){
                    echo "<div class='table-responsive'>";
                    echo "<table class='table table-hover table-bordered'>";
                     
                        //creating our table heading
                        echo "<tr>";
                            echo "<th>Nombre</th>";
                            echo "<th>Categoría</th>";
                            echo "<th>Costo</th>";
                            echo "<th>Precio unitario</th>";
                            echo "<th>Imagen</th>";
                            echo "<th></th>";
                        echo "</tr>";
                         
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                            extract($row);    
                            //Creating new table row per record
                            echo "<tr>";
                                echo "<td>{$name}</td>";
                                echo "<td>{$category}</td>";
                                echo "<td>$".$cost_price."</td>";
                                echo "<td>$".$unit_price."</td>";
                                echo "<td><a target='_blank' href='{$pic_filename}'>Ver Imagen</a></td>";
                                echo "<td>";                                     
                                    echo "<a href='update.php?id={$id}' class='btn btn-primary m-r-1em'>Editar</a>";
                                    echo "<a href='#' onclick='deleteItem({$id});'  class='btn btn-danger'>Eliminar</a>";
                                echo "</td>";
                            echo "</tr>";
                        }

                    echo "</table>";
                    echo "</div>";
                }else{
                    echo "<div class='alert alert-danger'>No has creado items aún.</div>";
                }
            ?>
        </div>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>           
        <!-- Latest compiled and minified Bootstrap JavaScript -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script type='text/javascript'>
            //Delete comfirmation message
            function deleteItem(id){
                var answer = confirm('¿Estás seguro que deseas eliminar este ítem?');
                if (answer){
                    window.location = 'delete.php?id=' + id;
                } 
            }
        </script>
    </body>
</html>