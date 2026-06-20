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
        $fullPath = Storage::disk($disk)->path($path);

        return $this->fromAbsolutePath($fullPath);
    }

    /**
     * Extract plain text from any absolute filesystem path (e.g. an uploaded temp file).
     * Returns null on failure so callers can fall back to manual text input.
     */
    public function fromAbsolutePath(string $fullPath): ?string
    {
        try {
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
