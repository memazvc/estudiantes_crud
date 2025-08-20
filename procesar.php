<?php
include 'conexion.php';

// Función para sanitizar datos de entrada
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Función para validar datos del estudiante
function validateStudentData($data) {
    $errors = [];
    
    if (empty($data['nombre_completo']) || strlen($data['nombre_completo']) < 3) {
        $errors[] = "El nombre completo debe tener al menos 3 caracteres";
    }
    
    if (empty($data['numero_documento']) || !preg_match('/^[0-9]+$/', $data['numero_documento'])) {
        $errors[] = "El número de documento debe contener solo números";
    }
    
    if (empty($data['grado'])) {
        $errors[] = "El grado es obligatorio";
    }
    
    if (empty($data['barrio'])) {
        $errors[] = "El barrio es obligatorio";
    }
    
    if (empty($data['ciudad'])) {
        $errors[] = "La ciudad es obligatoria";
    }
    
    if (!in_array($data['genero'], ['Masculino', 'Femenino', 'Otro'])) {
        $errors[] = "Género inválido";
    }
    
    return $errors;
}

// Función para mostrar errores y redireccionar
function showErrorAndRedirect($message) {
    $encoded_message = urlencode($message);
    header("Location: index.php?error=" . $encoded_message);
    exit;
}

// Función para mostrar éxito y redireccionar
function showSuccessAndRedirect($message = "") {
    $encoded_message = urlencode($message);
    header("Location: index.php?success=" . $encoded_message);
    exit;
}

// Obtener acción
$accion = $_POST['accion'] ?? $_GET['accion'] ?? '';

