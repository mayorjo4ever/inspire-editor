<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EditorController;

// ── Projects dashboard ─────────────────────────────────────────────
Route::get('/',              [EditorController::class, 'projects'])->name('projects');
Route::post('/projects',     [EditorController::class, 'create']  )->name('projects.create');
Route::delete('/projects/{slug}', [EditorController::class, 'destroy'])->name('projects.destroy');

// ── Editor ─────────────────────────────────────────────────────────
Route::get('/editor/{slug}', [EditorController::class, 'show'])->name('editor.show');

// ── File management ────────────────────────────────────────────────
Route::post  ('/editor/{slug}/save',            [EditorController::class, 'saveFile']  )->name('editor.save');
Route::post  ('/editor/{slug}/add-file',        [EditorController::class, 'addFile']   )->name('editor.add-file');
Route::delete('/editor/{slug}/files/{filename}',[EditorController::class, 'deleteFile'])->name('editor.delete-file')
      ->where('filename', '.*');
Route::patch ('/editor/{slug}/rename',          [EditorController::class, 'rename']    )->name('editor.rename');
Route::get   ('/editor/{slug}/files-list',      [EditorController::class, 'filesList'] )->name('editor.files-list');

// ── Preview server ─────────────────────────────────────────────────
Route::get('/preview/{slug}/{filename?}', [EditorController::class, 'preview'])
     ->name('editor.preview')
     ->where('filename', '.*');