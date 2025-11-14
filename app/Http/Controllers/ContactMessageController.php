<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactMessageRequest;
use App\Jobs\SendContactMessageToAdminJob;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreContactMessageRequest $request)
    {
        $attributes = collect($request->validated());

        $message = ContactMessage::query()->create($attributes->toArray());

        SendContactMessageToAdminJob::dispatch($message);

        alert(
            'Başarılı',
            'Mesaj başarıyla gönderildi.',
            'success'
        );

        return redirect()->back();
    }
}
