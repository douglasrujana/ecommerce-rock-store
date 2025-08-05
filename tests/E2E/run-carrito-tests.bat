@echo off
REM 🛒 EJECUTOR DE PRUEBAS E2E DEL CARRITO
REM 
REM Script para ejecutar todas las pruebas del módulo de carrito
REM con diferentes configuraciones y generar reportes detallados.
REM
REM OPCIONES DE EJECUCIÓN:
REM - Todas las pruebas del carrito
REM - Pruebas específicas por archivo
REM - Modo debug con screenshots
REM - Reportes HTML detallados
REM
REM @author Rock Store Testing Team
REM @version 1.0.0

echo.
echo ========================================
echo 🛒 PRUEBAS E2E - MÓDULO CARRITO
echo ========================================
echo.

REM Verificar que Node.js y Playwright están disponibles
where node >nul 2>nul
if %ERRORLEVEL% neq 0 (
    echo ❌ Error: Node.js no está instalado o no está en PATH
    pause
    exit /b 1
)

where npx >nul 2>nul
if %ERRORLEVEL% neq 0 (
    echo ❌ Error: NPX no está disponible
    pause
    exit /b 1
)

REM Verificar que Laravel está corriendo
echo 🔍 Verificando que Laravel esté corriendo...
curl -s http://localhost:8000 >nul 2>nul
if %ERRORLEVEL% neq 0 (
    echo ❌ Error: Laravel no está corriendo en http://localhost:8000
    echo    Por favor ejecuta: php artisan serve
    pause
    exit /b 1
)

echo ✅ Laravel está corriendo correctamente
echo.

REM Menú de opciones
echo Selecciona el tipo de prueba a ejecutar:
echo.
echo 1. 🛒 Todas las pruebas del carrito
echo 2. ➕ Solo pruebas de agregar productos
echo 3. 🔧 Solo pruebas de gestión del carrito
echo 4. 🔄 Solo pruebas de flujos completos
echo 5. 🐛 Modo debug (con screenshots y videos)
echo 6. 📊 Generar reporte completo
echo 7. 🚀 Pruebas de performance
echo 8. 📱 Pruebas móviles
echo 9. 🔍 Prueba específica (interactivo)
echo.

set /p choice="Ingresa tu opción (1-9): "

REM Configurar variables base
set TIMESTAMP=%date:~-4,4%%date:~-10,2%%date:~-7,2%_%time:~0,2%%time:~3,2%%time:~6,2%
set TIMESTAMP=%TIMESTAMP: =0%
set REPORT_DIR=reports\carrito_%TIMESTAMP%
set BASE_CMD=npx playwright test

REM Crear directorio de reportes
if not exist "reports" mkdir reports
if not exist "%REPORT_DIR%" mkdir "%REPORT_DIR%"

echo.
echo 📁 Reportes se guardarán en: %REPORT_DIR%
echo.

REM Ejecutar según la opción seleccionada
if "%choice%"=="1" goto :all_tests
if "%choice%"=="2" goto :add_tests
if "%choice%"=="3" goto :manage_tests
if "%choice%"=="4" goto :flow_tests
if "%choice%"=="5" goto :debug_tests
if "%choice%"=="6" goto :full_report
if "%choice%"=="7" goto :performance_tests
if "%choice%"=="8" goto :mobile_tests
if "%choice%"=="9" goto :specific_test

echo ❌ Opción inválida
pause
exit /b 1

:all_tests
echo 🛒 Ejecutando todas las pruebas del carrito...
%BASE_CMD% tests/02-carrito-agregar.spec.js tests/03-carrito-gestionar.spec.js tests/04-carrito-flujo-completo.spec.js --reporter=html --output=%REPORT_DIR%
goto :end

:add_tests
echo ➕ Ejecutando pruebas de agregar productos...
%BASE_CMD% tests/02-carrito-agregar.spec.js --reporter=html --output=%REPORT_DIR%
goto :end

:manage_tests
echo 🔧 Ejecutando pruebas de gestión del carrito...
%BASE_CMD% tests/03-carrito-gestionar.spec.js --reporter=html --output=%REPORT_DIR%
goto :end

:flow_tests
echo 🔄 Ejecutando pruebas de flujos completos...
%BASE_CMD% tests/04-carrito-flujo-completo.spec.js --reporter=html --output=%REPORT_DIR%
goto :end

