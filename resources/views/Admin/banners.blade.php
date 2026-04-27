@extends('layouts.app')

@section('content')

<div x-data="bannerManager()" class="min-h-screen" style="background:#0d0d0f; font-family:'DM Sans',sans-serif;">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&family=Playfair+Display:wght@500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --gold: #c9a84c;
            --gold-light: #e8c97a;
            --gold-dim: #7a5f28;
            --surface: #161618;
            --surface-2: #1e1e21;
            --surface-3: #252528;
            --border: rgba(255,255,255,0.07);
            --border-gold: rgba(201,168,76,0.25);
            --text: #f0ede8;
            --text-muted: #888580;
            --text-dim: #555250;
            --red: #e05555;
            --red-dim: rgba(224,85,85,0.12);
            --green: #5aad7a;
            --green-dim: rgba(90,173,122,0.12);
        }

        * { box-sizing: border-box; }

        .bm-page { padding: 2.5rem 2rem; max-width: 1100px; margin: 0 auto; }

        /* Header */
        .bm-header { display: flex; align-items: flex-end; justify-content: space-between; margin-bottom: 2rem; }
        .bm-eyebrow { font-size: 10px; letter-spacing: .16em; text-transform: uppercase; color: var(--gold); margin-bottom: .4rem; font-weight: 500; }
        .bm-title { font-family: 'Playfair Display', serif; font-size: 2rem; font-weight: 500; color: var(--text); line-height: 1.2; }
        .bm-subtitle { font-size: 13px; color: var(--text-muted); margin-top: .3rem; }

        /* Add button */
        .btn-add {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--gold); color: #0d0d0f;
            padding: 10px 20px; border-radius: 10px;
            font-size: 13px; font-weight: 500; border: none; cursor: pointer;
            transition: background .2s, transform .15s;
            letter-spacing: .02em;
        }
        .btn-add:hover { background: var(--gold-light); transform: translateY(-1px); }
        .btn-add svg { transition: transform .2s; }
        .btn-add:hover svg { transform: rotate(90deg); }

        /* Flash */
        .flash-success {
            background: rgba(90,173,122,0.1); border: 1px solid rgba(90,173,122,0.25);
            color: #7ed9a0; border-radius: 10px; padding: 12px 16px;
            font-size: 13px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 8px;
        }

        /* Stats row */
        .bm-stats { display: grid; grid-template-columns: repeat(3,1fr); gap: 12px; margin-bottom: 1.5rem; }
        .stat-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 12px; padding: 16px 18px;
        }
        .stat-card-gold { border-color: var(--border-gold); }
        .stat-lbl { font-size: 10px; letter-spacing: .12em; text-transform: uppercase; color: var(--text-muted); margin-bottom: 8px; }
        .stat-val { font-family: 'Playfair Display', serif; font-size: 26px; color: var(--text); line-height: 1; }
        .stat-val-gold { color: var(--gold); }
        .stat-sub { font-size: 11px; color: var(--text-dim); margin-top: 4px; }

        /* Table card */
        .table-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 16px; overflow: hidden;
        }
        .table-toolbar {
            display: flex; align-items: center; justify-content: space-between;
            padding: 14px 18px; border-bottom: 1px solid var(--border);
        }
        .toolbar-title { font-size: 13px; font-weight: 500; color: var(--text); }
        .toolbar-controls { display: flex; gap: 8px; }
        .search-input {
            background: var(--surface-2); border: 1px solid var(--border);
            color: var(--text); border-radius: 8px; padding: 7px 12px;
            font-size: 12px; outline: none; width: 180px; font-family: inherit;
        }
        .search-input::placeholder { color: var(--text-dim); }
        .search-input:focus { border-color: var(--border-gold); }
        .filter-select {
            background: var(--surface-2); border: 1px solid var(--border);
            color: var(--text-muted); border-radius: 8px; padding: 7px 10px;
            font-size: 12px; outline: none; font-family: inherit; cursor: pointer;
        }
        .filter-select:focus { border-color: var(--border-gold); }

        /* Table */
        table { width: 100%; border-collapse: collapse; }
        thead tr { border-bottom: 1px solid var(--border); }
        th {
            padding: 11px 18px; text-align: left;
            font-size: 10px; letter-spacing: .12em; text-transform: uppercase;
            color: var(--text-dim); font-weight: 500;
        }
        th:last-child { text-align: right; }
        td { padding: 14px 18px; vertical-align: middle; }
        tbody tr { border-bottom: 1px solid var(--border); transition: background .15s; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: var(--surface-2); }

        /* Thumbnail */
        .thumb {
            width: 76px; height: 46px; border-radius: 8px; object-fit: cover;
            border: 1px solid var(--border); background: var(--surface-3);
            flex-shrink: 0;
        }
        .banner-title { font-size: 13px; font-weight: 500; color: var(--text); }
        .banner-sub { font-size: 11px; color: var(--text-muted); margin-top: 2px; }

        /* Order badge */
        .order-badge {
            display: inline-flex; align-items: center; justify-content: center;
            width: 28px; height: 28px; border-radius: 7px;
            background: var(--surface-3); border: 1px solid var(--border);
            font-size: 12px; color: var(--text-muted); font-weight: 500;
        }

        /* Date */
        .date-range { font-size: 12px; color: var(--text-muted); line-height: 1.8; }
        .date-range span { color: var(--text-dim); font-size: 10px; margin-right: 4px; letter-spacing: .05em; text-transform: uppercase; }

        /* Badges */
        .badge { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 500; letter-spacing: .02em; }
        .badge-active { background: var(--green-dim); color: var(--green); border: 1px solid rgba(90,173,122,0.2); }
        .badge-inactive { background: var(--red-dim); color: var(--red); border: 1px solid rgba(224,85,85,0.2); }
        .badge-dot { width: 5px; height: 5px; border-radius: 50%; background: currentColor; }

        /* Action buttons */
        .btn-edit {
            background: transparent; border: 1px solid var(--border);
            color: var(--text-muted); padding: 6px 14px; border-radius: 7px;
            font-size: 12px; cursor: pointer; font-family: inherit; transition: all .15s;
        }
        .btn-edit:hover { border-color: var(--border-gold); color: var(--gold); background: rgba(201,168,76,0.06); }
        .btn-del {
            background: transparent; border: 1px solid var(--border);
            color: var(--text-dim); padding: 6px 14px; border-radius: 7px;
            font-size: 12px; cursor: pointer; font-family: inherit; transition: all .15s;
        }
        .btn-del:hover { border-color: rgba(224,85,85,0.4); color: var(--red); background: var(--red-dim); }

        /* Empty state */
        .empty-state { text-align: center; padding: 4rem 1rem; }
        .empty-icon { width: 48px; height: 48px; border-radius: 12px; background: var(--surface-3); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; }
        .empty-text { font-size: 14px; color: var(--text-muted); }
        .empty-sub { font-size: 12px; color: var(--text-dim); margin-top: 4px; }

        /* Pagination */
        .pagination { display: flex; align-items: center; justify-content: space-between; padding: 12px 18px; border-top: 1px solid var(--border); }
        .pagination-info { font-size: 12px; color: var(--text-dim); }
        .pagination-btns { display: flex; gap: 4px; }
        .page-btn { background: transparent; border: 1px solid var(--border); color: var(--text-muted); width: 30px; height: 30px; border-radius: 7px; font-size: 12px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all .15s; }
        .page-btn:hover, .page-btn.active { border-color: var(--border-gold); color: var(--gold); }

        /* ── MODALS ── */
        .modal-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,0.7);
            backdrop-filter: blur(4px); z-index: 100;
            display: flex; align-items: center; justify-content: center; padding: 1rem;
        }
        .modal {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 18px; width: 100%; max-width: 560px;
            padding: 0; overflow: hidden;
            animation: modal-in .22s cubic-bezier(.34,1.4,.64,1);
        }
        @keyframes modal-in {
            from { opacity: 0; transform: scale(.95) translateY(8px); }
            to   { opacity: 1; transform: scale(1)  translateY(0); }
        }
        .modal-top-bar { height: 3px; background: linear-gradient(90deg, var(--gold-dim), var(--gold), var(--gold-dim)); }
        .modal-inner { padding: 1.75rem 2rem 2rem; }
        .modal-head { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 1.5rem; }
        .modal-title { font-family: 'Playfair Display', serif; font-size: 20px; font-weight: 500; color: var(--text); }
        .modal-sub { font-size: 12px; color: var(--text-muted); margin-top: 3px; }
        .modal-close {
            background: var(--surface-3); border: 1px solid var(--border);
            width: 30px; height: 30px; border-radius: 8px; color: var(--text-muted);
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            font-size: 18px; line-height: 1; transition: all .15s; flex-shrink: 0;
        }
        .modal-close:hover { border-color: var(--border-gold); color: var(--gold); }

        /* Form */
        .form-row { margin-bottom: 1.1rem; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 1.1rem; }
        .form-lbl { display: block; font-size: 10px; letter-spacing: .12em; text-transform: uppercase; color: var(--text-muted); margin-bottom: 7px; font-weight: 500; }
        .form-input, .form-select {
            width: 100%; background: var(--surface-2); border: 1px solid var(--border);
            border-radius: 9px; padding: 10px 13px; font-size: 13px;
            color: var(--text); font-family: inherit; outline: none; transition: border .15s;
        }
        .form-input::placeholder { color: var(--text-dim); }
        .form-input:focus, .form-select:focus { border-color: var(--border-gold); box-shadow: 0 0 0 3px rgba(201,168,76,0.08); }
        .form-select option { background: var(--surface-2); }

        /* Upload zone */
        .upload-zone {
            background: var(--surface-2); border: 1px dashed rgba(201,168,76,0.25);
            border-radius: 9px; padding: 18px; text-align: center;
            cursor: pointer; transition: all .15s;
        }
        .upload-zone:hover { border-color: var(--gold); background: rgba(201,168,76,0.04); }
        .upload-label { font-size: 12px; color: var(--text-muted); margin-top: 6px; }
        .upload-hint { font-size: 11px; color: var(--text-dim); margin-top: 2px; }
        .upload-icon { color: var(--gold-dim); }

        /* Modal footer */
        .modal-footer { display: flex; justify-content: flex-end; gap: 10px; padding-top: 1.25rem; border-top: 1px solid var(--border); margin-top: 1.5rem; }
        .btn-cancel-modal {
            background: transparent; border: 1px solid var(--border);
            color: var(--text-muted); padding: 9px 18px; border-radius: 9px;
            font-size: 13px; cursor: pointer; font-family: inherit; transition: all .15s;
        }
        .btn-cancel-modal:hover { border-color: rgba(255,255,255,0.15); color: var(--text); }
        .btn-save {
            background: var(--gold); color: #0d0d0f; border: none;
            padding: 9px 22px; border-radius: 9px; font-size: 13px;
            font-weight: 500; cursor: pointer; font-family: inherit; transition: all .15s;
        }
        .btn-save:hover { background: var(--gold-light); }
        .btn-delete-confirm {
            background: var(--red); color: #fff; border: none;
            padding: 9px 22px; border-radius: 9px; font-size: 13px;
            font-weight: 500; cursor: pointer; font-family: inherit; transition: all .15s;
        }
        .btn-delete-confirm:hover { background: #c94444; }

        /* Delete modal special */
        .delete-icon-wrap {
            width: 52px; height: 52px; border-radius: 14px;
            background: var(--red-dim); border: 1px solid rgba(224,85,85,0.2);
            display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;
        }
        .delete-msg { font-size: 13px; color: var(--text-muted); line-height: 1.6; }
    </style>


    <div class="bm-page">

        {{-- Header --}}
        <div class="bm-header">
            <div>
                <div class="bm-eyebrow">Content / Banners</div>
                <h1 class="bm-title">Banner Management</h1>
                <p class="bm-subtitle">Manage homepage banners and promotions</p>
            </div>
            <button class="btn-add" @click="openCreate=true">
                <svg width="13" height="13" viewBox="0 0 13 13" fill="none">
                    <path d="M6.5 1v11M1 6.5h11" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
                Add Banner
            </button>
        </div>

        {{-- Flash --}}
        @if(session('success'))
        <div class="flash-success">
            <svg width="15" height="15" viewBox="0 0 15 15" fill="none">
                <path d="M3 7.5l3 3 6-6" stroke="#5aad7a" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            {{ session('success') }}
        </div>
        @endif

        {{-- Stats --}}
        <div class="bm-stats">
            <div class="stat-card stat-card-gold">
                <div class="stat-lbl">Total Banners</div>
                <div class="stat-val stat-val-gold">{{ $banners->count() }}</div>
                <div class="stat-sub">All entries</div>
            </div>
            <div class="stat-card">
                <div class="stat-lbl">Active</div>
                <div class="stat-val" style="color:var(--green)">{{ $banners->where('status',1)->count() }}</div>
                <div class="stat-sub">Currently live</div>
            </div>
            <div class="stat-card">
                <div class="stat-lbl">Inactive</div>
                <div class="stat-val" style="color:var(--red)">{{ $banners->where('status',0)->count() }}</div>
                <div class="stat-sub">Paused or scheduled</div>
            </div>
        </div>

        {{-- Table --}}
        <div class="table-card">
            <div class="table-toolbar">
                <span class="toolbar-title">All Banners</span>
                <div class="toolbar-controls">
                    <input class="search-input" type="text" placeholder="Search banners…" />
                    <select class="filter-select">
                        <option>All statuses</option>
                        <option>Active</option>
                        <option>Inactive</option>
                    </select>
                </div>
            </div>

            <div style="overflow-x:auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Banner</th>
                            <th>Order</th>
                            <th>Schedule</th>
                            <th>Status</th>
                            <th style="text-align:right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($banners as $banner)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:14px;">
                                    <img src="{{ $banner->image }}" class="thumb" alt="{{ $banner->title }}">
                                    <div>
                                        <div class="banner-title">{{ $banner->title }}</div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="order-badge">{{ $banner->sort_order ?? '—' }}</span>
                            </td>

                            <td>
                                <div class="date-range">
                                    <div><span>From</span>{{ $banner->start_date ?? '—' }}</div>
                                    <div><span>To</span>{{ $banner->end_date ?? '—' }}</div>
                                </div>
                            </td>

                            <td>
                                @if($banner->status)
                                    <span class="badge badge-active">
                                        <span class="badge-dot"></span> Active
                                    </span>
                                @else
                                    <span class="badge badge-inactive">
                                        <span class="badge-dot"></span> Inactive
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div style="display:flex;justify-content:flex-end;gap:8px;">
                                    <button class="btn-edit"
                                        @click='editBanner({
                                            id:"{{ $banner->id }}",
                                            title:`{{ addslashes($banner->title) }}`,
                                            sort_order:"{{ $banner->sort_order }}",
                                            status:"{{ $banner->status }}",
                                            start_date:"{{ $banner->start_date }}",
                                            end_date:"{{ $banner->end_date }}"
                                        })'>
                                        Edit
                                    </button>
                                    <button class="btn-del"
                                        @click="deleteId='{{ $banner->id }}'; deleteModal=true">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                                            <rect x="2" y="5" width="18" height="12" rx="3" stroke="var(--text-dim)" stroke-width="1.2"/>
                                            <path d="M7 9h8M7 13h5" stroke="var(--text-dim)" stroke-width="1.2" stroke-linecap="round"/>
                                        </svg>
                                    </div>
                                    <div class="empty-text">No banners found</div>
                                    <div class="empty-sub">Add your first banner to get started</div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                <span class="pagination-info">Showing {{ $banners->count() }} banners</span>
                <div class="pagination-btns">
                    <button class="page-btn">‹</button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn">2</button>
                    <button class="page-btn">›</button>
                </div>
            </div>
        </div>

    </div>{{-- /bm-page --}}


    {{-- ══════════════════ CREATE MODAL ══════════════════ --}}
    <div x-show="openCreate" x-transition.opacity class="modal-overlay" style="display:none;">
        <div @click.away="openCreate=false" class="modal">
            <div class="modal-top-bar"></div>
            <div class="modal-inner">

                <div class="modal-head">
                    <div>
                        <div class="modal-title">Add Banner</div>
                        <div class="modal-sub">Fill in the details below to publish a new banner</div>
                    </div>
                    <button class="modal-close" @click="openCreate=false">×</button>
                </div>

                <form action="{{ route('banners.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-row">
                        <label class="form-lbl">Title</label>
                        <input name="title" required class="form-input" placeholder="e.g. Summer Sale 2025" />
                    </div>

                    <div class="form-row">
                        <label class="form-lbl">Banner Image</label>
                        <div class="upload-zone" onclick="this.querySelector('input').click()">
                            <svg class="upload-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" style="margin:0 auto 6px;display:block;">
                                <path d="M12 16V8M12 8l-3 3M12 8l3 3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                            </svg>
                            <div class="upload-label">Click to upload or drag &amp; drop</div>
                            <div class="upload-hint">PNG, JPG up to 5 MB</div>
                            <input type="file" name="image" style="display:none;" />
                        </div>
                    </div>

                    <div class="form-grid">
                        <div>
                            <label class="form-lbl">Sort Order</label>
                            <input type="number" name="sort_order" value="0" class="form-input" />
                        </div>
                        <div>
                            <label class="form-lbl">Status</label>
                            <select name="status" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-grid" style="margin-bottom:0;">
                        <div>
                            <label class="form-lbl">Start Date</label>
                            <input type="date" name="start_date" class="form-input" />
                        </div>
                        <div>
                            <label class="form-lbl">End Date</label>
                            <input type="date" name="end_date" class="form-input" />
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" @click="openCreate=false" class="btn-cancel-modal">Cancel</button>
                        <button type="submit" class="btn-save">Save Banner</button>
                    </div>
                </form>

            </div>
        </div>
    </div>


    {{-- ══════════════════ EDIT MODAL ══════════════════ --}}
    <div x-show="openEdit" x-transition.opacity class="modal-overlay" style="display:none;">
        <div @click.away="openEdit=false" class="modal">
            <div class="modal-top-bar"></div>
            <div class="modal-inner">

                <div class="modal-head">
                    <div>
                        <div class="modal-title">Edit Banner</div>
                        <div class="modal-sub">Update the details for this banner</div>
                    </div>
                    <button class="modal-close" @click="openEdit=false">×</button>
                </div>

                <form :action="'/admin/banners/'+edit.id" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-row">
                        <label class="form-lbl">Title</label>
                        <input name="title" x-model="edit.title" class="form-input" />
                    </div>

                    <div class="form-row">
                        <label class="form-lbl">Replace Image</label>
                        <div class="upload-zone" onclick="this.querySelector('input').click()">
                            <svg class="upload-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" style="margin:0 auto 6px;display:block;">
                                <path d="M12 16V8M12 8l-3 3M12 8l3 3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                            </svg>
                            <div class="upload-label">Upload a new image to replace</div>
                            <div class="upload-hint">Leave empty to keep current image</div>
                            <input type="file" name="image" style="display:none;" />
                        </div>
                    </div>

                    <div class="form-grid">
                        <div>
                            <label class="form-lbl">Sort Order</label>
                            <input type="number" name="sort_order" x-model="edit.sort_order" class="form-input" />
                        </div>
                        <div>
                            <label class="form-lbl">Status</label>
                            <select name="status" x-model="edit.status" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-grid" style="margin-bottom:0;">
                        <div>
                            <label class="form-lbl">Start Date</label>
                            <input type="date" name="start_date" x-model="edit.start_date" class="form-input" />
                        </div>
                        <div>
                            <label class="form-lbl">End Date</label>
                            <input type="date" name="end_date" x-model="edit.end_date" class="form-input" />
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" @click="openEdit=false" class="btn-cancel-modal">Cancel</button>
                        <button type="submit" class="btn-save">Update Banner</button>
                    </div>
                </form>

            </div>
        </div>
    </div>


    {{-- ══════════════════ DELETE MODAL ══════════════════ --}}
    <div x-show="deleteModal" x-transition.opacity class="modal-overlay" style="display:none;">
        <div @click.away="deleteModal=false" class="modal" style="max-width:420px;">
            <div class="modal-top-bar" style="background:linear-gradient(90deg,#7a2020,var(--red),#7a2020);"></div>
            <div class="modal-inner">

                <div class="delete-icon-wrap">
                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                        <path d="M3 5.5h16M8.5 5.5V4a1 1 0 011-1h3a1 1 0 011 1v1.5M9.5 10v5M12.5 10v5M4.5 5.5l1 12a1.5 1.5 0 001.5 1.5h8a1.5 1.5 0 001.5-1.5l1-12" stroke="var(--red)" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>

                <div class="modal-title" style="margin-bottom:.5rem;">Delete Banner?</div>
                <p class="delete-msg">This will permanently remove this banner and cannot be undone. Are you sure you want to continue?</p>

                <form :action="'/admin/banners/'+deleteId" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="modal-footer">
                        <button type="button" @click="deleteModal=false" class="btn-cancel-modal">Cancel</button>
                        <button type="submit" class="btn-delete-confirm">Yes, Delete</button>
                    </div>
                </form>

            </div>
        </div>
    </div>


    <script>
        function bannerManager() {
            return {
                openCreate: false,
                openEdit: false,
                deleteModal: false,
                deleteId: null,

                edit: {
                    id: '',
                    title: '',
                    sort_order: '',
                    status: 1,
                    start_date: '',
                    end_date: ''
                },

                editBanner(banner) {
                    this.edit = banner;
                    this.openEdit = true;
                }
            }
        }
    </script>

</div>

@endsection