try {
    switch ($accion) {
        case 'crear':
            // Sanitizar datos
            $data = [
                'nombre_completo' => sanitizeInput($_POST['nombre_completo'] ?? ''),
                'numero_documento' => sanitizeInput($_POST['numero_documento'] ?? ''),
                'grado' => sanitizeInput($_POST['grado'] ?? ''),
                'barrio' => sanitizeInput($_POST['barrio'] ?? ''),
                'ciudad' => sanitizeInput($_POST['ciudad'] ?? ''),
                'genero' => sanitizeInput($_POST['genero'] ?? '')
            ];
            
            // Validar datos
            $errors = validateStudentData($data);
            if (!empty($errors)) {
                showErrorAndRedirect(implode(", ", $errors));
            }
            
            // Verificar documento único
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM estudiantes WHERE numero_documento = ?");
            $stmt->bind_param("s", $data['numero_documento']);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            
            if ($row['count'] > 0) {
                showErrorAndRedirect("Ya existe un estudiante con ese número de documento");
            }
            
            // Insertar estudiante
            $stmt = $conn->prepare("INSERT INTO estudiantes (nombre_completo, numero_documento, grado, barrio, ciudad, genero) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", 
                $data['nombre_completo'], 
                $data['numero_documento'], 
                $data['grado'], 
                $data['barrio'], 
                $data['ciudad'], 
                $data['genero']
            );
            
            if ($stmt->execute()) {
                showSuccessAndRedirect("Estudiante registrado exitosamente");
            } else {
                throw new Exception("Error al registrar el estudiante");
            }
            break;

        case 'eliminar':
            $id = (int)($_POST['id'] ?? 0);
            
            if ($id <= 0) {
                showErrorAndRedirect("ID inválido");
            }
            
            // Verificar que el estudiante existe
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM estudiantes WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            
            if ($row['count'] == 0) {
                showErrorAndRedirect("Estudiante no encontrado");
            }
            
            // Eliminar estudiante
            $stmt = $conn->prepare("DELETE FROM estudiantes WHERE id = ?");
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                showSuccessAndRedirect("Estudiante eliminado exitosamente");
            } else {
                throw new Exception("Error al eliminar el estudiante");
            }
            break;

        case 'editar':
            $id = (int)($_POST['id'] ?? 0);
            
            if ($id <= 0) {
                showErrorAndRedirect("ID inválido");
            }
            
            // Obtener datos del estudiante
            $stmt = $conn->prepare("SELECT * FROM estudiantes WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($est = $result->fetch_assoc()) {
                ?>
                <!DOCTYPE html>
                <html lang="es">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Editar Estudiante</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
                    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
                    <style>
                        body {
                            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                            min-height: 100vh;
                            font-family: 'Inter', sans-serif;
                        }
                        .card {
                            background: rgba(255, 255, 255, 0.95);
                            backdrop-filter: blur(20px);
                            border-radius: 20px;
                            border: none;
                            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
                        }
                        .form-control, .form-select {
                            border-radius: 12px;
                            border: 2px solid transparent;
                            background: rgba(255, 255, 255, 0.9);
                        }
                        .form-control:focus, .form-select:focus {
                            border-color: #667eea;
                            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
                        }
                        .btn-custom {
                            border-radius: 50px;
                            padding: 0.75rem 2rem;
                            font-weight: 600;
                            text-transform: uppercase;
                            letter-spacing: 0.5px;
                        }
                    </style>
                </head>
                <body>
                    <div class="container mt-5">
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="card p-4">
                                    <h3 class="text-center mb-4">
                                        <i class="fas fa-user-edit text-primary"></i>
                                        Editar Estudiante
                                    </h3>
                                    
                                    <form method="POST" action="procesar.php">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($est['id']) ?>">
                                        
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">
                                                    <i class="fas fa-user me-1"></i> Nombre Completo
                                                </label>
                                                <input type="text" class="form-control" name="nombre_completo" 
                                                       value="<?= htmlspecialchars($est['nombre_completo']) ?>" required>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label class="form-label">
                                                    <i class="fas fa-id-card me-1"></i> Número Documento
                                                </label>
                                                <input type="text" class="form-control" name="numero_documento" 
                                                       value="<?= htmlspecialchars($est['numero_documento']) ?>" required>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <label class="form-label">
                                                    <i class="fas fa-layer-group me-1"></i> Grado
                                                </label>
                                                <input type="text" class="form-control" name="grado" 
                                                       value="<?= htmlspecialchars($est['grado']) ?>" required>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <label class="form-label">
                                                    <i class="fas fa-home me-1"></i> Barrio
                                                </label>
                                                <input type="text" class="form-control" name="barrio" 
                                                       value="<?= htmlspecialchars($est['barrio']) ?>" required>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <label class="form-label">
                                                    <i class="fas fa-city me-1"></i> Ciudad
                                                </label>
                                                <input type="text" class="form-control" name="ciudad" 
                                                       value="<?= htmlspecialchars($est['ciudad']) ?>" required>
                                            </div>
                                            
                                            <div class="col-12">
                                                <label class="form-label">
                                                    <i class="fas fa-venus-mars me-1"></i> Género
                                                </label>
                                                <select name="genero" class="form-select" required>
                                                    <option value="Masculino" <?= ($est['genero']=="Masculino")?"selected":"" ?>>Masculino</option>
                                                    <option value="Femenino" <?= ($est['genero']=="Femenino")?"selected":"" ?>>Femenino</option>
                                                    <option value="Otro" <?= ($est['genero']=="Otro")?"selected":"" ?>>Otro</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="text-center mt-4">
                                            <button type="submit" name="accion" value="actualizar" 
                                                    class="btn btn-primary btn-custom me-2">
                                                <i class="fas fa-save me-2"></i>Actualizar
                                            </button>
                                            
                                            <a href="index.php" class="btn btn-secondary btn-custom">
                                                <i class="fas fa-arrow-left me-2"></i>Cancelar
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </body>
                </html>
                <?php
                exit;
            } else {
                showErrorAndRedirect("Estudiante no encontrado");
            }
            break;

        case 'actualizar':
            $id = (int)($_POST['id'] ?? 0);
            
            if ($id <= 0) {
                showErrorAndRedirect("ID inválido");
            }
            
            // Sanitizar datos
            $data = [
                'nombre_completo' => sanitizeInput($_POST['nombre_completo'] ?? ''),
                'numero_documento' => sanitizeInput($_POST['numero_documento'] ?? ''),
                'grado' => sanitizeInput($_POST['grado'] ?? ''),
                'barrio' => sanitizeInput($_POST['barrio'] ?? ''),
                'ciudad' => sanitizeInput($_POST['ciudad'] ?? ''),
                'genero' => sanitizeInput($_POST['genero'] ?? '')
            ];
            
            // Validar datos
            $errors = validateStudentData($data);
            if (!empty($errors)) {
                showErrorAndRedirect(implode(", ", $errors));
            }
            
            // Verificar que el estudiante existe
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM estudiantes WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            
            if ($row['count'] == 0) {
                showErrorAndRedirect("Estudiante no encontrado");
            }
            
            // Verificar documento único (excepto el actual)
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM estudiantes WHERE numero_documento = ? AND id != ?");
            $stmt->bind_param("si", $data['numero_documento'], $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            
            if ($row['count'] > 0) {
                showErrorAndRedirect("Ya existe otro estudiante con ese número de documento");
            }
            
            // Actualizar estudiante
            $stmt = $conn->prepare("UPDATE estudiantes SET nombre_completo=?, numero_documento=?, grado=?, barrio=?, ciudad=?, genero=? WHERE id=?");
            $stmt->bind_param("ssssssi", 
                $data['nombre_completo'], 
                $data['numero_documento'], 
                $data['grado'], 
                $data['barrio'], 
                $data['ciudad'], 
                $data['genero'], 
                $id
            );
            
            if ($stmt->execute()) {
                showSuccessAndRedirect("Estudiante actualizado exitosamente");
            } else {
                throw new Exception("Error al actualizar el estudiante");
            }
            break;

        default:
            showErrorAndRedirect("Acción no válida");
    }

} catch (Exception $e) {
    error_log("Error en procesar.php: " . $e->getMessage());
    showErrorAndRedirect("Error interno del servidor: " . $e->getMessage());
} catch (Error $e) {
    error_log("Error fatal en procesar.php: " . $e->getMessage());
    showErrorAndRedirect("Error interno del servidor");
}
?>