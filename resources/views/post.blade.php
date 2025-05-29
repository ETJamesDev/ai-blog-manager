<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $post->title }} - AI Blog Manager</title>
        <meta name="description" content="{{ $post->excerpt }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            'sans': ['Inter', 'sans-serif'],
                        }
                    }
                }
            }
        </script>
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
                        <a href="/" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100 transition-colors text-sm">
                            Home
                        </a>
                        <a href="/blog" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100 transition-colors text-sm">
                            Blog
                        </a>
                        @if($post->status === 'draft')
                        <a href="/drafts" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100 transition-colors text-sm">
                            Drafts
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Post Content -->
        <main class="flex-1 bg-white dark:bg-gray-950">
            <article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <!-- Post Header -->
                <header class="mb-12 border-b border-gray-200 dark:border-gray-800 pb-8">
                    <!-- Category and Status Badges -->
                    <div class="mb-6 flex flex-wrap gap-2">
                        @if($post->category)
                        <span class="inline-block px-3 py-1 text-sm font-medium rounded-md bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                            {{ $post->category->name }}
                        </span>
                        @endif
                        @if($post->featured)
                        <span class="inline-block px-3 py-1 text-sm font-medium bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200 rounded-md">
                            Featured
                        </span>
                        @endif
                        @if($post->status === 'draft')
                        <span class="inline-block px-3 py-1 text-sm font-medium bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-md">
                            Draft
                        </span>
                        @endif
                    </div>
                    
                    <!-- Post Title -->
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 dark:text-gray-50 mb-6 leading-tight tracking-tight">
                        {{ $post->title }}
                    </h1>
                    
                    <!-- Post Meta -->
                    <div class="flex flex-wrap items-center gap-4 text-gray-600 dark:text-gray-400 text-sm mb-6">
                        <div class="flex items-center space-x-2">
                            <div class="w-6 h-6 bg-gray-900 dark:bg-gray-100 rounded-md flex items-center justify-center">
                                <span class="text-white dark:text-gray-900 text-xs font-medium">AI</span>
                            </div>
                            <span class="font-medium">{{ $post->author }}</span>
                        </div>
                        <span class="hidden sm:block">•</span>
                        <span>{{ $post->published_at ? $post->published_at->format('M j, Y') : $post->created_at->format('M j, Y') }}</span>
                        <span class="hidden sm:block">•</span>
                        <div class="flex items-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ $post->read_time }} min read</span>
                        </div>
                    </div>
                    
                    <!-- Post Excerpt -->
                    @if($post->excerpt)
                    <div class="text-lg text-gray-700 dark:text-gray-300 leading-relaxed mb-8 italic border-l-4 border-gray-200 dark:border-gray-700 pl-4">
                        {{ $post->excerpt }}
                    </div>
                    @endif
                    
                    <!-- Tags -->
                    @if($post->tags->count() > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($post->tags as $tag)
                        <span class="inline-block px-2 py-1 text-xs font-medium rounded-md bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400">
                            {{ $tag->name }}
                        </span>
                        @endforeach
                    </div>
                    @endif
                </header>

                <!-- Post Content -->
                <div class="bg-white dark:bg-gray-950 rounded-lg border border-gray-200 dark:border-gray-800 p-8 mb-12 shadow-sm">
                    <div class="prose prose-xl dark:prose-invert max-w-none
                        prose-headings:text-gray-900 dark:prose-headings:text-gray-100 prose-headings:font-bold prose-headings:tracking-tight
                        prose-p:text-gray-700 dark:prose-p:text-gray-300 prose-p:leading-relaxed prose-p:text-lg prose-p:mb-8
                        prose-li:text-gray-700 dark:prose-li:text-gray-300 prose-li:text-lg prose-li:leading-relaxed
                        prose-strong:text-gray-900 dark:prose-strong:text-gray-100 prose-strong:font-semibold
                        prose-em:text-gray-800 dark:prose-em:text-gray-200 prose-em:italic
                        prose-a:text-indigo-600 dark:prose-a:text-indigo-400 prose-a:font-medium prose-a:no-underline hover:prose-a:underline
                        prose-a:hover:text-indigo-800 dark:prose-a:hover:text-indigo-300 prose-a:transition-colors
                        prose-code:text-indigo-600 dark:prose-code:text-indigo-400 prose-code:font-mono prose-code:text-base
                        prose-code:bg-indigo-50 dark:prose-code:bg-gray-800 prose-code:px-2 prose-code:py-1 prose-code:rounded
                        prose-pre:bg-gray-900 dark:prose-pre:bg-gray-900 prose-pre:border prose-pre:border-gray-200 dark:prose-pre:border-gray-700
                        prose-pre:text-gray-100 prose-pre:text-sm prose-pre:leading-relaxed prose-pre:overflow-x-auto prose-pre:rounded-lg prose-pre:shadow-inner
                        prose-blockquote:text-gray-600 dark:prose-blockquote:text-gray-400 prose-blockquote:text-lg prose-blockquote:italic
                        prose-blockquote:border-l-4 prose-blockquote:border-indigo-500 prose-blockquote:bg-indigo-50/50 dark:prose-blockquote:bg-gray-800/50
                        prose-blockquote:pl-6 prose-blockquote:py-4 prose-blockquote:my-8 prose-blockquote:rounded-r-lg
                        prose-h1:text-4xl prose-h1:font-bold prose-h1:mt-12 prose-h1:mb-8 prose-h1:leading-tight
                        prose-h1:border-b-2 prose-h1:border-gray-200 dark:prose-h1:border-gray-700 prose-h1:pb-4
                        prose-h2:text-3xl prose-h2:font-bold prose-h2:mt-16 prose-h2:mb-6 prose-h2:leading-tight
                        prose-h2:border-b prose-h2:border-gray-200 dark:prose-h2:border-gray-700 prose-h2:pb-3
                        prose-h3:text-2xl prose-h3:font-semibold prose-h3:mt-12 prose-h3:mb-5 prose-h3:leading-tight
                        prose-h4:text-xl prose-h4:font-semibold prose-h4:mt-10 prose-h4:mb-4 prose-h4:leading-tight
                        prose-h5:text-lg prose-h5:font-semibold prose-h5:mt-8 prose-h5:mb-3
                        prose-h6:text-base prose-h6:font-semibold prose-h6:mt-6 prose-h6:mb-2
                        prose-ul:mb-8 prose-ul:space-y-2 prose-ol:mb-8 prose-ol:space-y-2
                        prose-li:mb-2 prose-li:text-gray-700 dark:prose-li:text-gray-300 prose-li:text-lg prose-li:leading-relaxed
                        prose-table:border-collapse prose-table:border prose-table:border-gray-300 dark:prose-table:border-gray-600 prose-table:rounded-lg prose-table:overflow-hidden prose-table:shadow-sm
                        prose-th:bg-gray-100 dark:prose-th:bg-gray-800 prose-th:text-gray-900 dark:prose-th:text-gray-100 prose-th:font-semibold prose-th:text-left prose-th:px-4 prose-th:py-3
                        prose-td:text-gray-700 dark:prose-td:text-gray-300 prose-td:border prose-td:border-gray-200 dark:prose-td:border-gray-600 prose-td:px-4 prose-td:py-3
                        prose-hr:border-gray-300 dark:prose-hr:border-gray-600 prose-hr:my-12
                        prose-img:rounded-lg prose-img:shadow-md prose-img:mx-auto
                        prose-figure:mx-auto prose-figure:text-center
                        prose-figcaption:text-gray-500 dark:prose-figcaption:text-gray-400 prose-figcaption:text-sm prose-figcaption:mt-2 prose-figcaption:italic">
                        
                        <!-- Enhanced styling for code blocks and syntax highlighting -->
                        <style>
                            /* Light mode code blocks */
                            .post-content pre {
                                background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important;
                                border: 1px solid #e2e8f0;
                                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                                position: relative;
                                margin: 2rem 0;
                            }
                            
                            .post-content pre code {
                                background: transparent !important;
                                color: #e2e8f0 !important;
                                font-family: 'JetBrains Mono', 'Fira Code', 'Monaco', 'Menlo', monospace;
                                font-size: 0.875rem;
                                line-height: 1.7;
                                padding: 0 !important;
                                border-radius: 0;
                            }
                            
                            .post-content pre::before {
                                content: '';
                                position: absolute;
                                top: 0;
                                left: 0;
                                right: 0;
                                height: 3px;
                                background: linear-gradient(90deg, #6366f1, #8b5cf6, #06b6d4);
                                border-radius: 0.5rem 0.5rem 0 0;
                            }
                            
                            /* Inline code styling */
                            .post-content code:not(pre code) {
                                font-family: 'JetBrains Mono', 'Fira Code', 'Monaco', 'Menlo', monospace !important;
                                font-weight: 500 !important;
                                border: 1px solid #e0e7ff !important;
                            }
                            
                            /* Typography and spacing fixes */
                            .post-content h1 {
                                font-size: 2.25rem !important; /* 36px */
                                font-weight: 700 !important;
                                line-height: 1.2 !important;
                                margin-top: 3rem !important;
                                margin-bottom: 1.5rem !important;
                                border-bottom: 2px solid #e5e7eb !important;
                                padding-bottom: 1rem !important;
                                color: #111827 !important;
                            }
                            
                            .post-content h2 {
                                font-size: 1.875rem !important; /* 30px */
                                font-weight: 700 !important;
                                line-height: 1.3 !important;
                                margin-top: 2.5rem !important;
                                margin-bottom: 1.25rem !important;
                                border-bottom: 1px solid #e5e7eb !important;
                                padding-bottom: 0.75rem !important;
                                color: #111827 !important;
                            }
                            
                            .post-content h3 {
                                font-size: 1.5rem !important; /* 24px */
                                font-weight: 600 !important;
                                line-height: 1.4 !important;
                                margin-top: 2rem !important;
                                margin-bottom: 1rem !important;
                                color: #111827 !important;
                            }
                            
                            .post-content h4 {
                                font-size: 1.25rem !important; /* 20px */
                                font-weight: 600 !important;
                                line-height: 1.4 !important;
                                margin-top: 1.75rem !important;
                                margin-bottom: 0.75rem !important;
                                color: #111827 !important;
                            }
                            
                            .post-content h5 {
                                font-size: 1.125rem !important; /* 18px */
                                font-weight: 600 !important;
                                margin-top: 1.5rem !important;
                                margin-bottom: 0.5rem !important;
                                color: #111827 !important;
                            }
                            
                            .post-content h6 {
                                font-size: 1rem !important; /* 16px */
                                font-weight: 600 !important;
                                margin-top: 1.25rem !important;
                                margin-bottom: 0.5rem !important;
                                color: #111827 !important;
                            }
                            
                            .post-content p {
                                font-size: 1.125rem !important; /* 18px */
                                line-height: 1.75 !important;
                                margin-bottom: 1.5rem !important;
                                color: #374151 !important;
                            }
                            
                            .post-content ul,
                            .post-content ol {
                                margin-bottom: 1.5rem !important;
                                padding-left: 1.5rem !important;
                            }
                            
                            .post-content li {
                                font-size: 1.125rem !important;
                                line-height: 1.75 !important;
                                margin-bottom: 0.5rem !important;
                                color: #374151 !important;
                            }
                            
                            .post-content strong {
                                font-weight: 600 !important;
                                color: #111827 !important;
                            }
                            
                            .post-content em {
                                font-style: italic !important;
                                color: #374151 !important;
                            }
                            
                            .post-content a {
                                color: #4f46e5 !important;
                                text-decoration: none !important;
                                font-weight: 500 !important;
                                transition: color 0.15s ease-in-out !important;
                            }
                            
                            .post-content a:hover {
                                color: #3730a3 !important;
                                text-decoration: underline !important;
                            }
                            
                            .post-content blockquote {
                                font-size: 1.125rem !important;
                                font-style: italic !important;
                                color: #6b7280 !important;
                                border-left: 4px solid #4f46e5 !important;
                                background-color: #f8fafc !important;
                                padding: 1.5rem !important;
                                margin: 2rem 0 !important;
                                border-radius: 0 0.5rem 0.5rem 0 !important;
                            }
                            
                            .post-content hr {
                                border: none !important;
                                border-top: 1px solid #e5e7eb !important;
                                margin: 3rem 0 !important;
                            }
                            
                            /* Dark mode adjustments */
                            .dark .post-content h1,
                            .dark .post-content h2,
                            .dark .post-content h3,
                            .dark .post-content h4,
                            .dark .post-content h5,
                            .dark .post-content h6 {
                                color: rgb(243 244 246) !important;
                                border-color: #374151 !important;
                            }
                            
                            .dark .post-content p,
                            .dark .post-content li {
                                color: rgb(209 213 219) !important;
                            }
                            
                            .dark .post-content strong {
                                color: rgb(243 244 246) !important;
                            }
                            
                            .dark .post-content em {
                                color: rgb(209 213 219) !important;
                            }
                            
                            .dark .post-content a {
                                color: rgb(129 140 248) !important;
                            }
                            
                            .dark .post-content a:hover {
                                color: rgb(165 180 252) !important;
                            }
                            
                            .dark .post-content blockquote {
                                color: rgb(156 163 175) !important;
                                background-color: rgba(31, 41, 55, 0.5) !important;
                            }
                            
                            .dark .post-content hr {
                                border-color: #374151 !important;
                            }
                            .dark .post-content pre {
                                background: linear-gradient(135deg, #111827 0%, #000000 100%) !important;
                                border: 1px solid #374151 !important;
                            }
                            .dark .post-content code:not(pre code) {
                                background-color: rgb(31 41 55) !important;
                                color: rgb(129 140 248) !important;
                                border-color: #374151 !important;
                            }
                            .dark .post-content blockquote {
                                color: rgb(156 163 175) !important;
                            }
                            
                            /* Table enhancements */
                            .post-content table {
                                margin: 2rem 0;
                                width: 100%;
                                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
                            }
                            
                            .post-content table tr:nth-child(even) {
                                background-color: rgba(249, 250, 251, 0.5);
                            }
                            
                            .dark .post-content table tr:nth-child(even) {
                                background-color: rgba(31, 41, 55, 0.3);
                            }
                            
                            /* Image enhancements */
                            .post-content img {
                                max-width: 100%;
                                height: auto;
                                border-radius: 0.75rem;
                                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                                margin: 2rem auto;
                                display: block;
                            }
                            
                            .post-content figure {
                                margin: 2rem 0;
                            }
                            
                            .post-content figcaption {
                                text-align: center;
                                font-style: italic;
                                margin-top: 0.75rem;
                                padding: 0 1rem;
                            }
                        </style>
                        
                        <div class="post-content">
                            {!! $post->content !!}
                        </div>
                    </div>
                </div>

                <!-- Post Footer -->
                <footer class="mt-12 bg-white dark:bg-gray-950 rounded-lg border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
                    <!-- Article Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 text-center">
                        <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-md">
                            <div class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-1">{{ $post->read_time }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Min Read</div>
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-md">
                            <div class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-1">{{ $post->tags->count() }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Tags</div>
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-md">
                            <div class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-1">{{ ucfirst($post->status) }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Status</div>
                        </div>
                    </div>
                    
                    <!-- Meta Information -->
                    <div class="border-t border-gray-200 dark:border-gray-800 pt-6">
                        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                <div class="flex items-center space-x-2 mb-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>Published {{ $post->published_at ? $post->published_at->format('F j, Y \a\t g:i A') : 'as draft on ' . $post->created_at->format('F j, Y \a\t g:i A') }}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                    </svg>
                                    <span>Generated by AI Blog Manager</span>
                                </div>
                            </div>
                            
                            <div class="flex flex-wrap gap-2">
                                <a href="/blog" class="bg-gray-900 dark:bg-gray-100 hover:bg-gray-800 dark:hover:bg-gray-200 text-white dark:text-gray-900 px-4 py-2 rounded-md transition-colors text-sm font-medium flex items-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    <span>Back to Blog</span>
                                </a>
                                @if($post->status === 'draft')
                                <a href="/drafts" class="border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900 px-4 py-2 rounded-md transition-colors text-sm font-medium flex items-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    <span>Back to Drafts</span>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </footer>
            </article>
        </main>

        <!-- Footer -->
        <footer class="border-t border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-950 mt-20">
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

        <!-- Dark mode detection -->
        <script>
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.documentElement.classList.add('dark');
            }
        </script>
    </body>
</html>