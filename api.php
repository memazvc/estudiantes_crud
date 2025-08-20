<?php
header('Content-Type: application/json');
include 'conexion.php';

$action = $_REQUEST['action'] ?? '';

try {
    switch ($action) {
        case 'list':
            // Listar todos los estudiantes
            $result = $conn->query("SELECT * FROM estudiantes ORDER BY id DESC");
            $estudiantes = [];
            
            while ($row = $result->fetch_assoc()) {
                $estudiantes[] = [
                    'id' => (int)$row['id'],
                    'nombre_completo' => htmlspecialchars($row['nombre_completo']),
                    'numero_documento' => htmlspecialchars($row['numero_documento']),
                    'grado' => htmlspecialchars($row['grado']),
                    'barrio' => htmlspecialchars($row['barrio']),
                    'ciudad' => htmlspecialchars($row['ciudad']),
                    'genero' => htmlspecialchars($row['genero'])
                ];
            }
            
            echo json_encode([
                'success' => true,
                'data' => $estudiantes,
                'count' => count($estudiantes)
            ]);
            break;
            
        case 'get':
            // Obtener un estudiante específico
            $id = (int)$_GET['id'];
            
            if ($id <= 0) {
                throw new Exception('ID inválido');
            }
            
            $stmt = $conn->prepare("SELECT * FROM estudiantes WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($row = $result->fetch_assoc()) {
                echo json_encode([
                    'success' => true,
                    'data' => [
                        'id' => (int)$row['id'],
                        'nombre_completo' => htmlspecialchars($row['nombre_completo']),
                        'numero_documento' => htmlspecialchars($row['numero_documento']),
                        'grado' => htmlspecialchars($row['grado']),
                        'barrio' => htmlspecialchars($row['barrio']),
                        'ciudad' => htmlspecialchars($row['ciudad']),
                        'genero' => htmlspecialchars($row['genero'])
                    ]
                ]);
            } else {
                throw new Exception('Estudiante no encontrado');
            }
            break;
            
        case 'crear':
            // Crear nuevo estudiante
            $nombre = trim($_POST['nombre_completo'] ?? '');
            $documento = trim($_POST['numero_documento'] ?? '');
            $grado = trim($_POST['grado'] ?? '');
            $barrio = trim($_POST['barrio'] ?? '');
            $ciudad = trim($_POST['ciudad'] ?? '');
            $genero = trim($_POST['genero'] ?? '');
            
            // Validaciones
            if (empty($nombre) || empty($documento) || empty($grado) || 
                empty($barrio) || empty($ciudad) || empty($genero)) {
                throw new Exception('Todos los campos son obligatorios');
            }
            
            if (strlen($nombre) < 3) {
                throw new Exception('El nombre debe tener al menos 3 caracteres');
            }
            
            if (!preg_match('/^[0-9]+$/', $documento)) {
                throw new Exception('El número de documento debe contener solo números');
            }
            
            if (!in_array($genero, ['Masculino', 'Femenino', 'Otro'])) {
                throw new Exception('Género inválido');
            }
            
            // Verificar documento único
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM estudiantes WHERE numero_documento = ?");
            $stmt->bind_param("s", $documento);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            
            if ($row['count'] > 0) {
                throw new Exception('Ya existe un estudiante con ese número de documento');
            }
            
            // Insertar estudiante
            $stmt = $conn->prepare("INSERT INTO estudiantes (nombre_completo, numero_documento, grado, barrio, ciudad, genero) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $nombre, $documento, $grado, $barrio, $ciudad, $genero);
            
            if ($stmt->execute()) {
                $new_id = $conn->insert_id;
                echo json_encode([
                    'success' => true,
                    'message' => 'Estudiante registrado exitosamente',
                    'data' => ['id' => $new_id]
                ]);
            } else {
                throw new Exception('Error al registrar el estudiante');
            }
            break;
            
        case 'actualizar':
            // Actualizar estudiante
            $id = (int)$_POST['id'];
            $nombre = trim($_POST['nombre_completo'] ?? '');
            $documento = trim($_POST['numero_documento'] ?? '');
            $grado = trim($_POST['grado'] ?? '');
            $barrio = trim($_POST['barrio'] ?? '');
            $ciudad = trim($_POST['ciudad'] ?? '');
            $genero = trim($_POST['genero'] ?? '');
            
            // Validaciones
            if ($id <= 0) {
                throw new Exception('ID inválido');
            }
            
            if (empty($nombre) || empty($documento) || empty($grado) || 
                empty($barrio) || empty($ciudad) || empty($genero)) {
                throw new Exception('Todos los campos son obligatorios');
            }
            
            if (strlen($nombre) < 3) {
                throw new Exception('El nombre debe tener al menos 3 caracteres');
            }
            
            if (!preg_match('/^[0-9]+$/', $documento)) {
                throw new Exception('El número de documento debe contener solo números');
            }
            
            if (!in_array($genero, ['Masculino', 'Femenino', 'Otro'])) {
                throw new Exception('Género inválido');
            }
            
            // Verificar que el estudiante existe
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM estudiantes WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            
            if ($row['count'] == 0) {
                throw new Exception('Estudiante no encontrado');
            }
            
            // Verificar documento único (excepto el actual)
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM estudiantes WHERE numero_documento = ? AND id != ?");
            $stmt->bind_param("si", $documento, $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            
            if ($row['count'] > 0) {
                throw new Exception('Ya existe otro estudiante con ese número de documento');
            }
            
            // Actualizar estudiante
            $stmt = $conn->prepare("UPDATE estudiantes SET nombre_completo=?, numero_documento=?, grado=?, barrio=?, ciudad=?, genero=? WHERE id=?");
            $stmt->bind_param("ssssssi", $nombre, $documento, $grado, $barrio, $ciudad, $genero, $id);
            
            if ($stmt->execute()) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Estudiante actualizado exitosamente'
                ]);
            } else {
                throw new Exception('Error al actualizar el estudiante');
            }
            break;
            
        case 'eliminar':
            // Eliminar estudiante
            $id = (int)$_POST['id'];
            
            if ($id <= 0) {
                throw new Exception('ID inválido');
            }
            
            // Verificar que el estudiante existe
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM estudiantes WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            
            if ($row['count'] == 0) {
                throw new Exception('Estudiante no encontrado');
            }
            
            // Eliminar estudiante
            $stmt = $conn->prepare("DELETE FROM estudiantes WHERE id = ?");
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Estudiante eliminado exitosamente'
                ]);
            } else {
                throw new Exception('Error al eliminar el estudiante');
            }
            break;
            
        case 'search':
            // Buscar estudiantes
            $query = trim($_GET['query'] ?? '');
            
            if (empty($query)) {
                throw new Exception('Término de búsqueda requerido');
            }
            
            $searchTerm = "%{$query}%";
            $stmt = $conn->prepare("
                SELECT * FROM estudiantes 
                WHERE nombre_completo LIKE ? 
                   OR numero_documento LIKE ? 
                   OR grado LIKE ? 
                   OR barrio LIKE ? 
                   OR ciudad LIKE ? 
                   OR genero LIKE ?
                ORDER BY nombre_completo ASC
            ");
            $stmt->bind_param("ssssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $estudiantes = [];
            while ($row = $result->fetch_assoc()) {
                $estudiantes[] = [
                    'id' => (int)$row['id'],
                    'nombre_completo' => htmlspecialchars($row['nombre_completo']),
                    'numero_documento' => htmlspecialchars($row['numero_documento']),
                    'grado' => htmlspecialchars($row['grado']),
                    'barrio' => htmlspecialchars($row['barrio']),
                    'ciudad' => htmlspecialchars($row['ciudad']),
                    'genero' => htmlspecialchars($row['genero'])
                ];
            }
            
            echo json_encode([
                'success' => true,
                'data' => $estudiantes,
                'count' => count($estudiantes),
                'query' => $query
            ]);
            break;
            
        case 'stats':
            // Estadísticas
            $stats = [];
            
            // Total de estudiantes
            $result = $conn->query("SELECT COUNT(*) as total FROM estudiantes");
            $row = $result->fetch_assoc();
            $stats['total'] = (int)$row['total'];
            
            // Por género
            $result = $conn->query("SELECT genero, COUNT(*) as count FROM estudiantes GROUP BY genero");
            $stats['por_genero'] = [];
            while ($row = $result->fetch_assoc()) {
                $stats['por_genero'][$row['genero']] = (int)$row['count'];
            }
            
            // Por grado
            $result = $conn->query("SELECT grado, COUNT(*) as count FROM estudiantes GROUP BY grado ORDER BY grado");
            $stats['por_grado'] = [];
            while ($row = $result->fetch_assoc()) {
                $stats['por_grado'][$row['grado']] = (int)$row['count'];
            }
            
            // Por ciudad
            $result = $conn->query("SELECT ciudad, COUNT(*) as count FROM estudiantes GROUP BY ciudad ORDER BY count DESC LIMIT 10");
            $stats['por_ciudad'] = [];
            while ($row = $result->fetch_assoc()) {
                $stats['por_ciudad'][$row['ciudad']] = (int)$row['count'];
            }
            
            echo json_encode([
                'success' => true,
                'data' => $stats
            ]);
            break;
            
        default:
            throw new Exception('Acción no válida');
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'error_code' => $e->getCode()
    ]);
} catch (Error $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error interno del servidor',
        'error_code' => 500
    ]);
}

$conn->close();
?>