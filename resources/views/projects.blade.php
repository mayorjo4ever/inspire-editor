<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>INSPIRE — All Projects </title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: #0A1220;
      color: #fff;
      min-height: 100vh;
    }

    /* ── TOPBAR ── */
    .topbar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 32px;
      height: 56px;
      background: #050E1A;
      border-bottom: 1px solid #1A3A55;
    }
    .logo {
      font-size: 18px;
      font-weight: 700;
      color: #00B4D8;
      letter-spacing: 0.5px;
    }
    .logo span { color: #7B2FBE; }
    .logo small {
      font-size: 12px;
      font-weight: 400;
      color: #3A5A78;
      margin-left: 8px;
    }

    /* ── MAIN CONTAINER ── */
    .container {
      max-width: 1100px;
      margin: 0 auto;
      padding: 48px 24px;
    }

    .page-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 36px;
      flex-wrap: wrap;
      gap: 16px;
    }
    .page-title {
      font-size: 26px;
      font-weight: 700;
      color: #fff;
    }
    .page-title span {
      font-size: 14px;
      font-weight: 400;
      color: #3A5A78;
      margin-left: 10px;
    }

    /* ── BUTTONS ── */
    .btn-primary {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 10px 22px;
      background: linear-gradient(135deg, #0077B6, #7B2FBE);
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      transition: opacity 0.2s;
      text-decoration: none;
    }
    .btn-primary:hover { opacity: 0.88; }

    .btn-ghost {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 7px 14px;
      background: transparent;
      color: #5C7A93;
      border: 1px solid #1A3A55;
      border-radius: 7px;
      font-size: 13px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.18s;
      text-decoration: none;
    }
    .btn-ghost:hover { border-color: #00B4D8; color: #fff; }

    .btn-danger {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 6px 12px;
      background: transparent;
      color: #5C7A93;
      border: 1px solid transparent;
      border-radius: 6px;
      font-size: 12px;
      cursor: pointer;
      transition: all 0.18s;
    }
    .btn-danger:hover {
      background: rgba(255, 70, 70, 0.12);
      border-color: #FF4646;
      color: #FF6B6B;
    }

    /* ── FLASH MESSAGE ── */
    .flash {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 12px 18px;
      border-radius: 8px;
      margin-bottom: 28px;
      font-size: 14px;
    }
    .flash.success {
      background: rgba(6, 214, 160, 0.1);
      border: 1px solid rgba(6, 214, 160, 0.3);
      color: #06D6A0;
    }

    /* ── PROJECT GRID ── */
    .project-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 20px;
    }

    /* ── PROJECT CARD ── */
    .project-card {
      background: #0D1B2A;
      border: 1px solid #1A3A55;
      border-radius: 12px;
      overflow: hidden;
      transition: border-color 0.2s, transform 0.18s;
      display: flex;
      flex-direction: column;
    }
    .project-card:hover {
      border-color: #0077B6;
      transform: translateY(-2px);
    }

    /* Preview thumbnail strip */
    .card-preview {
      height: 7px;
      background: linear-gradient(to right, #0077B6, #7B2FBE, #06D6A0);
    }

    .card-body {
      padding: 20px;
      flex: 1;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .card-name {
      font-size: 16px;
      font-weight: 700;
      color: #fff;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .card-slug {
      font-size: 12px;
      font-family: 'Consolas', monospace;
      color: #00B4D8;
      background: rgba(0, 180, 216, 0.08);
      border: 1px solid rgba(0, 180, 216, 0.2);
      border-radius: 4px;
      padding: 3px 8px;
      width: fit-content;
    }

    .card-meta {
      display: flex;
      align-items: center;
      gap: 14px;
      font-size: 12px;
      color: #3A5A78;
      margin-top: auto;
    }

    .card-meta .dot {
      width: 4px; height: 4px;
      border-radius: 50%;
      background: #1A3A55;
      flex-shrink: 0;
    }

    .file-badges {
      display: flex;
      gap: 5px;
      flex-wrap: wrap;
    }
    .file-badge {
      font-size: 10px;
      font-weight: 700;
      padding: 2px 7px;
      border-radius: 4px;
      font-family: 'Consolas', monospace;
    }
    .file-badge.html { background: rgba(255,107,53,0.15); color: #FF6B35; }
    .file-badge.css  { background: rgba(0,180,216,0.15);  color: #00B4D8; }
    .file-badge.js   { background: rgba(255,209,102,0.15);color: #FFD166; }

    .card-actions {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 12px 20px;
      border-top: 1px solid #1A3A55;
      background: rgba(0,0,0,0.15);
    }

    /* ── EMPTY STATE ── */
    .empty-state {
      text-align: center;
      padding: 80px 20px;
      color: #3A5A78;
    }
    .empty-state .icon {
      font-size: 56px;
      margin-bottom: 20px;
      opacity: 0.5;
    }
    .empty-state h3 { font-size: 20px; color: #5C7A93; margin-bottom: 10px; }
    .empty-state p  { font-size: 14px; margin-bottom: 28px; }

    /* ── NEW PROJECT MODAL ── */
    .modal-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.7);
      z-index: 999;
      align-items: center;
      justify-content: center;
    }
    .modal-overlay.open { display: flex; }

    .modal {
      background: #0D1B2A;
      border: 1px solid #1A3A55;
      border-radius: 14px;
      padding: 32px;
      width: 460px;
      max-width: 95vw;
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    .modal-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .modal-header h2 { font-size: 18px; font-weight: 700; }
    .modal-close {
      background: none;
      border: none;
      color: #3A5A78;
      font-size: 22px;
      cursor: pointer;
      line-height: 1;
      padding: 2px 6px;
      border-radius: 4px;
    }
    .modal-close:hover { color: #fff; background: rgba(255,255,255,0.08); }

    .form-group { display: flex; flex-direction: column; gap: 7px; }

    .form-group label {
      font-size: 13px;
      font-weight: 600;
      color: #90A4B8;
    }
    .form-group label small {
      font-weight: 400;
      color: #3A5A78;
      margin-left: 6px;
      font-size: 11px;
    }

    .form-group input {
      background: #050E1A;
      border: 1px solid #1A3A55;
      color: #fff;
      padding: 10px 14px;
      border-radius: 7px;
      font-size: 14px;
      transition: border-color 0.2s;
      font-family: inherit;
    }
    .form-group input:focus {
      outline: none;
      border-color: #00B4D8;
    }
    .form-group input.slug-input {
      font-family: 'Consolas', monospace;
      color: #00B4D8;
    }

    .slug-preview {
      font-size: 11px;
      color: #3A5A78;
      font-family: 'Consolas', monospace;
      margin-top: 2px;
    }
    .slug-preview span { color: #00B4D8; }

    .form-error {
      font-size: 12px;
      color: #FF6B35;
      min-height: 16px;
    }

    .modal-footer {
      display: flex;
      gap: 10px;
      justify-content: flex-end;
      margin-top: 4px;
    }

    /* ── SEARCH BAR ── */
    .search-bar {
      position: relative;
      max-width: 320px;
    }
    .search-bar input {
      width: 100%;
      background: #050E1A;
      border: 1px solid #1A3A55;
      color: #fff;
      padding: 8px 14px 8px 36px;
      border-radius: 7px;
      font-size: 13px;
      transition: border-color 0.2s;
    }
    .search-bar input:focus { outline: none; border-color: #00B4D8; }
    .search-bar .search-icon {
      position: absolute;
      left: 12px; top: 50%;
      transform: translateY(-50%);
      font-size: 14px;
      color: #3A5A78;
      pointer-events: none;
    }

    /* Responsive */
    @media (max-width: 600px) {
      .container { padding: 28px 16px; }
      .page-header { flex-direction: column; align-items: flex-start; }
    }
  </style>
</head>
<body>

  {{-- TOPBAR --}}
  <div class="topbar">
    <div class="logo">
      INSPIRE <span>|</span> Code Editor
      <small>Web Design Programme</small>
    </div>
  </div>

  <div class="container">

    {{-- FLASH --}}
    @if(session('success'))
      <div class="flash success">✓ {{ session('success') }}</div>
    @endif

    {{-- PAGE HEADER --}}
    <div class="page-header">
      <div>
        <div class="page-title">
          My Projects
          <span>{{ $projects->count() }} {{ Str::plural('project', $projects->count()) }}</span>
        </div>
      </div>
      <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap">
        <div class="search-bar">
          <span class="search-icon">🔍</span>
          <input type="text" id="searchInput"
                 placeholder="Search projects…"
                 oninput="filterProjects(this.value)">
        </div>
        <button class="btn-primary" onclick="openModal()">
          ＋ New Project
        </button>
      </div>
    </div>

    {{-- PROJECT GRID --}}
    @if($projects->isEmpty())
      <div class="empty-state">
        <div class="icon">📂</div>
        <h3>No projects yet</h3>
        <p>Create your first project to get started.</p>
        <button class="btn-primary" onclick="openModal()" style="margin:0 auto">
          ＋ Create First Project
        </button>
      </div>
    @else
      <div class="project-grid" id="projectGrid">
        @foreach($projects as $project)
          <div class="project-card"
               data-name="{{ strtolower($project->name) }}"
               data-slug="{{ strtolower($project->slug) }}">
            <div class="card-preview"></div>
            <div class="card-body">
              <div class="card-name" title="{{ $project->name }}">{{ $project->name }}</div>
              <div class="card-slug">/{{ $project->slug }}</div>

              <div class="file-badges">
                @foreach($project->files as $file)
                  <span class="file-badge {{ $file->type }}">{{ $file->filename }}</span>
                @endforeach
              </div>

              <div class="card-meta">
                <span>{{ $project->files_count }} {{ Str::plural('file', $project->files_count) }}</span>
                <div class="dot"></div>
                <span>Updated {{ $project->updated_at->diffForHumans() }}</span>
              </div>
            </div>

            <div class="card-actions">
              <a href="{{ route('editor.show', $project->slug) }}"
                 class="btn-primary"
                 style="font-size:13px;padding:8px 18px">
                Open Editor →
              </a>
              <form method="POST"
                    action="{{ route('projects.destroy', $project->slug) }}"
                    onsubmit="return confirm('Delete project \'{{ $project->name }}\'?\nAll files will be permanently deleted.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">🗑 Delete</button>
              </form>
            </div>
          </div>
        @endforeach
      </div>
    @endif

  </div>

  {{-- NEW PROJECT MODAL --}}
  <div class="modal-overlay" id="modalOverlay">
    <div class="modal">
      <div class="modal-header">
        <h2>Create New Project</h2>
        <button class="modal-close" onclick="closeModal()">×</button>
      </div>

      <form method="POST" action="{{ route('projects.create') }}" id="createForm">
        @csrf

        <div class="form-group">
          <label for="name">Project Name</label>
          <input type="text" id="name" name="name"
                 placeholder="e.g. My Portfolio Website"
                 required autocomplete="off"
                 oninput="autoSlug(this.value)">
        </div>

        <div class="form-group">
          <label for="slug">
            URL Slug
            <small>lowercase letters, numbers, hyphens only</small>
          </label>
          <input type="text" id="slug" name="slug"
                 class="slug-input"
                 placeholder="e.g. my-portfolio"
                 required autocomplete="off"
                 pattern="[a-z0-9\-]+"
                 oninput="cleanSlug(this)">
          <div class="slug-preview">
            Preview: /editor/<span id="slugPreview">my-portfolio</span>
          </div>
        </div>

        <div class="form-error" id="formError">
          @error('slug') {{ $message }} @enderror
          @error('name') {{ $message }} @enderror
        </div>

        <div class="modal-footer">
          <button type="button" class="btn-ghost" onclick="closeModal()">Cancel</button>
          <button type="submit" class="btn-primary">Create Project →</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    // ── MODAL ─────────────────────────────────────────────────────
    function openModal() {
      document.getElementById('modalOverlay').classList.add('open');
      setTimeout(() => document.getElementById('name').focus(), 60);
    }
    function closeModal() {
      document.getElementById('modalOverlay').classList.remove('open');
    }
    document.getElementById('modalOverlay').addEventListener('click', function(e) {
      if (e.target === this) closeModal();
    });
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape') closeModal();
    });

    // Open modal automatically if there were validation errors
    @if($errors->any())
      openModal();
    @endif

    // ── AUTO-SLUG from project name ───────────────────────────────
    function autoSlug(name) {
      const slug = name
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9\s\-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .slice(0, 80);

      document.getElementById('slug').value = slug;
      document.getElementById('slugPreview').textContent = slug || '…';
    }

    function cleanSlug(input) {
      input.value = input.value
        .toLowerCase()
        .replace(/[^a-z0-9\-]/g, '')
        .replace(/-+/g, '-');
      document.getElementById('slugPreview').textContent = input.value || '…';
    }

    // ── SEARCH / FILTER ───────────────────────────────────────────
    function filterProjects(query) {
      const q = query.toLowerCase().trim();
      document.querySelectorAll('.project-card').forEach(card => {
        const name = card.dataset.name || '';
        const slug = card.dataset.slug || '';
        const match = name.includes(q) || slug.includes(q);
        card.style.display = match ? '' : 'none';
      });
    }
  </script>

</body>
</html>