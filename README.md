# AI Blog Manager

An intelligent content management system that allows you to manage your blog using natural language commands. Built with Laravel and powered by OpenAI's Structured Outputs with Function Calling.

## ✨ Features

- **Natural Language Commands** - Manage your blog using plain English
- **AI-Powered Function Calling** - Commands are converted into structured function calls
- **Real-time Execution** - See immediate results from your commands
- **Content Management** - Create, update, delete, and search blog posts
- **Category & Tag Management** - Organize content with AI assistance
- **Analytics & Reporting** - Get insights about your blog performance
- **Modern UI** - Clean, professional interface with dark mode support
- **Responsive Design** - Works seamlessly on desktop, tablet, and mobile

## 📋 Requirements

- PHP 8.2+
- Laravel 11.x
- Composer
- OpenAI API Key
- SQLite (default) or your preferred database
- Node.js & npm (for frontend assets)

## 🛠 Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/ai-blog-manager.git
   cd ai-blog-manager
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Set up your OpenAI API key**
   ```bash
   # Edit .env file and add your OpenAI API key
   OPENAI_API_KEY=sk-proj-your-api-key-here
   ```

6. **Run database migrations**
   ```bash
   php artisan migrate
   ```

7. **Build frontend assets**
   ```bash
   npm run build
   ```

8. **Start the development server**
   ```bash
   php artisan serve
   ```

9. **Visit the application**
   Open your browser to `http://localhost:8000`

## 🎯 Usage

### Available Commands

The AI Blog Manager supports natural language commands across several categories:

#### Content Creation
- `"Create a blog post about Laravel best practices with sections on routing, middleware, and validation"`
- `"Create a new category called 'Web Development' with blue color"`
- `"Add tags for modern web technologies"`

#### Content Management
- `"Find all draft posts and publish them"`
- `"Update all PHP posts with new tags including 'modern-php'"`
- `"Set featured flag on trending posts"`
- `"Delete posts about outdated technology"`

#### Search & Analytics
- `"Show me all posts about JavaScript"`
- `"Generate analytics report for this month"`
- `"Find posts by category and status"`
- `"Search for posts containing 'Laravel'"`

#### AI Content Generation
- `"Generate content about 'Future of Web Development' in professional style"`
- `"Create engaging blog content about AI trends"`

### Quick Start Examples

1. **Create your first post:**
   ```
   Create a blog post about "Getting Started with Laravel" with an introduction, installation guide, and first steps
   ```

2. **Organize content:**
   ```
   Create categories for Programming, Web Development, and Tutorials with different colors
   ```

3. **Get insights:**
   ```
   Show me analytics for published posts this month
   ```

## 🏗 Architecture

### Core Components

- **AIBlogService** - Main service handling OpenAI Structured Outputs
- **Post Model** - Blog post entity with relationships
- **Category Model** - Content categorization
- **Tag Model** - Content tagging system
- **Responsive Frontend** - Modern UI with Tailwind CSS

### Function Calling System

The AI converts natural language into structured function calls:

- `create_post` - Creates new blog posts
- `update_post` - Updates existing posts
- `delete_post` - Removes posts
- `search_posts` - Finds posts by criteria
- `create_category` - Creates new categories
- `create_tag` - Creates new tags
- `update_category` - Updates category information
- `update_tags` - Manages post tags
- `generate_content` - AI content generation
- `get_analytics` - Blog performance analytics

### Database Schema

```
Posts
├── id, title, slug, content, excerpt
├── status (draft, published, archived)
├── featured (boolean)
├── published_at, created_at, updated_at
├── category_id (foreign key)
├── author, read_time
└── meta_data (JSON)

Categories
├── id, name, slug
├── description, color
└── created_at, updated_at

Tags
├── id, name, slug, color
└── created_at, updated_at

Post_Tag (pivot)
├── post_id, tag_id
└── created_at, updated_at
```

## 🔧 Configuration

### Environment Variables

```env
# OpenAI Configuration
OPENAI_API_KEY=your_openai_api_key_here

# Database Configuration
DB_CONNECTION=sqlite

# App Configuration
APP_NAME="AI Blog Manager"
APP_ENV=local
APP_DEBUG=true
```

