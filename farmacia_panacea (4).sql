-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-06-2025 a las 17:46:00
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `farmacia_panacea`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_actualizar_cliente` (IN `p_id_cliente` INT, IN `p_nombre` VARCHAR(255), IN `p_apellido` VARCHAR(255), IN `p_telefono` VARCHAR(50), IN `p_email` VARCHAR(255), IN `p_direccion` VARCHAR(255))   BEGIN
    UPDATE clientes 
    SET 
        nombre = p_nombre, 
        apellido = p_apellido, 
        telefono = p_telefono, 
        email = p_email, 
        direccion = p_direccion
    WHERE id_cliente = p_id_cliente;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_buscar_productos` (IN `p_categoria` VARCHAR(100), IN `p_nombre` VARCHAR(100), IN `p_fecha` DATE)   BEGIN
    SELECT *
    FROM productos
    WHERE (p_categoria IS NULL OR categoria LIKE CONCAT('%', p_categoria, '%'))
      AND (p_nombre IS NULL OR nombre_producto LIKE CONCAT('%', p_nombre, '%'))
      AND (p_fecha IS NULL OR fecha_vencimiento = p_fecha)
    ORDER BY nombre_producto;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_buscar_ventas` (IN `p_fecha` DATE, IN `p_cliente_nombre` VARCHAR(100), IN `p_empleado_nombre` VARCHAR(100))   BEGIN
    SELECT 
        v.id_venta, v.fecha_venta, v.hora_venta, 
        c.nombre AS cliente, e.nombre AS empleado, 
        v.total_venta, v.tipo_pago
    FROM 
        ventas v
    JOIN 
        clientes c ON v.id_cliente = c.id_cliente
    JOIN 
        empleados e ON v.id_empleado = e.id_empleado
    WHERE
        -- Si el parámetro de fecha no es nulo, filtra por fecha
        (p_fecha IS NULL OR v.fecha_venta = p_fecha)
    AND
        -- Si el parámetro de cliente no es nulo, filtra por nombre de cliente
        (p_cliente_nombre IS NULL OR c.nombre LIKE CONCAT('%', p_cliente_nombre, '%'))
    AND
        -- Si el parámetro de empleado no es nulo, filtra por nombre de empleado
        (p_empleado_nombre IS NULL OR e.nombre LIKE CONCAT('%', p_empleado_nombre, '%'))
    ORDER BY 
        v.fecha_venta DESC, v.hora_venta DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultar_clientes` ()   BEGIN
    SELECT * FROM clientes ORDER BY nombre, apellido;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultar_detalles` (IN `p_tipo_entidad` VARCHAR(20), IN `p_id_entidad` INT)   BEGIN
    IF p_tipo_entidad = 'cliente' THEN
        SELECT * FROM clientes WHERE id_cliente = p_id_entidad;
    ELSEIF p_tipo_entidad = 'empleado' THEN
        SELECT * FROM empleados WHERE id_empleado = p_id_entidad;
    ELSEIF p_tipo_entidad = 'proveedor' THEN
        SELECT * FROM proveedores WHERE id_proveedor = p_id_entidad;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_eliminar_cliente` (IN `p_id_cliente` INT)   BEGIN
    DELETE FROM clientes WHERE id_cliente = p_id_cliente;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insert_credito` (IN `p_id_cliente` INT, IN `p_monto` DECIMAL(10,2), IN `p_fecha` DATE, IN `p_venc` DATE)   BEGIN
  INSERT INTO creditos_clientes (id_cliente, estado_credito, monto_credito, fecha_otorgamiento, fecha_vencimiento_pago)
  VALUES (p_id_cliente, 'Pendiente', p_monto, p_fecha, p_venc);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insert_detalle_venta` (IN `p_id_venta` INT, IN `p_id_producto` INT, IN `p_cant` INT, IN `p_precio` DECIMAL(10,2))   BEGIN
  INSERT INTO detalles_venta (id_venta, id_producto, cantidad, precio_unitario, subtotal)
  VALUES (p_id_venta, p_id_producto, p_cant, p_precio, p_cant * p_precio);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insert_empleado` (IN `p_id_farmacia` INT, IN `p_nombre` VARCHAR(255), IN `p_apellido` VARCHAR(255), IN `p_rol` VARCHAR(100), IN `p_salario` DECIMAL(10,2), IN `p_fecha` DATE, IN `p_desc` TEXT)   BEGIN
  INSERT INTO empleados (id_farmacia, nombre, apellido, rol, salario, fecha_contratacion, descripcion)
  VALUES (p_id_farmacia, p_nombre, p_apellido, p_rol, p_salario, p_fecha, p_desc);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insert_empleado_simple` (IN `p_nombre` VARCHAR(100), IN `p_apellido` VARCHAR(100), IN `p_rol` VARCHAR(50), IN `p_salario` DECIMAL(10,2), IN `p_fecha` DATE, IN `p_descripcion` TEXT)   BEGIN
  INSERT INTO empleados (nombre, apellido, rol, salario, fecha_contratacion, descripcion)
  VALUES (p_nombre, p_apellido, p_rol, p_salario, p_fecha, p_descripcion);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insert_gasto` (IN `p_id_empleado` INT, IN `p_fecha` DATE, IN `p_categoria` VARCHAR(100), IN `p_desc` TEXT, IN `p_monto` DECIMAL(10,2))   BEGIN
  INSERT INTO gastos (id_empleado, fecha_gasto, categoria_gasto, descripcion, monto)
  VALUES (p_id_empleado, p_fecha, p_categoria, p_desc, p_monto);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insert_pago_credito` (IN `p_id_credito` INT, IN `p_monto` DECIMAL(10,2), IN `p_fecha` DATE, IN `p_metodo` VARCHAR(50))   BEGIN
  INSERT INTO pagos_creditos_clientes (id_credito_cliente, monto_pago, fecha_pago, metodo_pago)
  VALUES (p_id_credito, p_monto, p_fecha, p_metodo);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insert_producto` (IN `p_nombre` VARCHAR(255), IN `p_desc` TEXT, IN `p_cat` VARCHAR(100), IN `p_precio` DECIMAL(10,2), IN `p_stock` INT, IN `p_fecha` DATE)   BEGIN
  INSERT INTO productos (nombre, descripcion, categoria, precio_venta, stock_actual, fecha_registro)
  VALUES (p_nombre, p_desc, p_cat, p_precio, p_stock, p_fecha);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insert_proveedor` (IN `p_empresa` VARCHAR(255), IN `p_direccion` VARCHAR(255), IN `p_nombre_contacto` VARCHAR(255), IN `p_tel` VARCHAR(255), IN `p_email` VARCHAR(255))   BEGIN
  INSERT INTO proveedores (nombre_empresa, direccion, contacto_nombre, contacto_telefono, email)
  VALUES (p_empresa, p_direccion, p_nombre_contacto, p_tel, p_email);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insert_venta` (IN `p_id_cliente` INT, IN `p_id_empleado` INT, IN `p_fecha` DATE, IN `p_hora` TIME, IN `p_tipo_pago` VARCHAR(20), IN `p_total` DECIMAL(10,2))   BEGIN
  INSERT INTO ventas (id_cliente, id_empleado, fecha_venta, hora_venta, tipo_pago, total_venta)
  VALUES (p_id_cliente, p_id_empleado, p_fecha, p_hora, p_tipo_pago, p_total);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_limpiar_lotes_vencidos` ()   BEGIN
    -- Este procedimiento busca lotes vencidos, ajusta el stock y los elimina.

    -- Declaración de variables para iterar sobre los lotes vencidos.
    DECLARE done INT DEFAULT FALSE;
    DECLARE v_nombre_producto VARCHAR(255);
    DECLARE v_cantidad_vencida INT;

    -- El CURSOR selecciona todos los lotes cuya fecha de vencimiento es anterior a hoy.
    DECLARE cur_lotes_vencidos CURSOR FOR 
        SELECT nombre_producto, total 
        FROM lotes_productos 
        WHERE fecha_vencimiento < CURDATE();

    -- Handler para detener el bucle cuando no haya más filas.
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    -- Abrimos el cursor para empezar a leer.
    OPEN cur_lotes_vencidos;

    -- Iniciamos el bucle para leer cada lote vencido.
    read_loop: LOOP
        -- Obtenemos los datos de la fila actual del cursor.
        FETCH cur_lotes_vencidos INTO v_nombre_producto, v_cantidad_vencida;
        
        -- Si hemos terminado de leer, salimos del bucle.
        IF done THEN
            LEAVE read_loop;
        END IF;
        
        -- Actualizamos el stock en la tabla 'productos', restando la cantidad del lote vencido.
        -- Nos aseguramos de que el stock no sea negativo.
        UPDATE productos 
        SET stock_actual = GREATEST(0, IFNULL(stock_actual, 0) - v_cantidad_vencida)
        WHERE nombre = v_nombre_producto;
        
    END LOOP;

    -- Cerramos el cursor.
    CLOSE cur_lotes_vencidos;
    
    -- Una vez que todos los stocks han sido actualizados,
    -- eliminamos todos los lotes vencidos de la tabla 'lotes_productos' de una sola vez.
    DELETE FROM lotes_productos WHERE fecha_vencimiento < CURDATE();

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_listar_entidades` (IN `p_tipo_entidad` VARCHAR(20))   BEGIN
    IF p_tipo_entidad = 'cliente' THEN
        SELECT id_cliente AS id, CONCAT(nombre, ' ', apellido) AS nombre_completo 
        FROM clientes ORDER BY nombre_completo;
    ELSEIF p_tipo_entidad = 'empleado' THEN
        SELECT id_empleado AS id, CONCAT(nombre, ' ', apellido) AS nombre_completo 
        FROM empleados ORDER BY nombre_completo;
    ELSEIF p_tipo_entidad = 'proveedor' THEN
        SELECT id_proveedor AS id, nombre_empresa AS nombre_completo 
        FROM proveedores ORDER BY nombre_completo;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_obtener_cliente_por_id` (IN `p_id_cliente` INT)   BEGIN
    SELECT * FROM clientes WHERE id_cliente = p_id_cliente;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_registrar_lote_producto` (IN `p_nombre_producto` VARCHAR(100), IN `p_numero_lote` VARCHAR(50), IN `p_fecha_fabricacion` DATE, IN `p_fecha_vencimiento` DATE, IN `p_id_proveedor` INT, IN `p_total` INT, IN `p_precio_total` DECIMAL(10,2))   BEGIN
  INSERT INTO lotes_productos (
    nombre_producto,
    numero_lote,
    fecha_fabricacion,
    fecha_vencimiento,
    id_proveedor,
    total,
    precio_total
  ) VALUES (
    p_nombre_producto,
    p_numero_lote,
    p_fecha_fabricacion,
    p_fecha_vencimiento,
    p_id_proveedor,
    p_total,
    p_precio_total
  );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_registrar_pago_credito` (IN `p_id_credito` INT, IN `p_monto_pago` DECIMAL(10,2), IN `p_metodo_pago` VARCHAR(50), IN `p_fecha_pago` DATE)   BEGIN
  -- Variables
  DECLARE total_pagado DECIMAL(10,2);
  DECLARE monto_total DECIMAL(10,2);
  DECLARE fecha_vencimiento DATE;
  DECLARE estado_nuevo VARCHAR(20);
  DECLARE nuevo_restante DECIMAL(10,2);

  -- Insertar el pago
  INSERT INTO pagos_creditos_clientes (id_credito_cliente, monto_pago, metodo_pago, fecha_pago)
  VALUES (p_id_credito, p_monto_pago, p_metodo_pago, p_fecha_pago);

  -- Calcular total pagado
  SELECT SUM(monto_pago) INTO total_pagado
  FROM pagos_creditos_clientes
  WHERE id_credito_cliente = p_id_credito;

  -- Obtener información del crédito
  SELECT monto_credito, fecha_vencimiento_pago INTO monto_total, fecha_vencimiento
  FROM creditos_clientes
  WHERE id_credito_cliente = p_id_credito;

  -- Calcular monto restante
  SET nuevo_restante = monto_total - total_pagado;
  IF nuevo_restante < 0 THEN
    SET nuevo_restante = 0;
  END IF;

  -- Determinar estado
  IF total_pagado >= monto_total THEN
    SET estado_nuevo = 'Pagado';
  ELSEIF CURDATE() > fecha_vencimiento THEN
    SET estado_nuevo = 'Vencido';
  ELSE
    SET estado_nuevo = 'Pendiente';
  END IF;

  -- Actualizar estado y monto restante
  UPDATE creditos_clientes
  SET estado_credito = estado_nuevo,
      monto_restante = nuevo_restante
  WHERE id_credito_cliente = p_id_credito;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nombre`, `apellido`, `direccion`, `telefono`, `email`) VALUES
(1, 'sebastian', 'avilez', 'Kra 21 #09-34', '45302489', 'fkfewfegf@gmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `creditos_clientes`
--

CREATE TABLE `creditos_clientes` (
  `id_credito_cliente` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `estado_credito` varchar(50) DEFAULT NULL,
  `monto_credito` decimal(10,2) DEFAULT NULL,
  `fecha_otorgamiento` date DEFAULT NULL,
  `fecha_vencimiento_pago` date DEFAULT NULL,
  `id_venta` int(11) DEFAULT NULL,
  `monto_restante` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `creditos_clientes`
--

INSERT INTO `creditos_clientes` (`id_credito_cliente`, `id_cliente`, `estado_credito`, `monto_credito`, `fecha_otorgamiento`, `fecha_vencimiento_pago`, `id_venta`, `monto_restante`) VALUES
(2, 1, 'Pagado', 50000.00, NULL, NULL, 10, 0.00),
(3, 1, 'Pagado', 35000.00, '2025-06-15', '2025-07-15', 11, 0.00),
(4, 1, 'Pagado', 9000.00, '2025-06-14', '2025-06-29', 13, 0.00),
(5, 1, 'Pendiente', 13500.00, '2025-06-16', '2025-06-23', 14, 1500.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_venta`
--

