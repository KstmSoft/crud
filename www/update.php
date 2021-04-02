<!DOCTYPE HTML>
<html>
    <head>
        <title>Epico! | Editar ítem</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    </head>
    <body>
        <div class="container">

            <div class="pb-2 mt-4 mb-2 border-bottom">
                <h2>Editar ítem</h2>
            </div>

            <?php
                //Get passed parameter value, in this case, the record ID
                $id=isset($_GET['id']) && $_GET['id'] != "" ? $_GET['id'] : die('No se encontró ID del ítem.');
                 
                //Database connection
                include 'config/database.php';
                 
                //Read current record's data
                try {
                    //Prepare select query
                    $query = "SELECT id, name, category, cost_price, unit_price, pic_filename FROM epico_items WHERE id = ? LIMIT 0,1";
                    $stmt = $con->prepare( $query );
                    $stmt->bindParam(1, $id);
                     
                    //Execute query
                    $stmt->execute();
                     
                    //Store retrieved row to a variable
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    $name = $row['name'];
                    $category = $row['category'];
                    $cost_price = $row['cost_price'];
                    $unit_price = $row['unit_price'];
                    $pic_filename = $row['pic_filename'];

                }catch(PDOException $exception){
                    die('ERROR: ' . $exception->getMessage());
                }
             
                //Submit modified values
                if($_POST){
                    try{
                        //Update query
                        $query = "UPDATE epico_items 
                                    SET name=:name, category=:category, cost_price=:cost_price, unit_price=:unit_price, pic_filename=:pic_filename
                                    WHERE id = :id";
                 
                        //Prepare query
                        $stmt = $con->prepare($query);
                 
                        //Posted values
                        $name=htmlspecialchars(strip_tags($_POST['name']));
                        $category=htmlspecialchars(strip_tags($_POST['category']));
                        $cost_price=htmlspecialchars(strip_tags($_POST['cost_price']));
                        $unit_price=htmlspecialchars(strip_tags($_POST['unit_price']));
                 
                        //Bind the parameters
                        $stmt->bindParam(':name', $name);
                        $stmt->bindParam(':category', $category);
                        $stmt->bindParam(':cost_price', $cost_price);
                        $stmt->bindParam(':unit_price', $unit_price);
                        $stmt->bindParam(':pic_filename', $pic_filename);
                        $stmt->bindParam(':id', $id);

                        //Check if image was modified
                        if(isset($_FILES['picture']) && $_FILES['picture']['name'] != ""){
                              $errors= array();
                              $file_name = $_FILES['picture']['name'];
                              $file_size = $_FILES['picture']['size'];
                              $file_tmp = $_FILES['picture']['tmp_name'];
                              $file_path = explode('.',$_FILES['picture']['name']);
                              $file_ext= end($file_path);

                              $extensions= array("jpeg","jpg","png");

                              //Specify image path (pic_filename) to database
                              $file_target = "./images/". uniqid(rand(), true) . ".$file_ext";
                              $stmt->bindParam(':pic_filename', $file_target);

                              if(in_array($file_ext,$extensions)=== false){
                                 $errors[]="Formato de imagen no válido.";
                              }
                              
                              if($file_size > 2097152) {
                                 $errors[]='El archivo no puede superar los 2 MB';
                              }
                              
                              if(empty($errors)==true) {
                                //Execute the query and upload image
                                if($stmt->execute()){
                                    move_uploaded_file($file_tmp, $file_target);
                                    echo "<div class='alert alert-success'>Datos actualizados satisfactoriamente.</div>";
                                }else{
                                    echo "<div class='alert alert-danger'>Error al actualizar datos.</div>";
                                }
                              }else{
                                 foreach ($errors as &$error) {
                                    echo "<div class='alert alert-danger'>$error</div>";
                                 }
                              }
                        }else{
                            //Image wasn't modified
                            //Execute the query
                            if($stmt->execute()){
                                echo "<div class='alert alert-success'>Datos actualizados satisfactoriamente.</div>";
                            }else{
                                echo "<div class='alert alert-danger'>Error al actualizar datos.</div>";
                            }
                        }
                    }catch(PDOException $exception){
                        echo "<div class='alert alert-danger'>Error interno.</div>";
                        die('Error: ' . $exception->getMessage());
                    }
                }
            ?>
             
            <!-- Html form here where item information can be updated -->
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}");?>" method="post" enctype="multipart/form-data">
                <div class="table-responsive">
                    <table class='table table-hover table-bordered'>
                        <tr>
                            <td>Nombre</td>
                            <td><input type='text' name='name' value="<?php echo htmlspecialchars($name, ENT_QUOTES);  ?>" class='form-control' /></td>
                        </tr>
                        <tr>
                            <td>Categoría</td>
                            <td><input type='text' name='category' value="<?php echo htmlspecialchars($category, ENT_QUOTES);  ?>" class='form-control' /></td>
                        </tr>
                        <tr>
                            <td>Costo</td>
                            <td><input type='number' step='.01' name='cost_price' value="<?php echo htmlspecialchars($cost_price, ENT_QUOTES);  ?>" class='form-control' /></td>
                        </tr>
                        <tr>
                            <td>Precio unitario</td>
                            <td><input type='number' step='.01' name='unit_price' value="<?php echo htmlspecialchars($unit_price, ENT_QUOTES);  ?>" class='form-control' /></td>
                        </tr>
                        <tr>
                            <td>Imagen</td>
                            <td><input type='file' name='picture'></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input type='submit' value='Guardar cambios' class='btn btn-primary' />
                                <a href='index.php' class='btn btn-danger'>Volver</a>
                            </td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
           
        <!-- Latest compiled and minified Bootstrap JavaScript -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    </body>
</html>