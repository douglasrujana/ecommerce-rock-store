<?php

use App\Http\Controllers\EntradaController;
use App\Http\Controllers\Entradamodelcontroller;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\controllers\RollController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\Auth\PerfilController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Mockery\Expectation;

// 🏠 RUTAS DE LA TIENDA WEB
Route::get('/', [\App\Http\Controllers\WebController::class, 'index'])
        ->name('web.index');
Route::get('/catalogo', [\App\Http\Controllers\WebController::class, 'catalogo'])
        ->name('web.catalogo');
Route::get('/album/{id}', [\App\Http\Controllers\WebController::class, 'show'])
        ->name('web.show');

// 🔧 RUTA DE PRUEBA DEL CARRITO
Route::get('/test-carrito', function() {
    return view('web.pedido', [
        'carrito' => [],
        'carrito_vacio' => true,
        'total_items' => 0,
        'subtotal' => 0,
        'impuestos' => 0,
        'total' => 0,
        'subtotal_formateado' => '$0.00',
        'impuestos_formateado' => '$0.00',
        'total_formateado' => '$0.00'
    ]);
});

// 🛒 RUTAS DEL CARRITO DE COMPRAS
Route::prefix('carrito')->name('carrito.')->group(function () {
    // 📋 Mostrar carrito
    Route::get('/', [\App\Http\Controllers\CarritoController::class, 'mostrar'])->name('mostrar');

    // 🎸 Agregar producto al carrito
    Route::post('/agregar', [\App\Http\Controllers\CarritoController::class, 'agregar'])->name('agregar');

    // 🔄 Actualizar cantidad (set/increment/decrement)
    Route::patch('/actualizar', [\App\Http\Controllers\CarritoController::class, 'actualizar'])->name('actualizar');

    // 🗑️ Eliminar producto específico
    Route::delete('/eliminar', [\App\Http\Controllers\CarritoController::class, 'eliminar'])->name('eliminar');

    // 🧹 Vaciar carrito completo
    Route::delete('/vaciar', [\App\Http\Controllers\CarritoController::class, 'vaciar'])->name('vaciar');
});

// 📊 API DEL CARRITO (para AJAX)
Route::prefix('api/carrito')->name('api.carrito.')->group(function () {
    // 🔢 Contar items en carrito
    Route::get('/contar', [\App\Http\Controllers\CarritoController::class, 'contar'])->name('contar');

    // 💰 Obtener totales del carrito
    Route::get('/total', [\App\Http\Controllers\CarritoController::class, 'total'])->name('total');

    // 🔍 Verificar disponibilidad de productos
    Route::get('/verificar', [\App\Http\Controllers\CarritoController::class, 'verificar'])->name('verificar');
});

// 🎸 RUTAS LEGACY (mantener por compatibilidad)
Route::get('/producto', function () {
    return view('web.item');
});
// Redirigir /pedido al carrito
Route::get('/pedido', function () {
    return redirect()->route('carrito.mostrar');
});

// APP
Route::get('/app', function () {
    return view('app');
});

//Route::view('/producto', 'almacen.producto')->name('producto');

// Route::get('/producto', function () {
//     // return view('almacen.producto', ['nombre' => 'Lapto', 'marca' => 'Lenovo']);
//     // return view('almacen.producto')->with('nombre', 'Lapto')->with('marca', 'Lenovo');
//     $nombre = 'Lapto';
//     $marca = 'Lenovo';
//     // compact() Crea un array asociativo con los valores de las variables
//     return view('almacen.producto', compact('nombre', 'marca'));
// });

// Definición básica de rutas
// Route::get('/saludo', function () {
//     return 'Hola desde la ruta saludo';
// });

// Parámetros en la ruta
// Route::get('/saludo/{nombre}', function ($nombre) {
//     return 'Hola ' . $nombre;
// });

// Rutas con parámetros opcionales
// Route::get('/saludo/{nombre?}', function ($nombre = 'Invitado') {
//     return 'Hola ' . $nombre;
// });

// Ruta Welcome
// Route::get('/welcome', function () {
//     return view('welcome');
// });

// Redirección de rutas
// Route::redirect('/saludo', '/redireccion');

// Route::get('/redireccion', function () {
//     return redirect('/welcome');
// });


// Grupos de rutas
// Route::prefix('admin')->group(function () {
//     Route::get('/dashboard', function () {
//         return 'Dashboard';
//     });

//     Route::get('/users', function () {
//         return 'Users';
//     });
// });

// env() Obtener variables de entorno
// Route::get('/env', function () {
//     return env('APP_NAME');
// });

// Depuración rápida
// Route::get('/debug', function () {
//     dd('Debugging');
// });

// dd() Detener la ejecución del código
// Route::get('/dd', function () {
//     $users = ['Juan', 'Pedro', 'Maria'];
//     dd($users);
// });

// config() Obtener valores de configuración
// Route::get('/config', function () {
//     return config('app.name');
// });

