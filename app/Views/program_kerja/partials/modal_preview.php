<!-- Document Preview Modal (Reusable Partial) -->
<div id="modal-preview" class="modal-overlay">
    <div class="modal-container modal-lg" style="max-width: 900px; height: 90vh; display: flex; flex-direction: column;">
        <div class="modal-header" style="flex-shrink: 0;">
            <h3 class="modal-title" id="preview-title" style="font-size: 1rem; font-weight: 600; color: #1e293b; max-width: 80%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Preview Dokumen</h3>
            <button type="button" class="modal-close" onclick="tutupPreview()">×</button>
        </div>
        
        <div class="modal-body" style="flex: 1; padding: 0; display: flex; flex-direction: column; overflow: hidden; background: #f3f4f6;">
            <!-- Main Content Area -->
            <div style="flex: 1; position: relative; overflow: hidden; display: flex; flex-direction: column;">
                <!-- Iframe for PDF/Images -->
                <iframe id="preview-frame" src="" style="width: 100%; height: 100%; border: none; background: white; display: none;"></iframe>
                
                <!-- Container for JS-rendered documents (DOCX, XLSX) -->
                <div id="preview-js-container" style="display: none; width: 100%; height: 100%; overflow: auto; background: white; padding: 10px;">
                    <div id="preview-js-content"></div>
                </div>

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
            <div id="preview-fallback-message" class="text-sm text-gray-500" style="display: none;">
                <i class="fas fa-info-circle mr-1"></i> Jika preview tidak muncul, silakan unduh langsung.
            </div>
            <a id="btn-download-processed" href="javascript:void(0)" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-download"></i>
                <span>Download File</span>
            </a>
        </div>
    </div>
</div>

<!-- Library for Document Previews -->
<script src="https://unpkg.com/jszip/dist/jszip.min.js"></script>
<script src="https://unpkg.com/docx-preview/dist/docx-preview.js"></script>
<script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>

<style>
    #preview-js-content table {
        border-collapse: collapse;
        width: 100%;
        margin-bottom: 20px;
    }
    #preview-js-content table td, #preview-js-content table th {
        border: 1px solid #ddd;
        padding: 8px;
        font-size: 13px;
    }
    #preview-js-content table th {
        background-color: #f8f9fa;
        text-align: left;
    }
</style>

