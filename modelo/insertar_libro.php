<?php
include ('../controlador/conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $descripcion = $_POST['descripcion'];
    $ruta_imagen = $_POST['ruta_imagen'];
    $prioridad_1 = $_POST['prioridad_1'];
    $prioridad_2 = $_POST['prioridad_2'];
    $prioridad_3 = $_POST['prioridad_3'];
    $id_genero_1 = $_POST['id_genero_1'];
    $id_genero_2 = $_POST['id_genero_2'];
    $id_genero_3 = $_POST['id_genero_3'];

    // Validar que los géneros sean diferentes
    if ($id_genero_1 == $id_genero_2 || $id_genero_1 == $id_genero_3 || $id_genero_2 == $id_genero_3) {
        echo "<script>alert('Los géneros no pueden ser iguales'); window.location.href='../vista/agregar.html';</script>";
        exit();
    }

    // Insertar el libro
    $query = "INSERT INTO libro (titulo, autor, descripcion, ruta_imagen) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $titulo, $autor, $descripcion, $ruta_imagen);

    if ($stmt->execute()) {
        // Obtener el ID del libro recién insertado
        $id_libro = $stmt->insert_id;
        $stmt->close();

        // Arrays de géneros y prioridades
        $generos = [$id_genero_1, $id_genero_2, $id_genero_3];
        $prioridades = [$prioridad_1, $prioridad_2, $prioridad_3];

        // Preparar la consulta para insertar en libro_genero
        $query_libro_genero = "INSERT INTO libro_genero (id_libro, id_genero, prioridad) VALUES (?, ?, ?)";
        $stmt_libro_genero = $conn->prepare($query_libro_genero);

        $error_en_generos = false;
        for ($i = 0; $i < 3; $i++) {
            $stmt_libro_genero->bind_param("iii", $id_libro, $generos[$i], $prioridades[$i]);
            if (!$stmt_libro_genero->execute()) {
                $error_en_generos = true;
                break;
            }
        }
        $stmt_libro_genero->close();

        if ($error_en_generos) {
            // Si hubo un error al insertar algún género, podrías eliminar el libro insertado o mostrar un error.
            // En este caso, eliminamos el libro para mantener la consistencia.
            $conn->query("DELETE FROM libro WHERE id_libro = $id_libro");
            echo "<script>alert('Error al insertar los géneros. El libro se ha eliminado.'); window.location.href='../vista/agregar.html';</script>";
        } else {
            echo "<script>alert('Libro insertado correctamente'); window.location.href='../vista/admin_login.html';</script>";
        }
    } else {
        echo "<script>alert('Error al insertar el libro: " . $stmt->error . "'); window.location.href='../vista/agregar.html';</script>";
    }

    $conn->close();
}
?>