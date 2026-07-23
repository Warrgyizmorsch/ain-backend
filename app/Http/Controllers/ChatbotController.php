<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    /**
     * Display the Chatbot Live CRM Dashboard.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $agentName = $user ? $user->name : 'Agent';

        return view('back-end.chatbot.index', compact('agentName'));
    }
}