:debug_tests
echo 🐛 Ejecutando en modo debug...
%BASE_CMD% tests/02-carrito-agregar.spec.js tests/03-carrito-gestionar.spec.js tests/04-carrito-flujo-completo.spec.js --headed --debug --reporter=html --output=%REPORT_DIR%
goto :end

:full_report
echo 📊 Generando reporte completo...
%BASE_CMD% tests/02-carrito-agregar.spec.js tests/03-carrito-gestionar.spec.js tests/04-carrito-flujo-completo.spec.js --reporter=html,json,junit --output=%REPORT_DIR%
goto :end

:performance_tests
echo 🚀 Ejecutando pruebas de performance...
%BASE_CMD% tests/04-carrito-flujo-completo.spec.js --grep "Performance" --reporter=html --output=%REPORT_DIR%
goto :end

:mobile_tests
echo 📱 Ejecutando pruebas móviles...
%BASE_CMD% tests/04-carrito-flujo-completo.spec.js --grep "móvil" --reporter=html --output=%REPORT_DIR%
goto :end

:specific_test
echo.
echo Pruebas disponibles:
echo 1. Agregar producto con cantidad por defecto
echo 2. Agregar producto con cantidad personalizada
echo 3. Validar límites de cantidad
echo 4. Incrementar cantidad de producto
echo 5. Eliminar producto individual
echo 6. Vaciar carrito completo
echo 7. Flujo comprador decidido
echo 8. Flujo comprador explorador
echo 9. Flujo comprador indeciso
echo.
set /p test_choice="Selecciona el número de prueba: "

REM Mapear números a nombres de pruebas
if "%test_choice%"=="1" set TEST_NAME="Debe agregar producto con cantidad por defecto"
if "%test_choice%"=="2" set TEST_NAME="Debe agregar producto con cantidad personalizada"
if "%test_choice%"=="3" set TEST_NAME="Debe respetar límites de cantidad"
if "%test_choice%"=="4" set TEST_NAME="Debe incrementar cantidad de producto"
if "%test_choice%"=="5" set TEST_NAME="Debe eliminar producto individual"
if "%test_choice%"=="6" set TEST_NAME="Debe vaciar carrito completo"
if "%test_choice%"=="7" set TEST_NAME="Flujo: Comprador decidido"
if "%test_choice%"=="8" set TEST_NAME="Flujo: Comprador explorador"
if "%test_choice%"=="9" set TEST_NAME="Flujo: Comprador indeciso"

if defined TEST_NAME (
    echo 🎯 Ejecutando prueba específica: %TEST_NAME%
    %BASE_CMD% --grep %TEST_NAME% --reporter=html --output=%REPORT_DIR%
) else (
    echo ❌ Opción de prueba inválida
    pause
    exit /b 1
)
goto :end

:end
echo.
echo ========================================
echo 📊 RESUMEN DE EJECUCIÓN
echo ========================================

REM Verificar si las pruebas pasaron
if %ERRORLEVEL% equ 0 (
    echo ✅ Todas las pruebas pasaron exitosamente
    echo.
    echo 📁 Reportes disponibles en: %REPORT_DIR%
    echo 🌐 Reporte HTML: %REPORT_DIR%\index.html
    
    REM Abrir reporte automáticamente si existe
    if exist "%REPORT_DIR%\index.html" (
        echo.
        set /p open_report="¿Abrir reporte en navegador? (s/n): "
        if /i "!open_report!"=="s" start "" "%REPORT_DIR%\index.html"
    )
) else (
    echo ❌ Algunas pruebas fallaron
    echo 📋 Revisa los reportes para más detalles
    echo 🌐 Reporte HTML: %REPORT_DIR%\index.html
)

echo.
echo 🔧 Comandos útiles:
echo    - Ver reportes: start %REPORT_DIR%\index.html
echo    - Limpiar reportes: rmdir /s reports
echo    - Ejecutar Laravel: php artisan serve
echo    - Ver logs Laravel: tail -f storage/logs/laravel.log
echo.

REM Mostrar estadísticas si están disponibles
if exist "%REPORT_DIR%\results.json" (
    echo 📈 Estadísticas rápidas:
    findstr /c:"\"tests\":" /c:"\"passed\":" /c:"\"failed\":" "%REPORT_DIR%\results.json" 2>nul
    echo.
)

echo Presiona cualquier tecla para salir...
pause >nul