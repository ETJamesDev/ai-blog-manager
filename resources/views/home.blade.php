<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>AI Blog Manager - Intelligent Content Management</title>
        <meta name="description" content="Manage your blog with natural language commands. Create, edit, and organize content using AI-powered structured outputs.">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            'sans': ['Inter', 'sans-serif'],
                        },
                        animation: {
                            'pulse-slow': 'pulse 3s ease-in-out infinite',
                            'bounce-slow': 'bounce 2s infinite',
                        }
                    }
                }
            }
        </script>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body class="bg-gray-50 dark:bg-gray-900 min-h-screen">
        <!-- Navigation -->
        <nav class="border-b border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-950 sticky top-0 z-50">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gray-900 dark:bg-gray-100 rounded-md flex items-center justify-center">
                                <svg class="w-4 h-4 text-white dark:text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                            </div>
                            <h1 class="text-lg font-semibold text-gray-900 dark:text-gray-100">AI Blog Manager</h1>
                        </div>
                    </div>
                    <div class="flex items-center space-x-6">
                        <a href="/blog" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100 transition-colors text-sm">
                            Blog
                        </a>
                        <a href="/drafts" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100 transition-colors text-sm">
                            Drafts
                        </a>
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100 transition-colors text-sm">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100 transition-colors text-sm">
                                    Sign in
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="bg-gray-900 dark:bg-gray-100 text-white dark:text-gray-900 hover:bg-gray-800 dark:hover:bg-gray-200 px-3 py-1.5 rounded-md text-sm font-medium transition-colors">
                                        Get started
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <main class="flex-1">
            <div class="bg-white dark:bg-gray-950">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
                    <div class="text-center space-y-8">
                        <!-- Simple, clean icon -->
                        <div class="flex justify-center">
                            <div class="w-16 h-16 bg-gray-900 dark:bg-gray-100 rounded-lg flex items-center justify-center">
                                <svg class="w-8 h-8 text-white dark:text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Clean typography -->
                        <div class="space-y-6">
                            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight text-gray-900 dark:text-gray-50">
                                AI Blog Manager
                            </h1>
                            
                            <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto leading-relaxed">
                                Manage your blog content with natural language commands. Create, edit, organize, and analyze your posts using AI-powered automation.
                            </p>
                        </div>
                        
                        <!-- Simple CTA buttons -->
                        <div class="flex flex-col sm:flex-row gap-3 justify-center mt-8">
                            <button onclick="document.getElementById('input-text').focus()" class="bg-gray-900 dark:bg-gray-50 text-white dark:text-gray-900 hover:bg-gray-800 dark:hover:bg-gray-200 font-medium px-6 py-3 rounded-md transition-colors">
                                Get started
                            </button>
                            <a href="/blog" class="border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900 font-medium px-6 py-3 rounded-md transition-colors">
                                View blog
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Blog Management Interface -->
            <div class="bg-gray-50 dark:bg-gray-900 py-24">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    <!-- Command Type Selector -->
                    <div class="bg-white dark:bg-gray-950 rounded-lg border border-gray-200 dark:border-gray-800 p-8 mb-8 shadow-sm">
                        <div class="text-center mb-8">
                            <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-50 mb-2">Choose an action</h2>
                            <p class="text-gray-600 dark:text-gray-400">Select a command type or write your own instruction</p>
                        </div>
                        
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                            <!-- Content Creation -->
                            <button onclick="setExampleCommand('create')" class="group p-4 rounded-md border border-gray-200 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors text-center">
                                <div class="w-8 h-8 bg-gray-100 dark:bg-gray-800 rounded-md flex items-center justify-center mb-3 mx-auto">
                                    <svg class="w-4 h-4 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </div>
                                <h3 class="font-medium text-gray-900 dark:text-gray-50 text-sm mb-1">Create</h3>
                                <p class="text-gray-600 dark:text-gray-400 text-xs">New content</p>
                            </button>

                            <!-- Content Management -->
                            <button onclick="setExampleCommand('manage')" class="group p-4 rounded-md border border-gray-200 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors text-center">
                                <div class="w-8 h-8 bg-gray-100 dark:bg-gray-800 rounded-md flex items-center justify-center mb-3 mx-auto">
                                    <svg class="w-4 h-4 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </div>
                                <h3 class="font-medium text-gray-900 dark:text-gray-50 text-sm mb-1">Manage</h3>
                                <p class="text-gray-600 dark:text-gray-400 text-xs">Edit posts</p>
                            </button>

                            <!-- Search & Analytics -->
                            <button onclick="setExampleCommand('search')" class="group p-4 rounded-md border border-gray-200 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors text-center">
                                <div class="w-8 h-8 bg-gray-100 dark:bg-gray-800 rounded-md flex items-center justify-center mb-3 mx-auto">
                                    <svg class="w-4 h-4 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="font-medium text-gray-900 dark:text-gray-50 text-sm mb-1">Search</h3>
                                <p class="text-gray-600 dark:text-gray-400 text-xs">Find & analyze</p>
                            </button>

                            <!-- AI Generation -->
                            <button onclick="setExampleCommand('generate')" class="group p-4 rounded-md border border-gray-200 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors text-center">
                                <div class="w-8 h-8 bg-gray-100 dark:bg-gray-800 rounded-md flex items-center justify-center mb-3 mx-auto">
                                    <svg class="w-4 h-4 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <h3 class="font-medium text-gray-900 dark:text-gray-50 text-sm mb-1">Generate</h3>
                                <p class="text-gray-600 dark:text-gray-400 text-xs">AI content</p>
                            </button>
                        </div>
                    </div>

                    <!-- Input Section -->
                    <div class="bg-white dark:bg-gray-950 rounded-lg border border-gray-200 dark:border-gray-800 p-8 shadow-sm">
                        <!-- Quick Examples -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-50 mb-4">Examples</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <button onclick="setCommand('Create a blog post about Laravel best practices with sections on routing, middleware, and validation')" class="text-left p-3 rounded-md border border-gray-200 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                                    <div class="flex items-center space-x-3">
                                        <div class="text-lg">📝</div>
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-gray-50 text-sm">Laravel guide</div>
                                            <div class="text-gray-600 dark:text-gray-400 text-xs">Best practices post</div>
                                        </div>
                                    </div>
                                </button>
                                
                                <button onclick="setCommand('Find all posts about PHP and update their tags to include modern-php')" class="text-left p-3 rounded-md border border-gray-200 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                                    <div class="flex items-center space-x-3">
                                        <div class="text-lg">🏷️</div>
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-gray-50 text-sm">Update tags</div>
                                            <div class="text-gray-600 dark:text-gray-400 text-xs">Bulk tag updates</div>
                                        </div>
                                    </div>
                                </button>
                                
                                <button onclick="setCommand('Generate analytics report for this month showing post performance')" class="text-left p-3 rounded-md border border-gray-200 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                                    <div class="flex items-center space-x-3">
                                        <div class="text-lg">📊</div>
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-gray-50 text-sm">Analytics</div>
                                            <div class="text-gray-600 dark:text-gray-400 text-xs">Performance report</div>
                                        </div>
                                    </div>
                                </button>
                                
                                <button onclick="setCommand('Create a new category called Web Development with blue color')" class="text-left p-3 rounded-md border border-gray-200 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                                    <div class="flex items-center space-x-3">
                                        <div class="text-lg">📁</div>
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-gray-50 text-sm">New category</div>
                                            <div class="text-gray-600 dark:text-gray-400 text-xs">Create categories</div>
                                        </div>
                                    </div>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Command Input -->
                        <div class="space-y-4">
                            <div>
                                <label for="input-text" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Command</label>
                                <textarea 
                                    id="input-text" 
                                    rows="4" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 dark:bg-gray-900 dark:text-gray-100 resize-none" 
                                    placeholder="Tell me what you want to do with your blog...">
                                </textarea>
                            </div>
                            
                            <button 
                                onclick="executeBlogCommand()" 
                                id="execute-btn"
                                class="w-full bg-gray-900 dark:bg-gray-50 text-white dark:text-gray-900 hover:bg-gray-800 dark:hover:bg-gray-200 font-medium py-2 px-4 rounded-md transition-colors">
                                Execute command
                            </button>
                        </div>
                    </div>

                    <!-- Results Section -->
                    <div id="results-section" class="hidden bg-white dark:bg-gray-950 rounded-lg border border-gray-200 dark:border-gray-800 p-6 shadow-sm mt-8">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-50">Results</h3>
                            <button onclick="clearResults()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <div id="loading" class="text-center py-8">
                            <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-gray-900 dark:border-gray-100"></div>
                            <p class="mt-2 text-gray-600 dark:text-gray-400 text-sm">Processing command...</p>
                        </div>
                        <div id="content" class="hidden space-y-4"></div>
                    </div>
                </div>

            </div>
        </main>

        <!-- Footer -->
        <footer class="border-t border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-950">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="text-center space-y-4">
                    <div class="flex items-center justify-center space-x-2">
                        <div class="w-6 h-6 bg-gray-900 dark:bg-gray-100 rounded flex items-center justify-center">
                            <svg class="w-3 h-3 text-white dark:text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                            </svg>
                        </div>
                        <span class="font-medium text-gray-900 dark:text-gray-100">AI Blog Manager</span>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">
                        Built with Laravel {{ app()->version() }} and AI-powered automation
                    </p>
                </div>
            </div>
        </footer>

        <script>
            function setCommand(command) {
                document.getElementById('input-text').value = command;
            }

            function setExampleCommand(type) {
                const examples = {
                    create: 'Create a blog post about "Introduction to Machine Learning" with sections on supervised learning, unsupervised learning, and practical applications',
                    manage: 'Find all posts with status "draft" and publish them with featured flag set to true',
                    search: 'Search for all posts about "JavaScript" and show me their categories and tags',
                    generate: 'Generate content for a blog post about "Future of Web Development" in a professional style'
                };
                
                setCommand(examples[type] || examples.create);
            }

            async function executeBlogCommand() {
                const command = document.getElementById('input-text').value.trim();
                
                if (!command) {
                    alert('Please enter a blog management command!');
                    return;
                }
                
                // Show results section
                document.getElementById('results-section').classList.remove('hidden');
                document.getElementById('loading').classList.remove('hidden');
                document.getElementById('content').classList.add('hidden');
                
                // Scroll to results
                document.getElementById('results-section').scrollIntoView({ behavior: 'smooth' });
                
                try {
                    const response = await fetch('/api/blog', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            command: command
                        })
                    });
                    
                    const data = await response.json();
                    
                    // Hide loading
                    document.getElementById('loading').classList.add('hidden');
                    document.getElementById('content').classList.remove('hidden');
                    
                    if (data.success) {
                        displayResults(data);
                    } else {
                        document.getElementById('content').innerHTML = `
                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                                <p class="text-red-700 dark:text-red-300">Error: ${data.message || 'Something went wrong. Please try again.'}</p>
                            </div>
                        `;
                    }
                } catch (error) {
                    document.getElementById('loading').classList.add('hidden');
                    document.getElementById('content').classList.remove('hidden');
                    document.getElementById('content').innerHTML = `
                        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                            <p class="text-red-700 dark:text-red-300">Network error. Please check your connection and try again.</p>
                        </div>
                    `;
                }
            }

            function displayResults(data) {
                const content = document.getElementById('content');
                let html = '';
                
                // Show command summary
                html += `
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6 mb-6">
                        <h4 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3">Command Analysis</h4>
                        <p class="text-blue-800 dark:text-blue-200 mb-3">${data.summary}</p>
                        <p class="text-blue-700 dark:text-blue-300 text-sm">Intent: <span class="font-medium">${data.intent}</span></p>
                    </div>
                `;

                // Show function execution results
                if (data.execution_results && data.execution_results.length > 0) {
                    html += `
                        <div class="space-y-4">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Execution Results</h4>
                    `;
                    
                    data.execution_results.forEach((result, index) => {
                        const bgColor = result.success ? 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800' : 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800';
                        const textColor = result.success ? 'text-green-900 dark:text-green-100' : 'text-red-900 dark:text-red-100';
                        const icon = result.success ? '✅' : '❌';
                        
                        html += `
                            <div class="${bgColor} border rounded-lg p-4">
                                <div class="flex items-start space-x-3">
                                    <span class="text-lg">${icon}</span>
                                    <div class="flex-1">
                                        <h5 class="font-semibold ${textColor} mb-2">${result.function}</h5>
                                        ${result.success ? `
                                            <div class="text-sm ${textColor.replace('900', '800').replace('100', '200')}">
                                                <pre class="bg-white dark:bg-gray-800 p-3 rounded text-xs overflow-x-auto">${JSON.stringify(result.result, null, 2)}</pre>
                                            </div>
                                        ` : `
                                            <p class="text-sm ${textColor.replace('900', '800').replace('100', '200')}">${result.error}</p>
                                        `}
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    
                    html += '</div>';
                }
                
                content.innerHTML = html;
            }

            function clearResults() {
                document.getElementById('results-section').classList.add('hidden');
                document.getElementById('input-text').value = '';
            }

            // Dark mode detection
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.documentElement.classList.add('dark');
            }
        </script>
    </body>
</html>