CREATE TABLE `detalles_venta` (
  `id_venta` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalles_venta`
--

INSERT INTO `detalles_venta` (`id_venta`, `id_producto`, `cantidad`, `precio_unitario`, `subtotal`) VALUES
(14, 3, 3, 3500.00, 10500.00),
(14, 7, 2, 1500.00, 3000.00),
(15, 2, 6, 4500.00, 27000.00);

--
-- Disparadores `detalles_venta`
--
DELIMITER $$
CREATE TRIGGER `tr_descuento_stock_venta` AFTER INSERT ON `detalles_venta` FOR EACH ROW BEGIN
  -- Descontar del stock del producto
  UPDATE productos
  SET stock_actual = stock_actual - NEW.cantidad
  WHERE id_producto = NEW.id_producto;

  -- Descontar también del lote más antiguo con stock disponible
  UPDATE lotes_productos
  SET total = total - NEW.cantidad
  WHERE nombre_producto = (
      SELECT nombre FROM productos WHERE id_producto = NEW.id_producto
    )
    AND total >= NEW.cantidad
  ORDER BY fecha_fabricacion ASC
  LIMIT 1;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_actualizar_total_venta` AFTER INSERT ON `detalles_venta` FOR EACH ROW BEGIN
  UPDATE ventas
  SET total_venta = (
    SELECT SUM(subtotal)
    FROM detalles_venta
    WHERE id_venta = NEW.id_venta
  )
  WHERE id_venta = NEW.id_venta;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_descuento_stock_venta` AFTER INSERT ON `detalles_venta` FOR EACH ROW BEGIN
  UPDATE productos
  SET stock_actual = stock_actual - NEW.cantidad
  WHERE id_producto = NEW.id_producto;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id_empleado` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) DEFAULT NULL,
  `rol` varchar(100) DEFAULT NULL,
  `salario` decimal(10,2) DEFAULT NULL,
  `fecha_contratacion` date DEFAULT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id_empleado`, `nombre`, `apellido`, `rol`, `salario`, `fecha_contratacion`, `descripcion`) VALUES
(2, 'roberto', 'hernadez', 'cajero', 800000.00, '2024-05-15', 'fefefe');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gastos`
--

