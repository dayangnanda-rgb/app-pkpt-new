<!-- Document Preview Modal (Reusable Partial) -->
<div id="modal-preview" class="modal-overlay">
    <div class="modal-container modal-lg" style="max-width: 900px; height: 90vh; display: flex; flex-direction: column;">
        <div class="modal-header" style="flex-shrink: 0;">
            <h3 class="modal-title" id="preview-title">Preview Dokumen</h3>
            <button type="button" class="modal-close" onclick="tutupPreview()">×</button>
        </div>
        
        <div class="modal-body" style="flex: 1; padding: 0; display: flex; flex-direction: column; overflow: hidden; background: #f3f4f6;">
            <!-- Iframe Container -->
            <div style="flex: 1; position: relative;">
                <iframe id="preview-frame" src="" style="width: 100%; height: 100%; border: none; background: white;"></iframe>
                <!-- Loading Indicator -->
                <div id="preview-loading" style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.8); z-index: 10;">
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin text-3xl text-primary"></i>
                        <p class="mt-2 text-sm text-gray-600">Memuat preview...</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer" style="padding: 1rem; background: white; border-top: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;">
            <div class="text-sm text-gray-500">
                <i class="fas fa-info-circle mr-1"></i> Jika preview tidak muncul, silakan unduh langsung.
            </div>
            <a id="btn-download-processed" href="#" class="btn btn-primary" target="_blank" download>
                <i class="fas fa-download mr-2"></i> Download File
            </a>
        </div>
    </div>
</div>

<script>
    function bukaPreview(id, name) {
        const modal = document.getElementById('modal-preview');
        const frame = document.getElementById('preview-frame');
        const title = document.getElementById('preview-title');
        const downloadBtn = document.getElementById('btn-download-processed');
        const loading = document.getElementById('preview-loading');
        
        // Set content
        title.textContent = name || 'Preview Dokumen';
        const previewUrl = `<?= base_url('program-kerja/preview/') ?>${id}`;
        const downloadUrl = `<?= base_url('program-kerja/download/') ?>${id}`;
        
        downloadBtn.href = downloadUrl;
        
        // Show modal first
        modal.classList.add('show');
        loading.style.display = 'flex';
        
        // Load iframe
        frame.src = previewUrl;
        frame.onload = () => {
             loading.style.display = 'none';
        };
    }

    function tutupPreview() {
        const modal = document.getElementById('modal-preview');
        const frame = document.getElementById('preview-frame');
        
        modal.classList.remove('show');
        // Clear src to stop loading/playing
        setTimeout(() => {
            frame.src = 'about:blank';
        }, 300);
    }
    
    // Close on outside click
    document.getElementById('modal-preview').addEventListener('click', (e) => {
        if (e.target === document.getElementById('modal-preview')) {
            tutupPreview();
        }
    });
</script>
