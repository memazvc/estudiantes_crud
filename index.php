<?php include 'conexion.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Estudiantes - Sistema Moderno</title>
    
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6.5 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Animate.css -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --warning-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --danger-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --dark-gradient: linear-gradient(135deg, #434343 0%, #000000 100%);
            --glass-bg: rgba(255, 255, 255, 0.25);
            --glass-border: rgba(255, 255, 255, 0.18);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--primary-gradient);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Animated background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.3) 0%, transparent 50%);
            z-index: -1;
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        /* Glass morphism effect */
        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .main-container {
            margin-top: 2rem;
            margin-bottom: 2rem;
        }

        .main-title {
            color: white;
            font-weight: 700;
            text-align: center;
            margin-bottom: 2rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            font-size: 2.5rem;
        }

        .form-card {
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .form-label {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-control, .form-select {
            border: 2px solid transparent;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            background: rgba(255, 255, 255, 0.95);
        }

        .btn-custom {
            border: none;
            border-radius: 50px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-custom:hover::before {
            left: 100%;
        }

        .btn-primary-custom {
            background: var(--primary-gradient);
            color: white;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-success-custom {
            background: var(--success-gradient);
            color: white;
        }

        .btn-warning-custom {
            background: var(--warning-gradient);
            color: white;
        }

        .btn-danger-custom {
            background: var(--danger-gradient);
            color: white;
        }

        .table-card {
            padding: 1.5rem;
        }

        .table-modern {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .table-modern th {
            background: var(--primary-gradient);
            color: white;
            font-weight: 600;
            text-align: center;
            padding: 1rem 0.75rem;
            border: none;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.85rem;
        }

        .table-modern td {
            padding: 1rem 0.75rem;
            vertical-align: middle;
            border-color: rgba(0, 0, 0, 0.05);
        }

        .table-modern tbody tr {
            transition: all 0.3s ease;
        }

        .table-modern tbody tr:hover {
            background: rgba(102, 126, 234, 0.05);
            transform: scale(1.01);
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-action {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .btn-action:hover {
            transform: translateY(-2px) scale(1.1);
        }

        .section-title {
            color: white;
            font-weight: 600;
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.3);
        }

        .loading-spinner {
            display: none;
            text-align: center;
            padding: 2rem;
        }

        .spinner-border-custom {
            width: 3rem;
            height: 3rem;
            border-width: 0.3em;
            border-color: #667eea;
            border-right-color: transparent;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .main-title {
                font-size: 2rem;
            }
            
            .form-card, .table-card {
                padding: 1rem;
            }
            
            .btn-custom {
                padding: 0.6rem 1.5rem;
            }
            
            .action-buttons {
                gap: 0.25rem;
            }
            
            .btn-action {
                width: 35px;
                height: 35px;
                font-size: 0.8rem;
            }
        }

        /* Toast notifications */
        .toast-container {
            z-index: 9999;
        }

        .toast-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Modal improvements */
        .modal-content {
            border-radius: 20px;
            border: none;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
        }

        .modal-header {
            background: var(--primary-gradient);
            color: white;
            border-radius: 20px 20px 0 0;
            border-bottom: none;
        }
    </style>
</head>
<body>
    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" id="toastContainer"></div>

    <div class="container main-container">
        <!-- Header -->
        <h1 class="main-title animate__animated animate__fadeInDown">
            <i class="fas fa-user-graduate me-3"></i>
            Sistema de Gestión de Estudiantes
        </h1>

        <!-- Formulario -->
        <div class="glass-card form-card animate__animated animate__fadeInUp">
            <h3 class="section-title mb-4">
                <i class="fas fa-user-plus me-2"></i>
                <span id="form-title">Registrar Estudiante</span>
            </h3>
            
            <form id="studentForm">
                <input type="hidden" id="student-id" name="id">
                
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label">
                            <i class="fas fa-user text-primary"></i>
                            Nombre Completo
                        </label>
                        <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" required>
                    </div>
                    
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label">
                            <i class="fas fa-id-card text-info"></i>
                            Número de Documento
                        </label>
                        <input type="text" class="form-control" id="numero_documento" name="numero_documento" required>
                    </div>
                    
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label">
                            <i class="fas fa-layer-group text-warning"></i>
                            Grado
                        </label>
                        <input type="text" class="form-control" id="grado" name="grado" value="11-2" required>
                    </div>
                    
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label">
                            <i class="fas fa-home text-success"></i>
                            Barrio
                        </label>
                        <input type="text" class="form-control" id="barrio" name="barrio" required>
                    </div>
                    
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label">
                            <i class="fas fa-city text-danger"></i>
                            Ciudad
                        </label>
                        <input type="text" class="form-control" id="ciudad" name="ciudad" required>
                    </div>
                    
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label">
                            <i class="fas fa-venus-mars text-purple"></i>
                            Género
                        </label>
                        <select id="genero" name="genero" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-custom btn-primary-custom me-2">
                        <i class="fas fa-save me-2"></i>
                        <span id="btn-text">Guardar Estudiante</span>
                    </button>
                    
                    <button type="button" class="btn btn-custom btn-secondary" id="btn-cancel" style="display: none;">
                        <i class="fas fa-times me-2"></i>
                        Cancelar
                    </button>
                </div>
            </form>
        </div>

        <!-- Loading Spinner -->
        <div class="loading-spinner" id="loadingSpinner">
            <div class="spinner-border spinner-border-custom" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2 text-white">Procesando...</p>
        </div>

        <!-- Tabla -->
        <div class="glass-card table-card animate__animated animate__fadeInUp">
            <h3 class="section-title mb-4">
                <i class="fas fa-list me-2"></i>
                Lista de Estudiantes
            </h3>
            
            <div class="table-responsive">
                <table class="table table-hover table-modern" id="studentsTable">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag me-1"></i>ID</th>
                            <th><i class="fas fa-user me-1"></i>Nombre</th>
                            <th><i class="fas fa-id-card me-1"></i>Documento</th>
                            <th><i class="fas fa-layer-group me-1"></i>Grado</th>
                            <th><i class="fas fa-home me-1"></i>Barrio</th>
                            <th><i class="fas fa-city me-1"></i>Ciudad</th>
                            <th><i class="fas fa-venus-mars me-1"></i>Género</th>
                            <th><i class="fas fa-cogs me-1"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="studentsTableBody">
                        <!-- Content loaded via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        $(document).ready(function() {
            // Load students on page load
            loadStudents();

            // Form submission
            $('#studentForm').on('submit', function(e) {
                e.preventDefault();
                submitForm();
            });

            // Cancel button
            $('#btn-cancel').on('click', function() {
                resetForm();
            });
        });

        function loadStudents() {
            $.ajax({
                url: 'api.php',
                method: 'GET',
                data: { action: 'list' },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        displayStudents(response.data);
                    } else {
                        showToast('Error al cargar estudiantes', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading students:', xhr.responseText);
                    showToast('Error de conexión', 'error');
                }
            });
        }

        function displayStudents(students) {
            const tbody = $('#studentsTableBody');
            tbody.empty();

            if (students.length === 0) {
                tbody.append(`
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No hay estudiantes registrados</p>
                        </td>
                    </tr>
                `);
                return;
            }

            students.forEach(function(student) {
                const row = `
                    <tr class="animate__animated animate__fadeIn">
                        <td class="fw-bold">${student.id}</td>
                        <td>${student.nombre_completo}</td>
                        <td>${student.numero_documento}</td>
                        <td><span class="badge bg-primary rounded-pill">${student.grado}</span></td>
                        <td>${student.barrio}</td>
                        <td>${student.ciudad}</td>
                        <td><span class="badge bg-secondary rounded-pill">${student.genero}</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-action btn-warning-custom" onclick="editStudent(${student.id})" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-action btn-danger-custom" onclick="deleteStudent(${student.id})" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
                tbody.append(row);
            });
        }

        function submitForm() {
            const form = document.getElementById('studentForm');
            const formData = new FormData(form);
            
            // Determinar la acción basada en si estamos editando o creando
            const isEditing = $('#student-id').val() !== '';
            const action = isEditing ? 'actualizar' : 'crear';
            
            // Agregar la acción correcta al FormData
            formData.set('action', action);
            
            showLoading(true);

            $.ajax({
                url: 'api.php',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    showLoading(false);
                    
                    if (response.success) {
                        const message = action === 'crear' ? 'Estudiante registrado exitosamente' : 'Estudiante actualizado exitosamente';
                        
                        showSweetAlert('success', '¡Éxito!', message);
                        resetForm();
                        loadStudents();
                    } else {
                        showSweetAlert('error', 'Error', response.message || 'Error al procesar la solicitud');
                    }
                },
                error: function(xhr, status, error) {
                    showLoading(false);
                    console.error('Error AJAX:', xhr.responseText);
                    showSweetAlert('error', 'Error', 'Error de conexión con el servidor');
                }
            });
        }

        function editStudent(id) {
            $.ajax({
                url: 'api.php',
                method: 'GET',
                data: { action: 'get', id: id },
                dataType: 'json',
                success: function(response) {
                    if (response.success && response.data) {
                        const student = response.data;
                        
                        $('#student-id').val(student.id);
                        $('#nombre_completo').val(student.nombre_completo);
                        $('#numero_documento').val(student.numero_documento);
                        $('#grado').val(student.grado);
                        $('#barrio').val(student.barrio);
                        $('#ciudad').val(student.ciudad);
                        $('#genero').val(student.genero);
                        
                        $('#form-title').text('Editar Estudiante');
                        $('#btn-text').text('Actualizar Estudiante');
                        $('#btn-cancel').show();
                        
                        // Scroll to form
                        $('html, body').animate({
                            scrollTop: $('.form-card').offset().top - 20
                        }, 500);
                    } else {
                        showToast('Error al cargar datos del estudiante', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al editar:', xhr.responseText);
                    showToast('Error de conexión', 'error');
                }
            });
        }

        function deleteStudent(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                background: 'rgba(255, 255, 255, 0.95)',
                backdrop: 'rgba(0, 0, 0, 0.4)'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'api.php',
                        method: 'POST',
                        data: {
                            action: 'eliminar',
                            id: id
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                showSweetAlert('success', '¡Eliminado!', 'El estudiante ha sido eliminado exitosamente');
                                loadStudents();
                            } else {
                                showSweetAlert('error', 'Error', response.message || 'Error al eliminar el estudiante');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error al eliminar:', xhr.responseText);
                            showSweetAlert('error', 'Error', 'Error de conexión con el servidor');
                        }
                    });
                }
            });
        }

        function resetForm() {
            document.getElementById('studentForm').reset();
            $('#student-id').val('');
            $('#form-title').text('Registrar Estudiante');
            $('#btn-text').text('Guardar Estudiante');
            $('#btn-cancel').hide();
            $('#grado').val('11-2');
        }

        function showLoading(show) {
            if (show) {
                $('#loadingSpinner').show();
                $('.form-card, .table-card').css('opacity', '0.5');
            } else {
                $('#loadingSpinner').hide();
                $('.form-card, .table-card').css('opacity', '1');
            }
        }

        function showSweetAlert(type, title, text) {
            Swal.fire({
                icon: type,
                title: title,
                text: text,
                confirmButtonColor: type === 'success' ? '#667eea' : '#d33',
                background: 'rgba(255, 255, 255, 0.95)',
                backdrop: 'rgba(0, 0, 0, 0.4)'
            });
        }

        function showToast(message, type) {
            const toastHtml = `
                <div class="toast toast-custom" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <i class="fas fa-${type === 'success' ? 'check-circle text-success' : 'exclamation-triangle text-danger'} me-2"></i>
                        <strong class="me-auto">${type === 'success' ? 'Éxito' : 'Error'}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                    </div>
                    <div class="toast-body">
                        ${message}
                    </div>
                </div>
            `;
            
            $('#toastContainer').append(toastHtml);
            const toast = new bootstrap.Toast($('#toastContainer .toast').last()[0]);
            toast.show();
        }
    </script>
</body>
</html>