@echo off
echo 🎭 Ejecutando Pruebas E2E con Playwright
echo =====================================

echo.
echo 📦 Instalando dependencias...
cd tests\E2E
call npm install

echo.
echo 🌐 Instalando navegadores...
call npx playwright install

echo.
echo 🚀 Iniciando servidor Laravel...
start /B php -S localhost:8000 -t public

echo.
echo ⏳ Esperando que el servidor esté listo...
timeout /t 3

echo.
echo 🧪 Ejecutando pruebas E2E...
call npx playwright test password-reset-simplified.spec.js --reporter=html

echo.
echo 📊 Abriendo reporte...
call npx playwright show-report

echo.
echo ✅ Pruebas completadas!
pause