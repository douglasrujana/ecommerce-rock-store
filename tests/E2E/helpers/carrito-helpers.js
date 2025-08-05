/**
 * 🛒 CARRITO HELPERS - Utilidades para Pruebas E2E
 * 
 * Conjunto de funciones auxiliares para facilitar las pruebas del carrito.
 * Proporciona métodos reutilizables para operaciones comunes y validaciones.
 * 
 * FUNCIONALIDADES:
 * - Preparación de datos de prueba
 * - Validaciones complejas
 * - Operaciones batch
 * - Cálculos matemáticos
 * - Simulación de escenarios
 * 
 * @author Rock Store Testing Team
 * @version 1.0.0
 */

const testData = require('../fixtures/carrito-test-data.json');

class CarritoHelpers {
    /**
     * 🏗️ PREPARAR CARRITO CON PRODUCTOS
     * 
     * Prepara un carrito con productos específicos para pruebas.
     * Útil para tests que necesitan un estado inicial consistente.
     * 
     * @param {Object} page - Página de Playwright
     * @param {Array} productos - Array de productos con {id, cantidad}
     * @returns {Promise<Array>} Items agregados al carrito
     */
    static async prepararCarritoConProductos(page, productos) {
        const ProductoPage = require('../pages/ProductoPage');
        const CarritoPage = require('../pages/CarritoPage');
        
        const productoPage = new ProductoPage(page);
        const carritoPage = new CarritoPage(page);
        
        // Limpiar carrito primero
        await carritoPage.navegarAlCarrito();
        const carritoVacio = await carritoPage.estaCarritoVacio();
        if (!carritoVacio) {
            await carritoPage.vaciarCarrito();
        }
        
        // Agregar productos especificados
        const itemsAgregados = [];
        for (const { id, cantidad } of productos) {
            await productoPage.navegarAProducto(id);
            await productoPage.agregarAlCarrito(cantidad);
            
            itemsAgregados.push({ id, cantidad });
        }
        
        return itemsAgregados;
    }

    /**
     * 🧮 CALCULAR TOTALES ESPERADOS
     * 
     * Calcula los totales esperados basado en productos y cantidades.
     * Útil para validar que los cálculos del carrito son correctos.
     * 
     * @param {Array} items - Items del carrito con {precio, cantidad}
     * @param {number} tasaImpuestos - Tasa de impuestos (default: 0.16)
     * @returns {Object} Totales calculados
     */
    static calcularTotalesEsperados(items, tasaImpuestos = 0.16) {
        let subtotal = 0;
        let totalItems = 0;
        
        for (const item of items) {
            const precio = typeof item.precio === 'string' 
                ? parseFloat(item.precio.replace('$', '').replace(',', ''))
                : item.precio;
            
            subtotal += precio * item.cantidad;
            totalItems += item.cantidad;
        }
        
        const impuestos = subtotal * tasaImpuestos;
        const total = subtotal + impuestos;
        
        return {
            subtotal: subtotal,
            impuestos: impuestos,
            total: total,
            totalItems: totalItems,
            productosUnicos: items.length,
            subtotalFormateado: `$${subtotal.toFixed(2)}`,
            impuestosFormateado: `$${impuestos.toFixed(2)}`,
            totalFormateado: `$${total.toFixed(2)}`
        };
    }

    /**
     * 🔍 VALIDAR ESTRUCTURA DEL CARRITO
     * 
     * Valida que la estructura del carrito cumple con los requisitos.
     * Verifica integridad de datos y consistencia.
     * 
     * @param {Array} items - Items del carrito
     * @param {Object} totales - Totales del carrito
     * @returns {Object} Resultado de validación
     */
    static validarEstructuraCarrito(items, totales) {
        const errores = [];
        
        // Validar items
        if (!Array.isArray(items)) {
            errores.push('Items debe ser un array');
        } else {
            for (let i = 0; i < items.length; i++) {
                const item = items[i];
                
                if (!item.id) errores.push(`Item ${i}: falta ID`);
                if (!item.nombre) errores.push(`Item ${i}: falta nombre`);
                if (!item.precio) errores.push(`Item ${i}: falta precio`);
                if (!item.cantidad || item.cantidad < 1) errores.push(`Item ${i}: cantidad inválida`);
                if (item.cantidad > 99) errores.push(`Item ${i}: cantidad excede máximo`);
            }
        }
        
        // Validar totales
        if (!totales) {
            errores.push('Faltan totales');
        } else {
            if (typeof totales.totalItems !== 'number') errores.push('totalItems debe ser número');
            if (typeof totales.productosUnicos !== 'number') errores.push('productosUnicos debe ser número');
            if (!totales.subtotal) errores.push('Falta subtotal');
            if (!totales.impuestos) errores.push('Faltan impuestos');
            if (!totales.total) errores.push('Falta total');
        }
        
        return {
            valido: errores.length === 0,
            errores: errores
        };
    }

