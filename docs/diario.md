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
- **Tipado de retorno Nullable (`?tipo`)**: Al definir una función como `function validar(): ?string`, le indicamos a PHP de forma estricta que esta función devolverá un texto o, en caso de que la validación falle, `null`. 
- **Separación de responsabilidades (Arquitectura)**: He aprendido a dividir el código. El archivo `index.php` ahora actúa como un **Router** o controlador (recibe la petición, mira el parámetro `action` y dirige el tráfico), mientras que `lib/validar.php` actúa como **Librería** (solo procesa datos matemáticos o lógicos y devuelve resultados, pero nunca imprime nada por pantalla).

**5:** Por qué testear aunque sea feo: Escribir tests automatizados desde el día 1 me permite refactorizar código sin miedo. Si rompo la validación en el futuro, este script me avisará, evitando que el bug llegue a producción.

**6:** Investigación sobre acceso a bases de datos con PHP mediante PDO

- **PDO (PHP Data Objects)**: Es la clase nativa de PHP que permite conectarse y trabajar con diferentes sistemas de bases de datos, como MariaDB, MySQL, PostgreSQL, SQLite, etc. Permite realizar consultas de una forma más segura y estructurada.

- **DSN (Data Source Name)**: Es la cadena de texto que indica a PDO a qué base de datos debe conectarse y dónde se encuentra. Para MariaDB/MySQL, un DSN básico tiene una estructura como:

```
mysql:host=localhost;dbname=mi_base_de_datos;charset=utf8mb4
```

En él se indica el controlador (mysql), el servidor (host), la base de datos (dbname) y la codificación de caracteres (charset).

- **Prepared Statements (Sentencias Preparadas)**: Son una forma segura de realizar consultas que utilizan datos que nos dé el usuario. En lugar de concatenar directamente una variable dentro de la consulta, se utilizan `prepare()` y `execute()`:

```php
$stmt = $pdo->prepare(
    'SELECT * FROM tabla WHERE ean = :ean'
);
$stmt->execute([
    'ean' => $ean
]);
```

Esto es importante para evitar la Inyección SQL. Si concatenáramos directamente el valor, un usuario podría introducir contenido que modificase la consulta SQL:

```php
"SELECT * FROM tabla WHERE ean = '$ean'"
```

- **PDO::ATTR_ERRMODE y PDO::ERRMODE_EXCEPTION**: Permiten configurar PDO para que los errores de las consultas se conviertan en excepciones. De esta forma podemos capturarlos mediante try/catch y controlar qué ocurre cuando algo falla:

```php
$pdo->setAttribute(
    PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION
);
```

Por ejemplo:

```php
try {
    $stmt = $pdo->prepare(
        'SELECT * FROM tabla WHERE ean = :ean'
    );

    $stmt->execute(['ean' => $ean]);
} catch (PDOException $e) {
    // Gestionar el error
}
```

- **PDO::FETCH_ASSOC**: Es un modo de recuperación de datos que hace que cada fila obtenida de la base de datos se convierta en un array asociativo, utilizando los nombres de las columnas como claves:

```php
$stmt->fetch(PDO::FETCH_ASSOC);
```

Por ejemplo, si la tabla tiene las columnas ean, nombre y precio, el resultado podría ser:

```php
[
    'ean' => '8412345678901',
    'nombre' => 'Producto de prueba',
    'precio' => 12.50
]
```

Esto resulta más limpio y fácil de utilizar que recibir también índices numéricos (0, 1, 2, etc.).

**Tabla resumen**

| Concepto | Función |
|---|---|
| PDO | Permite conectar y trabajar con bases de datos desde PHP |
| DSN | Indica a PDO dónde y a qué base de datos conectarse |
| prepare() | Prepara una consulta SQL con parámetros |
| execute() | Ejecuta la consulta proporcionando los valores de los parámetros |
| Prepared Statements | Separan el SQL de los datos y ayudan a prevenir la Inyección SQL |
| PDO::ERRMODE_EXCEPTION | Hace que los errores de PDO lancen excepciones |
| try/catch | Permite capturar y gestionar esas excepciones |
| PDO::FETCH_ASSOC | Devuelve cada fila como un array asociativo |

**Buenas prácticas: Conexiones a Base de Datos en PHP**
Para mantener un proyecto ordenado y seguro, sigo esta estructura al trabajar con PDO:

1. **Aislamiento (`lib/db.php`)**: La lógica de conexión (DSN, usuario, contraseña) nunca debe estar mezclada con la lógica de negocio (enrutadores o vistas), debe existir en una función aislada que devuelva el objeto PDO.
2. **Nomenclatura**:
    * `$pdo` o `$db`: Para la variable que almacena la conexión (el objeto PDO).
    * `$stmt` (Statement): Para la variable que guarda la sentencia preparada tras llamar a `prepare()`.
    * `$rows` o `$resultados`: Para los datos crudos extraídos tras el `fetchAll()`.
3. **El Flujo Seguro**:
    * **Paso 1:** Llamar a la conexión: `$pdo = db();`
    * **Paso 2:** Preparar la consulta con marcadores (`?` o `:nombre`): `$stmt = $pdo->prepare("SELECT * FROM tabla WHERE campo = ?");`
    * **Paso 3:** Ejecutar pasando los parámetros (limpios): `$stmt->execute([$variable]);`
    * **Paso 4:** Extraer asociativamente: `$datos = $stmt->fetchAll(PDO::FETCH_ASSOC);`
4. **Protección**: Nunca interpolar variables directamente en el SQL (`"SELECT * FROM t WHERE id = $id"`). **Siempre** usar `execute([$id])`.