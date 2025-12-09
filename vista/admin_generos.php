<?php
// Conexión a la base de datos
include ('../controlador/conexion.php');

// Consulta para obtener todos los géneros
$sql_generos = "SELECT * FROM genero ORDER BY nombre_genero";
$result_generos = $conn->query($sql_generos);

// Verificar si hay resultados
if ($result_generos->num_rows > 0) {
    $generos = array();
    while($row = $result_generos->fetch_assoc()) {
        $generos[] = $row;
    }
} else {
    $generos = array();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Géneros - Biblioteca</title>
    <link rel="icon" type="image/png" href="images/favicon.ico"> 
    <link rel="stylesheet" href="style.css/generos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <img src="images/hojas.jpg" alt="Fondo Hojas" class="bg-image">
    <div class="bg-overlay"></div>

    <header>
        <div class="header-title">
            <svg style="width:28px; height:28px; fill:white; margin-right:10px;" viewBox="0 0 24 24">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
            Admin Géneros
        </div>

        <div class="header-buttons">
            <a href="admin_login.php" class="btn-glass">
                <svg viewBox="0 0 24 24"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>
                Volver al Panel
            </a>
            
            <a href="agregar_genero.php" class="btn-glass btn-add">
                <svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                Nuevo Género
            </a>
        </div>
    </header>

    <div class="container">
        
        <div class="page-intro">
            <h2>Gestión de Categorías</h2>
            <p>Edita o elimina los géneros literarios del sistema.</p>
        </div>

        <div class="grid">
            <?php if (count($generos) > 0): ?>
                <?php foreach ($generos as $genero): ?>
                    <div class="card">
                        <div class="card-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M12 2l-5.5 9h11L12 2zm0 3.84L13.93 9h-3.87L12 5.84zM17.5 13c-2.48 0-4.5 2.02-4.5 4.5s2.02 4.5 4.5 4.5 4.5-2.02 4.5-4.5-2.02-4.5-4.5-4.5zm0 7c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5zM3 13c-2.48 0-4.5 2.02-4.5 4.5S.52 22 3 22s4.5-2.02 4.5-4.5S5.48 13 3 13zm0 7c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                            </svg>
                        </div>
                        <h3><?php echo htmlspecialchars($genero['nombre_genero']); ?></h3>
                        <p><?php echo !empty($genero['descripcion']) ? htmlspecialchars($genero['descripcion']) : 'Sin descripción'; ?></p>
                        
                        <div class="card-actions">
                            <a href="editar_genero.php?id=<?php echo $genero['id_genero']; ?>" class="btn-action btn-edit">
                                Editar
                            </a>
                            <a href="../modelo/eliminar_genero.php?id=<?php echo $genero['id_genero']; ?>" 
                               class="btn-action btn-delete" 
                               onclick="return confirm('¿Estás seguro de eliminar el género <?php echo $genero['nombre_genero']; ?>? Esto podría afectar a los libros asociados.');">
                                Eliminar
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-generos">
                    <p>No hay géneros registrados.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>