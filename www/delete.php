<?php
    //Database connection
    include 'config/database.php';
     
    try {
        //Get record ID
        $id=isset($_GET['id']) && $_GET['id'] != "" ? $_GET['id'] : die('No se encontró ID del ítem.');
     
        //Delete query
        $query = "DELETE FROM epico_items WHERE id = ?";
        $stmt = $con->prepare($query);
        $stmt->bindParam(1, $id);
         
        if($stmt->execute()){
            //Redirect to index.php
            header('Location: index.php?action=deleted');
        }else{
            die('Error al eliminar ítem.');
        }
    }
     
    //Show error
    catch(PDOException $exception){
        die('Error: ' . $exception->getMessage());
    }
?>