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
    public function index(Request $request)
    {
        $query = SupportTicket::with('user');
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        if ($request->filled('q')) {
            $query->where(function($q) use ($request) {
                $q->where('subject', 'like', '%' . $request->q . '%')
                  ->orWhere('ticket_number', 'like', '%' . $request->q . '%')
                  ->orWhereHas('user', function($userQuery) use ($request) {
                      $userQuery->where('name', 'like', '%' . $request->q . '%');
                  });
            });
        }
        
        $tickets = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.support.index', compact('tickets'));
    }

    public function show(SupportTicket $ticket)
    {
        $ticket->load('user');
        return view('admin.support.show', compact('ticket'));
    }

    public function respond(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'admin_response' => 'required|string|min:10',
            'status' => 'required|in:open,in_progress,resolved,closed',
        ]);

        $ticket->admin_response = $validated['admin_response'];
        $ticket->status = $validated['status'];
        $ticket->responded_at = now();
        $ticket->save();

        return redirect()->back()->with('success', 'Đã gửi phản hồi cho khách hàng!');
    }

    public function updateStatus(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
        ]);

        $ticket->status = $validated['status'];
        $ticket->save();

        return redirect()->back()->with('success', 'Đã cập nhật trạng thái ticket!');
    }

    public function destroy(SupportTicket $ticket)
    {
        $ticket->delete();
        return redirect()->route('admin.support.index')->with('success', 'Đã xóa ticket!');
    }
}
