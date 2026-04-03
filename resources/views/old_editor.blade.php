<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>INSPIRE Code Editor</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/theme/dracula.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/xml/xml.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/css/css.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/javascript/javascript.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/htmlmixed/htmlmixed.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/edit/closetag.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/edit/closebrackets.min.js"></script>

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
      padding: 0 20px;
      height: 52px;
      background: #050E1A;
      border-bottom: 1px solid #1A3A55;
      flex-shrink: 0;
    }
    .topbar .logo {
      font-size: 16px;
      font-weight: 700;
      color: #00B4D8;
      letter-spacing: 0.5px;
    }
    .topbar .logo span { color: #7B2FBE; }

    .toolbar { display: flex; gap: 10px; align-items: center; }

    .btn-run {
      background: linear-gradient(135deg, #06D6A0, #0077B6);
      color: white;
      border: none;
      padding: 8px 22px;
      border-radius: 6px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 7px;
      transition: opacity 0.2s;
    }
    .btn-run:hover { opacity: 0.88; }
    .btn-run svg { width: 14px; height: 14px; fill: white; }

    .btn-clear {
      background: rgba(255,255,255,0.08);
      color: #90A4B8;
      border: 1px solid rgba(255,255,255,0.12);
      padding: 8px 16px;
      border-radius: 6px;
      font-size: 13px;
      cursor: pointer;
      transition: background 0.2s;
    }
    .btn-clear:hover { background: rgba(255,255,255,0.14); }

    .auto-run-label {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 13px;
      color: #90A4B8;
      cursor: pointer;
    }
    .auto-run-label input { cursor: pointer; accent-color: #06D6A0; }

    /* ── WORKSPACE ── */
    .workspace {
      display: flex;
      flex: 1;
      overflow: hidden;
    }

    /* ── EDITOR PANEL ── */
    .editors-panel {
      display: flex;
      flex-direction: column;
      width: 50%;
      border-right: 2px solid #1A3A55;
      overflow: hidden;
    }

    /* ── TAB BAR ── */
    .tab-bar {
      display: flex;
      background: #050E1A;
      border-bottom: 2px solid #1A3A55;
      flex-shrink: 0;
    }

    .tab-btn {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 12px 22px;
      font-size: 13px;
      font-weight: 600;
      letter-spacing: 0.5px;
      cursor: pointer;
      color: #5C7A93;
      border: none;
      background: transparent;
      border-bottom: 3px solid transparent;
      margin-bottom: -2px;
      transition: color 0.2s, border-color 0.2s, background 0.2s;
      position: relative;
    }
    .tab-btn:hover {
      color: #fff;
      background: rgba(255,255,255,0.05);
    }
    .tab-btn.active {
      color: #fff;
      background: #0D1F30;
    }
    .tab-btn.active.html-tab  { border-bottom-color: #FF6B35; }
    .tab-btn.active.css-tab   { border-bottom-color: #00B4D8; }
    .tab-btn.active.js-tab    { border-bottom-color: #FFD166; }

    .tab-dot {
      width: 9px;
      height: 9px;
      border-radius: 50%;
      flex-shrink: 0;
    }

    /* Line count badge */
    .tab-lines {
      margin-left: 4px;
      font-size: 10px;
      font-weight: 400;
      color: #3A5A78;
      letter-spacing: 0;
    }

    /* ── EDITOR PANES ── */
    .editor-pane {
      display: none;
      flex-direction: column;
      flex: 1;
      overflow: hidden;
    }
    .editor-pane.active {
      display: flex;
    }

    .editor-pane .CodeMirror {
      flex: 1;
      height: 100%;
      font-size: 15px;
      font-family: 'Consolas', 'Fira Code', monospace;
      line-height: 1.75;
      background: #0D1F30;
    }
    .editor-pane .CodeMirror-scroll {
      overflow: auto !important;
    }

    /* ── EDITOR FOOTER (line/col info) ── */
    .editor-footer {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 4px 14px;
      background: #050E1A;
      border-top: 1px solid #1A3A55;
      font-size: 11px;
      color: #3A5A78;
      flex-shrink: 0;
    }
    .editor-footer .cursor-pos { font-family: 'Consolas', monospace; }
    .editor-footer .lang-badge {
      font-size: 10px;
      font-weight: 600;
      letter-spacing: 1px;
      text-transform: uppercase;
    }

    /* ── RESIZE HANDLE ── */
    .resize-handle {
      width: 4px;
      background: #1A3A55;
      cursor: col-resize;
      transition: background 0.2s;
      flex-shrink: 0;
    }
    .resize-handle:hover { background: #00B4D8; }

    /* ── OUTPUT PANEL ── */
    .output-panel {
      display: flex;
      flex-direction: column;
      flex: 1;
      min-width: 0;
    }

    .output-tab {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 12px 16px;
      background: #050E1A;
      font-size: 13px;
      font-weight: 600;
      letter-spacing: 0.5px;
      color: #90A4B8;
      border-bottom: 2px solid #1A3A55;
      flex-shrink: 0;
    }
    .output-status {
      font-size: 11px;
      font-weight: 400;
      letter-spacing: 0;
    }
    .output-status.ok  { color: #06D6A0; }
    .output-status.err { color: #FF6B35; }

    #output-frame {
      flex: 1;
      border: none;
      background: white;
      width: 100%;
    }
  </style>
</head>
<body>

  {{-- TOP BAR --}}
  <div class="topbar">
    <div class="logo">UNILORIN INSPIRE <span>|</span> Code Editor</div>
    <div class="toolbar">
      <label class="auto-run-label">
        <input type="checkbox" id="autoRun" checked> Auto-run
      </label>
      <button class="btn-clear" onclick="clearActive()">Clear</button>
      <button class="btn-run" onclick="runCode()">
        <svg viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
        Run
      </button>
    </div>
  </div>

  {{-- WORKSPACE --}}
  <div class="workspace" id="workspace">

    {{-- EDITOR PANEL --}}
    <div class="editors-panel" id="editorsPanel">

      {{-- TAB BAR --}}
      <div class="tab-bar">
        <button class="tab-btn html-tab active" onclick="switchTab('html', this)">
          <div class="tab-dot" style="background:#FF6B35"></div>
          HTML
          <span class="tab-lines" id="lines-html"></span>
        </button>
        <button class="tab-btn css-tab" onclick="switchTab('css', this)">
          <div class="tab-dot" style="background:#00B4D8"></div>
          CSS
          <span class="tab-lines" id="lines-css"></span>
        </button>
        <button class="tab-btn js-tab" onclick="switchTab('js', this)">
          <div class="tab-dot" style="background:#FFD166"></div>
          JavaScript
          <span class="tab-lines" id="lines-js"></span>
        </button>
      </div>

      {{-- HTML PANE --}}
      <div class="editor-pane active" id="pane-html">
        <textarea id="html-code"><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>   
  <nav>
    <a href="index.html" class="brand"> SOJI CAR DEALER</a>
    <div class="nav-links">
      <a href="index.html" class="active"> Home </a>  
      <a href="about-us.html" class=""> About Us </a>     
      <a href="contact-us.html" class=""> Contact Us </a>        
   </div>    
  </nav>
  
  <header>
    <h1> WELCOME TO SOJI CAR DEALER</h1>
    <p>We provides Strong And Affordable Vehicles </p>
  </header>
  
  <main>
    <h1>
      Vehicles 
    </h1>
   <div class="container flex-row">
    <div class="item">1</div>
    <div class="item">2</div>
    <div class="item">3</div>
</div>

<div class="container grid-basic">
  <div class="item">1</div>
  <div class="item">2</div>
  <div class="item">3</div>
</div>
    
  </main>

</body>
</html></textarea>
      </div>

      {{-- CSS PANE --}}
      <div class="editor-pane" id="pane-css">
        <textarea id="css-code">body {
  font-family: 'Segoe UI', sans-serif;
  background: #fff;
  color: #000;  
}
nav {
  display:flex;
  flex-direction: row;
  padding:0px 32px;
  justify-content:space-between;
  background:linear-gradient(135deg, green,blue,pink); 
  height:auto; border-bottom:1px solid #ccc; 
  }

nav .brand{
  color:#fff; font-weight:bold; text-decoration:none;
  font-size:24px; padding: 20px; 
}

nav .nav-links {
  padding:20px; 
}

nav .nav-links a{
  color:#fff; padding:20px; font-size:20px; text-decoration:none;
}

nav .nav-links a.active{
  color:#fff;  background:rgba(120,90,20,0.5);
}

header{
  display:flex; flex-direction:column; 
  height:30vh; background:linear-gradient(240deg, pink,blue,green);
  align-items:center; 
}
header h1, header p {
  color:#fff; 
}

.container {
  margin: 20px;
}

.item {
  background: #4f8ef7;
  color: #fff;
  padding: 20px;
  text-align: center;
  border-radius: 6px;
}


/* 1. FLEX ROW (Navbar style) */
.flex-row {
  display: flex;
  justify-content: space-between;
  gap: 10px;
}

/* 2. FLEX COLUMN (Vertical layout) */
.flex-column {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 10px;
}

/* 3. FLEX WRAP (Card layout) */
.flex-wrap {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

.flex-wrap .item {
  flex: 1 1 150px;
}

/* 4. FLEX CENTER (Perfect centering) */
.flex-center {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 200px;
  background: #f5f5f5;
}

/* ===============================
   GRID EXAMPLES
================================ */

/* 5. BASIC GRID (3 columns) */
.grid-basic {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 10px;
}

/* 6. GRID SPANNING */
.grid-span {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 10px;
}

.grid-span .item1 {
  grid-column: span 2;
}

/* 7. DASHBOARD GRID */
.grid-dashboard {
  display: grid;
  grid-template-areas:
    "header header"
    "sidebar content";
  grid-template-columns: 200px 1fr;
  gap: 10px;
}

.header {
  grid-area: header;
  background: #e74c3c;
  color: white;
  padding: 20px;
}

.sidebar {
  grid-area: sidebar;
  background: #2c3e50;
  color: white;
  padding: 20px;
}

.content {
  grid-area: content;
  background: #27ae60;
  color: white;
  padding: 20px;
}

/* 8. COMPLEX / MAGAZINE GRID */
.grid-complex {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  grid-auto-rows: 100px;
  gap: 10px;
}

.grid-complex .item1 {
  grid-column: span 2;
}

.grid-complex .item2 {
  grid-row: span 2;
}

/* ===============================
   OPTIONAL COLORS FOR VARIETY
================================ */
.item:nth-child(2) { background: #e67e22; }
.item:nth-child(3) { background: #27ae60; }
.item:nth-child(4) { background: #8e44ad; }
.item:nth-child(5) { background: #16a085; }
.item:nth-child(6) { background: #c0392b; }








</textarea>
      </div>

      {{-- JS PANE --}}
      <div class="editor-pane" id="pane-js">
        <textarea id="js-code">function greet() {
  const name = prompt('What is your name?');
  if (name) {
    document.querySelector('h1').textContent =
      `Hello, ${name}! 🎉`;
  }
}</textarea>
      </div>

      {{-- EDITOR FOOTER --}}
      <div class="editor-footer">
        <span class="cursor-pos" id="cursorPos">Ln 1, Col 1</span>
        <span class="lang-badge" id="langBadge" style="color:#FF6B35">HTML</span>
      </div>

    </div>{{-- end editors-panel --}}

    {{-- DRAG HANDLE --}}
    <div class="resize-handle" id="resizeHandle"></div>

    {{-- OUTPUT --}}
    <div class="output-panel">
      <div class="output-tab">
        <span>⬛ Output</span>
        <span class="output-status ok" id="outputStatus">Ready</span>
      </div>
      <iframe id="output-frame" sandbox="allow-scripts"></iframe>
    </div>

  </div>{{-- end workspace --}}


  <script>
    // ── CODEMIRROR INSTANCES ──────────────────────────────────────
    const editorConfigs = [
      { id: 'html-code', key: 'html', mode: 'htmlmixed'  },
      { id: 'css-code',  key: 'css',  mode: 'css'        },
      { id: 'js-code',   key: 'js',   mode: 'javascript' },
    ];

    const editors = {};

    editorConfigs.forEach(cfg => {
      const cm = CodeMirror.fromTextArea(
        document.getElementById(cfg.id),
        {
          mode:              cfg.mode,
          theme:             'dracula',
          lineNumbers:       true,
          autoCloseTags:     true,
          autoCloseBrackets: true,
          tabSize:           2,
          indentWithTabs:    false,
          lineWrapping:      false,
          extraKeys: {
            'Ctrl-Enter': runCode,   // Ctrl+Enter to run
            'Tab': cm => cm.execCommand('insertSoftTab'),
          },
        }
      );

      // Fill the pane height
      cm.getWrapperElement().style.height = '100%';

      // Update line count badge on tab
      const updateLines = () => {
        const count = cm.lineCount();
        document.getElementById('lines-' + cfg.key).textContent =
          count + ' ln';
      };
      updateLines();

      // Update cursor position in footer
      cm.on('cursorActivity', () => {
        if (activeTab === cfg.key) {
          const cur = cm.getCursor();
          document.getElementById('cursorPos').textContent =
            `Ln ${cur.line + 1}, Col ${cur.ch + 1}`;
        }
      });

      // Auto-run + line count on change
      cm.on('change', () => {
        updateLines();
        if (document.getElementById('autoRun').checked) {
          debounceRun();
        }
      });

      editors[cfg.key] = cm;
    });

    // ── TAB SWITCHING ─────────────────────────────────────────────
    let activeTab = 'html';

    const tabColors = { html: '#FF6B35', css: '#00B4D8', js: '#FFD166' };
    const tabLabels = { html: 'HTML',    css: 'CSS',     js: 'JavaScript' };

    function switchTab(key, btnEl) {
      // Deactivate all tabs and panes
      document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
      document.querySelectorAll('.editor-pane').forEach(p => p.classList.remove('active'));

      // Activate chosen tab and pane
      btnEl.classList.add('active');
      document.getElementById('pane-' + key).classList.add('active');

      activeTab = key;

      // Update footer
      const cur = editors[key].getCursor();
      document.getElementById('cursorPos').textContent =
        `Ln ${cur.line + 1}, Col ${cur.ch + 1}`;
      document.getElementById('langBadge').style.color = tabColors[key];
      document.getElementById('langBadge').textContent = tabLabels[key];

      // Refresh CodeMirror so it renders correctly after being hidden
      setTimeout(() => editors[key].refresh(), 10);
    }

    // ── RUN CODE ─────────────────────────────────────────────────
    let debounceTimer;

    function debounceRun() {
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(runCode, 600);
    }

    function runCode() {
      const html = editors.html.getValue();
      const css  = editors.css.getValue();
      const js   = editors.js.getValue();

      // Inject CSS into <head> and JS before </body>
      let doc = html;

      if (doc.includes('</head>')) {
        doc = doc.replace('</head>', `<style>${css}</style></head>`);
      } else {
        doc = `<style>${css}</style>` + doc;
      }

      if (doc.includes('</body>')) {
        doc = doc.replace('</body>', `<script>${js}<\/script></body>`);
      } else {
        doc = doc + `<script>${js}<\/script>`;
      }

      document.getElementById('output-frame').srcdoc = doc;

      // Status update
      const status = document.getElementById('outputStatus');
      status.className = 'output-status ok';
      status.textContent = '✓ Updated at ' + new Date().toLocaleTimeString();
    }

    // ── CLEAR ACTIVE EDITOR ──────────────────────────────────────
    function clearActive() {
      const label = tabLabels[activeTab];
      if (!confirm(`Clear the ${label} editor?`)) return;
      editors[activeTab].setValue('');
      editors[activeTab].focus();
    }

    // ── DRAG-TO-RESIZE ───────────────────────────────────────────
    const handle    = document.getElementById('resizeHandle');
    const workspace = document.getElementById('workspace');
    const leftPanel = document.getElementById('editorsPanel');
    let   dragging  = false;

    handle.addEventListener('mousedown', () => {
      dragging = true;
      document.body.style.cursor     = 'col-resize';
      document.body.style.userSelect = 'none';
    });
    document.addEventListener('mousemove', e => {
      if (!dragging) return;
      const rect    = workspace.getBoundingClientRect();
      const pct     = (e.clientX - rect.left) / rect.width * 100;
      const clamped = Math.min(Math.max(pct, 20), 80);
      leftPanel.style.width = clamped + '%';
    });
    document.addEventListener('mouseup', () => {
      dragging = false;
      document.body.style.cursor     = '';
      document.body.style.userSelect = '';
      // Refresh active editor after resize
      editors[activeTab].refresh();
    });

    // ── KEYBOARD SHORTCUT HINT ───────────────────────────────────
    // Ctrl+Enter anywhere on page runs the code
    document.addEventListener('keydown', e => {
      if (e.ctrlKey && e.key === 'Enter') runCode();
    });

    // ── INITIAL RENDER ───────────────────────────────────────────
    runCode();
  </script>

</body>
</html>