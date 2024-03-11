<?php

/**
 * Función que recibe dos arrays y devuelve "checked" si $needle está en $haystack, de lo contrario, devuelve una cadena vacía.
 *
 * @param mixed $needle   El valor que se busca en el arreglo.
 * @param array $haystack El arreglo en el que se busca $needle.
 *
 * @return string   "checked" si $needle está en $haystack, de lo contrario, una cadena vacía.
 */
function checked($needle, $haystack)
{
    if ($haystack) {
        return in_array($needle, $haystack) ? 'checked' : '';
    }

    return '';
}

/**
 * Función que recibe dos arrays
 */
function selected($needle, $haystack)
{
    if ($haystack) {
        return in_array($needle, $haystack) ? 'selected' : '';
    }

    return '';
}

function redirect_to(string $url): void
{
    header('Location:' . $url);
    exit;
}

function redirect_with(string $url, array $items): void
{
    foreach ($items as $key => $value) {
        $_SESSION[$key] = $value;
    }
    redirect_to($url);
}

function generarPDF($inputs)
{
    if (isset($_POST['generate_pdf'])) {
        // Crear un objeto FPDF
        $pdf = new FPDF();

        // Configurar la codificación de caracteres
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->SetTitle('Resumen del Pedido');
        $pdf->SetAutoPageBreak(true, 10);
        $pdf->AliasNbPages();
        $pdf->SetLeftMargin(10);
        $pdf->SetRightMargin(10);

        // Agregar contenido al PDF
        $pdf->Cell(0, 10, utf8_decode('Resumen del pedido:'), 0, 1);
        $pdf->Ln(10); // Salto de línea

        $pdf->Cell(0, 10, utf8_decode('1 Dirección de envío'), 0, 1);
        $pdf->Cell(0, 10, utf8_decode($inputs['full-name']), 0, 1);
        $pdf->Cell(0, 10, utf8_decode($inputs['direccion']), 0, 1);
        $pdf->Cell(0, 10, utf8_decode($_SESSION['selected_ciudad'][0] . ", " . $_SESSION['selected_provincia'][0] . ", " . $inputs['codigo-postal']), 0, 1);

        $pdf->Ln(10); // Salto de línea

        $pdf->Cell(0, 10, utf8_decode('2 Método de Pago'), 0, 1);
        $pdf->Cell(0, 10, utf8_decode("Pagada con Visa / 4B / Euro6000 " . substr($inputs['numeroTarjeta'], -4)), 0, 1);

        $pdf->Ln(10); // Salto de línea

        $pdf->Cell(0, 10, utf8_decode('3 Productos'), 0, 1);
        foreach ($_SESSION['productos'] as $producto) {
            foreach ($producto as $key => $value) {
                $pdf->Cell(0, 10, utf8_decode($key . ": " . $value), 0, 1);
            }
            $pdf->Ln(5); // Salto de línea entre productos
        }

        // Calcular el IVA y el precio total
        $iva = $inputs['total'] * 0.21;
        $precioTotal = $inputs['total'] + $iva;

        $pdf->Cell(0, 10, utf8_decode('___________________________'), 0, 1);
        $pdf->Cell(0, 10, utf8_decode("Productos: " . number_format($inputs['total'], 2) . " $"), 0, 1);
        $pdf->Cell(0, 10, utf8_decode("Total sin IVA: " . number_format($inputs['total'], 2) . " $"), 0, 1);
        $pdf->Cell(0, 10, utf8_decode("IVA estimado: " . number_format($iva, 2) . " $"), 0, 1);
        $pdf->Cell(0, 10, utf8_decode("Importe total: " . number_format($precioTotal, 2) . " $"), 0, 1);

        // Salida del PDF al navegador
        $pdf->Output('ticket_compra.pdf', 'D');
        exit;
    }
}
