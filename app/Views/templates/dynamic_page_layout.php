<?php
// app/Views/templates/dynamic_page_layout.php

// Esta plantilla simple recibe el contenido del encabezado y el contenido principal
// desde el controlador y los imprime.

// Imprime el contenido del encabezado (puede ser el completo o el mínimo)
echo $header_content;
// Imprime el contenido principal (en este caso, la vista de registro)
echo $main_content;