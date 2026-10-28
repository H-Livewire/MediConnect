<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Specialty;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        return view('pages.about');
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function sendMessage(Request $request): RedirectResponse
    {
        ContactMessage::create($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:3000'],
        ]));

        return back()->with('status', 'Thank you! Your message has been received.');
    }

    public function afyaSmart(): View
    {
        return view('pages.afya-smart', ['specialties' => Specialty::orderBy('name')->get()]);
    }
}