Route::get('condicional/{nota}', function ($nota=20) {
    return view('estructuras.condicional', compact('nota'));
});

//
Route::get('categoria', function () {
    return view('categoria');
});

//
Route::get('contacto', function () {
    return view('contacto');
});

// Probar conexón BD
Route::get('conexion', function () {
    try {
        DB::connection()->getPdo();
        return 'Conexión exitosa';
    } catch (\Exception $e) {
        return 'Conexión fallida' . $e->getMessage();
    }
});

// Query Builder

// Busca todos los registros
Route::get('query', function () {
    $usuarios = DB::table('users')->get();
    return $usuarios;
});

// Buscar un solo registro
Route::get('find', function () {
    $usuarios = DB::table('users')->first();
    return $usuarios;
});

// buscar por filtro
Route::get('filtro', function () {
    $entradas = DB::table('entradas')
    ->where('user_id', 5)
    ->where('titulo', 'LIKE', 'e%')
    ->orWhere('titulo', 'LIKE', 'l%')
    ->get();
    return $entradas;
});

// Unión de tablas usando Join
Route::get('join', function () {
    $entradas = DB::table('entradas')
        ->join('users','entradas.user_id', '=', 'users.id')
        ->select('entradas.titulo', 'entradas.tag', 'entradas.imagen', 'users.email')
        ->get();
    return $entradas;
});

// Insercción de registros
Route::get('insert', function () {
    $insert = DB::table('users')
        ->insert([
            "name" => "sapin",
            "email" => 'sapin@sapin.com',
            "password" => "123"
        ]);
    return $insert;
});

//
Route::get('/getId', function () {
    $insertId = DB::table('users')
        ->insertGetId([
            "name" => "sapin",
            "email" => 'sapin2@sapin.com',
            "password" => "123"
        ]);
    return $insertId;
});

//
Route::get('/index', [EntradaController::class, 'index']);

// Controlador tipo Resource
//Route::resource('entrada', Entradamodelcontroller::class);
//Route::resource('entrada', Entradamodelcontroller::class)->only('index', 'show');
Route::resource('entrada', Entradamodelcontroller::class);

Route::get('response', function () {
    return response('Respuesta:',200);
});

// Admin LTE 4
Route::get('app', function () {
    return view('usuario.index');
});

//
Route::get('action', function () {
    return view('usuario.action');
});

// Rutas privadas
Route::middleware(['auth'])->group(function () {
    // CRUD: Formulario de Usuarios
    Route::resource('usuarios', UserController::class);

    // Ruta Roles
    Route::resource('roles', RollController::class);

    // Ruta Prodcutos
    Route::resource('productos', ProductoController::class);

    // Activas o sesactivar un usuario
    Route::patch('usuarios/{id}toggle', [UserController::class, 'toggleStatus'])
                ->name('usuarios.toggle');
    // Dashboard
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    // Logout
    Route::post('logout', function () {
        Auth::logout();
        return redirect('/login');
    })->name('logout');

    // Rutas para modificar el perfil del usuario
    Route::get('/perfil', [PerfilController::class, 'editPerfil'])
            ->name('perfil.editPerfil');
    Route::put('/perfil', [PerfilController::class, 'update'])->name('perfil.update');
});

// Rutas públicas
Route::middleware('guest')->group(function () {
    //
    Route::get('login', function () {
        return view('auth.login');
    })->name('login');

    // Auth
    Route::post('login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/registro', [RegisterController::class, 'showRegistroForm'])
            ->name('registro');

    Route::post('/registro', [RegisterController::class, 'registrar'])
            ->name('registro.store');

    // Autenticación Social
    Route::get('/auth/{provider}', [\App\Http\Controllers\Auth\SocialAuthController::class, 'redirectToProvider'])
            ->name('social.redirect');
    Route::get('/auth/{provider}/callback', [\App\Http\Controllers\Auth\SocialAuthController::class, 'handleProviderCallback'])
            ->name('social.callback');

    // Rrcuperar Password
    Route::get('password/reset', [ResetPasswordController::class, 'showRequestForm'])->name('password.request');
    Route::post('password/email', [ResetPasswordController::class, 'sendResetLinkEmail'])->name('password.send-link');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'resetPassword'])->name('password.update');
});

// Rutas para testing E2E (solo en entorno testing)
if (app()->environment('testing')) {
    Route::prefix('api/test')->group(function () {
        Route::post('create-user', [\App\Http\Controllers\TestController::class, 'createUser']);
        Route::post('get-reset-token', [\App\Http\Controllers\TestController::class, 'getResetToken']);
        Route::post('create-reset-token', [\App\Http\Controllers\TestController::class, 'createResetToken']);
        Route::post('cleanup-user', [\App\Http\Controllers\TestController::class, 'cleanupUser']);
    });

    Route::get('test-setup', function () {
        return response()->json(['message' => 'Test environment ready']);
    });



}
