<?php
require_once __DIR__ . '/src/layout.php';
render_header('Verifica las operaciones', 'salonestudiosjuridicos.jpg');
?>
<div class="container">
    <div class="row min-vh-100 justify-content-center align-items-center">
        <div class="col-auto p-5">
            <form action="system2.php" method="post">
                <div class="form-floating mb-3">
                    <input type="number" name="input" max="999999" class="form-control" required>
                    <label for="floatingInput">Inpre</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="number" name="input2" class="form-control" min="2000" max="2030">
                    <label for="floatingInput"><span class="text-secondary">Año (opcional)</span></label>
                </div>
                <input type="submit" class="btn btn-success w-100" value="Buscar">
            </form>
            <br>
            <form action="search.php">
                <input type="submit" class="btn btn-outline-success w-100" value="Buscar por cédula">
            </form>
            <br>
            <form action="index.php">
                <label for="buttom" class="form-label"><span class="text-light">Ej: 211210, 2023</span></label>
                <button type="submit" id="buttom" class="btn btn-warning">Salir</button>
            </form>
        </div>
    </div>
</div>
<div class="sticky-bottom">
    <a class="img-fluid" href="soport.php">
        <img src="contact2.png" alt="Soporte" width="100" height="100">
    </a>
</div>
<?php render_footer(); ?>