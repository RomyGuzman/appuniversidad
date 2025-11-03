<?php
require 'vendor/autoload.php';
$db = \Config\Database::connect();
$query = $db->query('SELECT * FROM Categoria');
foreach ($query->getResult() as $row) {
    echo $row->id . ' - ' . $row->codigo_categoria . ' - ' . $row->nombre_categoria . ' - ' . $row->carrera_id . PHP_EOL;
}
$query2 = $db->query('SELECT * FROM Modalidad');
foreach ($query2->getResult() as $row) {
    echo $row->id . ' - ' . $row->codigo_modalidad . ' - ' . $row->nombre_modalidad . ' - ' . $row->carrera_id . PHP_EOL;
}
$query3 = $db->query('SELECT * FROM Carrera');
foreach ($query3->getResult() as $row) {
    echo $row->id . ' - ' . $row->nombre_carrera . PHP_EOL;
}
?>
