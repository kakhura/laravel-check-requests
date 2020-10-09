<?php

namespace Kakhura\CheckRequest\Http\Controllers\Admin\Page;

use Kakhura\CheckRequest\Http\Controllers\Admin\Controller;
use Kakhura\CheckRequest\Models\Contact\Contact;
use Kakhura\CheckRequest\Http\Requests\Contact\Request;
use Kakhura\CheckRequest\Services\Contact\ContactService;

class ContactController extends Controller
{
    public function contact()
    {
        $contact = Contact::first();
        return view('vendor.site-bases.admin.contact.edit', compact('contact'));
    }

    public function storeContact(Request $request, ContactService $contactService)
    {
        $contact = Contact::first();
        if (!is_null($contact)) {
            $contactService->update($request->validated(), $contact);
        } else {
            $contactService->create($request->validated());
        }
        return back()->with(['success' => 'ინფორმაცია წარმატებით შეიცვალა']);
    }
}