    /**
     * 💰 VALIDAR FORMATO DE MONEDA
     * 
     * Valida que los valores monetarios tienen el formato correcto.
     * 
     * @param {string} valor - Valor a validar (ej: "$25.99")
     * @returns {boolean} True si el formato es válido
     */
    static validarFormatoMoneda(valor) {
        const regex = testData.validaciones.formatoMoneda;
        return regex.test(valor);
    }

    /**
     * 🎯 SIMULAR COMPORTAMIENTO DE USUARIO
     * 
     * Simula comportamientos realistas de usuario con delays.
     * 
     * @param {Object} page - Página de Playwright
     * @param {string} comportamiento - Tipo de comportamiento a simular
     */
    static async simularComportamientoUsuario(page, comportamiento) {
        switch (comportamiento) {
            case 'lectura':
                // Simular tiempo de lectura de producto
                await page.waitForTimeout(1000 + Math.random() * 2000);
                break;
                
            case 'decision':
                // Simular tiempo de decisión
                await page.waitForTimeout(500 + Math.random() * 1500);
                break;
                
            case 'navegacion':
                // Simular navegación natural
                await page.waitForTimeout(300 + Math.random() * 700);
                break;
                
            case 'comparacion':
                // Simular tiempo de comparación de productos
                await page.waitForTimeout(2000 + Math.random() * 3000);
                break;
                
            default:
                await page.waitForTimeout(500);
        }
    }

    /**
     * 📊 GENERAR REPORTE DE CARRITO
     * 
     * Genera un reporte detallado del estado actual del carrito.
     * Útil para debugging y análisis de pruebas.
     * 
     * @param {Array} items - Items del carrito
     * @param {Object} totales - Totales del carrito
     * @returns {Object} Reporte detallado
     */
    static generarReporteCarrito(items, totales) {
        const totalesCalculados = this.calcularTotalesEsperados(items);
        const validacion = this.validarEstructuraCarrito(items, totales);
        
        return {
            timestamp: new Date().toISOString(),
            resumen: {
                totalProductos: items.length,
                totalItems: totales.totalItems,
                valorTotal: totales.total
            },
            items: items.map(item => ({
                id: item.id,
                nombre: item.nombre,
                cantidad: item.cantidad,
                precio: item.precio,
                subtotal: item.subtotal
            })),
            totales: {
                mostrados: totales,
                calculados: totalesCalculados,
                coinciden: Math.abs(
                    parseFloat(totales.total.replace('$', '').replace(',', '')) - 
                    totalesCalculados.total
                ) < 0.01
            },
            validacion: validacion,
            metricas: {
                precioPromedio: totalesCalculados.subtotal / totales.totalItems,
                itemsPorProducto: totales.totalItems / items.length
            }
        };
    }

    /**
     * 🔄 EJECUTAR OPERACION CON REINTENTOS
     * 
     * Ejecuta una operación con reintentos en caso de fallo.
     * Útil para operaciones que pueden fallar por timing.
     * 
     * @param {Function} operacion - Función a ejecutar
     * @param {number} maxReintentos - Máximo número de reintentos
     * @param {number} delayMs - Delay entre reintentos
     * @returns {Promise} Resultado de la operación
     */
    static async ejecutarConReintentos(operacion, maxReintentos = 3, delayMs = 1000) {
        let ultimoError;
        
        for (let intento = 1; intento <= maxReintentos; intento++) {
            try {
                return await operacion();
            } catch (error) {
                ultimoError = error;
                
                if (intento < maxReintentos) {
                    console.log(`Intento ${intento} falló, reintentando en ${delayMs}ms...`);
                    await new Promise(resolve => setTimeout(resolve, delayMs));
                }
            }
        }
        
        throw new Error(`Operación falló después de ${maxReintentos} intentos: ${ultimoError.message}`);
    }

