<?php
include ('../controlador/conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Datos básicos del libro
    $descripcion = $_POST['descripcion'];
    $ruta_imagen = $_POST['ruta_imagen'];
    $id_libro = $_POST['id_libro'];
    
    // Datos de géneros (si se enviaron en el formulario)
    $id_genero_1 = isset($_POST['id_genero_1']) ? $_POST['id_genero_1'] : null;
    $id_genero_2 = isset($_POST['id_genero_2']) ? $_POST['id_genero_2'] : null;
    $id_genero_3 = isset($_POST['id_genero_3']) ? $_POST['id_genero_3'] : null;
    $prioridad_1 = isset($_POST['prioridad_1']) ? $_POST['prioridad_1'] : 1;
    $prioridad_2 = isset($_POST['prioridad_2']) ? $_POST['prioridad_2'] : 2;
    $prioridad_3 = isset($_POST['prioridad_3']) ? $_POST['prioridad_3'] : 3;
    
    // Iniciar transacción para asegurar que todas las operaciones se completen
    $conn->begin_transaction();
    
    try {
        // 1. Actualizar los datos básicos del libro
        $query_libro = "UPDATE libro SET descripcion = ?, ruta_imagen = ? WHERE id_libro = ?";
        $stmt_libro = $conn->prepare($query_libro);
        $stmt_libro->bind_param("ssi", $descripcion, $ruta_imagen, $id_libro);
        
        if (!$stmt_libro->execute()) {
            throw new Exception("Error al editar el libro: " . $stmt_libro->error);
        }
        $stmt_libro->close();
        
        // 2. Verificar si se enviaron géneros para actualizar
        if ($id_genero_1 && $id_genero_2 && $id_genero_3) {
            // Validar que no haya géneros repetidos (seguridad en backend)
            $generos_array = [$id_genero_1, $id_genero_2, $id_genero_3];
            if (count(array_unique($generos_array)) < 3) {
                throw new Exception("No se pueden repetir los géneros");
            }
            
            // 3. Eliminar los géneros existentes para este libro
            $query_delete = "DELETE FROM libro_genero WHERE id_libro = ?";
            $stmt_delete = $conn->prepare($query_delete);
            $stmt_delete->bind_param("i", $id_libro);
            
            if (!$stmt_delete->execute()) {
                throw new Exception("Error al eliminar géneros anteriores: " . $stmt_delete->error);
            }
            $stmt_delete->close();
            
            // 4. Insertar los nuevos géneros
            $query_insert_genero = "INSERT INTO libro_genero (id_libro, id_genero, prioridad) VALUES (?, ?, ?)";
            $stmt_insert = $conn->prepare($query_insert_genero);
            
            // Insertar primer género
            $stmt_insert->bind_param("iii", $id_libro, $id_genero_1, $prioridad_1);
            if (!$stmt_insert->execute()) {
                // Verificar si es error de clave duplicada (por si acaso)
                if ($stmt_insert->errno == 1062) {
                    throw new Exception("Error: El género ya está asignado a este libro");
                } else {
                    throw new Exception("Error al insertar género 1: " . $stmt_insert->error);
                }
            }
            
            // Insertar segundo género
            $stmt_insert->bind_param("iii", $id_libro, $id_genero_2, $prioridad_2);
            if (!$stmt_insert->execute()) {
                if ($stmt_insert->errno == 1062) {
                    throw new Exception("Error: El género ya está asignado a este libro");
                } else {
                    throw new Exception("Error al insertar género 2: " . $stmt_insert->error);
                }
            }
            
            // Insertar tercer género
            $stmt_insert->bind_param("iii", $id_libro, $id_genero_3, $prioridad_3);
            if (!$stmt_insert->execute()) {
                if ($stmt_insert->errno == 1062) {
                    throw new Exception("Error: El género ya está asignado a este libro");
                } else {
                    throw new Exception("Error al insertar género 3: " . $stmt_insert->error);
                }
            }
            
            $stmt_insert->close();
        }
        
        // Confirmar la transacción
        $conn->commit();
        
        echo "<script>alert('Libro editado correctamente'); window.location.href='../vista/admin_login.php';</script>";
        
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conn->rollback();
        echo "<script>alert('Error: " . addslashes($e->getMessage()) . "'); window.location.href='../vista/admin_login.php';</script>";
    }
    
    $conn->close();
} else {
    echo "<script>alert('Método no permitido'); window.location.href='../vista/admin_login.php';</script>";
}
?>