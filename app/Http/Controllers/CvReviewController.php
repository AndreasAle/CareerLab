<?php

namespace App\Http\Controllers;

use App\Http\Requests\CvReviewRequest;
use App\Models\CvReview;
use App\Models\CvUpload;
use App\Services\AI\CvReviewService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class CvReviewController extends Controller
{
    public function show(Request $request, CvUpload $cv)
    {
        $this->authorizeCv($request, $cv);

        $review = $cv->reviews()->latest()->first();

        return view('cv.review', [
            'cv' => $cv,
            'review' => $review,
        ]);
    }

    public function run(CvReviewRequest $request, CvUpload $cv, CvReviewService $service, SubscriptionService $subs)
    {
        $this->authorizeCv($request, $cv);

        if (! $subs->canUse($request->user(), 'cv_review')) {
            return redirect()->route('pricing')
                ->with('upgrade', 'Limit CV Review kamu sudah habis. Upgrade untuk lanjut review CV tanpa batas.');
        }

        // Allow manual text if extraction failed earlier.
        if (blank($cv->extracted_text) && filled($request->input('manual_text'))) {
            $cv->update([
                'extracted_text' => $request->input('manual_text'),
                'status' => 'completed',
            ]);
        }

        if (blank($cv->extracted_text)) {
            return back()->with('warning', 'Teks CV masih kosong. Tempel teks CV kamu dulu ya.');
        }

        $review = $service->review($cv, $request->input('target_position'));

        return redirect()->route('cv.review.show', $cv)
            ->with('success', 'Review CV selesai! Lihat hasilnya di bawah.')
            ->with('review_id', $review->id);
    }

    public function showReview(Request $request, CvReview $review)
    {
        abort_unless($review->user_id === $request->user()->id, 403);

        return view('cv.review', [
            'cv' => $review->cvUpload,
            'review' => $review,
        ]);
    }

    protected function authorizeCv(Request $request, CvUpload $cv): void
    {
        abort_unless($cv->user_id === $request->user()->id, 403);
    }
}
