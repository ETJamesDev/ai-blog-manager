<?php

namespace App\Services;

use OpenAI;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Str;

class AIBlogService
{
    private $client;

    public function __construct()
    {
        $this->client = OpenAI::client(env('OPENAI_API_KEY'));
    }

    /**
     * Process natural language blog management commands
     */
    public function processBlogCommand(string $command): array
    {
        $schema = [
            'type' => 'object',
            'properties' => [
                'intent' => [
                    'type' => 'string',
                    'enum' => ['create_post', 'update_post', 'delete_post', 'search_posts', 'manage_categories', 'manage_tags', 'generate_content', 'analytics'],
                    'description' => 'The primary action the user wants to perform'
                ],
                'function_name' => [
                    'type' => 'string',
                    'enum' => [
                        'create_post',
                        'update_post', 
                        'delete_post',
                        'search_posts',
                        'create_category',
                        'create_tag',
                        'update_category',
                        'update_tags',
                        'generate_content',
                        'get_analytics'
                    ],
                    'description' => 'The main function to execute'
                ],
                'title' => [
                    'type' => ['string', 'null'],
                    'description' => 'Title for posts or categories'
                ],
                'content' => [
                    'type' => ['string', 'null'],
                    'description' => 'Content for posts'
                ],
                'category' => [
                    'type' => ['string', 'null'],
                    'description' => 'Category name'
                ],
                'tags' => [
                    'type' => ['array', 'null'],
                    'items' => ['type' => 'string'],
                    'description' => 'Array of tag names'
                ],
                'status' => [
                    'type' => ['string', 'null'],
                    'enum' => ['draft', 'published', 'archived', null],
                    'description' => 'Post status'
                ],
                'featured' => [
                    'type' => ['boolean', 'null'],
                    'description' => 'Whether post is featured'
                ],
                'query' => [
                    'type' => ['string', 'null'],
                    'description' => 'Search query'
                ],
                'description' => [
                    'type' => ['string', 'null'],
                    'description' => 'Description for categories'
                ],
                'color' => [
                    'type' => ['string', 'null'],
                    'description' => 'Color code for categories/tags'
                ],
                'summary' => [
                    'type' => 'string',
                    'description' => 'Human-readable summary of what will be executed'
                ]
            ],
            'required' => ['intent', 'function_name', 'title', 'content', 'category', 'tags', 'status', 'featured', 'query', 'description', 'color', 'summary'],
            'additionalProperties' => false
        ];

        $systemPrompt = "You are an AI blog management assistant. Analyze user commands and determine the appropriate function to call with parameters.

Available functions:
- create_post: Creates a new blog post
- update_post: Updates existing posts  
- delete_post: Removes posts
- search_posts: Finds posts by criteria
- create_category: Creates new categories
- create_tag: Creates new tags
- update_category: Updates category info
- update_tags: Updates post tags
- generate_content: Generates AI content
- get_analytics: Shows blog statistics

Extract the intent, function to call, and relevant parameters from the user's command. Use specific field names like title, content, category, tags, status, featured, query, description, color as needed.";

        $userPrompt = "Process this blog management command: {$command}";

        return $this->makeStructuredRequest($systemPrompt, $userPrompt, $schema, 'blog_command');
    }

