<?php

namespace App\Http\Controllers;

use App\Mail\NormalEmail;
use App\Models\Mail as MailModel;
use App\Models\MailAttachement;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SendMailController extends Controller
{
    public function index(Request $request)
    {
        $email = $request->query('email');
        return view('dashboard.sendmail', compact('email'));
    }

    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'attachments' => 'nullable|array|max:10',
            // Graph's sendMail endpoint caps the whole request at 4MB once base64 encoded,
            // so keep the raw payload comfortably under that.
            'attachments.*' => 'file|max:2560|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,webp,zip',
        ], [
            'email.required' => 'البريد المرسل إليه مطلوب.',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة.',
            'subject.required' => 'الموضوع مطلوب.',
            'body.required' => 'نص الرسالة مطلوب.',
            'attachments.*.max' => 'حجم كل ملف يجب ألا يتجاوز 2.5 ميغابايت.',
            'attachments.*.mimes' => 'صيغة الملف غير مسموحة.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'success' => false], 422);
        }

        $totalSize = collect($request->file('attachments') ?? [])->sum(fn ($file) => $file->getSize());

        if ($totalSize > 2.5 * 1024 * 1024) {
            return response()->json([
                'errors' => ['attachments' => ['مجموع حجم المرفقات يجب ألا يتجاوز 2.5 ميغابايت.']],
                'success' => false,
            ], 422);
        }

        try {
            $mail = new MailModel();
            $mail->user_id = Auth::id();
            $mail->email = $request->input('email');
            $mail->subject = $request->input('subject');
            $mail->body = $request->input('body');
            $mail->save();

            // Absolute paths handed to the mailable so the files travel with the email.
            $files = [];

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $uniqueName = Str::uuid()->toString() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                    $path = $file->storeAs('attachments', $uniqueName, 'public');

                    MailAttachement::create([
                        'mail_id'   => $mail->id,
                        'file_path' => asset('storage/' . $path),
                        'file_name' => $uniqueName,
                    ]);

                    $files[] = Storage::disk('public')->path($path);
                }
            }

            Mail::to($mail->email)->send(new NormalEmail($mail, $files));

            return response()->json(['message' => 'تم إرسال البريد بنجاح.', 'success' => true], 200);
        } catch (\Exception $e) {
            Log::error('Sending mail from dashboard failed', [
                'to' => $request->input('email'),
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return response()->json(['error' => $e->getMessage(), 'success' => false], 500);
        }
    }
}
