<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ContactMessage;
use App\Models\Project;
use App\Models\Review;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home()
    {
        $stats = [
            'projects' => Project::count(),
            'clients' => Client::count(),
        ];

        $reviews = Review::where('is_approved', true)->latest()->take(12)->get();
        $avgRating = round((float) (Review::where('is_approved', true)->avg('rating') ?: 5), 1);
        $reviewCount = Review::where('is_approved', true)->count();

        return view('public.home', compact('stats', 'reviews', 'avgRating', 'reviewCount'));
    }

    public function about()
    {
        return view('public.about');
    }

    public function services()
    {
        return view('public.services');
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function submitContact(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        ContactMessage::create($data);

        return back()->with('status', 'Thank you! Your message has been received. We will get back to you shortly.');
    }
}
