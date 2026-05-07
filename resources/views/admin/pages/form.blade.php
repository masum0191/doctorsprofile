<!-- resources/views/admin/pages/form.blade.php -->
<div class="detail-section">
    <h5><i class="fas fa-info-circle me-2"></i>Page Information</h5>
    
    <div class="detail-row">
        <div class="detail-label">Title <span class="text-danger">*</span></div>
        <div class="detail-value">
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                   value="{{ old('title', $page->title ?? '') }}" placeholder="Enter page title" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    
    <div class="detail-row">
        <div class="detail-label">Slug <span class="text-danger">*</span></div>
        <div class="detail-value">
            <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" 
                   value="{{ old('slug', $page->slug ?? '') }}" placeholder="page-slug" required>
            <small class="text-muted">URL-friendly identifier (lowercase, hyphens)</small>
            @error('slug')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    
    <div class="detail-row">
        <div class="detail-label">Menu Title <span class="text-danger">*</span></div>
        <div class="detail-value">
            <input type="text" name="menu_title" class="form-control @error('menu_title') is-invalid @enderror" 
                   value="{{ old('menu_title', $page->menu_title ?? '') }}" placeholder="Navigation menu title" required>
            @error('menu_title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    
    <div class="mt-2">
        <div class="detail-label">Content</div>
        <div class="mt-2">
            <textarea name="content" class="form-control summernote @error('content') is-invalid @enderror" 
                      rows="6" placeholder="Page content">{{ old('content', $page->content ?? '') }}</textarea>
            @error('content')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="detail-section">
    <h5><i class="fas fa-image me-2"></i>Featured Image</h5>
    
    <div class="detail-row">
        <div class="detail-label">Image</div>
        <div class="detail-value">
            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
            @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            
            @if(!empty($page->image))
                <div class="mt-3 d-flex flex-column">
                    <span class="text-muted mb-1">Current Image:</span>
                    <div class="d-flex align-items-center gap-3">
                        <img src="{{ asset('storage/' . $page->image) }}" class="img-thumbnail" style="width: 150px; height: auto;">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remove_image" id="remove_image" value="1">
                            <label class="form-check-label text-danger" for="remove_image">
                                <i class="fas fa-trash-alt me-1"></i> Remove image
                            </label>
                        </div>
                    </div>
                </div>
            @endif
            <small class="text-muted d-block mt-1">Recommended size: 1200x630 pixels (Max 2MB)</small>
        </div>
    </div>
</div>

<div class="detail-section">
    <h5><i class="fas fa-bars me-2"></i>Menu Settings</h5>
    
    <div class="detail-row">
        <div class="detail-label">Show in Menu</div>
        <div class="detail-value">
            <div class="form-check form-switch ps-0">
                <div class="d-flex align-items-center">
                    <input class="form-check-input ms-0" type="checkbox" name="show_in_menu" 
                           id="show_in_menu" value="1" {{ old('show_in_menu', $page->show_in_menu ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label ms-2" for="show_in_menu">Include in navigation menu</label>
                </div>
            </div>
        </div>
    </div>
    
    <div class="detail-row">
        <div class="detail-label">Menu Order</div>
        <div class="detail-value">
            <input type="number" name="menu_order" class="form-control" 
                   value="{{ old('menu_order', $page->menu_order ?? 0) }}" min="0" style="width: 100px;">
            <small class="text-muted">Lower numbers appear first in menu</small>
        </div>
    </div>
</div>

<div class="detail-section">
    <h5><i class="fas fa-toggle-on me-2"></i>Publish Settings</h5>
    
    <div class="detail-row">
        <div class="detail-label">Status</div>
        <div class="detail-value">
            <div class="form-check form-switch ps-0">
                <div class="d-flex align-items-center">
                    <input class="form-check-input ms-0" type="checkbox" name="is_published" 
                           id="is_published" value="1" {{ old('is_published', $page->is_published ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label ms-2" for="is_published">Published (visible to public)</label>
                </div>
            </div>
        </div>
    </div>
</div>