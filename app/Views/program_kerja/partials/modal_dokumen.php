<!-- Document Management Modal (Reusable Partial) -->
<div id="modal-dokumen" class="modal-overlay">
    <div class="modal-container" style="max-width: 640px; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
        <div class="modal-header" style="padding: 16px 20px; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center;">
            <h3 class="modal-title" style="font-size: 1.1rem; font-weight: 700; color: #1e293b; margin: 0;">Kelola Dokumen Output</h3>
            <button type="button" class="modal-close" onclick="tutupModalDokumen()" style="font-size: 1.5rem; line-height: 1; border: none; background: transparent; cursor: pointer; color: #64748b;">×</button>
        </div>
        
        <div class="modal-body" style="padding: 0; display: flex; flex-direction: column; background: #fff;">
            <!-- Document List Section -->
            <div id="dm-doc-list" style="padding: 20px; border-bottom: 1px solid #f3f4f6; min-height: 100px; max-height: 350px; overflow-y: auto;">
                <!-- Default / Empty State (Matching Original Screenshot) -->
                <div style="display: flex; align-items: center; gap: 8px; color: #475569; font-size: 0.95rem;">
                    <span style="font-size: 1.2rem;">📂</span>
                    <span>Belum ada dokumen yang akan diupload</span>
                </div>
            </div>

            <?php 
            // Better Role Detection
            $sessionRole = session()->get('role');
            $isAdminRole = ($sessionRole === 'admin');
            $isUserRole = ($sessionRole === 'user');
            
            // Allow if canUpload passed from parent OR if user is admin/user (fallback)
            $allowUpload = (isset($canUpload) && $canUpload) || $isAdminRole || $isUserRole;
            
            if ($allowUpload): 
            ?>
            <!-- Upload Form Section (Matching Original Screenshot Layout) -->
            <div id="upload-section-container" style="padding: 20px; background: #fff;">
                <div style="margin-bottom: 15px;">
                    <label for="dm-tipe" style="display: block; font-size: 0.9rem; font-weight: 500; color: #334155; margin-bottom: 8px;">Jenis Dokumen</label>
                    <select id="dm-tipe" style="width: 200px; height: 38px; padding: 0 10px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.9rem; color: #1e293b; background: #fff;">
                        <option value="Surat Tugas">Surat Tugas</option>
                        <option value="Laporan">Laporan</option>
                        <option value="Dokumen Komunikasi">Dokumen Komunikasi</option>
                        <option value="Bukti Dukung">Bukti Dukung</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="flex: 1;">
                        <input type="file" id="dm-file" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.ppt,.pptx"
                            style="width: 100%; height: 40px; border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px; font-size: 0.85rem; color: #64748b;">
                    </div>
                    <button type="button" id="dm-btn-upload" 
                        onclick="window.startUploadDokumen ? startUploadDokumen() : console.error('Upload handler not defined')"
                        style="height: 40px; padding: 0 24px; background: #1a202c; color: white; border: none; border-radius: 8px; font-size: 0.9rem; font-weight: 600; cursor: pointer; transition: opacity 0.2s;">
                        Upload
                    </button>
                </div>

                <!-- Progress Indicator -->
                <div id="dm-upload-progress" style="display: none; margin-top: 15px; text-align: center;">
                    <div style="width: 100%; background: #f1f5f9; height: 6px; border-radius: 3px; overflow: hidden; margin-bottom: 6px;">
                        <div class="progress-fill-upload" style="width: 0%; height: 100%; background: #3b82f6; transition: width 0.3s;"></div>
                    </div>
                    <span style="font-size: 0.8rem; color: #64748b;">Mengupload...</span>
                </div>
            </div>
            <?php else: ?>
            <div style="padding: 20px; color: #94a3b8; font-size: 0.85rem; text-align: center; font-style: italic;">
                <i class="fas fa-lock mr-1"></i> Anda tidak memiliki hak akses untuk mengupload dokumen.
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
