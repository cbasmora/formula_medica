<?php
require('fpdf/fpdf.php');



class PDF extends FPDF
{
    // Función para envolver automáticamente con utf8_decode
    function MultiCell($w, $h, $txt, $border = 0, $align = 'J', $fill = false)
    {
        parent::MultiCell($w, $h, utf8_decode($txt), $border, $align, $fill);
    }

    // Sobrescribe el método Cell para incluir utf8_decode y ser compatible con FPDF
    function Cell($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '')
    {
        $txt = utf8_decode($txt);
        parent::Cell($w, $h, $txt, $border, $ln, $align, $fill, $link);
    }

    function Header()
    {
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 8, 'CLÍNICA MEINTEGRAL S.A.S', 0, 1, 'C');

        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 5, 'FORMATO DE HISTORIA CLÍNICA', 0, 1, 'C');
        $this->Cell(0, 5, 'FÓRMULA MÉDICA', 0, 1, 'C');

        $this->SetDrawColor(200, 200, 200);
        $this->SetLineWidth(0.5);
        $this->Line(10, $this->GetY(), $this->GetPageWidth() - 10, $this->GetY());

        // Ajusta la ruta de la imagen 'logo.png' según la ubicación real
        $this->Image('img/logo.png', 10, 5, 40);

        $this->SetFont('Arial', 'B', 10);
        $this->Ln(1);

        $posX = $this->GetX();
        $posY = $this->GetY();

        $anchoDoc = $this->GetPageWidth();
        $this->SetXY($anchoDoc - 40, 15);

        $this->SetFont('Arial', '', 8);
        $this->Cell(0, 5, 'Código: M-FT-HCL-FM-17', 0, 1, 'R');
        $this->Cell(0, 5, 'Versión: 1.0  Año: 2024', 0, 1, 'R');

        $this->SetXY($posX, $posY);
        $this->SetTextColor(0, 0, 0);
    }

    // Función para obtener la hora actual de Colombia
    function getServerTime()
    {
        // Definir la zona horaria de Colombia
        $timezone = 'America/Bogota';
        
        // Crear un objeto DateTime con la zona horaria específica
        $dateTime = new DateTime('now', new DateTimeZone($timezone));
        
        // Formatear la fecha y hora en el formato deseado
        return $dateTime->format('Y-m-d H:i:s');
    }

    function Footer()
    {
        // Obtener la hora actual de Colombia
        $horaActual = $this->getServerTime();

        // Generar un número único basado en la fecha y un valor aleatorio
        $numeroUnico = mt_rand(100000000000, 9000000000000);

        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo() . ' -  PACI ' . $horaActual . ' - Número único de impresión: ' .'FM'. $numeroUnico, 0, 0, 'C');
    }

    function PrintForm($data)
    {
        // Establecer el fondo azul y texto blanco para las celdas en negrita
        $this->SetFillColor(200, 220, 255); // Azul claro
        $this->SetTextColor(0); // Texto negro

        // Títulos con fondo azul y datos correspondientes
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(12, 10, 'Fecha:', 0, 0, 'L',); // Título Fecha con fondo azul
        $this->SetFont('Arial', '', 10);
        $this->Cell(20, 10, $data['fecha'], 0); // Fecha
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(10, 10, 'Hora:', 0, 0, 'L',); // Título Hora con fondo azul
        $this->SetFont('Arial', '', 10);
        $this->Cell(60, 10, $data['hora'], 0);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(50, 10, 'N° HC', 0, 0, 'R',); // Título N° HC con fondo azul
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(60, 10, $data['numeroDocumento'], 0); // Nro de HC
        $this->Ln(10);

        // Datos del paciente
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(30, 5, 'Paciente:', 1, 0, 'L', true); // Título Paciente con fondo azul
        $this->SetFont('Arial', '', 10);
        $this->Cell(69, 5, $data['paciente'], 1); // Paciente
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(30, 5, 'Identificación:', 1, 0, 'L', true); // Título Documento con fondo azul
        $this->SetFont('Arial', '', 10);
        $this->Cell(60, 5, $data['tipoDocumento'] . ' - ' . $data['numeroDocumento'], 1); // Tipo de documento y número
        $this->Ln();

        $this->SetFont('Arial', 'B', 10);
        $this->Cell(30, 5, 'Sexo:', 1, 0, 'L', true); // Título Sexo con fondo azul
        $this->SetFont('Arial', '', 10);
        $this->Cell(69, 5, $data['sexo'], 1); // Sexo
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(30, 5, 'Edad:', 1, 0, 'L', true); // Título Edad con fondo azul
        $this->SetFont('Arial', '', 10);
        $this->Cell(60, 5, $data['edad'], 1); // Edad
        $this->Ln();

        $this->SetFont('Arial', 'B', 10);
        $this->Cell(30, 5, 'Cama:', 1, 0, 'L', true); // Título Cama con fondo azul
        $this->SetFont('Arial', '', 10);
        $this->Cell(69, 5, $data['cama'], 1); // Cama
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(30, 5, 'Servicio:', 1, 0, 'L', true); // Título Servicio con fondo azul
        $this->SetFont('Arial', '', 10);
        $this->Cell(60, 5, $data['servicio'], 1); // Servicio
        $this->Ln(10);

        // Restablecer colores y fuentes normales para los datos posteriores
        $this->SetFillColor(255); // Restablecer el color de fondo a blanco
        $this->SetTextColor(0); // Restablecer el color de texto a negro

        $this->SetFont('Arial', 'B', 10);
        $this->Cell(30, 6, 'Entidad:', 1, 0, 'L', true); // Título Entidad con fondo azul
        $this->SetFont('Arial', '', 10);
        $this->Cell(159, 6, $data['entidad'] . ' - ' . $data['tipo'], 1, true); // Tipo de documento y número
        $this->Ln(1);



        $this->SetFont('Arial', 'B', 15);
        $this->Cell(0, 15, 'MEDICAMENTOS SOLICITADOS', 0, 1, 'C');

        $header = array(
            'N°',
            'NOMBRE DEL MEDICAMENTO',
            'FORMA FARMACÉUTICA',
            'VÍA ADMINISTRACIÓN',
            'DOSIS/FRECUENCIA',
            'CANTIDAD',
            'DESP',
            'VDO',
        );

        $this->SetFillColor(200, 220, 255);
        $this->SetTextColor(0);
        $this->SetDrawColor(0);
        $this->SetFont('Arial', 'B', 6);

        // Ajusta los anchos de las columnas
        $w = array(10, 50, 35, 25, 40, 20, 10, 10);

        $anchoTotal = array_sum($w);
        $anchoDisponible = $this->GetPageWidth() - $this->lMargin - $this->rMargin;

        // Ajusta los anchos si exceden el espacio disponible
        if ($anchoTotal > $anchoDisponible) {
            $factorAjuste = $anchoDisponible / $anchoTotal;
            foreach ($w as &$valor) {
                $valor *= $factorAjuste;
            }
            unset($valor);
        }

        foreach ($header as $key => $value) {
            $this->Cell($w[$key], 5, $value, 1, 0, 'C', true);
        }
        $this->Ln();

        $this->SetFont('Arial', '', 8);
        foreach ($data['medicamentos'] as $row) {
            $maxLines = 0;
            foreach ($row as $key => $value) {
                $nb = $this->NbLines($w[$key], $value);
                if ($nb > $maxLines) {
                    $maxLines = $nb;
                }
            }

            $alturaPorLinea = 4; // Ajusta este valor para cambiar la altura de cada línea
            $h = $alturaPorLinea * $maxLines;
            foreach ($row as $key => $value) {
                $x = $this->GetX();
                $y = $this->GetY();
                $this->Rect($x, $y, $w[$key], $h);

                // Guarda la posición actual
                $currentX = $this->GetX();
                $currentY = $this->GetY();

                // Calcula la altura del texto
                $nb = $this->NbLines($w[$key], $value);
                $textHeight = $nb * $this->FontSize * 1;

                // Calcula el nuevo Y para centrar el texto verticalmente
                $newY = $currentY + ($h - $textHeight) / 2;

                // Ajusta la posición y agrega el texto
                $this->SetXY($currentX, $newY);
                $this->MultiCell($w[$key], 3, $value, 0, 'L');

                // Restaura la posición X para la próxima celda
                $this->SetXY($x + $w[$key], $y);
            }

            // Agrega celdas adicionales para DESP y VDO
            $this->Rect($x + $w[5], $y, $w[6], $h); // DESP
            $this->Rect($x + $w[5] + $w[6], $y, $w[7], $h); // VDO

            $this->Ln($h);
        }
        $this->Cell(array_sum($w), 0, '', 'T');
        $this->Ln(5);

        $this->SetFont('Arial', 'B', 10);
        $this->Cell(30, 5, 'DX Principal', 1, 0, 'L', true); // Título Diagnósticos con fondo azul
        $this->SetFont('Arial', '', 10);
        $this->MultiCell(159, 5, $data['diagnosticos'], 1); // Diagnósticos
        $this->Ln(35);

        $this->SetFont('Arial', '', 9);
          //-------------------------------------------------------------------------   
// Definir los nombres
$nombres = array(
    array($data['nombreMedico'], $data['nombreSolicitante'], "Nombre:                             ", "Nombre:                             "),
    array('C.C. ' . $data['numeroMedico'],'C.C. ' .$data['numeroSolicitante'], "C.C:                                    ", "C.C:                                    "),
);

// Anchuras de las celdas para las firmas
$w_firma = array(50, 50, 50, 50); // Ancho para cada firma

// Calculamos el ancho total para centrar
$ancho_total_firmas = array_sum($w_firma);

// Calculamos la posición X para centrar
$posicion_x = ($this->GetPageWidth() - $ancho_total_firmas) / 2;

// Tamaño y posición de las líneas personalizables arriba de los nombres
$tamano_linea = 0.4; // Tamaño de las líneas
$longitud_corte = 10; // Longitud del corte en los extremos de la línea
$posicion_y_linea_arriba = $this->GetY() - 2; // Posición Y ajustable según necesidad

// Dibujar las líneas arriba de los nombres
$this->SetDrawColor(0); // Color de la línea: negro
$this->SetLineWidth($tamano_linea); // Ancho de la línea

// Dibujar las líneas para cada firma
foreach ($w_firma as $key => $ancho) {
    $posicion_x_linea = $posicion_x + array_sum(array_slice($w_firma, 0, $key));
    $this->Line($posicion_x_linea + $longitud_corte / 2, $posicion_y_linea_arriba, $posicion_x_linea + $ancho - $longitud_corte / 2, $posicion_y_linea_arriba);
}

// Imprimir nombres con celdas y separaciones
foreach ($nombres as $row) {
    // Centrar la fila
    $this->SetX($posicion_x);
    
    foreach ($row as $key => $value) {
        // Si el valor está vacío, imprime una celda vacía
        if ($value === '') {
            $this->Cell($w_firma[$key], 3, '', 0, 0, 'C');
        } else {
            $this->Cell($w_firma[$key], 2, utf8_decode($value), 0, 0, 'C');
        }
    }
    $this->Ln(4);
}

// Etiquetas debajo de los nombres
$this->SetX($posicion_x); // Centrar
$this->SetFont('Arial', 'I', 8);
$this->Cell($w_firma[0], 5, 'Nombre del Médico', 0, 0, 'C'); // Etiqueta para la primera firma
$this->Cell($w_firma[1], 5, 'Nombre de quien Solicita', 0, 0, 'C'); // Etiqueta para la segunda firma
$this->Cell($w_firma[2], 5, 'Nombre Farmacia (Dispensa)', 0, 0, 'C'); // Etiqueta para la tercera firma
$this->Cell($w_firma[3], 5, 'Nombre de Recibido (Servicio)', 0, 1, 'C'); // Etiqueta para la cuarta firma

$this->Ln(15); // Espacio después de las etiquetas


// No se incluyen líneas adicionales para firmas abajo

            //-------------------------------------------------------------------------------
    }


    function NbLines($w, $txt)
    {
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
    }
}

