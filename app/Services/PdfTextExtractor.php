<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;
use Throwable;

class PdfTextExtractor
{
    /**
     * Extract plain text from a stored PDF (private disk path).
     * Returns null on failure so callers can fall back to manual text input.
     */
    public function fromStoredPath(string $path, string $disk = 'local'): ?string
    {
        try {
            $fullPath = Storage::disk($disk)->path($path);

            if (! is_file($fullPath)) {
                return null;
            }

            $parser = new Parser();
            $pdf = $parser->parseFile($fullPath);
            $text = trim($pdf->getText());

            // Normalise whitespace.
            $text = preg_replace("/[ \t]+/", ' ', $text);
            $text = preg_replace("/\n{3,}/", "\n\n", $text);

            return $text !== '' ? $text : null;
        } catch (Throwable $e) {
            return null;
        }
    }
}