    /**
     * Execute the function call from structured data
     */
    public function executeStructuredCommand(array $data): array
    {
        try {
            $result = $this->executeFunction($data['function_name'], $data);
            return [
                'function' => $data['function_name'],
                'success' => true,
                'result' => $result
            ];
        } catch (Exception $e) {
            return [
                'function' => $data['function_name'],
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Execute individual function
     */
    private function executeFunction(string $function, array $parameters): mixed
    {
        return match($function) {
            'create_post' => $this->createPost($parameters),
            'update_post' => $this->updatePost($parameters),
            'delete_post' => $this->deletePost($parameters),
            'search_posts' => $this->searchPosts($parameters),
            'create_category' => $this->createCategory($parameters),
            'create_tag' => $this->createTag($parameters),
            'update_category' => $this->updateCategory($parameters),
            'update_tags' => $this->updateTags($parameters),
            'generate_content' => $this->generateContent($parameters),
            'get_analytics' => $this->getAnalytics($parameters),
            default => throw new Exception("Unknown function: {$function}")
        };
    }

    /**
     * Blog function implementations
     */
    private function createPost(array $params): array
    {
        $post = new Post();
        $post->title = $params['title'] ?? 'Untitled Post';
        $post->status = $params['status'] ?? 'draft';
        $post->featured = $params['featured'] ?? false;
        $post->author = 'AI Assistant';

        // Generate full content if not provided
        if (isset($params['content']) && !empty($params['content'])) {
            $post->content = $params['content'];
        } else {
            // Generate comprehensive blog content using AI
            $contentResult = $this->generateFullBlogContent($post->title);
            $post->content = $contentResult['content'];
            $post->excerpt = $contentResult['excerpt'];
        }

        if (isset($params['category'])) {
            $category = Category::firstOrCreate(['name' => $params['category']]);
            $post->category_id = $category->id;
        }

        $post->save();

        if (isset($params['tags']) && is_array($params['tags'])) {
            $tagIds = [];
            foreach ($params['tags'] as $tagName) {
                $tag = Tag::firstOrCreate(['name' => $tagName]);
                $tagIds[] = $tag->id;
            }
            $post->tags()->sync($tagIds);
        }

        return [
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'status' => $post->status,
            'content_length' => strlen($post->content),
            'excerpt' => substr($post->excerpt, 0, 100) . '...',
            'message' => 'Post created successfully with AI-generated content'
        ];
    }

    private function updatePost(array $params): array
    {
        $post = isset($params['id']) 
            ? Post::findOrFail($params['id'])
            : Post::where('slug', $params['slug'])->firstOrFail();

        if (isset($params['title'])) $post->title = $params['title'];
        if (isset($params['content'])) $post->content = $params['content'];
        if (isset($params['excerpt'])) $post->excerpt = $params['excerpt'];
        if (isset($params['status'])) $post->status = $params['status'];
        if (isset($params['featured'])) $post->featured = $params['featured'];

        if (isset($params['category'])) {
            $category = Category::where('name', $params['category'])->first();
            $post->category_id = $category ? $category->id : null;
        }

        $post->save();

        if (isset($params['tags'])) {
            $tagIds = [];
            foreach ($params['tags'] as $tagName) {
                $tag = Tag::firstOrCreate(['name' => $tagName]);
                $tagIds[] = $tag->id;
            }
            $post->tags()->sync($tagIds);
        }

        return [
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'status' => $post->status
        ];
    }

    private function deletePost(array $params): array
    {
        $post = isset($params['id']) 
            ? Post::findOrFail($params['id'])
            : Post::where('slug', $params['slug'])->firstOrFail();

        $deletedPost = [
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug
        ];

        $post->delete();

        return $deletedPost;
    }

    private function searchPosts(array $params): array
    {
        $query = Post::with(['category', 'tags']);

        if (isset($params['query'])) {
            $query->where('title', 'like', "%{$params['query']}%")
                  ->orWhere('content', 'like', "%{$params['query']}%");
        }

        if (isset($params['category'])) {
            $query->whereHas('category', function($q) use ($params) {
                $q->where('name', $params['category']);
            });
        }

        if (isset($params['tags'])) {
            $query->whereHas('tags', function($q) use ($params) {
                $q->whereIn('name', $params['tags']);
            });
        }

        if (isset($params['status'])) {
            $query->where('status', $params['status']);
        }

        $limit = $params['limit'] ?? 10;
        $posts = $query->orderBy('created_at', 'desc')->limit($limit)->get();

        return $posts->map(function($post) {
            return [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'status' => $post->status,
                'category' => $post->category?->name,
                'tags' => $post->tags->pluck('name')->toArray(),
                'created_at' => $post->created_at->format('Y-m-d H:i')
            ];
        })->toArray();
    }

    private function createCategory(array $params): array
    {
        $category = Category::create([
            'name' => $params['name'],
            'description' => $params['description'] ?? null,
            'color' => $params['color'] ?? '#6366f1'
        ]);

        return [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug
        ];
    }

    private function createTag(array $params): array
    {
        $tag = Tag::create([
            'name' => $params['name'],
            'color' => $params['color'] ?? '#10b981'
        ]);

        return [
            'id' => $tag->id,
            'name' => $tag->name,
            'slug' => $tag->slug
        ];
    }

    private function updateCategory(array $params): array
    {
        $category = isset($params['id']) 
            ? Category::findOrFail($params['id'])
            : Category::where('slug', $params['slug'])->firstOrFail();

        if (isset($params['name'])) $category->name = $params['name'];
        if (isset($params['description'])) $category->description = $params['description'];
        if (isset($params['color'])) $category->color = $params['color'];

        $category->save();

        return [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug
        ];
    }

    private function updateTags(array $params): array
    {
        $post = Post::findOrFail($params['post_id']);
        
        $tagIds = [];
        foreach ($params['tags'] as $tagName) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $tagIds[] = $tag->id;
        }
        
        $post->tags()->sync($tagIds);

        return [
            'post_id' => $post->id,
            'tags' => $post->tags->pluck('name')->toArray()
        ];
    }

    private function generateContent(array $params): array
    {
        $contentPrompt = "Generate {$params['type']} content about {$params['topic']}.";
        
        if (isset($params['length'])) {
            $contentPrompt .= " Length: {$params['length']}.";
        }
        
        if (isset($params['style'])) {
            $contentPrompt .= " Style: {$params['style']}.";
        }

        $response = $this->client->chat()->create([
            'model' => 'gpt-4o-2024-08-06',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a professional content writer. Create engaging, well-structured content.'],
                ['role' => 'user', 'content' => $contentPrompt]
            ],
            'max_tokens' => 2000,
            'temperature' => 0.7
        ]);

        return [
            'topic' => $params['topic'],
            'type' => $params['type'],
            'content' => $response->choices[0]->message->content
        ];
    }

    /**
     * Generate full blog post content with structured format
     */
    private function generateFullBlogContent(string $title): array
    {
        $schema = [
            'type' => 'object',
            'properties' => [
                'content' => [
                    'type' => 'string',
                    'description' => 'Full blog post content in HTML format with proper headings, paragraphs, and structure'
                ],
                'excerpt' => [
                    'type' => 'string',
                    'description' => 'A compelling 150-200 character excerpt/summary for the blog post'
                ]
            ],
            'required' => ['content', 'excerpt'],
            'additionalProperties' => false
        ];

        $systemPrompt = "You are an expert blog content writer. Create comprehensive, engaging blog posts with proper HTML structure including headings (h2, h3), paragraphs, lists, and formatting. Make the content informative, well-researched, and professionally written.";

        $userPrompt = "Write a complete blog post about: {$title}. 

Requirements:
- Create a comprehensive blog post of 800-1200 words
- Use proper HTML structure with h2 and h3 headings for sections
- Include an introduction, main sections with detailed explanations, and a conclusion
- Make it informative, engaging, and well-researched
- Include practical examples or applications where relevant
- Format with proper HTML paragraphs, lists, and emphasis
- Also provide a compelling excerpt of 150-200 characters";

        $result = $this->makeStructuredRequest($systemPrompt, $userPrompt, $schema, 'blog_content');
        
        if ($result['success']) {
            return $result['data'];
        } else {
            // Fallback content if AI generation fails
            return [
                'content' => '<p>This is a comprehensive blog post about ' . htmlspecialchars($title) . '. The content will be generated to provide valuable insights and information on this topic.</p>',
                'excerpt' => 'An in-depth exploration of ' . $title . ' with practical insights and detailed analysis.'
            ];
        }
    }

    private function getAnalytics(array $params): array
    {
        $period = $params['period'] ?? 'month';
        
        $stats = [
            'total_posts' => Post::count(),
            'published_posts' => Post::where('status', 'published')->count(),
            'draft_posts' => Post::where('status', 'draft')->count(),
            'featured_posts' => Post::where('featured', true)->count(),
            'total_categories' => Category::count(),
            'total_tags' => Tag::count()
        ];

        if ($period === 'month') {
            $stats['posts_this_month'] = Post::where('created_at', '>=', now()->startOfMonth())->count();
        } elseif ($period === 'week') {
            $stats['posts_this_week'] = Post::where('created_at', '>=', now()->startOfWeek())->count();
        }

        return $stats;
    }

    /**
     * Make a structured request to OpenAI API using Responses API
     */
    private function makeStructuredRequest(string $systemPrompt, string $userPrompt, array $schema, string $schemaName): array
    {
        try {
            $response = $this->client->responses()->create([
                'model' => 'gpt-4o-2024-08-06',
                'input' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt]
                ],
                'text' => [
                    'format' => [
                        'type' => 'json_schema',
                        'name' => $schemaName,
                        'strict' => true,
                        'schema' => $schema
                    ]
                ],
                'max_output_tokens' => 4000
            ]);

            // Handle different response status
            if ($response->status === 'incomplete') {
                throw new Exception('AI response was incomplete: ' . $response->incompleteDetails->reason);
            }

            // Check for refusal
            $content = $response->output[0]->content[0];
            if ($content->type === 'refusal') {
                throw new Exception('AI refused to generate content: ' . $content->refusal);
            }

            if ($content->type !== 'output_text') {
                throw new Exception('Unexpected response content type: ' . $content->type);
            }

            $data = json_decode($content->text, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Failed to parse AI response as JSON: ' . json_last_error_msg());
            }

            return [
                'success' => true,
                'data' => $data
            ];

        } catch (Exception $e) {
            Log::error('AI Blog Service Error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Failed to process blog command. Please try again.',
                'error' => $e->getMessage()
            ];
        }
    }
}