<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Support\PhoneNumber;
use App\Support\PublicFormAbuseGuard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        if (PublicFormAbuseGuard::hasHoneypotValue($request)) {
            return back()->with('success', 'Terima kasih, kami akan menghubungi Anda.');
        }

        $data = $request->validate([
            'parent_name' => ['required', 'string', 'min:3', 'max:150'],
            'child_name' => ['required', 'string', 'min:2', 'max:150'],
            'child_age' => ['required', 'integer', 'min:2', 'max:12'],
            'selected_program' => ['required', 'string', 'max:100'],
            'whatsapp_number' => ['required', 'regex:/^(\\+62|62|0)8[0-9]{8,13}$/', 'max:30'],
            'note' => ['nullable', 'string', 'max:1000'],
            'source_page' => ['nullable', 'string', 'max:100'],
            PublicFormAbuseGuard::honeypotField() => ['nullable', 'max:0'],
        ], [
            'whatsapp_number.regex' => 'Nomor WhatsApp harus memakai format Indonesia yang valid.',
        ]);

        unset($data[PublicFormAbuseGuard::honeypotField()]);

        $normalizedPhone = PhoneNumber::normalizeIndonesianWhatsapp($data['whatsapp_number']);
        PublicFormAbuseGuard::ensureLeadAllowed($request, (string) $normalizedPhone);

        Lead::create($data + ['status' => 'baru']);
        PublicFormAbuseGuard::hit($request, 'lead', (string) $normalizedPhone);

        return back()->with('success', 'Terima kasih, kami akan menghubungi Anda.');
    }
}