### OpenAI Model

The application uses `gpt-4o-2024-08-06` for optimal structured output performance. You can modify this in `AIBlogService.php` if needed.

## 📊 API Endpoints

### Blog Management API

**POST** `/api/blog`

Process natural language blog management commands.

**Request:**
```json
{
  "command": "Create a blog post about Laravel tips"
}
```

**Response:**
```json
{
  "success": true,
  "intent": "create_post",
  "summary": "Creating a new blog post about Laravel tips",
  "function_name": "create_post",
  "execution_results": [
    {
      "function": "create_post",
      "success": true,
      "result": {
        "id": 1,
        "title": "Laravel Tips",
        "slug": "laravel-tips",
        "status": "draft",
        "message": "Post created successfully"
      }
    }
  ]
}
```

### Blog Display

**GET** `/blog` - View published blog posts

## 🎨 Frontend Features

- **Clean, Professional Design** - Modern shadcn-inspired UI components
- **Responsive Design** - Works seamlessly on desktop, tablet, and mobile
- **Dark Mode Support** - Automatic detection of user preference
- **Interactive UI** - Real-time command processing and results
- **Error Handling** - Graceful error messages and recovery
- **Example Commands** - Quick-access buttons for common tasks
- **Tailwind CSS** - Utility-first CSS framework for rapid styling

## 🚦 Command Examples

### Content Creation
```
Create a comprehensive blog post about "Modern JavaScript Features" with sections on ES6+, async/await, and modules
```

### Batch Operations
```
Find all posts tagged with "outdated" and update them to archived status
```

### Analytics
```
Generate a detailed analytics report showing post performance, popular categories, and publishing trends for the last quarter
```

### Content Generation
```
Generate engaging content for a blog post about "The Future of Artificial Intelligence" in a professional, informative style
```

## 🔍 Troubleshooting

### Common Issues

1. **OpenAI API Errors**
   - Verify your API key is correct
   - Check your OpenAI account has sufficient credits
   - Ensure you're using a supported model

2. **Database Issues**
   - Run `php artisan migrate` to ensure tables exist
   - Check database permissions

3. **Schema Validation Errors**
   - The application uses strict JSON schemas
   - All function parameters must be properly defined

### Debug Mode

Enable debug mode in `.env`:
```env
APP_DEBUG=true
```

Check Laravel logs:
```bash
tail -f storage/logs/laravel.log
```

## 🎬 Demo

### Example Workflow

1. **Navigate to the application** at `http://localhost:8000`
2. **Choose a command type** from the interface buttons or write your own
3. **Enter your natural language command** in the text area
4. **Click "Execute AI Command"** to process your request
5. **View structured results** with detailed execution information
6. **Check your blog** at `/blog` to see the results

### Screenshots

The application features a clean, professional interface with:
- Modern card-based design
- Intuitive command input area
- Real-time execution results
- Responsive layout that works on all devices

## 🤝 Contributing

We welcome contributions! Please follow these steps:

1. **Fork the repository**
2. **Create a feature branch** (`git checkout -b feature/amazing-feature`)
3. **Make your changes** and ensure they follow the project's coding standards
4. **Add tests** if applicable
5. **Commit your changes** (`git commit -m 'Add some amazing feature'`)
6. **Push to the branch** (`git push origin feature/amazing-feature`)
7. **Open a Pull Request**

### Development Guidelines

- Follow PSR-12 coding standards for PHP
- Use meaningful commit messages
- Update documentation for new features
- Ensure all tests pass before submitting

## 📝 License

This project is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🙏 Acknowledgments

- Built with [Laravel](https://laravel.com/)
- Powered by [OpenAI](https://openai.com/) Structured Outputs
- UI styled with [Tailwind CSS](https://tailwindcss.com/)
- Uses [OpenAI PHP Client](https://github.com/openai-php/client)

## 📞 Support

For questions, issues, or feature requests, please open an issue on the repository.

---

**Built with ❤️ using Laravel 11 & OpenAI Structured Outputs + Function Calling**