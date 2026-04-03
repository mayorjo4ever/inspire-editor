<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $project->name }} — INSPIRE Editor</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/theme/dracula.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/xml/xml.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/css/css.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/javascript/javascript.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/htmlmixed/htmlmixed.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/edit/closetag.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/edit/closebrackets.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: #0D1B2A;
      color: #fff;
      height: 100vh;
      display: flex;
      flex-direction: column;
      overflow: hidden;
    }

    /* ── TOP BAR ── */
    .topbar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 16px;
      height: 50px;
      background: #050E1A;
      border-bottom: 1px solid #1A3A55;
      flex-shrink: 0;
      gap: 12px;
    }

    .logo {
      font-size: 15px;
      font-weight: 700;
      color: #00B4D8;
      white-space: nowrap;
    }
    .logo span { color: #7B2FBE; }

    .project-name-wrap {
      display: flex;
      align-items: center;
      gap: 8px;
      flex: 1;
      min-width: 0;
    }
    #projectName {
      background: transparent;
      border: 1px solid transparent;
      color: #90A4B8;
      font-size: 13px;
      padding: 4px 8px;
      border-radius: 5px;
      min-width: 160px;
      max-width: 280px;
      transition: border-color 0.2s, color 0.2s;
    }
    #projectName:hover { border-color: #1A3A55; color: #fff; }
    #projectName:focus { border-color: #00B4D8; color: #fff; outline: none; }

    .save-status {
      font-size: 11px;
      color: #3A5A78;
      white-space: nowrap;
    }
    .save-status.saving { color: #FFD166; }
    .save-status.saved  { color: #06D6A0; }
    .save-status.error  { color: #FF6B35; }

    .toolbar { display: flex; gap: 8px; align-items: center; flex-shrink: 0; }

    .btn {
      display: flex;
      align-items: center;
      gap: 6px;
      padding: 7px 14px;
      border-radius: 6px;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      border: none;
      transition: opacity 0.2s, background 0.2s;
    }
    .btn-run {
      background: linear-gradient(135deg, #06D6A0, #0077B6);
      color: white;
    }
    .btn-run:hover { opacity: 0.85; }
    .btn-run svg { width: 13px; height: 13px; fill: white; }

    .btn-ghost {
      background: rgba(255,255,255,0.07);
      color: #90A4B8;
      border: 1px solid rgba(255,255,255,0.1);
    }
    .btn-ghost:hover { background: rgba(255,255,255,0.13); color: #fff; }

    .auto-run-label {
      display: flex;
      align-items: center;
      gap: 5px;
      font-size: 12px;
      color: #5C7A93;
      cursor: pointer;
    }
    .auto-run-label input { accent-color: #06D6A0; }

    /* ── WORKSPACE ── */
    .workspace {
      display: flex;
      flex: 1;
      overflow: hidden;
    }

    /* ── LEFT: FILES + EDITOR ── */
    .left-panel {
      display: flex;
      flex-direction: column;
      width: 50%;
      border-right: 2px solid #1A3A55;
      overflow: hidden;
    }

    /* ── FILE TABS ── */
    .file-tabs-bar {
      display: flex;
      align-items: center;
      background: #050E1A;
      border-bottom: 2px solid #1A3A55;
      flex-shrink: 0;
      overflow-x: auto;
      scrollbar-width: none;
    }
    .file-tabs-bar::-webkit-scrollbar { display: none; }

    .file-tab {
      display: flex;
      align-items: center;
      gap: 7px;
      padding: 10px 16px;
      font-size: 13px;
      font-weight: 500;
      cursor: pointer;
      color: #5C7A93;
      border: none;
      background: transparent;
      border-bottom: 3px solid transparent;
      margin-bottom: -2px;
      white-space: nowrap;
      transition: color 0.2s, background 0.2s;
      position: relative;
    }
    .file-tab:hover { color: #fff; background: rgba(255,255,255,0.04); }
    .file-tab.active { color: #fff; background: #0D1F30; }

    /* Tab dot colour per file type */
    .file-tab.active[data-type="html"] { border-bottom-color: #FF6B35; }
    .file-tab.active[data-type="css"]  { border-bottom-color: #00B4D8; }
    .file-tab.active[data-type="js"]   { border-bottom-color: #FFD166; }

    .type-dot {
      width: 8px; height: 8px;
      border-radius: 50%;
      flex-shrink: 0;
    }

    .tab-close {
      margin-left: 2px;
      font-size: 14px;
      line-height: 1;
      color: #3A5A78;
      cursor: pointer;
      padding: 0 2px;
      border-radius: 3px;
    }
    .tab-close:hover { color: #FF6B35; background: rgba(255,107,53,0.15); }

    /* + New File button */
    .btn-new-file {
      display: flex;
      align-items: center;
      gap: 5px;
      padding: 8px 14px;
      font-size: 12px;
      font-weight: 600;
      color: #3A5A78;
      background: transparent;
      border: none;
      cursor: pointer;
      white-space: nowrap;
      flex-shrink: 0;
      transition: color 0.2s;
    }
    .btn-new-file:hover { color: #06D6A0; }

    /* ── EDITOR AREA ── */
    .editor-area {
      flex: 1;
      display: flex;
      flex-direction: column;
      overflow: hidden;
      position: relative;
    }

    .cm-instance {
      display: none;
      flex: 1;
      overflow: hidden;
    }
    .cm-instance.active { display: flex; }

    .cm-instance .CodeMirror {
      flex: 1;
      height: 100%;
      font-size: 18px;
      font-family: 'Consolas', 'Fira Code', monospace;
      line-height: 1.75;
      background: #0D1F30;
    }
    .cm-instance .CodeMirror-scroll { overflow: auto !important; }

    /* ── STATUS BAR ── */
    .status-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 3px 12px;
      background: #050E1A;
      border-top: 1px solid #1A3A55;
      font-size: 11px;
      color: #3A5A78;
      flex-shrink: 0;
      font-family: 'Consolas', monospace;
    }

    /* ── RESIZE ── */
    .resize-handle {
      width: 4px;
      background: #1A3A55;
      cursor: col-resize;
      flex-shrink: 0;
      transition: background 0.2s;
    }
    .resize-handle:hover { background: #00B4D8; }

    /* ── RIGHT: OUTPUT ── */
    .output-panel {
      display: flex;
      flex-direction: column;
      flex: 1;
      min-width: 0;
    }

    .output-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 16px;
      background: #050E1A;
      border-bottom: 2px solid #1A3A55;
      font-size: 13px;
      font-weight: 600;
      color: #5C7A93;
      flex-shrink: 0;
    }
    .output-bar .right { display: flex; align-items: center; gap: 12px; }
    .output-info {
      font-size: 11px;
      font-weight: 400;
      font-family: 'Consolas', monospace;
    }
    .output-info.ok  { color: #06D6A0; }
    .output-info.err { color: #FF6B35; }

    #output-frame {
      flex: 1;
      border: none;
      background: white;
      width: 100%;
    }

    /* ── NEW FILE MODAL ── */
    .modal-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.65);
      z-index: 999;
      align-items: center;
      justify-content: center;
    }
    .modal-overlay.open { display: flex; }

    .modal {
      background: #0D1B2A;
      border: 1px solid #1A3A55;
      border-radius: 10px;
      padding: 28px;
      width: 380px;
      display: flex;
      flex-direction: column;
      gap: 16px;
    }
    .modal h3 { font-size: 15px; color: #fff; }

    .modal input, .modal select {
      width: 100%;
      background: #050E1A;
      border: 1px solid #1A3A55;
      color: #fff;
      padding: 9px 12px;
      border-radius: 6px;
      font-size: 13px;
      font-family: 'Consolas', monospace;
    }
    .modal input:focus, .modal select:focus {
      outline: none;
      border-color: #00B4D8;
    }

    .modal-actions { display: flex; gap: 10px; justify-content: flex-end; }

    .modal-error {
      font-size: 12px;
      color: #FF6B35;
      min-height: 16px;
    }

    /* ── VIEWPORT BUTTONS ── */
.viewport-btns {
  display: flex;
  align-items: center;
  gap: 4px;
}
.vp-btn {
  display: flex;
  align-items: center;
  gap: 5px;
  padding: 5px 11px;
  font-size: 12px;
  font-weight: 600;
  border: 1px solid #1A3A55;
  border-radius: 5px;
  background: transparent;
  color: #5C7A93;
  cursor: pointer;
  transition: all 0.18s;
  white-space: nowrap;
}
.vp-btn:hover { border-color: #00B4D8; color: #fff; }
.vp-btn.active {
  border-color: #00B4D8;
  background: rgba(0, 180, 216, 0.12);
  color: #00B4D8;
}
.vp-btn svg {
  width: 13px;
  height: 13px;
  fill: currentColor;
  flex-shrink: 0;
}

/* ── DOWNLOAD BUTTON ── */
.btn-download {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 5px 13px;
  font-size: 12px;
  font-weight: 600;
  border: 1px solid #06D6A0;
  border-radius: 5px;
  background: rgba(6, 214, 160, 0.08);
  color: #06D6A0;
  cursor: pointer;
  transition: all 0.18s;
  white-space: nowrap;
}
.btn-download:hover {
  background: rgba(6, 214, 160, 0.2);
  color: #fff;
}
.btn-download svg { width: 13px; height: 13px; fill: currentColor; }
.btn-download.downloading { opacity: 0.6; pointer-events: none; }

/* ── OUTPUT WRAPPER (constrains iframe for viewport simulation) ── */
.output-body {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;       /* centres the constrained frame */
  background: #0A0A14;       /* letterbox background */
  overflow: hidden;
  position: relative;
}

.output-body.desktop {
  align-items: stretch;      /* desktop = full width */
}

.iframe-wrapper {
  display: flex;
  flex-direction: column;
  width: 100%;
  height: 100%;
  transition: width 0.25s ease, max-width 0.25s ease;
  background: white;
}

/* Constrained sizes */
.output-body.mobile .iframe-wrapper  { width: 375px;  max-width: 375px;  }
.output-body.tablet .iframe-wrapper  { width: 768px;  max-width: 768px;  }
.output-body.desktop .iframe-wrapper { width: 100%;   max-width: 100%;   }

#output-frame {
  flex: 1;
  border: none;
  width: 100%;
}

/* ── SIZE INDICATOR BAR ── */
.size-bar {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  padding: 4px 14px;
  background: #050E1A;
  border-top: 1px solid #1A3A55;
  font-size: 11px;
  font-family: 'Consolas', monospace;
  flex-shrink: 0;
  color: #3A5A78;
}

.size-bar .size-px {
  font-weight: 700;
  color: #00B4D8;
  min-width: 56px;
  text-align: right;
}

.size-bar .size-divider { color: #1A3A55; }

.size-bar .size-label {
  font-weight: 600;
  min-width: 68px;
}
.size-bar .size-label.mobile  { color: #FF6B35; }
.size-bar .size-label.tablet  { color: #FFD166; }
.size-bar .size-label.desktop { color: #06D6A0; }

/* ── BREAKPOINT RULER DOTS ── */
.breakpoint-ruler {
  display: flex;
  align-items: center;
  gap: 6px;
  flex: 1;
  max-width: 220px;
}
.bp-segment {
  height: 3px;
  border-radius: 2px;
  flex: 1;
  transition: opacity 0.2s;
}
.bp-segment.mobile-seg  { background: #FF6B35; }
.bp-segment.tablet-seg  { background: #FFD166; }
.bp-segment.desktop-seg { background: #06D6A0; }
.bp-segment.inactive    { opacity: 0.18; }
    
 @keyframes spin {
    from { transform: rotate(0deg); }
    to   { transform: rotate(360deg); }
  }


  </style>
</head>
<body>

{{-- PROJECT DATA passed from Laravel --}}
<script>
  const PROJECT_SLUG  = @json($project->slug);
  const PROJECT_FILES = @json($files);          // plain array now
  const PREVIEW_BASE  = '/preview/' + PROJECT_SLUG + '/';
  const SAVE_URL      = '/editor/' + PROJECT_SLUG + '/save';
  const ADD_FILE_URL  = '/editor/' + PROJECT_SLUG + '/add-file';
  const DEL_FILE_URL  = '/editor/' + PROJECT_SLUG + '/files/';
  const RENAME_URL    = '/editor/' + PROJECT_SLUG + '/rename';
  const CSRF          = document.querySelector('meta[name="csrf-token"]').content;
</script>

{{-- TOP BAR --}}
<div class="topbar">
  <div style="display:flex;align-items:center;gap:16px">
  <a href="{{ route('projects') }}"
     style="color:#3A5A78;font-size:12px;text-decoration:none;
            display:flex;align-items:center;gap:5px;
            transition:color 0.2s"
     onmouseover="this.style.color='#00B4D8'"
     onmouseout="this.style.color='#3A5A78'">
    ← Projects
  </a>
  <div class="logo">UIL-INSPIRE <span>|</span> Editor</div>
</div>

  <div class="project-name-wrap">
    <input type="text" id="projectName"
           value="{{ $project->name }}"
           title="Click to rename project">
    <span class="save-status" id="saveStatus">All saved</span>
  </div>

  <div class="toolbar">
    <label class="auto-run-label">
      <input type="checkbox" id="autoRun" checked> Auto-run
    </label>
    <button class="btn btn-ghost" onclick="openNewFileModal()">+ New File</button>
    <button class="btn btn-run" onclick="runPreview()">
      <svg viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
      Run
    </button>
  </div>
</div>

{{-- WORKSPACE --}}
<div class="workspace" id="workspace">

  {{-- LEFT PANEL --}}
  <div class="left-panel" id="leftPanel">

    {{-- FILE TABS --}}
    <div class="file-tabs-bar" id="fileTabsBar">
      {{-- Tabs are injected by JS --}}
      <button class="btn-new-file" onclick="openNewFileModal()">＋ New File</button>
    </div>

    {{-- EDITORS --}}
    <div class="editor-area" id="editorArea">
      {{-- CodeMirror instances are created by JS --}}
    </div>

    {{-- STATUS BAR --}}
    <div class="status-bar">
      <span id="cursorPos">Ln 1, Col 1</span>
      <span id="activeLang" style="color:#FF6B35">HTML</span>
    </div>

  </div>

  {{-- RESIZE HANDLE --}}
  <div class="resize-handle" id="resizeHandle"></div>

 {{-- OUTPUT --}}
<div class="output-panel">

  {{-- OUTPUT TOP BAR --}}
  <div class="output-bar">
    <span style="color:#90A4B8;font-weight:600;font-size:13px">⬛ Output</span>
    <div class="right" style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">

      {{-- VIEWPORT BUTTONS --}}
      <div class="viewport-btns">
        <button class="vp-btn" id="vp-mobile"
                onclick="setViewport('mobile',this)" title="Mobile — 375px">
          <svg viewBox="0 0 24 24"><path d="M17 1H7a2 2 0 00-2 2v18a2 2 0 002 2h10a2 2 0 002-2V3a2 2 0 00-2-2zm-5 20a1 1 0 110-2 1 1 0 010 2zm5-4H7V4h10v13z"/></svg>
          Mobile
        </button>
        <button class="vp-btn" id="vp-tablet"
                onclick="setViewport('tablet',this)" title="Tablet — 768px">
          <svg viewBox="0 0 24 24"><path d="M19 1H5a2 2 0 00-2 2v18a2 2 0 002 2h14a2 2 0 002-2V3a2 2 0 00-2-2zm-7 20a1 1 0 110-2 1 1 0 010 2zm7-4H5V4h14v13z"/></svg>
          Tablet
        </button>
        <button class="vp-btn active" id="vp-desktop"
                onclick="setViewport('desktop',this)" title="Desktop — Full width">
          <svg viewBox="0 0 24 24"><path d="M21 2H3a2 2 0 00-2 2v12a2 2 0 002 2h7v2H8v2h8v-2h-2v-2h7a2 2 0 002-2V4a2 2 0 00-2-2zm0 14H3V4h18v12z"/></svg>
          Desktop
        </button>
      </div>

      <div style="width:1px;height:20px;background:#1A3A55;flex-shrink:0"></div>

      {{-- DOWNLOAD BUTTON --}}
      <button class="btn-download" id="btnDownload" onclick="downloadProject()">
        <svg viewBox="0 0 24 24"><path d="M19 9h-4V3H9v6H5l7 7 7-7zm-8 2V5h2v6h1.17L12 13.17 9.83 11H11zm-6 8h14v2H5z"/></svg>
        Download ZIP
      </button>

      <div style="width:1px;height:20px;background:#1A3A55;flex-shrink:0"></div>

      {{-- RELOAD + STATUS --}}
      <span class="output-info ok" id="outputInfo">Ready</span>
      <button class="btn btn-ghost"
              style="padding:4px 10px;font-size:12px"
              onclick="refreshPreview()" title="Reload preview">⟳</button>
    </div>
  </div>

  {{-- OUTPUT BODY (viewport container) --}}
  <div class="output-body desktop" id="outputBody">
    <div class="iframe-wrapper" id="iframeWrapper">
      <iframe id="output-frame"
        src="{{ route('editor.preview', ['slug' => $project->slug, 'filename' => 'index.html']) }}"
        sandbox="allow-scripts allow-same-origin allow-forms">
      </iframe>
    </div>
  </div>

  {{-- SIZE INDICATOR BAR --}}
  <div class="size-bar" id="sizeBar">
    <span class="size-px" id="sizePx">— px</span>
    <span class="size-divider">|</span>
    <span class="size-label desktop" id="sizeLabel">Desktop</span>
    <span class="size-divider">|</span>
    <div class="breakpoint-ruler">
      <div class="bp-segment mobile-seg"  id="seg-mobile"></div>
      <div class="bp-segment tablet-seg"  id="seg-tablet"></div>
      <div class="bp-segment desktop-seg" id="seg-desktop"></div>
    </div>
  </div>

</div>

</div>

{{-- NEW FILE MODAL --}}
<div class="modal-overlay" id="modalOverlay">
  <div class="modal">
    <h3>Add a new file</h3>
    <div>
      <input type="text" id="newFilename"
             placeholder="e.g. about.html, gallery.html, extra.css"
             autocomplete="off">
    </div>
    <div>
      <select id="newFileType">
        <option value="html">HTML (.html)</option>
        <option value="css">CSS (.css)</option>
        <option value="js">JavaScript (.js)</option>
      </select>
    </div>
    <div class="modal-error" id="modalError"></div>
    <div class="modal-actions">
      <button class="btn btn-ghost" onclick="closeModal()">Cancel</button>
      <button class="btn btn-run" onclick="createNewFile()">Create File</button>
    </div>
  </div>
</div>


<script>
// ══════════════════════════════════════════════════════════════════
//  STATE
// ══════════════════════════════════════════════════════════════════
const editors    = {};
const fileData   = {};
let   activeFile = null;

const TYPE_COLOR = { html: '#FF6B35', css: '#00B4D8', js: '#FFD166' };
const TYPE_LABEL = { html: 'HTML', css: 'CSS', js: 'JavaScript' };
const MODE_MAP   = { html: 'htmlmixed', css: 'css', js: 'javascript' };

// ══════════════════════════════════════════════════════════════════
//  BOOT — iterate the plain array
// ══════════════════════════════════════════════════════════════════
function boot() {
  if (!PROJECT_FILES || PROJECT_FILES.length === 0) {
    console.warn('No files found for this project.');
    return;
  }

  // Build ALL tabs and editors while they are still VISIBLE
  // (position them off-screen temporarily so CodeMirror measures correctly)
  PROJECT_FILES.forEach(f => {
    fileData[f.filename] = { type: f.type, content: f.content };
    buildTab(f.filename, f.type);
    buildEditor(f.filename, f.type, f.content);
  });

  // Now hide all except the first / index.html
  const first = PROJECT_FILES.find(f => f.filename === 'index.html')?.filename
             ?? PROJECT_FILES[0]?.filename;

  if (first) {
    switchFile(first);
  }
}

// ══════════════════════════════════════════════════════════════════
//  BUILD TAB BUTTON
// ══════════════════════════════════════════════════════════════════
function buildTab(filename, type) {
  const bar    = document.getElementById('fileTabsBar');
  const addBtn = bar.querySelector('.btn-new-file');

  const btn = document.createElement('button');
  btn.className      = 'file-tab';
  btn.id             = 'tab-' + CSS.escape(filename);
  btn.dataset.file   = filename;
  btn.dataset.type   = type;
  btn.title          = filename;
  btn.onclick        = () => switchFile(filename);

  const dot = document.createElement('span');
  dot.className        = 'type-dot';
  dot.style.background = TYPE_COLOR[type] || '#90A4B8';

  const label = document.createElement('span');
  label.textContent = filename;

  btn.appendChild(dot);
  btn.appendChild(label);

  // Delete button — not on index.html
  if (filename !== 'index.html') {
    const close = document.createElement('span');
    close.className   = 'tab-close';
    close.textContent = '×';
    close.title       = 'Delete ' + filename;
    close.onclick = e => { e.stopPropagation(); deleteFile(filename); };
    btn.appendChild(close);
  }

  bar.insertBefore(btn, addBtn);
}

// ══════════════════════════════════════════════════════════════════
//  BUILD CODEMIRROR — created VISIBLE, hidden after switchFile
// ══════════════════════════════════════════════════════════════════
function buildEditor(filename, type, content) {
  const area = document.getElementById('editorArea');

  const wrap = document.createElement('div');
  wrap.className = 'cm-instance';
  wrap.id        = 'cm-' + CSS.escape(filename);
  // Temporarily visible so CodeMirror can measure itself
  wrap.style.display = 'flex';
  area.appendChild(wrap);

  const cm = CodeMirror(wrap, {
    value:             content,
    mode:              MODE_MAP[type] || 'htmlmixed',
    theme:             'dracula',
    lineNumbers:       true,
    autoCloseTags:     true,
    autoCloseBrackets: true,
    tabSize:           2,
    indentWithTabs:    false,
    lineWrapping:      false,
    extraKeys: {
      'Ctrl-Enter': runPreview,
      'Ctrl-S':     () => saveFile(activeFile),
      'Tab':        cm => cm.execCommand('insertSoftTab'),
    },
  });

  cm.getWrapperElement().style.height = '100%';

  // Cursor position in status bar
  cm.on('cursorActivity', () => {
    if (activeFile === filename) {
      const c = cm.getCursor();
      document.getElementById('cursorPos').textContent =
        `Ln ${c.line + 1}, Col ${c.ch + 1}`;
    }
  });

  // Auto-save + auto-run on change
  cm.on('change', () => {
    fileData[filename].content = cm.getValue();
    scheduleSave(filename);
    if (document.getElementById('autoRun').checked) {
      debounceRun();
    }
  });

  // Refresh once it's in the DOM
  requestAnimationFrame(() => cm.refresh());

  editors[filename] = cm;
}

// ══════════════════════════════════════════════════════════════════
//  SWITCH ACTIVE FILE
// ══════════════════════════════════════════════════════════════════
function switchFile(filename) {
  if (!editors[filename]) return;

  // Deactivate everything
  document.querySelectorAll('.cm-instance').forEach(el => {
    el.classList.remove('active');
    el.style.display = 'none';
  });
  document.querySelectorAll('.file-tab').forEach(el => {
    el.classList.remove('active');
  });

  // Activate the chosen file
  const wrap = document.getElementById('cm-' + CSS.escape(filename));
  if (wrap) {
    wrap.style.display = 'flex';
    wrap.classList.add('active');
  }

  const tab = document.getElementById('tab-' + CSS.escape(filename));
  if (tab) tab.classList.add('active');

  activeFile = filename;

  // Update status bar
  const type = fileData[filename]?.type || 'html';
  document.getElementById('activeLang').textContent = TYPE_LABEL[type] || filename;
  document.getElementById('activeLang').style.color = TYPE_COLOR[type] || '#90A4B8';

  const cur = editors[filename].getCursor();
  document.getElementById('cursorPos').textContent =
    `Ln ${cur.line + 1}, Col ${cur.ch + 1}`;

  // Refresh AFTER the element is visible
  setTimeout(() => {
    editors[filename].refresh();
    editors[filename].focus();
  }, 20);
}

// ══════════════════════════════════════════════════════════════════
//  SAVE FILE
// ══════════════════════════════════════════════════════════════════
const saveTimers = {};

function scheduleSave(filename) {
  setStatus('saving', '● Saving…');
  clearTimeout(saveTimers[filename]);
  saveTimers[filename] = setTimeout(() => saveFile(filename), 800);
}

async function saveFile(filename) {
  if (!editors[filename]) return;
  const data = fileData[filename];
  try {
    const res = await fetch(SAVE_URL, {
      method:  'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
      body: JSON.stringify({
        filename: filename,
        type:     data.type,
        content:  editors[filename].getValue(),
      }),
    });
    const json = await res.json();
    setStatus(json.ok ? 'saved' : 'error',
              json.ok ? `✓ Saved ${json.saved_at}` : '✗ Save failed');
  } catch {
    setStatus('error', '✗ Network error');
  }
}

function setStatus(cls, text) {
  const el = document.getElementById('saveStatus');
  el.className   = 'save-status ' + cls;
  el.textContent = text;
}

// ══════════════════════════════════════════════════════════════════
//  PROJECT RENAME
// ══════════════════════════════════════════════════════════════════
document.getElementById('projectName').addEventListener('blur', async function () {
  await fetch(RENAME_URL, {
    method:  'PATCH',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
    body: JSON.stringify({ name: this.value }),
  });
  document.title = this.value + ' — INSPIRE Editor';
});

// ══════════════════════════════════════════════════════════════════
//  RUN / REFRESH PREVIEW
// ══════════════════════════════════════════════════════════════════
let runTimer;
function debounceRun() {
  clearTimeout(runTimer);
  runTimer = setTimeout(runPreview, 900);
}

async function runPreview() {
  // Save the active file immediately, schedule the rest
  await saveFile(activeFile);
  Object.keys(editors).forEach(fn => {
    if (fn !== activeFile) scheduleSave(fn);
  });
  refreshPreview();
}

function refreshPreview() {
  const frame = document.getElementById('output-frame');
  frame.src   = PREVIEW_BASE + 'index.html?t=' + Date.now();
  const info  = document.getElementById('outputInfo');
  info.className   = 'output-info ok';
  info.textContent = '✓ ' + new Date().toLocaleTimeString();
}

// ══════════════════════════════════════════════════════════════════
//  NEW FILE MODAL
// ══════════════════════════════════════════════════════════════════
function openNewFileModal() {
  document.getElementById('newFilename').value    = '';
  document.getElementById('newFileType').value    = 'html';
  document.getElementById('modalError').textContent = '';
  document.getElementById('modalOverlay').classList.add('open');
  setTimeout(() => document.getElementById('newFilename').focus(), 50);
}
function closeModal() {
  document.getElementById('modalOverlay').classList.remove('open');
}

document.getElementById('newFilename').addEventListener('input', function () {
  const v = this.value.toLowerCase();
  const sel = document.getElementById('newFileType');
  if (v.endsWith('.css'))       sel.value = 'css';
  else if (v.endsWith('.js'))   sel.value = 'js';
  else if (v.endsWith('.html')) sel.value = 'html';
});

document.getElementById('modalOverlay').addEventListener('click', function (e) {
  if (e.target === this) closeModal();
});

// Enter key submits the modal
document.getElementById('newFilename').addEventListener('keydown', e => {
  if (e.key === 'Enter') createNewFile();
});

async function createNewFile() {
  const filename = document.getElementById('newFilename').value.trim();
  const type     = document.getElementById('newFileType').value;
  const errEl    = document.getElementById('modalError');

  if (!filename)         { errEl.textContent = 'Please enter a filename.'; return; }
  if (editors[filename]) { errEl.textContent = 'This file is already open.'; return; }

  try {
    const res  = await fetch(ADD_FILE_URL, {
      method:  'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
      body: JSON.stringify({ filename, type }),
    });
    const json = await res.json();

    if (!res.ok) { errEl.textContent = json.error || 'Could not create file.'; return; }

    const f = json.file;
    fileData[f.filename] = { type: f.type, content: f.content };
    buildTab(f.filename, f.type);
    buildEditor(f.filename, f.type, f.content);
    closeModal();
    switchFile(f.filename);

  } catch {
    errEl.textContent = 'Network error. Please try again.';
  }
}

// ══════════════════════════════════════════════════════════════════
//  DELETE FILE
// ══════════════════════════════════════════════════════════════════
async function deleteFile(filename) {
  if (!confirm(`Delete "${filename}"?\nThis cannot be undone.`)) return;

  try {
    const res  = await fetch(DEL_FILE_URL + encodeURIComponent(filename), {
      method:  'DELETE',
      headers: { 'X-CSRF-TOKEN': CSRF },
    });
    const json = await res.json();
    if (!res.ok) { alert(json.error || 'Could not delete file.'); return; }

    document.getElementById('tab-' + CSS.escape(filename))?.remove();
    document.getElementById('cm-'  + CSS.escape(filename))?.remove();
    delete editors[filename];
    delete fileData[filename];

    if (activeFile === filename) {
      const next = Object.keys(editors)[0];
      if (next) switchFile(next);
    }
  } catch {
    alert('Network error. Please try again.');
  }
}


// ══════════════════════════════════════════════════════════════════
//  VIEWPORT SIZE SIMULATION
// ══════════════════════════════════════════════════════════════════
const VIEWPORT_WIDTHS = { mobile: 375, tablet: 768, desktop: null };
let   currentViewport = 'desktop';

function setViewport(size, btnEl) {
  currentViewport = size;

  // Update button active states
  document.querySelectorAll('.vp-btn').forEach(b => b.classList.remove('active'));
  btnEl.classList.add('active');

  // Switch the output body class (CSS handles the iframe width)
  const body = document.getElementById('outputBody');
  body.className = 'output-body ' + size;

  // Update size bar immediately
  updateSizeBar();

  // Reload preview so media queries fire correctly at the new width
  setTimeout(refreshPreview, 280);
}

// ══════════════════════════════════════════════════════════════════
//  SIZE INDICATOR BAR
// ══════════════════════════════════════════════════════════════════
function getBreakpointLabel(px) {
  if (px < 480)  return { label: 'Mobile (XS)',  cls: 'mobile'  };
  if (px < 768)  return { label: 'Mobile',       cls: 'mobile'  };
  if (px < 1024) return { label: 'Tablet',       cls: 'tablet'  };
  if (px < 1280) return { label: 'Small Desktop',cls: 'desktop' };
  return              { label: 'Desktop',        cls: 'desktop' };
}

function updateSizeBar() {
  const wrapper = document.getElementById('iframeWrapper');
  if (!wrapper) return;

  const px   = Math.round(wrapper.getBoundingClientRect().width);
  const info = getBreakpointLabel(px);

  document.getElementById('sizePx').textContent    = px + ' px';

  const lbl = document.getElementById('sizeLabel');
  lbl.textContent = info.label;
  lbl.className   = 'size-label ' + info.cls;

  // Ruler segments — highlight up to the current breakpoint
  document.getElementById('seg-mobile').className  =
    'bp-segment mobile-seg'  + (px < 768  ? '' : ' inactive');
  document.getElementById('seg-tablet').className  =
    'bp-segment tablet-seg'  + (px >= 768 && px < 1024 ? '' : ' inactive');
  document.getElementById('seg-desktop').className =
    'bp-segment desktop-seg' + (px >= 1024 ? '' : ' inactive');
}

// Watch the iframe wrapper size with ResizeObserver
(function watchSize() {
  const wrapper = document.getElementById('iframeWrapper');
  if (!wrapper) return;
  const ro = new ResizeObserver(() => updateSizeBar());
  ro.observe(wrapper);
})();

// Also update size bar after drag-resize (patch into existing mouseup)
const _origMouseUp = document.onmouseup;
document.addEventListener('mouseup', () => {
  setTimeout(updateSizeBar, 50);
});

// ══════════════════════════════════════════════════════════════════
//  DOWNLOAD PROJECT AS ZIP
// ══════════════════════════════════════════════════════════════════
async function downloadProject() {
  const btn = document.getElementById('btnDownload');
  btn.classList.add('downloading');
  btn.innerHTML = `
    <svg viewBox="0 0 24 24" style="animation:spin 1s linear infinite">
      <path d="M12 4V1L8 5l4 4V6a6 6 0 110 12 6 6 0 01-6-6H4a8 8 0 108-8z"/>
    </svg>
    Zipping…`;

  try {
    const zip  = new JSZip();
    const name = document.getElementById('projectName').value.trim()
               || PROJECT_SLUG;

    // Add every open file into the zip
    for (const [filename, data] of Object.entries(fileData)) {
      const content = editors[filename]
        ? editors[filename].getValue()
        : (data.content ?? '');
      zip.file(filename, content);
    }

    // Also fetch any files that exist in the DB but are NOT currently open
    // (edge case: files added by another browser tab)
    try {
      const res  = await fetch('/editor/' + PROJECT_SLUG + '/files-list', {
        headers: { 'X-CSRF-TOKEN': CSRF },
      });
      if (res.ok) {
        const list = await res.json();
        for (const f of list) {
          if (!editors[f.filename]) {       // not already in zip
            zip.file(f.filename, f.content ?? '');
          }
        }
      }
    } catch (_) { /* non-critical */ }

    const blob = await zip.generateAsync({ type: 'blob' });
    const url  = URL.createObjectURL(blob);
    const a    = document.createElement('a');
    a.href     = url;
    a.download = name.replace(/[^a-z0-9_\-]/gi, '_') + '.zip';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);

  } catch (err) {
    alert('Download failed: ' + err.message);
  } finally {
    btn.classList.remove('downloading');
    btn.innerHTML = `
      <svg viewBox="0 0 24 24"><path d="M19 9h-4V3H9v6H5l7 7 7-7zm-8 2V5h2v6h1.17L12 13.17 9.83 11H11zm-6 8h14v2H5z"/></svg>
      Download ZIP`;
  }
}

// ══════════════════════════════════════════════════════════════════
//  DRAG-TO-RESIZE
// ══════════════════════════════════════════════════════════════════
const handle    = document.getElementById('resizeHandle');
const workspace = document.getElementById('workspace');
const leftPanel = document.getElementById('leftPanel');
let   dragging  = false;

handle.addEventListener('mousedown', () => {
  dragging = true;
  document.body.style.cursor     = 'col-resize';
  document.body.style.userSelect = 'none';
});
document.addEventListener('mousemove', e => {
  if (!dragging) return;
  const r = workspace.getBoundingClientRect();
  const p = Math.min(Math.max((e.clientX - r.left) / r.width * 100, 20), 80);
  leftPanel.style.width = p + '%';
});
document.addEventListener('mouseup', () => {
  if (!dragging) return;
  dragging = false;
  document.body.style.cursor     = '';
  document.body.style.userSelect = '';
  if (activeFile) editors[activeFile]?.refresh();
});

// Global keyboard shortcuts
document.addEventListener('keydown', e => {
  if (e.ctrlKey && e.key === 'Enter') { e.preventDefault(); runPreview(); }
  if (e.key === 'Escape') closeModal();
});

// ══════════════════════════════════════════════════════════════════
//  INIT
// ══════════════════════════════════════════════════════════════════
boot();
</script>

</body>
</html>