    # Diario de Aprendizaje

    **1:** Una SAPI (Server Application Programming Interface) es la interfaz que usa PHP para comunicarse con el entorno que lo ejecuta. La función `phpinfo()` se ve mal en la terminal (CLI SAPI) porque imprime todo el código HTML crudo como texto plano, mientras que en el navegador (Web SAPI) ese mismo HTML se interpreta y se muestra con un diseño visual.

    **2:** He configurado un endpoint básico que recibe datos por GET. Me he dado cuenta de las diferencias al validar variables en PHP:

    - `isset($var)`: Solo comprueba si la variable existe y no es nula.
    - `empty($var)`: Devuelve verdadero si la variable no existe, es nula, es falsa, o es un string vacío `""` o un cero `"0"`.
    - El operador `??` (Null Coalescing): Es un atajo que devuelve la variable si existe, o un valor por defecto si no existe o es nula.

    **Resumen:**

    | Valor de `$x` | `isset($x)` | `empty($x)` | `$x ?? 'defecto'` | `!empty($x) ? $x : 'defecto'` |
    |:---|:---:|:---:|:---:|:---:|
    | No definida | `false` | `true` | `'defecto'` | `'defecto'` |
    | `null` | `false` | `true` | `'defecto'` | `'defecto'` |
    | `''` (string vacío) | `true` | `true` | `''` | `'defecto'` |
    | `0` | `true` | `true` | `0` | `'defecto'` |
    | `'0'` | `true` | `true` | `'0'` | `'defecto'` |
    | `false` | `true` | `true` | `false` | `'defecto'` |
    | `[]` (array vacío) | `true` | `true` | `[]` | `'defecto'` |
    | `'8412345678901'` | `true` | `false` | `'8412345678901'` | `'8412345678901'` |
    | `42` | `true` | `false` | `42` | `42` |

    **Comportamiento:**

    | Aspecto | `isset()` | `empty()` | `??` |
    |:---|:---|:---|:---|
    | **Devuelve** | `bool` | `bool` | El valor |
    | **Reacciona a** | No existe / `null` | No existe / `null` / falsy | No existe / `null` |
    | **Genera warning** | No | No | No |
    | **Acceso a índices** | `isset($a['k'])` | `empty($a['k'])` | `$a['k'] ?? 'def'` |
    | **Uso típico** | Comprobar antes de usar | Comprobar contenido real | Asignar default |
    | **Equivalente a** | — | `!isset($x) \|\| $x === falsy` | `isset($x) ? $x : $default` |
    | **Asignación** | — | — | `$x ??= 'def'` (PHP 7.4+) |   

    **3:** Investigacion de terminos necesarios para continuar con el proyecto:
    - **`Arrays asociativos`**: Para crear este tipo de arrays debemos utilizar la sintaxis `clave => valor`.
    - **`json_encode()`**: Convierte un valor PHP (array, objeto, string, etc.) en una cadena JSON.
    - **`JSON_UNESCAPED_UNICODE`**: Opcional. Evita que los caracteres no ASCII se escapen como `\uXXXX`, imprimiéndolos directamente (p. ej. `é` en vez de `\u00e9`).   
    - **`header()`**: Envía un encabezado HTTP al cliente.
    - Ejemplo: `header('Content-Type: application/json');`
    - Ejemplo: `header('Location: /login');` → redirige (302).
    - Debe ir antes de cualquier `echo` o HTML, PHP envía los headers una sola vez al inicio; si ya hay salida, da error *"headers already sent"*.
    - **`http_response_code()`**: Obtiene o define el código de estado HTTP.
    - `http_response_code(404);` → fija el código.
    - `echo http_response_code();` → lo imprime.   

    **4:** Refactorización y Enrutamiento:
    - **`require_once`**: Importa un archivo externo para poder usar sus funciones. A diferencia de `include` o `require` normales, si el archivo ya fue importado en otra parte del código, PHP lo ignora. Esto evita errores fatales por "redefinición de funciones".
    - **Estructura `match`**: Es la evolución moderna del `switch` (introducida en PHP 8). Es mucho más limpia, evalúa con tipado estricto (`===` en lugar de `==`), no necesita la instrucción `break` para no colarse en otros casos, y puede devolver un valor directamente.
    - **Tipado de retorno Nullable (`?tipo`)**: Al definir una función como `function validar(): ?string`, le indicamos a PHP de forma estricta que esta función devolverá un texto o, en caso de que la validación falle, `null`. Es una excelente práctica de código defensivo.
    - **Separación de responsabilidades (Arquitectura)**: He aprendido a dividir el código. El archivo `index.php` ahora actúa como un **Router** o controlador (recibe la petición, mira el parámetro `action` y dirige el tráfico), mientras que `lib/validar.php` actúa como **Librería** (solo procesa datos matemáticos o lógicos y devuelve resultados, pero nunca imprime nada por pantalla).