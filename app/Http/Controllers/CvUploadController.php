<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadCvRequest;
use App\Models\CvUpload;
use App\Services\PdfTextExtractor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CvUploadController extends Controller
{
    public function index(Request $request)
    {
        $cvs = $request->user()->cvUploads()->withCount('reviews')->latest()->get();

        return view('cv.index', ['cvs' => $cvs]);
    }

    public function store(UploadCvRequest $request, PdfTextExtractor $extractor)
    {
        $user = $request->user();
        $extractedText = null;
        $filename = 'CV Manual - ' . now()->format('d M Y H:i');
        $path = '';
        $size = 0;
        $mime = 'text/plain';

        if ($request->hasFile('cv_file')) {
            $file = $request->file('cv_file');
            $filename = $file->getClientOriginalName();
            $size = $file->getSize();
            $mime = $file->getMimeType();
            // Private storage: storage/app/private/cv/{userId}/...
            $path = $file->store("cv/{$user->id}", 'local');
            $extractedText = $extractor->fromStoredPath($path, 'local');
        }

        // Manual text fallback (used when no PDF or extraction failed).
        if (blank($extractedText) && filled($request->input('manual_text'))) {
            $extractedText = $request->input('manual_text');
        }

        $cv = CvUpload::create([
            'user_id' => $user->id,
            'original_filename' => $filename,
            'file_path' => $path,
            'extracted_text' => $extractedText,
            'file_size' => $size,
            'mime_type' => $mime,
            'status' => filled($extractedText) ? 'completed' : 'failed',
        ]);

        if (blank($extractedText)) {
            return redirect()->route('cv.review.show', $cv)
                ->with('warning', 'Teks CV tidak terbaca otomatis. Tempel teks CV kamu secara manual di bawah, lalu jalankan review.');
        }

        return redirect()->route('cv.review.show', $cv)
            ->with('success', 'CV berhasil diunggah. Pilih target posisi untuk mulai review.');
    }

    public function destroy(Request $request, CvUpload $cv)
    {
        abort_unless($cv->user_id === $request->user()->id, 403);

        if ($cv->file_path) {
            Storage::disk('local')->delete($cv->file_path);
        }
        $cv->delete();

        return redirect()->route('cv.index')->with('success', 'CV dihapus.');
    }
}
