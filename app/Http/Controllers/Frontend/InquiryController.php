<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Subscriber;
use App\Mail\AdminNotificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class InquiryController extends Controller
{
    /**
     * Handle Contact Us form submission
     */
    public function contactStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|string|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $lead = Lead::create([
            'type'    => 'contact',
            'name'    => $request->name,
            'email'   => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'metadata' => [
                'phone' => $request->phone,
            ],
        ]);

        // Send Email to Admin
        $this->notifyAdmin($lead);

        return response()->json(['success' => true, 'message' => 'Your message has been sent successfully.']);
    }

    /**
     * Handle Advertise With Us form submission
     */
    public function advertiseStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $lead = Lead::create([
            'type'    => 'advertising_inquiry',
            'name'    => $request->name,
            'email'   => $request->email,
            'subject' => 'Advertise With Us Inquiry',
            'message' => 'New advertisement inquiry received.',
            'metadata' => [
                'phone'   => $request->phone,
                'company' => $request->company,
            ],
        ]);

        // Send Email to Admin
        $this->notifyAdmin($lead);

        return response()->json(['success' => true, 'message' => 'Your advertising request has been received.']);
    }

    /**
     * Handle Service Inquiry from Modal
     */
    public function serviceInquiryStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'message' => 'required|string',
            'service' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $lead = Lead::create([
            'type'     => 'service_inquiry',
            'name'     => $request->name,
            'email'    => $request->email,
            'message'  => $request->message,
            'metadata' => ['service' => $request->service],
        ]);

        // Send Email to Admin
        $this->notifyAdmin($lead);

        return response()->json(['success' => true, 'message' => 'Your inquiry has been received.']);
    }

    /**
     * Handle Newsletter signup
     */
    public function newsletterStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:subscribers,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'This email is already subscribed or invalid.'], 422);
        }

        Subscriber::create([
            'email' => $request->email,
        ]);

        // Also create a Lead record so it shows in the Admin Leads panel
        $lead = Lead::create([
            'type'    => 'newsletter',
            'name'    => 'Newsletter Subscriber',
            'email'   => $request->email,
            'subject' => 'New Newsletter Subscription',
            'message' => 'User subscribed to the daily newsletter.',
        ]);

        // Send Email to Admin
        $this->notifyAdmin($lead);

        return response()->json(['success' => true, 'message' => 'Thank you for subscribing!']);
    }

    /**
     * Notify Admin about new Lead
     */
    protected function notifyAdmin(Lead $lead)
    {
        try {
            $emailsString = \App\Models\Setting::get('notification_emails', config('mail.from.address'));
            $emails = array_filter(array_map('trim', explode(',', $emailsString)));
            
            if (!empty($emails)) {
                Mail::to($emails)->send(new AdminNotificationMail($lead));
            }
        } catch (\Exception $e) {
            \Log::error("Mail failed: " . $e->getMessage());
        }
    }
}
