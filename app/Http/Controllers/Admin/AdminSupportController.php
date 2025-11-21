<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\Request;

/**
 * Admin Support Controller
 * For responding to customer support tickets
 */
class AdminSupportController extends Controller
{
    /**
     * Show all support tickets (admin view)
     */
    public function index()
    {
        // TODO: Implement admin ticket list view
        // $tickets = SupportTicket::orderBy('created_at', 'desc')->paginate(20);
        // return view('admin.support.index', compact('tickets'));
    }

    /**
     * Show ticket detail for admin response
     */
    public function show(SupportTicket $ticket)
    {
        // TODO: Implement admin ticket detail view
        // return view('admin.support.show', compact('ticket'));
    }

    /**
     * Store admin response to ticket
     */
    public function respond(Request $request, SupportTicket $ticket)
    {
        // TODO: Implement response logic
        // $validated = $request->validate([
        //     'admin_response' => 'required|string|min:10',
        // ]);

        // $ticket->admin_response = $validated['admin_response'];
        // $ticket->status = 'resolved';
        // $ticket->responded_at = now();
        // $ticket->save();

        // TODO: Send notification email to customer

        // return redirect()->back()->with('success', 'Response sent to customer');
    }

    /**
     * Update ticket status
     */
    public function updateStatus(Request $request, SupportTicket $ticket)
    {
        // TODO: Implement status update
        // $validated = $request->validate([
        //     'status' => 'required|in:open,in_progress,resolved,closed',
        // ]);

        // $ticket->status = $validated['status'];
        // $ticket->save();

        // return redirect()->back()->with('success', 'Ticket status updated');
    }

    /**
     * Delete ticket
     */
    public function destroy(SupportTicket $ticket)
    {
        // TODO: Implement soft delete or hard delete
        // $ticket->delete();
        // return redirect()->route('admin.support.index')->with('success', 'Ticket deleted');
    }
}
