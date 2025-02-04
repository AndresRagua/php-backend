# 📦 PHP Backend - API REST  
🚀 **Backend ligero en PHP** para gestionar productos, inventario y categorías. Este proyecto sigue una arquitectura **MVC** e incluye controladores, modelos y un sistema de enrutamiento propio.

## ✨ Características  
✅ Desarrollado en **PHP** con estructura modular  
✅ Uso de **Composer** para la gestión de dependencias  
✅ **Sistema de enrutamiento** personalizado en `Router.php`  
✅ **Conexión a base de datos** centralizada en `Database.php`  
✅ **Pruebas unitarias** en `tests/` para garantizar calidad del código  
✅ **Punto de entrada seguro** en `public/index.php`  

## 📂 Estructura del Proyecto  
```bash
php-backend-master/
│── public/                 # Punto de entrada de la aplicación  
│── src/                    
│   ├── Controller/         # Lógica de negocio (controladores)  
│   ├── Model/              # Modelos de datos  
│   ├── Utils/              # Utilidades (BD, respuestas, router)  
│── tests/                  # Pruebas unitarias  
│── composer.json           # Dependencias de Composer  
│── README.md               # Documentación del proyecto  
```

## ⚡ Instalación y Uso  
### 1️⃣ Clona el repositorio  
```bash
git clone https://github.com/tu-usuario/php-backend.git
cd php-backend
```

### 2️⃣ Instala las dependencias  
```bash
composer install
```

### 3️⃣ Configura el archivo **`.env`** con los datos de la base de datos  

### 4️⃣ Inicia el servidor  
```bash
php -S localhost:8000 -t public
```

### 5️⃣ ¡Listo! Ahora puedes probar la API desde `http://localhost:8000`

## 🔥 Contribuciones  
¡Las contribuciones son bienvenidas! Si encuentras un error o quieres mejorar el código, abre un **issue** o envía un **pull request**. 🚀





## Creacion de la base de datos de prueba


Creaccion de la base de datos:

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'manager', 'employee') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock INT DEFAULT 0,
    category_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);


CREATE TABLE inventory_movements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    movement_type ENUM('entry', 'exit') NOT NULL,  -- 'entry' para entradas y 'exit' para salidas
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT NOT NULL,  -- Usuario que realiza la acción
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


CREATE TABLE sales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sale_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10, 2) NOT NULL,
    user_id INT NOT NULL,  -- Usuario que registra la venta
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


CREATE TABLE sale_products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sale_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);


CREATE INDEX idx_product_name ON products (name);
CREATE INDEX idx_user_role ON users (role);
CREATE INDEX idx_product_category ON products (category_id);
