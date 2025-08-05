# Manual de Comandos de la CLI de Gemini

Este manual describe los comandos disponibles en la interfaz de línea de comandos de Gemini, que son las herramientas que el agente puede utilizar para interactuar con el sistema de archivos, ejecutar comandos de shell, realizar búsquedas web y más.

---

## 1. `list_directory`

### Descripción
Lista los nombres de archivos y subdirectorios directamente dentro de una ruta de directorio especificada. Puede ignorar opcionalmente las entradas que coincidan con los patrones glob proporcionados.

### Parámetros
-   `path` (string, requerido): La ruta absoluta al directorio a listar (debe ser absoluta, no relativa).
-   `ignore` (list[string], opcional): Lista de patrones glob a ignorar.
-   `respect_git_ignore` (boolean, opcional): Si se deben respetar los patrones `.gitignore` al listar archivos. Solo disponible en repositorios Git. Por defecto es `true`.

### Ejemplos
```python
# Listar todos los elementos en el directorio actual
print(default_api.list_directory(path='C:/MyCode/php/udy_curso_laravel12/sistema'))

# Listar elementos ignorando archivos .log
print(default_api.list_directory(path='C:/MyCode/php/udy_curso_laravel12/sistema', ignore=['*.log']))
```

---

## 2. `read_file`

### Descripción
Lee y devuelve el contenido de un archivo especificado del sistema de archivos local. Maneja archivos de texto, imágenes (PNG, JPG, GIF, WEBP, SVG, BMP) y archivos PDF. Para archivos de texto, puede leer rangos de líneas específicos.

### Parámetros
-   `absolute_path` (string, requerido): La ruta absoluta al archivo a leer (ej. '/home/user/project/file.txt'). Las rutas relativas no son compatibles. Debe proporcionar una ruta absoluta.
-   `limit` (float, opcional): Para archivos de texto, número máximo de líneas a leer. Usar con `offset` para paginar archivos grandes. Si se omite, lee el archivo completo (si es factible, hasta un límite predeterminado).
-   `offset` (float, opcional): Para archivos de texto, el número de línea (basado en 0) desde donde empezar a leer. Requiere que `limit` esté establecido. Usar para paginar archivos grandes.

### Ejemplos
```python
# Leer el contenido completo de un archivo
print(default_api.read_file(absolute_path='C:/MyCode/php/udy_curso_laravel12/sistema/README.md'))

# Leer las primeras 10 líneas de un archivo
print(default_api.read_file(absolute_path='C:/MyCode/php/udy_curso_laravel12/sistema/app/Http/Controllers/Controller.php', limit=10))
```

---

## 3. `search_file_content`

### Descripción
Busca un patrón de expresión regular dentro del contenido de los archivos en un directorio especificado (o el directorio de trabajo actual). Puede filtrar archivos por un patrón glob. Devuelve las líneas que contienen coincidencias, junto con sus rutas de archivo y números de línea.

### Parámetros
-   `pattern` (string, requerido): La expresión regular (regex) a buscar dentro del contenido de los archivos (ej. 'function\\s+myFunction', 'import\\s+\\{.*\\}\\s+from\\s+.* ').
-   `include` (string, opcional): Un patrón glob para filtrar qué archivos se buscan (ej. '*.js', '*.{ts,tsx}', 'src/**'). Si se omite, busca en todos los archivos (respetando posibles ignorados globales).
-   `path` (string, opcional): La ruta absoluta al directorio donde buscar. Si se omite, busca en el directorio de trabajo actual.

### Ejemplos
```python
# Buscar una función específica en todos los archivos PHP
print(default_api.search_file_content(pattern='function\\s+index', include='*.php', path='C:/MyCode/php/udy_curso_laravel12/sistema/app/Http/Controllers'))

# Buscar una cadena en todos los archivos .env
print(default_api.search_file_content(pattern='APP_NAME', include='*.env', path='C:/MyCode/php/udy_curso_laravel12/sistema'))
```

