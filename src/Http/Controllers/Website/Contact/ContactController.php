<?php

namespace Kakhura\CheckRequest\Http\Controllers\Website\Contact;

use Kakhura\CheckRequest\Http\Controllers\Website\Controller;
use Kakhura\CheckRequest\Models\Contact\Contact;

class ContactController extends Controller
{
    public function contact()
    {
        $contact = Contact::with([
            'detail' => function ($query) {
                $query->where('locale', app()->getLocale());
            },
        ])->first();
        return view('vendor.site-bases.website.contact.main', compact('contact'));
    }
}
