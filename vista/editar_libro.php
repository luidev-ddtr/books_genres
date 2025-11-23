<?php
// Recibimos los datos del libro desde la URL (enviados desde admin_panel.php)
// Si no hay datos, ponemos cadenas vacías para evitar errores
$id_libro = isset($_GET['id_libro']) ? $_GET['id_libro'] : '';
$titulo = isset($_GET['titulo']) ? $_GET['titulo'] : '';
$autor = isset($_GET['autor']) ? $_GET['autor'] : '';
$genero = isset($_GET['genero']) ? $_GET['genero'] : '';
$descripcion = isset($_GET['descripcion']) ? $_GET['descripcion'] : '';
$ruta_imagen = isset($_GET['ruta_imagen']) ? $_GET['ruta_imagen'] : '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Libro - Administración</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* --- RESET Y BASE --- */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: white;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* --- FONDO --- */
        .bg-image {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -2;
        }

        .bg-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* Fondo oscuro SIN blur para nitidez */
            background-color: rgba(0, 0, 0, 0.6); 
            z-index: -1;
        }

        /* --- HEADER --- */
        header {
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 100;
        }

        .header-title {
            font-size: 1.3rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
        }

        /* --- BOTONES --- */
        .btn-glass {
            text-decoration: none;
            color: white;
            font-weight: 600;
            display: flex;
            align-items: center;
            padding: 8px 20px;
            border-radius: 50px;
            transition: all 0.3s;
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            font-size: 0.9rem;
        }

        .btn-glass:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-1px);
        }

        /* --- CONTENEDOR DEL FORMULARIO --- */
        .main-container {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .form-container {
            background: rgba(30, 30, 30, 0.6); 
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 40px 50px;
            width: 100%;
            max-width: 650px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        }

        .form-title {
            text-align: center;
            margin-top: 0;
            margin-bottom: 30px;
            font-size: 1.8rem;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        /* --- INPUTS Y LABELS --- */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            color: #ddd;
        }

        .form-input, 
        .form-textarea {
            width: 100%;
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.05);
            color: white;
            font-family: inherit;
            font-size: 0.95rem;
            transition: all 0.3s;
            box-sizing: border-box;
        }

        /* ESTILO PARA CAMPOS DE SOLO LECTURA (NO EDITABLES) */
        .form-input[readonly] {
            background-color: rgba(0, 0, 0, 0.3); /* Fondo más oscuro */
            color: #aaa; /* Texto más apagado */
            border-color: rgba(255, 255, 255, 0.05);
            cursor: not-allowed; /* Cursor de prohibido */
        }

        /* ESTILO PARA CAMPOS EDITABLES (FOCUS) */
        .form-input:not([readonly]):focus, 
        .form-textarea:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.1);
            border-color: #e67e22; /* Naranja al enfocar */
            box-shadow: 0 0 10px rgba(230, 126, 34, 0.2);
        }

        .form-textarea {
            resize: vertical;
            min-height: 120px;
        }

        /* --- BOTÓN GUARDAR CAMBIOS --- */
        .btn-submit {
            width: 100%;
            padding: 14px;
            border-radius: 50px;
            border: none;
            /* CAMBIO: Color Naranja/Ocre igual que en 'Agregar' */
            background-color: #d37234; 
            color: white;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.2s, transform 0.2s;
            margin-top: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            /* CAMBIO: Propiedades Flex para que la etiqueta 'a' centre el contenido */
            display: flex;
            justify-content: center;
            align-items: center;
            text-decoration: none;
            box-sizing: border-box;
        }

        .btn-submit:hover {
            background-color: #e67e22;
            transform: scale(1.02);
        }

        /* Responsive */
        @media (max-width: 600px) {
            .form-container {
                padding: 25px;
            }
        }
    </style>
</head>
<body>

    <!-- IMAGEN DE FONDO -->
    <img src="images/editar.jpg" alt="Fondo Administración" class="bg-image">
    <div class="bg-overlay"></div>

    <!-- HEADER -->
    <header>
        <div class="header-title">
            <i class="fas fa-edit" style="margin-right: 10px;"></i> Editor de Libro
        </div>
        <a href="admin_login.php" class="btn-glass">
            <i class="fas fa-arrow-left" style="margin-right: 8px;"></i> Cancelar
        </a>
    </header>

    <!-- FORMULARIO CENTRAL -->
    <div class="main-container">
        <div class="form-container">
            <h2 class="form-title">Editar Información</h2>
            
            <!-- El formulario envía los datos a un archivo PHP que procese la actualización -->
            <form action="../modelo/actualizar_libro.php" method="POST">
                
                <!-- ID Oculto (Necesario para saber qué libro actualizar) -->
                <input type="hidden" name="id_libro" value="<?php echo htmlspecialchars($id_libro); ?>">

                <!-- Título (SOLO LECTURA) -->
                <div class="form-group">
                    <label class="form-label">Título del Libro (No editable)</label>
                    <input type="text" class="form-input" name="titulo" 
                           value="<?php echo htmlspecialchars($titulo); ?>" readonly>
                </div>

                <!-- Autor (SOLO LECTURA) -->
                <div class="form-group">
                    <label class="form-label">Autor (No editable)</label>
                    <input type="text" class="form-input" name="autor" 
                           value="<?php echo htmlspecialchars($autor); ?>" readonly>
                </div>

                <!-- Género (SOLO LECTURA) -->
                <div class="form-group">
                    <label class="form-label">Género Principal (No editable)</label>
                    <!-- Usamos input text en lugar de select para mostrarlo fijo -->
                    <input type="text" class="form-input" name="genero" 
                           value="<?php echo htmlspecialchars($genero); ?>" readonly>
                </div>

                <!-- Descripción (EDITABLE) -->
                <div class="form-group">
                    <label class="form-label" style="color: #e67e22;">Descripción / Sinopsis (Editable)</label>
                    <textarea class="form-textarea" name="descripcion" required><?php echo htmlspecialchars($descripcion); ?></textarea>
                </div>

                <!-- Ruta de Imagen (EDITABLE) -->
                <div class="form-group">
                    <label class="form-label" style="color: #e67e22;">Ruta de Imagen (Editable)</label>
                    <input type="text" class="form-input" name="ruta_imagen" 
                           value="<?php echo htmlspecialchars($ruta_imagen); ?>" required>
                </div>

                <!-- Botón Guardar (ENLACE CON ESTILO DE BOTÓN) -->
                <a href="admin_login.php" class="btn-submit">
                    <i class="fas fa-save" style="margin-right: 8px;"></i> Guardar Cambios
                </a> 

            </form>
        </div>
    </div>

</body>
</html>