    /**
     * 🎲 GENERAR DATOS DE PRUEBA ALEATORIOS
     * 
     * Genera datos de prueba aleatorios para tests de carga.
     * 
     * @param {number} cantidadProductos - Cantidad de productos a generar
     * @returns {Array} Array de productos con datos aleatorios
     */
    static generarDatosPruebaAleatorios(cantidadProductos = 3) {
        const productosDisponibles = Object.values(testData.productos);
        const productos = [];
        
        for (let i = 0; i < cantidadProductos; i++) {
            const productoAleatorio = productosDisponibles[
                Math.floor(Math.random() * productosDisponibles.length)
            ];
            
            const cantidadAleatoria = Math.floor(Math.random() * 5) + 1; // 1-5
            
            productos.push({
                id: productoAleatorio.id,
                cantidad: cantidadAleatoria,
                data: productoAleatorio
            });
        }
        
        return productos;
    }

    /**
     * 📸 CAPTURAR ESTADO DEL CARRITO
     * 
     * Captura el estado completo del carrito para comparaciones.
     * 
     * @param {Object} carritoPage - Instancia de CarritoPage
     * @returns {Promise<Object>} Estado completo del carrito
     */
    static async capturarEstadoCarrito(carritoPage) {
        const items = await carritoPage.obtenerItemsCarrito();
        const totales = await carritoPage.obtenerTotales();
        const badge = await carritoPage.obtenerBadgeCarrito();
        const carritoVacio = await carritoPage.estaCarritoVacio();
        
        return {
            timestamp: Date.now(),
            items: items,
            totales: totales,
            badge: badge,
            carritoVacio: carritoVacio,
            hash: this.generarHashEstado(items, totales)
        };
    }

    /**
     * 🔐 GENERAR HASH DE ESTADO
     * 
     * Genera un hash único para un estado del carrito.
     * Útil para detectar cambios inesperados.
     * 
     * @param {Array} items - Items del carrito
     * @param {Object} totales - Totales del carrito
     * @returns {string} Hash del estado
     */
    static generarHashEstado(items, totales) {
        const estadoString = JSON.stringify({
            items: items.map(item => ({ id: item.id, cantidad: item.cantidad })),
            totalItems: totales.totalItems,
            total: totales.total
        });
        
        // Hash simple (en producción usar crypto)
        let hash = 0;
        for (let i = 0; i < estadoString.length; i++) {
            const char = estadoString.charCodeAt(i);
            hash = ((hash << 5) - hash) + char;
            hash = hash & hash; // Convert to 32-bit integer
        }
        
        return hash.toString(36);
    }

    /**
     * 🔍 COMPARAR ESTADOS DEL CARRITO
     * 
     * Compara dos estados del carrito y reporta diferencias.
     * 
     * @param {Object} estadoAnterior - Estado anterior
     * @param {Object} estadoActual - Estado actual
     * @returns {Object} Resultado de la comparación
     */
    static compararEstados(estadoAnterior, estadoActual) {
        const diferencias = [];
        
        // Comparar items
        if (estadoAnterior.items.length !== estadoActual.items.length) {
            diferencias.push(`Cantidad de items cambió: ${estadoAnterior.items.length} → ${estadoActual.items.length}`);
        }
        
        // Comparar totales
        if (estadoAnterior.totales.totalItems !== estadoActual.totales.totalItems) {
            diferencias.push(`Total items cambió: ${estadoAnterior.totales.totalItems} → ${estadoActual.totales.totalItems}`);
        }
        
        if (estadoAnterior.totales.total !== estadoActual.totales.total) {
            diferencias.push(`Total cambió: ${estadoAnterior.totales.total} → ${estadoActual.totales.total}`);
        }
        
        // Comparar badge
        if (estadoAnterior.badge !== estadoActual.badge) {
            diferencias.push(`Badge cambió: ${estadoAnterior.badge} → ${estadoActual.badge}`);
        }
        
        return {
            sonIguales: diferencias.length === 0,
            diferencias: diferencias,
            hashAnterior: estadoAnterior.hash,
            hashActual: estadoActual.hash
        };
    }
}

module.exports = CarritoHelpers;