---

## 4. `glob`

### Descripción
Encuentra eficientemente archivos que coinciden con patrones glob específicos (ej. `src/**/*.ts`, `**/*.md`), devolviendo rutas absolutas ordenadas por tiempo de modificación (más reciente primero). Ideal para localizar rápidamente archivos basados en su nombre o estructura de ruta, especialmente en grandes bases de código.

### Parámetros
-   `pattern` (string, requerido): El patrón glob a comparar (ej. '**/*.py', 'docs/*.md').
-   `case_sensitive` (boolean, opcional): Si la búsqueda debe ser sensible a mayúsculas y minúsculas. Por defecto es `false`.
-   `path` (string, opcional): La ruta absoluta al directorio donde buscar. Si se omite, busca en el directorio raíz.
-   `respect_git_ignore` (boolean, opcional): Si se deben respetar los patrones `.gitignore` al encontrar archivos. Solo disponible en repositorios Git. Por defecto es `true`.

### Ejemplos
```python
# Encontrar todos los archivos .php en el directorio actual y subdirectorios
print(default_api.glob(pattern='**/*.php', path='C:/MyCode/php/udy_curso_laravel12/sistema'))

# Encontrar todos los archivos .blade.php en la carpeta views
print(default_api.glob(pattern='resources/views/**/*.blade.php', path='C:/MyCode/php/udy_curso_laravel12/sistema'))
```

---

## 5. `replace`

### Descripción
Reemplaza texto dentro de un archivo. Por defecto, reemplaza una sola ocurrencia, pero puede reemplazar múltiples ocurrencias cuando se especifica `expected_replacements`. Esta herramienta requiere proporcionar un contexto significativo alrededor del cambio para asegurar una focalización precisa. Siempre use la herramienta `read_file` para examinar el contenido actual del archivo antes de intentar un reemplazo de texto.

### Parámetros
-   `file_path` (string, requerido): La ruta absoluta al archivo a modificar. Debe comenzar con '/'.
-   `new_string` (string, requerido): El texto literal exacto con el que reemplazar `old_string`, preferiblemente sin escapar. Proporcione el texto EXACTO. Asegúrese de que el código resultante sea correcto e idiomático.
-   `old_string` (string, requerido): El texto literal exacto a reemplazar, preferiblemente sin escapar. Para reemplazos únicos (por defecto), incluya al menos 3 líneas de contexto ANTES y DESPUÉS del texto objetivo, coincidiendo con el espacio en blanco y la indentación con precisión. Para múltiples reemplazos, especifique el parámetro `expected_replacements`. Si esta cadena no es el texto literal exacto (es decir, si la escapó) o no coincide exactamente, la herramienta fallará.
-   `expected_replacements` (float, opcional): Número de reemplazos esperados. Por defecto es 1 si no se especifica. Usar cuando se desea reemplazar múltiples ocurrencias.

### Ejemplos
```python
# Reemplazar una línea específica en un archivo
print(default_api.replace(
    file_path='C:/MyCode/php/udy_curso_laravel12/sistema/config/app.php',
    old_string="    'timezone' => 'UTC',",
    new_string="    'timezone' => 'America/Lima',"
))

# Reemplazar múltiples ocurrencias de una cadena
# (Asegúrese de que 'old_string' sea único para cada ocurrencia si expected_replacements > 1)
# print(default_api.replace(
#     file_path='/path/to/your/file.js',
#     old_string='console.log("debug");',
#     new_string='// console.log("debug");',
#     expected_replacements=5
# ))
```

---

## 6. `write_file`

### Descripción
Escribe contenido en un archivo especificado en el sistema de archivos local.

### Parámetros
-   `content` (string, requerido): El contenido a escribir en el archivo.
-   `file_path` (string, requerido): La ruta absoluta al archivo donde escribir (ej. '/home/user/project/file.txt'). Las rutas relativas no son compatibles.

