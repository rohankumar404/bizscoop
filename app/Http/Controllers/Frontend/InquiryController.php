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
        ]);

        // Send Email to Admin
        $this->notifyAdmin($lead);

        return response()->json(['success' => true, 'message' => 'Your message has been sent successfully.']);
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
            $adminEmail = config('mail.from.address'); // Or a specific admin email from settings
            Mail::to($adminEmail)->send(new AdminNotificationMail($lead));
        } catch (\Exception $e) {
            \Log::error("Mail failed: " . $e->getMessage());
        }
    }
}
