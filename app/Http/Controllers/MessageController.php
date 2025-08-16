<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->hasRole('super_admin')) {
            $messages = Message::with(['sender', 'recipient', 'salon'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        } else {
            $messages = Message::where('sender_id', $user->id)
                ->orWhere('recipient_id', $user->id)
                ->with(['sender', 'recipient', 'salon'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        }
        
        return view('messages.index', compact('messages'));
    }

    public function create()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return view('messages.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:internal,notification,system',
            'priority' => 'required|in:low,normal,high,urgent',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $request->recipient_id,
            'salon_id' => Auth::user()->salon_id,
            'subject' => $request->subject,
            'message' => $request->message,
            'type' => $request->type,
            'priority' => $request->priority,
            'sent_at' => now(),
        ]);

        return redirect()->route('messages.index')
            ->with('success', 'Message sent successfully!');
    }

    public function show(Message $message)
    {
        // Check if user can view this message
        if (!Auth::user()->hasRole('super_admin') && 
            $message->sender_id !== Auth::id() && 
            $message->recipient_id !== Auth::id()) {
            abort(403);
        }

        // Mark as read if recipient is viewing
        if ($message->recipient_id === Auth::id() && !$message->isRead()) {
            $message->markAsRead();
        }

        return view('messages.show', compact('message'));
    }

    public function edit(Message $message)
    {
        // Only sender can edit
        if ($message->sender_id !== Auth::id()) {
            abort(403);
        }

        $users = User::where('id', '!=', Auth::id())->get();
        return view('messages.edit', compact('message', 'users'));
    }

    public function update(Request $request, Message $message)
    {
        // Only sender can update
        if ($message->sender_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:internal,notification,system',
            'priority' => 'required|in:low,normal,high,urgent',
        ]);

        $message->update($request->all());

        return redirect()->route('messages.index')
            ->with('success', 'Message updated successfully!');
    }

    public function destroy(Message $message)
    {
        // Only sender or recipient can delete
        if ($message->sender_id !== Auth::id() && $message->recipient_id !== Auth::id()) {
            abort(403);
        }

        $message->delete();

        return redirect()->route('messages.index')
            ->with('success', 'Message deleted successfully!');
    }

    public function inbox()
    {
        $messages = Message::where('recipient_id', Auth::id())
            ->with(['sender', 'salon'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('messages.inbox', compact('messages'));
    }

    public function sent()
    {
        $messages = Message::where('sender_id', Auth::id())
            ->with(['recipient', 'salon'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('messages.sent', compact('messages'));
    }

    public function markAsRead(Message $message)
    {
        if ($message->recipient_id === Auth::id()) {
            $message->markAsRead();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 403);
    }

    public function getUnreadCount()
    {
        $count = Message::where('recipient_id', Auth::id())
            ->whereNull('read_at')
            ->count();

        return response()->json(['count' => $count]);
    }
}
