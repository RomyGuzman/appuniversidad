<?= $this->include('templates/head') ?>

<body>

    <?= $this->include('templates/Navbar') ?>

    <?php if (!isset($show_header) || $show_header): ?>
        <?= $this->include('templates/header_content') ?>
    <?php endif; ?>

    <main>
        <?php if (isset($page_content)) : ?>
            <?= $page_content; // Muestra el contenido pasado desde el controlador Home ?>
        <?php else : ?>
            <?= $this->renderSection('content') // Mantiene la compatibilidad para otras vistas que usen extend() ?>
        <?php endif; ?>
    </main>

    <?= $this->include('templates/footer') ?>

</body>