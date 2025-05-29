<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Blog Posts - AI Blog Manager</title>
        <meta name="description" content="Browse blog posts managed by AI-powered content management.">
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
                        <a href="/drafts" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100 transition-colors text-sm">
                            Drafts
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Blog Content -->
        <main class="flex-1">
            <div class="bg-white dark:bg-gray-950">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
                    <div class="text-center space-y-4 mb-16">
                        <h1 class="text-4xl font-bold tracking-tight text-gray-900 dark:text-gray-50">
                            Blog Posts
                        </h1>
                        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                            Content created and managed with AI-powered automation
                        </p>
                    </div>

                    @if($posts->count() > 0)
                        <!-- Blog Posts Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                            @foreach($posts as $post)
                            <article class="bg-white dark:bg-gray-950 rounded-lg border border-gray-200 dark:border-gray-800 p-6 hover:shadow-sm transition-shadow">
                                <div class="mb-4 flex flex-wrap gap-2">
                                    @if($post->category)
                                    <span class="inline-block px-2 py-1 text-xs font-medium rounded-md bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                                        {{ $post->category->name }}
                                    </span>
                                    @endif
                                    @if($post->featured)
                                    <span class="inline-block px-2 py-1 text-xs font-medium bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200 rounded-md">
                                        Featured
                                    </span>
                                    @endif
                                    <span class="inline-block px-2 py-1 text-xs font-medium rounded-md 
                                        {{ $post->status === 'published' ? 'bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200' : 
                                           'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">
                                        {{ ucfirst($post->status) }}
                                    </span>
                                </div>
                                
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-50 mb-2">
                                    <a href="{{ $post->status === 'published' ? '/post/' . $post->slug : '/draft/' . $post->slug }}" class="hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                                        {{ $post->title }}
                                    </a>
                                </h2>
                                
                                <p class="text-gray-600 dark:text-gray-400 mb-4 text-sm line-clamp-3">
                                    {{ $post->excerpt }}
                                </p>
                                
                                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-4">
                                    <span>By {{ $post->author }}</span>
                                    <span>{{ $post->read_time }} min read</span>
                                </div>
                                
                                @if($post->tags->count() > 0)
                                <div class="flex flex-wrap gap-1 mb-4">
                                    @foreach($post->tags->take(3) as $tag)
                                    <span class="inline-block px-2 py-1 text-xs rounded-md bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400">
                                        {{ $tag->name }}
                                    </span>
                                    @endforeach
                                    @if($post->tags->count() > 3)
                                    <span class="inline-block px-2 py-1 text-xs rounded-md bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400">
                                        +{{ $post->tags->count() - 3 }}
                                    </span>
                                    @endif
                                </div>
                                @endif
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $post->published_at ? $post->published_at->format('M j, Y') : 'Draft' }}
                                    </span>
                                    <a href="{{ $post->status === 'published' ? '/post/' . $post->slug : '/draft/' . $post->slug }}" class="text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-gray-100 text-sm font-medium transition-colors">
                                        Read more →
                                    </a>
                                </div>
                            </article>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="flex justify-center">
                            {{ $posts->links() }}
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-16">
                            <div class="w-12 h-12 bg-gray-100 dark:bg-gray-800 rounded-md flex items-center justify-center mx-auto mb-4">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-50 mb-2">No blog posts yet</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6">Start creating content using the AI Blog Manager</p>
                            <a href="/" class="inline-flex items-center px-4 py-2 bg-gray-900 dark:bg-gray-100 hover:bg-gray-800 dark:hover:bg-gray-200 text-white dark:text-gray-900 font-medium rounded-md transition-colors text-sm">
                                Create your first post
                            </a>
                        </div>
                    @endif
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

        <!-- Dark mode detection -->
        <script>
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.documentElement.classList.add('dark');
            }
        </script>
    </body>
</html>