<script>
    function bukaPreview(id, name) {
        const modal = document.getElementById('modal-preview');
        const frame = document.getElementById('preview-frame');
        const jsContainer = document.getElementById('preview-js-container');
        const jsContent = document.getElementById('preview-js-content');
        const title = document.getElementById('preview-title');
        const downloadBtn = document.getElementById('btn-download-processed');
        const loading = document.getElementById('preview-loading');
        
        // Reset contents
        jsContent.innerHTML = '';
        frame.style.display = 'none';
        jsContainer.style.display = 'none';
        
        // Set info
        // Set info - strip extension for a cleaner title if you want, but user asked for original name
        title.textContent = `Preview: ${name}`;
        title.title = name; // Full name on hover
        
        const previewUrl = `<?= base_url('program-kerja/preview/') ?>${id}`;
        const downloadUrl = `<?= base_url('program-kerja/download/') ?>${id}`;
        
        // Link behavior
        downloadBtn.href = downloadUrl;
        downloadBtn.setAttribute('download', name);
        downloadBtn.target = '_blank';
        downloadBtn.onclick = null; // Reset any previous handler
        
        // Show modal
        modal.classList.add('show');
        loading.style.display = 'flex';
        
        // Detect file extension
        const ext = name.toLowerCase().split('.').pop();
        const nativePreviewTypes = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'txt'];
        const docxTypes = ['docx', 'doc'];
        const xlsxTypes = ['xlsx', 'xls', 'csv'];
        
        if (nativePreviewTypes.includes(ext)) {
            // Load iframe for PDF/Images
            frame.src = previewUrl;
            frame.style.display = 'block';
            
            frame.onload = () => {
                loading.style.display = 'none';
            };
            
            frame.onerror = () => {
                showPreviewError(loading, ext);
            };
            
            // Timeout fallback
            setTimeout(() => {
                if (loading.style.display !== 'none' && frame.style.display === 'block') {
                    loading.style.display = 'none'; // Sometimes iframe doesn't fire onload but content is there
                }
            }, 5000);

        } else if (docxTypes.includes(ext)) {
            // Render DOCX client-side
            jsContainer.style.display = 'block';
            
            fetch(previewUrl)
                .then(response => {
                    if (!response.ok) throw new Error('File tidak ditemukan atau terjadi kesalahan server');
                    return response.arrayBuffer();
                })
                .then(buffer => {
                    docx.renderAsync(buffer, jsContent)
                        .then(() => {
                            loading.style.display = 'none';
                        })
                        .catch(err => {
                            console.error('Docx Preview Error:', err);
                            showPreviewError(loading, ext);
                        });
                })
                .catch(err => {
                    console.error('Fetch Error:', err);
                    showPreviewError(loading, ext);
                });

        } else if (xlsxTypes.includes(ext)) {
            // Render XLSX client-side
            jsContainer.style.display = 'block';
            
            fetch(previewUrl)
                .then(response => {
                    if (!response.ok) throw new Error('File tidak ditemukan atau terjadi kesalahan server');
                    return response.arrayBuffer();
                })
                .then(buffer => {
                    const workbook = XLSX.read(buffer, { type: 'array' });
                    // Read first sheet
                    const firstSheetName = workbook.SheetNames[0];
                    const worksheet = workbook.Sheets[firstSheetName];
                    const htmlContent = XLSX.utils.sheet_to_html(worksheet);
                    
                    jsContent.innerHTML = `
                        <div class="mb-4 p-2 bg-blue-50 text-blue-700 text-xs rounded border border-blue-100">
                            <i class="fas fa-info-circle mr-1"></i> Menampilkan sheet: <strong>${firstSheetName}</strong>
                        </div>
                        <div style="overflow-x: auto;">${htmlContent}</div>
                    `;
                    loading.style.display = 'none';
                })
                .catch(err => {
                    console.error('XLSX Preview Error:', err);
                    showPreviewError(loading, ext);
                });
        } else {
            // For non-previewable files
            showPreviewError(loading, ext, true);
        }
    }
    
    function showPreviewError(loadingEl, ext, isNonPreviewable = false) {
        const fallbackMessage = document.getElementById('preview-fallback-message');
        const message = isNonPreviewable 
            ? `File ${ext.toUpperCase()} belum didukung untuk preview langsung di browser.`
            : `Gagal memuat preview untuk file ${ext.toUpperCase()}.`;
            
        loadingEl.innerHTML = `
            <div class="text-center p-6">
                <i class="fas fa-file-${getFileIcon(ext)} text-6xl mb-4" style="color: #64748b;"></i>
                <p class="text-gray-700 font-medium mb-2">Preview Tidak Tersedia</p>
                <p class="text-sm text-gray-500 max-w-md mx-auto">${message}<br>Silakan klik tombol "Download File" di bawah.</p>
            </div>
        `;
        loadingEl.style.display = 'flex';
        
        if (fallbackMessage) {
            fallbackMessage.style.display = 'block';
        }
    }
    
    function getFileIcon(ext) {
        const icons = {
            'pdf': 'pdf',
            'doc': 'word', 'docx': 'word',
            'xls': 'excel', 'xlsx': 'excel',
            'csv': 'excel',
            'ppt': 'powerpoint', 'pptx': 'powerpoint',
            'jpg': 'image', 'jpeg': 'image', 'png': 'image', 'gif': 'image',
            'zip': 'archive', 'rar': 'archive'
        };
        return icons[ext] || 'alt';
    }

    function tutupPreview() {
        const modal = document.getElementById('modal-preview');
        const frame = document.getElementById('preview-frame');
        const loading = document.getElementById('preview-loading');
        const fallbackMessage = document.getElementById('preview-fallback-message');
        const jsContent = document.getElementById('preview-js-content');
        
        modal.classList.remove('show');
        
        if (fallbackMessage) {
            fallbackMessage.style.display = 'none';
        }
        
        // Reset loading display
        loading.innerHTML = `
            <div class="text-center">
                <i class="fas fa-spinner fa-spin text-3xl text-primary"></i>
                <p class="mt-2 text-sm text-gray-600">Memuat preview...</p>
            </div>
        `;
        
        // Clear containers
        setTimeout(() => {
            frame.src = 'about:blank';
            jsContent.innerHTML = '';
        }, 300);
    }
    
    // Close on outside click
    document.getElementById('modal-preview').addEventListener('click', (e) => {
        if (e.target === document.getElementById('modal-preview')) {
            tutupPreview();
        }
    });
</script>
