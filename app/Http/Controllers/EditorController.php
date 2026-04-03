<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectFile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EditorController extends Controller
{
    // ── Projects list page ─────────────────────────────────────────
    public function projects()
    {
        $projects = Project::withCount('files')
                           ->latest()
                           ->get();

        return view('projects', compact('projects'));
    }

    // ── Create a new project with a custom slug ────────────────────
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:80|unique:projects,slug|regex:/^[a-z0-9\-]+$/',
        ], [
            'slug.regex'  => 'Slug can only contain lowercase letters, numbers, and hyphens.',
            'slug.unique' => 'That slug is already taken. Please choose another.',
        ]);

        $project = Project::create([
            'name' => $request->name,
            'slug' => $request->slug,
        ]);

        $project->files()->createMany([
            ['filename' => 'index.html', 'type' => 'html', 'content' => $this->defaultHtml()],
            ['filename' => 'style.css',  'type' => 'css',  'content' => $this->defaultCss()],
            ['filename' => 'script.js',  'type' => 'js',   'content' => $this->defaultJs()],
        ]);

        return redirect()->route('editor.show', $project->slug)
                         ->with('success', 'Project "' . $project->name . '" created!');
    }

    // ── Delete a project ───────────────────────────────────────────
    public function destroy(string $slug)
    {
        $project = Project::where('slug', $slug)->firstOrFail();
        $project->delete(); // cascades to project_files

        return redirect()->route('projects')
                         ->with('success', 'Project deleted.');
    }

    // ── Load editor for existing project ──────────────────────────
    public function show(string $slug)
    {
        $project = Project::where('slug', $slug)
                          ->with('files')
                          ->firstOrFail();

        $files = $project->files->map(fn($f) => [
            'id'       => $f->id,
            'filename' => $f->filename,
            'type'     => $f->type,
            'content'  => $f->content ?? '',
        ])->values()->toArray();

        return view('editor', compact('project', 'files'));
    }

    // ── Rename project ─────────────────────────────────────────────
    public function rename(Request $request, string $slug)
    {
        $project = Project::where('slug', $slug)->firstOrFail();
        $project->update(['name' => $request->name]);
        return response()->json(['ok' => true]);
    }

    // ── Save / update a single file ────────────────────────────────
    public function saveFile(Request $request, string $slug)
    {
        $request->validate([
            'filename' => 'required|string|max:100',
            'content'  => 'nullable|string',
            'type'     => 'required|in:html,css,js',
        ]);

        $project = Project::where('slug', $slug)->firstOrFail();

        $project->files()->updateOrCreate(
            ['filename' => $request->filename],
            ['content'  => $request->content, 'type' => $request->type]
        );

        return response()->json([
            'ok'       => true,
            'saved_at' => now()->format('H:i:s'),
        ]);
    }

    // ── Add a new file to the project ─────────────────────────────
    public function addFile(Request $request, string $slug)
    {
        $request->validate([
            'filename' => 'required|string|max:100',
            'type'     => 'required|in:html,css,js',
        ]);

        $project = Project::where('slug', $slug)->firstOrFail();

        if ($project->files()->where('filename', $request->filename)->exists()) {
            return response()->json(['error' => 'File already exists.'], 422);
        }

        $starters = [
            'html' => "<!DOCTYPE html>\n<html lang=\"en\">\n<head>\n  <meta charset=\"UTF-8\">\n  <title>New Page</title>\n  <link rel=\"stylesheet\" href=\"style.css\">\n</head>\n<body>\n\n  <h1>New Page</h1>\n\n  <script src=\"script.js\"><\/script>\n</body>\n</html>",
            'css'  => "/* New stylesheet */\n",
            'js'   => "// New script\n",
        ];

        $file = $project->files()->create([
            'filename' => $request->filename,
            'type'     => $request->type,
            'content'  => $starters[$request->type],
        ]);

        return response()->json([
            'ok'   => true,
            'file' => [
                'id'       => $file->id,
                'filename' => $file->filename,
                'type'     => $file->type,
                'content'  => $file->content ?? '',
            ],
        ]);
    }

    // ── Delete a single file ───────────────────────────────────────
    public function deleteFile(string $slug, string $filename)
    {
        $project = Project::where('slug', $slug)->firstOrFail();

        if ($filename === 'index.html') {
            return response()->json(['error' => 'Cannot delete index.html.'], 422);
        }

        $project->files()->where('filename', $filename)->delete();
        return response()->json(['ok' => true]);
    }

    // ── List all files (for ZIP download) ─────────────────────────
    public function filesList(string $slug)
    {
        $project = Project::where('slug', $slug)->firstOrFail();

        return response()->json(
            $project->files->map(fn($f) => [
                'filename' => $f->filename,
                'type'     => $f->type,
                'content'  => $f->content ?? '',
            ])->values()
        );
    }

    // ── Preview server — serves files so inter-page links work ────
    public function preview(string $slug, string $filename = 'index.html')
    {
        $project = Project::where('slug', $slug)->firstOrFail();
        $file    = $project->files()->where('filename', $filename)->first();

        if (! $file) {
            abort(404, "File '{$filename}' not found in this project.");
        }

        $mimeMap = [
            'html' => 'text/html',
            'css'  => 'text/css',
            'js'   => 'application/javascript',
        ];

        return response($file->content ?? '', 200)
            ->header('Content-Type', $mimeMap[$file->type] ?? 'text/plain');
    }

    // ── Default starter content ────────────────────────────────────
    private function defaultHtml(): string
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Project</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <nav>
    <a href="index.html">Home</a>
    <a href="about.html">About</a>
  </nav>

  <main>
    <h1>Hello, INSPIRE! 👋</h1>
    <p>Edit the files in the tabs above.</p>
    <button onclick="greet()">Click me</button>
  </main>

  <script src="script.js"></script>
</body>
</html>
HTML;
    }

    private function defaultCss(): string
    {
        return <<<CSS
* { box-sizing: border-box; margin: 0; padding: 0; }

body {
  font-family: 'Segoe UI', sans-serif;
  background: linear-gradient(135deg, #0D1B2A, #1E3A5F);
  color: white;
  min-height: 100vh;
}

nav {
  display: flex;
  gap: 12px;
  padding: 16px 32px;
  background: rgba(0,0,0,0.3);
}

nav a {
  color: #00B4D8;
  text-decoration: none;
  font-weight: 600;
  padding: 6px 14px;
  border-radius: 6px;
  transition: background 0.2s;
}

nav a:hover { background: rgba(255,255,255,0.1); }

main {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 80vh;
  gap: 20px;
  text-align: center;
  padding: 40px;
}

h1 { font-size: 2.8rem; }
p  { opacity: 0.8; font-size: 1.1rem; }

button {
  background: linear-gradient(135deg, #06D6A0, #0077B6);
  color: white;
  border: none;
  padding: 12px 32px;
  border-radius: 8px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: transform 0.2s;
}
button:hover { transform: translateY(-3px); }
CSS;
    }

    private function defaultJs(): string
    {
        return <<<JS
function greet() {
  const name = prompt('What is your name?');
  if (name) {
    document.querySelector('h1').textContent = `Hello, \${name}! 🎉`;
  }
}
JS;
    }
}