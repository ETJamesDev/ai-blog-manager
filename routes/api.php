<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Services\AIBlogService;

// API route for blog management
Route::post('/blog', function (Request $request, AIBlogService $blogService) {
    // Validate the incoming request
    $validated = $request->validate([
        'command' => 'required|string|max:1000'
    ]);

    try {
        // Step 1: Process the command and get structured function calls
        $commandResult = $blogService->processBlogCommand($validated['command']);
        
        if (!$commandResult['success']) {
            return response()->json([
                'success' => false,
                'message' => $commandResult['message'],
                'error' => $commandResult['error'] ?? null
            ], 500);
        }
        
        $commandData = $commandResult['data'];
        
        // Step 2: Execute the function call
        $executionResult = $blogService->executeStructuredCommand($commandData);
        
        // Step 3: Return structured response
        return response()->json([
            'success' => true,
            'intent' => $commandData['intent'],
            'summary' => $commandData['summary'],
            'function_name' => $commandData['function_name'],
            'execution_results' => [$executionResult]
        ]);
        
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while processing your blog command. Please try again.',
            'error' => config('app.debug') ? $e->getMessage() : null
        ], 500);
    }
});