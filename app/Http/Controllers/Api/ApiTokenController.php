<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApiToken;
use Illuminate\Http\Request;

class ApiTokenController extends Controller
{
    public function index()
    {
        $apiTokens = ApiToken::all();
        return view('api_tokens.index', compact('apiTokens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $apiToken = ApiToken::generate($request->input('name'));

        return redirect()->route('api_tokens.index')->with('status', 'API Key generated successfully. Token: ' . $apiToken->token);
    }

    public function destroy(ApiToken $apiToken)
    {
        $apiToken->delete();

        return redirect()->route('api_tokens.index')->with('status', 'API Key deleted successfully.');
    }
}
