<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Productos</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <style>
        .table-responsive {
            margin-top: 20px;
        }
        .action-buttons {
            white-space: nowrap;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Gestión de Productos</h1>

    <!-- Botón para agregar nuevo producto -->
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addProductModal">
        <i class="fas fa-plus"></i> Agregar Producto
    </button>

    <!-- Tabla de productos -->
    <div class="table-responsive">
        <table id="productsTable" class="table table-striped table-hover">
            <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Fecha Creación</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <!-- Los productos se cargarán aquí dinámicamente -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para agregar producto -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Agregar Nuevo Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addProductForm">
                    <div class="mb-3">
                        <label for="productName" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="productName" required>
                    </div>
                    <div class="mb-3">
                        <label for="productPrice" class="form-label">Precio</label>
                        <input type="number" class="form-control" id="productPrice" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveProductBtn">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar producto -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Editar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editProductForm">
                    <input type="hidden" id="editProductId">
                    <div class="mb-3">
                        <label for="editProductName" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="editProductName" required>
                    </div>
                    <div class="mb-3">
                        <label for="editProductPrice" class="form-label">Precio</label>
                        <input type="number" class="form-control" id="editProductPrice" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="updateProductBtn">Actualizar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar este producto?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        // Variable para almacenar el ID del producto a eliminar
        let productIdToDelete = null;

        // Inicializar DataTable
        const table = $('#productsTable').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
            }
        });

        // Cargar productos al iniciar la página
        loadProducts();

        // Función para cargar productos desde la API
        function loadProducts() {
            $.ajax({
                url: 'http://127.0.0.1:8000/api/products',
                type: 'GET',
                success: function(response) {
                    table.clear().draw();

                    response.forEach(function(product) {
                        table.row.add([
                            product.id,
                            product.name,
                            formatCurrency(product.price),
                            new Date(product.created_at).toLocaleString(),
                            `<div class="action-buttons">
                                    <button class="btn btn-sm btn-warning edit-btn" data-id="${product.id}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger delete-btn" data-id="${product.id}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>`
                        ]).draw(false);
                    });
                },
                error: function(xhr, status, error) {
                    alert('Error al cargar los productos: ' + error);
                }
            });
        }

        // Función para formatear moneda
        function formatCurrency(amount) {
            return '$' + parseFloat(amount).toLocaleString('es-CO');
        }

        // Evento para guardar nuevo producto
        $('#saveProductBtn').click(function() {
            const name = $('#productName').val();
            const price = $('#productPrice').val();

            if (!name || !price) {
                alert('Por favor completa todos los campos');
                return;
            }

            $.ajax({
                url: 'http://127.0.0.1:8000/api/products',
                type: 'POST',
                data: JSON.stringify({
                    name: name,
                    price: price
                }),
                contentType: 'application/json',
                success: function(response) {
                    $('#addProductModal').modal('hide');
                    $('#addProductForm')[0].reset();
                    loadProducts();
                    alert('Producto creado exitosamente');
                },
                error: function(xhr, status, error) {
                    alert('Error al crear el producto: ' + error);
                }
            });
        });

        // Evento para abrir modal de edición
        $(document).on('click', '.edit-btn', function() {
            const productId = $(this).data('id');

            $.ajax({
                url: `http://127.0.0.1:8000/api/products/${productId}`,
                type: 'GET',
                success: function(product) {
                    $('#editProductId').val(product.id);
                    $('#editProductName').val(product.name);
                    $('#editProductPrice').val(product.price);
                    $('#editProductModal').modal('show');
                },
                error: function(xhr, status, error) {
                    alert('Error al cargar el producto: ' + error);
                }
            });
        });

        // Evento para actualizar producto
        $('#updateProductBtn').click(function() {
            const id = $('#editProductId').val();
            const name = $('#editProductName').val();
            const price = $('#editProductPrice').val();

            if (!name || !price) {
                alert('Por favor completa todos los campos');
                return;
            }

            $.ajax({
                url: 'http://127.0.0.1:8000/api/products',
                type: 'PUT',
                data: JSON.stringify({
                    id: id,
                    name: name,
                    price: price
                }),
                contentType: 'application/json',
                success: function(response) {
                    $('#editProductModal').modal('hide');
                    loadProducts();
                    alert('Producto actualizado exitosamente');
                },
                error: function(xhr, status, error) {
                    alert('Error al actualizar el producto: ' + error);
                }
            });
        });

        // Evento para abrir modal de confirmación de eliminación
        $(document).on('click', '.delete-btn', function() {
            productIdToDelete = $(this).data('id');
            $('#deleteConfirmModal').modal('show');
        });

        // Evento para confirmar eliminación
        $('#confirmDeleteBtn').click(function() {
            if (!productIdToDelete) return;

            $.ajax({
                url: `http://127.0.0.1:8000/api/products/${productIdToDelete}`,
                type: 'DELETE',
                success: function(response) {
                    $('#deleteConfirmModal').modal('hide');
                    loadProducts();
                    alert('Producto eliminado exitosamente');
                    productIdToDelete = null;
                },
                error: function(xhr, status, error) {
                    alert('Error al eliminar el producto: ' + error);
                }
            });
        });
    });
</script>
</body>
</html>