// Verifica si la solicitud es POST para procesar los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = array(
        'fecha' => utf8_encode($_POST['fecha']),
        'hora' => utf8_encode($_POST['hora']),
        'paciente' => ($_POST['paciente']),
        'sexo' => utf8_encode($_POST['sexo']),
        'tipoDocumento' => utf8_encode($_POST['tipoDocumento']),
        'tipo' => utf8_encode($_POST['tipo']),
        'numeroDocumento' => utf8_encode($_POST['numeroDocumento']),
        'edad' => ($_POST['edad']),
        'cama' => utf8_encode($_POST['cama']),
        'servicio' => ($_POST['servicio']),
        'entidad' => ($_POST['entidad']),
        'diagnosticos' => utf8_encode($_POST['diagnosticos']),
        'nombreMedico' => utf8_encode($_POST['nombreMedico']),
        'numeroMedico' => utf8_encode($_POST['numeroMedico']),
        'nombreSolicitante' => utf8_encode($_POST['nombreSolicitante']),
        'numeroSolicitante' => utf8_encode($_POST['numeroSolicitante']),

        'medicamentos' => array()
    );

    // Recorre los datos POST para obtener los medicamentos
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'medicamento') === 0) {
            $num = str_replace('medicamento', '', $key);
            $data['medicamentos'][] = array(
                $num,
                utf8_encode($value),
                utf8_encode($_POST['formaFarmaceutica' . $num]),
                utf8_encode($_POST['viaAdministracion' . $num]),
                utf8_encode($_POST['dosisFrecuencia' . $num]),
                utf8_encode($_POST['cantidad' . $num])
            );
        }
    }

    // Genera el PDF
    $pdf = new PDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 12);
    $pdf->PrintForm($data); // Imprime el formulario con los datos procesados
    $pdf->Output('I', 'FM-' . utf8_decode($data['paciente']) . ' - Fecha ' . $data['fecha'] . ' Hora ' . $data['hora'] . '.pdf');
    require_once('insertar_datos.php');
}