CREATE TABLE `gastos` (
  `id_gasto` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `fecha_gasto` date DEFAULT NULL,
  `categoria_gasto` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `gastos`
--

INSERT INTO `gastos` (`id_gasto`, `id_empleado`, `fecha_gasto`, `categoria_gasto`, `descripcion`, `monto`) VALUES
(1, 2, '2025-06-16', 'Compra de Lote', 'Compra de 50 unidades de \'fortiden\' (Lote: 2)', 70000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lotes_productos`
--

CREATE TABLE `lotes_productos` (
  `id_lote` int(11) NOT NULL,
  `numero_lote` varchar(100) DEFAULT NULL,
  `fecha_fabricacion` date DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `id_proveedor` int(11) NOT NULL,
  `nombre_producto` varchar(150) DEFAULT NULL,
  `precio_total` decimal(10,2) DEFAULT NULL,
  `total` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `lotes_productos`
--

INSERT INTO `lotes_productos` (`id_lote`, `numero_lote`, `fecha_fabricacion`, `fecha_vencimiento`, `id_proveedor`, `nombre_producto`, `precio_total`, `total`) VALUES
(4, '23', '2025-06-11', '2026-07-29', 2, 'Dicloxacina', 199999.97, 200),
(5, '12', '2025-06-03', '2027-02-23', 2, 'Noxpirin', 90000.00, 85),
(6, '1', '2025-06-11', '2025-06-25', 2, 'Dolex', 20000.00, 100),
(7, '2', '2025-06-05', '2026-02-10', 2, 'fortiden', 70000.00, 50);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_creditos_clientes`
--

CREATE TABLE `pagos_creditos_clientes` (
  `id_pago_credito` int(11) NOT NULL,
  `id_credito_cliente` int(11) NOT NULL,
  `monto_pago` decimal(10,2) DEFAULT NULL,
  `fecha_pago` date DEFAULT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pagos_creditos_clientes`
--

INSERT INTO `pagos_creditos_clientes` (`id_pago_credito`, `id_credito_cliente`, `monto_pago`, `fecha_pago`, `metodo_pago`) VALUES
(1, 4, 8000.00, '2025-06-14', 'Efectivo'),
(2, 4, 9000.00, '2025-06-14', 'Efectivo'),
(3, 3, 34000.00, '2025-06-14', 'Transferencia'),
(4, 3, 1000.00, '2025-06-14', 'Efectivo'),
(5, 2, 50000.00, '2025-06-16', 'Transferencia'),
(6, 5, 12000.00, '2025-06-17', 'Efectivo');

--
-- Disparadores `pagos_creditos_clientes`
--
DELIMITER $$
CREATE TRIGGER `trg_actualizar_estado_credito` AFTER INSERT ON `pagos_creditos_clientes` FOR EACH ROW BEGIN
  DECLARE total_pagado DECIMAL(10,2);
  DECLARE total_credito DECIMAL(10,2);

  SELECT SUM(monto_pago) INTO total_pagado
  FROM pagos_creditos_clientes
  WHERE id_credito_cliente = NEW.id_credito_cliente;

  SELECT monto_credito INTO total_credito
  FROM creditos_clientes
  WHERE id_credito_cliente = NEW.id_credito_cliente;

  IF total_pagado >= total_credito THEN
    UPDATE creditos_clientes
    SET estado_credito = 'Pagado'
    WHERE id_credito_cliente = NEW.id_credito_cliente;
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `precio_venta` decimal(10,2) DEFAULT NULL,
  `stock_actual` int(11) DEFAULT NULL,
  `fecha_registro` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `nombre`, `descripcion`, `categoria`, `precio_venta`, `stock_actual`, `fecha_registro`) VALUES
(2, 'Dolorsinfen', 'Pastilla para el dolor abdominal', 'Tableta de Pastilla', 4500.00, 28, '2025-06-14'),
(3, 'Paracetamol', 'pastillas para el dolor de cabeza', 'Tableta de pastilla', 3500.00, 16, '2025-06-14'),
(4, 'Dolex', 'dolor de cabeza y cuerpo', 'Tableta de Pastilla', 2000.00, 80, '2025-06-14'),
(5, 'cotrimazol', 'crema para heridas', 'Tubo de crema', 20000.00, 15, '2025-06-14'),
(7, 'Noxpirin', 'individual de 400mg', 'Pastilla', 1500.00, 83, '2025-06-15'),
(8, 'fortiden', 'crema en sobre de 50 gr', 'Crema', 2000.00, 50, '2025-06-16');

--
-- Disparadores `productos`
--
DELIMITER $$
CREATE TRIGGER `trg_set_stock_before_insert` BEFORE INSERT ON `productos` FOR EACH ROW BEGIN
    DECLARE v_lote_total INT;

    -- Busca el total de productos del lote más reciente con el mismo nombre.
    SELECT `total` INTO v_lote_total
    FROM `lotes_productos`
    WHERE `nombre_producto` = NEW.nombre
    ORDER BY `fecha_fabricacion` DESC
    LIMIT 1;

    -- Si se encuentra un lote, establece el stock del NUEVO producto
    -- con el total de ese lote, justo ANTES de que se guarde en la base de datos.
    IF v_lote_total IS NOT NULL THEN
        SET NEW.stock_actual = v_lote_total;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id_proveedor` int(11) NOT NULL,
  `nombre_empresa` varchar(255) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `contacto_nombre` varchar(255) DEFAULT NULL,
  `contacto_telefono` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id_proveedor`, `nombre_empresa`, `direccion`, `contacto_nombre`, `contacto_telefono`, `email`) VALUES
(2, 'FactoryFC', 'Kra 34a #1607', 'Fernando', '9988004385', 'sdefefe@gmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recibos_compra`
--

CREATE TABLE `recibos_compra` (
  `id_recibo` int(11) NOT NULL,
  `fecha_recepcion` date NOT NULL,
  `id_lote` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_compra` decimal(10,2) NOT NULL,
  `id_proveedor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Disparadores `recibos_compra`
--
DELIMITER $$
CREATE TRIGGER `trg_actualizar_stock_despues_compra` AFTER INSERT ON `recibos_compra` FOR EACH ROW BEGIN
  UPDATE productos
  SET stock_actual = stock_actual + NEW.cantidad
  WHERE id_producto = (
    SELECT id_producto FROM lotes_productos WHERE id_lote = NEW.id_lote
  );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id_venta` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `fecha_venta` date NOT NULL,
  `hora_venta` time DEFAULT NULL,
  `tipo_pago` varchar(50) DEFAULT NULL,
  `total_venta` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id_venta`, `id_cliente`, `id_empleado`, `fecha_venta`, `hora_venta`, `tipo_pago`, `total_venta`) VALUES
(1, 1, 2, '2025-06-15', '02:15:00', 'Contado', 45000.00),
(2, 1, 2, '2025-06-15', '02:25:00', 'Crédito', 50000.00),
(3, 1, 2, '2025-06-15', '02:31:00', 'Crédito', 50000.00),
(4, 1, 2, '2025-06-15', '02:37:00', 'Crédito', 50000.00),
(5, 1, 2, '2025-06-15', '02:39:00', 'Crédito', 35000.00),
(6, 1, 2, '2025-06-15', '02:41:00', 'Crédito', 34000.00),
(7, 1, 2, '2025-06-15', '02:45:00', 'Crédito', 10000.00),
(8, 1, 2, '2025-06-15', '02:52:00', 'Crédito', 45000.00),
(9, 1, 2, '2025-06-15', '02:54:00', 'Crédito', 60000.00),
(10, 1, 2, '2025-06-15', '02:56:00', 'Crédito', 50000.00),
(11, 1, 2, '2025-06-15', '03:14:00', 'Crédito', 35000.00),
(12, 1, 2, '2025-06-15', '03:21:00', 'Contado', 15000.00),
(13, 1, 2, '2025-06-14', '20:31:00', 'Crédito', 9000.00),
(14, 1, 2, '2025-06-16', '00:08:00', 'Crédito', 13500.00),
(15, 1, 2, '2025-06-16', '00:51:00', 'Contado', 27000.00);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_creditos_estado`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vw_creditos_estado` (
`id_credito_cliente` int(11)
,`nombre` varchar(255)
,`estado_credito` varchar(50)
,`monto_credito` decimal(10,2)
,`fecha_otorgamiento` date
,`fecha_vencimiento_pago` date
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_historial_ventas`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vw_historial_ventas` (
`id_venta` int(11)
,`fecha_venta` date
,`id_empleado` int(11)
,`id_cliente` int(11)
,`id_producto` int(11)
,`cantidad` int(11)
,`subtotal` decimal(10,2)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_productos_info`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vw_productos_info` (
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_proximos_a_vencer`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vw_proximos_a_vencer` (
`nombre` varchar(255)
,`numero_lote` varchar(100)
,`fecha_vencimiento` date
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_stock_actual`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vw_stock_actual` (
`id_producto` int(11)
,`nombre` varchar(255)
,`stock_actual` int(11)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_creditos_estado`
--
DROP TABLE IF EXISTS `vw_creditos_estado`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_creditos_estado`  AS SELECT `c`.`id_credito_cliente` AS `id_credito_cliente`, `cl`.`nombre` AS `nombre`, `c`.`estado_credito` AS `estado_credito`, `c`.`monto_credito` AS `monto_credito`, `c`.`fecha_otorgamiento` AS `fecha_otorgamiento`, `c`.`fecha_vencimiento_pago` AS `fecha_vencimiento_pago` FROM (`creditos_clientes` `c` join `clientes` `cl` on(`c`.`id_cliente` = `cl`.`id_cliente`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_historial_ventas`
--
DROP TABLE IF EXISTS `vw_historial_ventas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_historial_ventas`  AS SELECT `v`.`id_venta` AS `id_venta`, `v`.`fecha_venta` AS `fecha_venta`, `v`.`id_empleado` AS `id_empleado`, `v`.`id_cliente` AS `id_cliente`, `d`.`id_producto` AS `id_producto`, `d`.`cantidad` AS `cantidad`, `d`.`subtotal` AS `subtotal` FROM (`ventas` `v` join `detalles_venta` `d` on(`v`.`id_venta` = `d`.`id_venta`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_productos_info`
--
DROP TABLE IF EXISTS `vw_productos_info`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_productos_info`  AS SELECT `p`.`id_producto` AS `id_producto`, `p`.`nombre` AS `nombre`, `p`.`categoria` AS `categoria`, `l`.`fecha_vencimiento` AS `fecha_vencimiento` FROM (`productos` `p` left join `lotes_productos` `l` on(`p`.`id_producto` = `l`.`id_producto`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_proximos_a_vencer`
--
DROP TABLE IF EXISTS `vw_proximos_a_vencer`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_proximos_a_vencer`  AS SELECT `p`.`nombre` AS `nombre`, `l`.`numero_lote` AS `numero_lote`, `l`.`fecha_vencimiento` AS `fecha_vencimiento` FROM (`productos` `p` join `lotes_productos` `l` on(`p`.`nombre` = `l`.`nombre_producto`)) WHERE `l`.`fecha_vencimiento` <= curdate() + interval 30 day ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_stock_actual`
--
DROP TABLE IF EXISTS `vw_stock_actual`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_stock_actual`  AS SELECT `productos`.`id_producto` AS `id_producto`, `productos`.`nombre` AS `nombre`, `productos`.`stock_actual` AS `stock_actual` FROM `productos` ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Indices de la tabla `creditos_clientes`
--
ALTER TABLE `creditos_clientes`
  ADD PRIMARY KEY (`id_credito_cliente`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `fk_credito_venta` (`id_venta`);

--
-- Indices de la tabla `detalles_venta`
--
ALTER TABLE `detalles_venta`
  ADD PRIMARY KEY (`id_venta`,`id_producto`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id_empleado`);

--
-- Indices de la tabla `gastos`
--
ALTER TABLE `gastos`
  ADD PRIMARY KEY (`id_gasto`),
  ADD KEY `id_empleado` (`id_empleado`);

--
-- Indices de la tabla `lotes_productos`
--
ALTER TABLE `lotes_productos`
  ADD PRIMARY KEY (`id_lote`),
  ADD KEY `id_proveedor` (`id_proveedor`);

--
-- Indices de la tabla `pagos_creditos_clientes`
--
ALTER TABLE `pagos_creditos_clientes`
  ADD PRIMARY KEY (`id_pago_credito`),
  ADD KEY `id_credito_cliente` (`id_credito_cliente`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id_proveedor`);

--
-- Indices de la tabla `recibos_compra`
--
ALTER TABLE `recibos_compra`
  ADD PRIMARY KEY (`id_recibo`),
  ADD KEY `id_lote` (`id_lote`),
  ADD KEY `id_proveedor` (`id_proveedor`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_empleado` (`id_empleado`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `creditos_clientes`
--
ALTER TABLE `creditos_clientes`
  MODIFY `id_credito_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id_empleado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `gastos`
--
ALTER TABLE `gastos`
  MODIFY `id_gasto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `lotes_productos`
--
ALTER TABLE `lotes_productos`
  MODIFY `id_lote` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `pagos_creditos_clientes`
--
ALTER TABLE `pagos_creditos_clientes`
  MODIFY `id_pago_credito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id_proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `recibos_compra`
--
ALTER TABLE `recibos_compra`
  MODIFY `id_recibo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `creditos_clientes`
--
ALTER TABLE `creditos_clientes`
  ADD CONSTRAINT `creditos_clientes_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  ADD CONSTRAINT `fk_credito_venta` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`);

--
-- Filtros para la tabla `detalles_venta`
--
ALTER TABLE `detalles_venta`
  ADD CONSTRAINT `detalles_venta_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`),
  ADD CONSTRAINT `detalles_venta_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);

--
-- Filtros para la tabla `gastos`
--
ALTER TABLE `gastos`
  ADD CONSTRAINT `gastos_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`);

--
-- Filtros para la tabla `lotes_productos`
--
ALTER TABLE `lotes_productos`
  ADD CONSTRAINT `lotes_productos_ibfk_2` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`);

--
-- Filtros para la tabla `pagos_creditos_clientes`
--
ALTER TABLE `pagos_creditos_clientes`
  ADD CONSTRAINT `pagos_creditos_clientes_ibfk_1` FOREIGN KEY (`id_credito_cliente`) REFERENCES `creditos_clientes` (`id_credito_cliente`);

--
-- Filtros para la tabla `recibos_compra`
--
ALTER TABLE `recibos_compra`
  ADD CONSTRAINT `recibos_compra_ibfk_1` FOREIGN KEY (`id_lote`) REFERENCES `lotes_productos` (`id_lote`),
  ADD CONSTRAINT `recibos_compra_ibfk_2` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`);

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  ADD CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