### Ejemplos
```python
# Escribir un nuevo archivo de texto
print(default_api.write_file(
    file_path='C:/MyCode/php/udy_curso_laravel12/sistema/new_file.txt',
    content='Este es un nuevo archivo creado por Gemini.'
))

# Sobrescribir el contenido de un archivo existente
print(default_api.write_file(
    file_path='C:/MyCode/php/udy_curso_laravel12/sistema/README.md',
    content='# Nuevo README\nEste es el nuevo contenido del README.'
))
```

---

## 7. `web_fetch`

### Descripción
Procesa contenido de URL(s), incluyendo direcciones de red locales y privadas (ej. localhost), incrustadas en un prompt. Incluya hasta 20 URL y instrucciones (ej. resumir, extraer datos específicos) directamente en el parámetro `prompt`.

### Parámetros
-   `prompt` (string, requerido): Un prompt completo que incluye la(s) URL(s) (hasta 20) a buscar y las instrucciones específicas sobre cómo procesar su contenido (ej. "Resumir https://example.com/article y extraer puntos clave de https://another.com/data"). Debe contener al menos una URL que comience con `http://` o `https://`.

### Ejemplos
```python
# Resumir el contenido de una página web
print(default_api.web_fetch(prompt='Resumir el contenido de https://www.php.net/manual/es/index.php'))

# Extraer información específica de una página
print(default_api.web_fetch(prompt='Extraer los títulos de las secciones principales de https://laravel.com/docs/11'))
```

---

## 8. `run_shell_command`

### Descripción
Este comando ejecuta un comando de shell dado como `bash -c <command>`. El comando puede iniciar procesos en segundo plano usando `&`. El comando se ejecuta como un subproceso que lidera su propio grupo de procesos.

### Parámetros
-   `command` (string, requerido): El comando bash exacto a ejecutar como `bash -c <command>`.
-   `description` (string, opcional): Breve descripción del comando para el usuario. Sea específico y conciso. Idealmente una sola oración. Puede ser de hasta 3 oraciones para mayor claridad. Sin saltos de línea.
-   `directory` (string, opcional): Directorio donde ejecutar el comando, si no es el directorio raíz del proyecto. Debe ser relativo al directorio raíz del proyecto y debe existir.

### Ejemplos
```python
# Ejecutar un comando simple de listado de archivos
print(default_api.run_shell_command(command='ls -la', description='Listar todos los archivos y directorios, incluyendo los ocultos.'))

# Ejecutar un comando de Laravel Artisan
print(default_api.run_shell_command(command='php artisan migrate', description='Ejecutar las migraciones de la base de datos.'))

# Instalar dependencias de Composer
print(default_api.run_shell_command(command='composer install', description='Instalar las dependencias de Composer.'))
```

---

## 9. `save_memory`

### Descripción
Guarda una pieza específica de información o un hecho en su memoria a largo plazo.

### Parámetros
-   `fact` (string, requerido): El hecho o la pieza de información específica a recordar. Debe ser una declaración clara y autocontenida.

### Ejemplos
```python
# Recordar una preferencia del usuario
print(default_api.save_memory(fact='El usuario prefiere el estilo de código PSR-12.'))

# Recordar una ruta común del proyecto
print(default_api.save_memory(fact='La carpeta de controladores principal es app/Http/Controllers.'))
```

---

## 10. `google_web_search`

### Descripción
Realiza una búsqueda web utilizando Google Search (a través de la API de Gemini) y devuelve los resultados. Esta herramienta es útil para encontrar información en la web basada en una consulta.

### Parámetros
-   `query` (string, requerido): La consulta de búsqueda para encontrar información en la web.

### Ejemplos
```python
# Buscar información sobre un tema
print(default_api.google_web_search(query='Laravel 11 nuevas características'))

# Buscar soluciones a un error de programación
print(default_api.google_web_search(query='Laravel 419 page expired error fix'))
```