<?php
require 'C:\xampp\htdocs\tienda_online\vendor\setasign\fpdf\fpdf.php';
require 'C:\xampp\htdocs\tienda_online\config\database.php';

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 20);
        $this->Cell(0, 15, utf8_decode('Factura Electrónica'), 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer() {
        $this->SetY(-30);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Gracias por su compra en Tienda Online'), 0, 1, 'C');
        $this->Cell(0, 10, 'Tienda Online - Todos los derechos reservados', 0, 1, 'C');
        $this->Cell(0, 10, 'contacto@tiendaonline.com | +503 1234 5678', 0, 1, 'C');
    }

    function ClienteInfo($cliente) {
        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 10, utf8_decode("Nombre: {$cliente['nombres']} {$cliente['apellidos']}"), 0, 1);
        $this->Cell(0, 10, "Email: {$cliente['email']}", 0, 1);
        $this->Cell(0, 10, "Telefono: {$cliente['telefono']}", 0, 1);
        $this->Cell(0, 10, "DUI: {$cliente['dui']}", 0, 1);
        $this->Ln(10);
    }

    function CompraTable($compraDetalles) {
        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor(230, 230, 230);
        $this->Cell(35, 10, 'Cantidad', 1, 0, 'C', true);
        $this->Cell(100, 10, utf8_decode('Producto'), 1, 0, 'C', true);
        $this->Cell(55, 10, 'Importe', 1, 1, 'C', true);

        $this->SetFont('Arial', '', 12);
        foreach ($compraDetalles as $detalle) {
            $importe = $detalle['precio'] * $detalle['cantidad'];
            $this->Cell(35, 10, $detalle['cantidad'], 1, 0, 'C');
            $this->Cell(100, 10, utf8_decode($detalle['nombre']), 1, 0);
            $this->Cell(55, 10, '$' . number_format($importe, 2), 1, 1, 'R');
        }
    }

    function MostrarTotal($total) {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(135, 10, 'Total', 1);
        $this->Cell(55, 10, '$' . number_format($total, 2), 1, 1, 'R');
    }
}

// Conectar a la base de datos
$db = new Database();
$pdo = $db->conectar();

// Obtener el ID de la transacción desde el parámetro GET
$id_transaccion = isset($_GET['orden']) ? $_GET['orden'] : '0';

// Verificar si la compra existe
$sql = $pdo->prepare("SELECT id, fecha, total FROM compra WHERE id_transaccion = ? AND status = 'COMPLETED'");
$sql->execute([$id_transaccion]);
$compra = $sql->fetch(PDO::FETCH_ASSOC);

if (!$compra) {
    die('Compra no encontrada');
}

$idCompra = $compra['id'];
$total = $compra['total'];


// Obtener los datos del cliente de la última compra
$queryCliente = "
    SELECT c.nombres, c.apellidos, c.email, c.telefono, c.dui 
    FROM clientes c
    JOIN compra co ON c.id = co.id_cliente
    ORDER BY co.id DESC LIMIT 1";
$stmtCliente = $pdo->query($queryCliente);
$cliente = $stmtCliente->fetch(PDO::FETCH_ASSOC);

// Obtener los detalles de la compra
$sqlDet = $pdo->prepare("SELECT nombre, precio, cantidad FROM detalle_compra WHERE id_compra = ?");
$sqlDet->execute([$idCompra]);
$compraDetalles = $sqlDet->fetchAll(PDO::FETCH_ASSOC);

// Generar PDF
$pdf = new PDF();
$pdf->AddPage();

// Mostrar la información del cliente
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, utf8_decode('Información del Cliente'), 0, 1, 'L');
$pdf->ClienteInfo($cliente);

// Mostrar los detalles del cliente (puedes adaptarlo según tu necesidad)
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, utf8_decode('Detalles de la Compra'), 0, 1, 'L');
$pdf->Ln(5);
$pdf->CompraTable($compraDetalles);

// Mostrar el total
$pdf->MostrarTotal($total);

// Descargar el PDF
$pdf->Output('D', "Factura_Electronica_$idCompra.pdf");
?>
