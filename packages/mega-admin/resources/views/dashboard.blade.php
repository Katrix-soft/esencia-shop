<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mega Admin - Dashboard</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f3f4f6; margin: 0; padding: 0; }
        .sidebar { width: 250px; background: #1f2937; color: white; height: 100vh; position: fixed; }
        .sidebar h2 { text-align: center; padding: 20px 0; border-bottom: 1px solid #374151; margin: 0; }
        .sidebar ul { list-style: none; padding: 0; margin: 0; }
        .sidebar ul li a { display: block; padding: 15px 20px; color: #d1d5db; text-decoration: none; border-bottom: 1px solid #374151; }
        .sidebar ul li a:hover { background: #374151; color: white; }
        .main-content { margin-left: 250px; padding: 20px; }
        .header { background: white; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-top: 20px; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Mega Admin</h2>
        <ul>
            <li><a href="#">Dashboard</a></li>
            <li><a href="#">Usuarios</a></li>
            <li><a href="#">Roles y Permisos</a></li>
            <li><a href="#">Configuración</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="header">
            <h1>Bienvenido al Panel de Administración</h1>
            <div>
                Usuario: {{ auth()->user()->name ?? 'Admin' }}
            </div>
        </div>
        
        <div class="card">
            <h3>Visión General</h3>
            <p>Este es el panel principal de Mega Admin. Aquí podrás gestionar tu aplicación.</p>
        </div>
    </div>
</body>
</html>
