<?php

namespace App\Support;

class SimplePdfReport
{
    private const PAGE_WIDTH = 612;   // Carta (8.5in x 72pt)

    private const PAGE_HEIGHT = 792;  // 11in x 72pt

    private const MARGIN = 40;

    private const LINE_HEIGHT = 16;

    private const ROWS_PER_PAGE = 32;

    /**
     * @param  string  $title  Título del reporte.
     * @param  string[]  $subtitleLines  Líneas adicionales bajo el título (ej. el periodo filtrado).
     * @param  string[]  $headers  Encabezados de columna.
     * @param  array<int, array<int, string>>  $rows  Filas de datos, ya formateadas como texto.
     * @param  int[]  $colX  Posición X (en puntos) de cada columna.
     * @param  string[]  $footerLines  Líneas finales (ej. totales del periodo).
     */
    public static function make(
        string $title,
        array $subtitleLines,
        array $headers,
        array $rows,
        array $colX,
        array $footerLines = []
    ): string {
        $pagesOfRows = $rows === [] ? [[]] : array_chunk($rows, self::ROWS_PER_PAGE);
        $totalPages = count($pagesOfRows);

        // Reserva de ids de objeto: 1=Catalog, 2=Pages, 3=Fuente regular, 4=Fuente negrita.
        // A partir de 5, un par (página, contenido) por cada página del reporte.
        $pageObjIds = [];
        $contentObjIds = [];
        $nextId = 5;
        foreach ($pagesOfRows as $i => $_) {
            $pageObjIds[$i] = $nextId++;
            $contentObjIds[$i] = $nextId++;
        }

        $objects = [];
        $objects[1] = '<< /Type /Catalog /Pages 2 0 R >>';
        $kids = implode(' ', array_map(fn ($id) => "{$id} 0 R", $pageObjIds));
        $objects[2] = "<< /Type /Pages /Kids [{$kids}] /Count {$totalPages} >>";
        $objects[3] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>';
        $objects[4] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold /Encoding /WinAnsiEncoding >>';

        foreach ($pagesOfRows as $i => $pageRows) {
            $stream = self::buildPageStream(
                $title,
                $subtitleLines,
                $headers,
                $pageRows,
                $colX,
                $footerLines,
                $i === $totalPages - 1,
                $i + 1,
                $totalPages
            );

            $contentId = $contentObjIds[$i];
            $pageId = $pageObjIds[$i];

            $objects[$contentId] = '<< /Length '.strlen($stream)." >>\nstream\n{$stream}endstream";
            $objects[$pageId] = '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 '.self::PAGE_WIDTH.' '.self::PAGE_HEIGHT.'] '
                .'/Resources << /Font << /F1 3 0 R /F2 4 0 R >> >> '
                ."/Contents {$contentId} 0 R >>";
        }

        ksort($objects);

        return self::assemble($objects);
    }

    private static function buildPageStream(
        string $title,
        array $subtitleLines,
        array $headers,
        array $rows,
        array $colX,
        array $footerLines,
        bool $isLastPage,
        int $pageNumber,
        int $totalPages
    ): string {
        $lines = [];
        $y = self::PAGE_HEIGHT - self::MARGIN;

        $lines[] = self::textCommand('F2', 14, self::MARGIN, $y, $title);
        $y -= self::LINE_HEIGHT;

        foreach ($subtitleLines as $subtitle) {
            $lines[] = self::textCommand('F1', 10, self::MARGIN, $y, $subtitle);
            $y -= self::LINE_HEIGHT;
        }

        $y -= 4;

        // Encabezados de columna (negrita) + línea separadora.
        foreach ($headers as $i => $header) {
            $lines[] = self::textCommand('F2', 10, $colX[$i] ?? self::MARGIN, $y, $header);
        }
        $y -= 4;
        $lines[] = sprintf('%d %d m %d %d l S', self::MARGIN, $y, self::PAGE_WIDTH - self::MARGIN, $y);
        $y -= self::LINE_HEIGHT;

        if ($rows === []) {
            $lines[] = self::textCommand('F1', 10, self::MARGIN, $y, 'No hay datos para los filtros seleccionados.');
            $y -= self::LINE_HEIGHT;
        }

        foreach ($rows as $row) {
            foreach ($row as $i => $cell) {
                $lines[] = self::textCommand('F1', 10, $colX[$i] ?? self::MARGIN, $y, (string) $cell);
            }
            $y -= self::LINE_HEIGHT;
        }

        if ($isLastPage && $footerLines !== []) {
            $y -= 8;
            $lines[] = sprintf('%d %d m %d %d l S', self::MARGIN, $y, self::PAGE_WIDTH - self::MARGIN, $y);
            $y -= self::LINE_HEIGHT;
            foreach ($footerLines as $footer) {
                $lines[] = self::textCommand('F2', 10, self::MARGIN, $y, $footer);
                $y -= self::LINE_HEIGHT;
            }
        }

        $pieDePagina = "Página {$pageNumber} de {$totalPages} — Generado el ".date('d/m/Y H:i');
        $lines[] = self::textCommand('F1', 8, self::MARGIN, self::MARGIN - 10, $pieDePagina);

        return implode("\n", $lines)."\n";
    }

    private static function textCommand(string $font, int $size, float $x, float $y, string $text): string
    {
        $encoded = self::encode($text);

        return sprintf('BT /%s %d Tf 1 0 0 1 %.2F %.2F Tm (%s) Tj ET', $font, $size, $x, $y, $encoded);
    }

    /**
     * Convierte el texto UTF-8 de la aplicación a Windows-1252 (compatible con
     * WinAnsiEncoding) y escapa los caracteres especiales de las cadenas PDF.
     */
    private static function encode(string $text): string
    {
        $converted = @iconv('UTF-8', 'Windows-1252//TRANSLIT//IGNORE', $text);
        if ($converted === false) {
            $converted = $text;
        }

        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $converted);
    }

    private static function assemble(array $objects): string
    {
        $pdf = "%PDF-1.4\n";
        $offsets = [];

        foreach ($objects as $id => $body) {
            $offsets[$id] = strlen($pdf);
            $pdf .= "{$id} 0 obj\n{$body}\nendobj\n";
        }

        $maxId = max(array_keys($objects));

        $xrefLines = "0000000000 65535 f \n";
        for ($id = 1; $id <= $maxId; $id++) {
            $xrefLines .= sprintf("%010d 00000 n \n", $offsets[$id] ?? 0);
        }

        $xrefOffset = strlen($pdf);
        $pdf .= "xref\n0 ".($maxId + 1)."\n{$xrefLines}";
        $pdf .= 'trailer'."\n<< /Size ".($maxId + 1).' /Root 1 0 R >>'."\n";
        $pdf .= "startxref\n{$xrefOffset}\n%%EOF";

        return $pdf;
    }
}