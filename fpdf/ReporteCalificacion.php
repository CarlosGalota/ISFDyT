<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 3) {
    echo "ACCESO NO AUTORIZADO";
    exit();
}

require '../../ISFD/conexion.php';
require('./fpdf.php');

class PDF extends FPDF
{
    // Cabecera de página
    function Header()
    {
        $conexion = conectar();
        $consulta_info = $conexion->query("SELECT * FROM usuarios, notas, carreras WHERE usuarios.idUsuarios=" . $_SESSION['id_usuario'] . " AND notas.idUsuarios=" . $_SESSION['id_usuario'] . " AND carreras.idCarreras=" . $_SESSION['carrera_id'] . ";");
        $dato_info = $consulta_info->fetch_object();

        if (!$dato_info) {
            $this->Error('No se pudo obtener la información del estudiante.');
        }

        $this->Image('logo-chico.png', 270, 5, 20); // logo de la empresa
        $this->SetFont('Arial', 'B', 19); // tipo fuente, negrita, tamañoTexto
        $this->Cell(95); // Movernos a la derecha
        $this->SetTextColor(0, 0, 0); // color

        // Celda de título
        $this->Cell(110, 15, mb_convert_encoding($dato_info->nombreCarreras, 'ISO-8859-1', 'UTF-8'), 1, 1, 'C', 0);
        $this->Ln(3); // Salto de línea

        // Datos del estudiante
        $this->SetTextColor(103);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(180);
        $this->Cell(96, 10, mb_convert_encoding("Matricula: " . $dato_info->idUsuarios, 'ISO-8859-1', 'UTF-8'), 0, 1, '', 0);
        $this->Cell(180);
        $this->Cell(96, 10, mb_convert_encoding("Nombre: " . $dato_info->nombre, 'ISO-8859-1', 'UTF-8'), 0, 1, '', 0);
        $this->Cell(180);
        $this->Cell(59, 10, mb_convert_encoding("Apellido: " . $dato_info->apellido, 'ISO-8859-1', 'UTF-8'), 0, 1, '', 0);
        $this->Cell(180);
        $this->Cell(85, 10, mb_convert_encoding("DNI: " . $dato_info->dni, 'ISO-8859-1', 'UTF-8'), 0, 1, '', 0);

        // Título de la tabla
        $this->SetTextColor(84, 123, 199);
        $this->Cell(100);
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(100, 10, mb_convert_encoding("Notas", 'ISO-8859-1', 'UTF-8'), 0, 1, 'C', 0);
        $this->Ln(7);

        // Campos de la tabla
        $this->SetFillColor(84, 123, 199); // colorFondo
        $this->SetTextColor(255, 255, 255); // colorTexto
        $this->SetDrawColor(163, 163, 163); // colorBorde
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(30, 10, mb_convert_encoding('N°', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 1);
        $this->Cell(40, 10, mb_convert_encoding('Nombre', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 1);
        $this->Cell(40, 10, mb_convert_encoding('Apellido', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 1);
        $this->Cell(70, 10, mb_convert_encoding('Materia', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 1);
        $this->Cell(30, 10, mb_convert_encoding('Parcial 1', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 1);
        $this->Cell(30, 10, mb_convert_encoding('Parcial 2', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 1);
        $this->Cell(30, 10, mb_convert_encoding('Final', 'ISO-8859-1', 'UTF-8'), 1, 1, 'C', 1);
    }

    // Pie de página
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, mb_convert_encoding('Página ' . $this->PageNo() . '/{nb}', 'ISO-8859-1', 'UTF-8'), 0, 0, 'C');

        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $hoy = date('d/m/Y');
        $this->Cell(540, 10, mb_convert_encoding($hoy, 'ISO-8859-1', 'UTF-8'), 0, 0, 'C');
    }
}

$conexion = conectar();
$pdf = new PDF();
$pdf->AddPage('L');
$pdf->AliasNbPages();
$pdf->SetFont('Arial', '', 12);
$pdf->SetDrawColor(163, 163, 163);

$consulta_reporte = $conexion->query("SELECT * FROM usuarios, notas, materias WHERE usuarios.idUsuarios=" . $_SESSION['id_usuario'] . " AND notas.idUsuarios=" . $_SESSION['id_usuario'] . " AND materias.idMaterias=notas.idMaterias;");

if (!$consulta_reporte) {
    die('Error en la consulta: ' . $conexion->error);
}

$i = 0;
while ($datos_reporte = $consulta_reporte->fetch_object()) {
    $i++;
    $nombre = $datos_reporte->nombre ?? ''; // Asigna '' si es null
    $apellido = $datos_reporte->apellido ?? ''; // Asigna '' si es null
    $nombreMaterias = $datos_reporte->nombreMaterias ?? ''; // Asigna '' si es null
    $parcial1 = $datos_reporte->parcial1 ?? ''; // Asigna '' si es null
    $parcial2 = $datos_reporte->parcial2 ?? ''; // Asigna '' si es null
    $final = $datos_reporte->final ?? ''; // Asigna '' si es null

    // Asegúrate de que mb_convert_encoding no reciba valores null
    $pdf->Cell(30, 10, mb_convert_encoding($i, 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
    $pdf->Cell(40, 10, mb_convert_encoding($nombre, 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
    $pdf->Cell(40, 10, mb_convert_encoding($apellido, 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
    $pdf->Cell(70, 10, mb_convert_encoding($nombreMaterias, 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
    $pdf->Cell(30, 10, mb_convert_encoding($parcial1, 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
    $pdf->Cell(30, 10, mb_convert_encoding($parcial2, 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', 0);
    $pdf->Cell(30, 10, mb_convert_encoding($final, 'ISO-8859-1', 'UTF-8'), 1, 1, 'C', 0);
}

$pdf->Output('I', 'Calificaciones.pdf');
?>
