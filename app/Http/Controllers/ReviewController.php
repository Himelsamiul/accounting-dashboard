<?php

namespace App\Http\Controllers;

use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::latest()->get();
        $avg = round((float) (Review::where('is_approved', true)->avg('rating') ?: 0), 1);
        return view('reviews.index', compact('reviews', 'avg'));
    }

    public function toggle(Review $review)
    {
        $review->update(['is_approved' => ! $review->is_approved]);

        return back()->with('status', $review->is_approved ? 'Review approved and published.' : 'Review hidden from the website.');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return back()->with('status', 'Review deleted.');
    }
}
