<!-- Document Management Modal (Reusable Partial) -->
<div id="modal-dokumen" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title">Kelola Dokumen Output</h3>
            <button type="button" class="modal-close" onclick="tutupModalDokumen()">×</button>
        </div>
        
        <div class="modal-body">
            <!-- List Section -->
            <div id="dm-doc-list" class="doc-list-container">
                <!-- Content loaded via AJAX -->
            </div>

            <!-- Upload Section -->
            <div class="doc-upload-section">
                <div class="form-group mb-2">
                    <label class="text-sm font-medium mb-1 block">Jenis Dokumen</label>
                    <select id="dm-tipe" class="form-select text-sm h-9">
                        <option value="Surat Tugas">Surat Tugas</option>
                        <option value="Laporan">Laporan</option>
                        <option value="Dokumen Komunikasi">Dokumen Komunikasi</option>
                        <option value="Bukti Dukung">Bukti Dukung</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="form-group mb-0">
                    <label class="text-sm font-medium mb-1 block">File</label>
                    <div class="flex gap-2 items-center">
                        <input type="file" id="dm-file" class="form-file text-sm flex-1" multiple>
                        <!-- Onclick handler should be defined in the parent view script -->
                        <button type="button" id="dm-btn-upload" class="btn btn-primary" onclick="window.startUploadDokumen ? startUploadDokumen() : console.error('Upload handler not defined')">Upload</button>
                    </div>
                    <!-- Progress Bar (Initially Hidden) -->
                    <div id="dm-upload-progress" class="hidden mt-2">
                        <div class="w-full bg-gray-200 rounded-full h-1.5">
                            <div class="bg-blue-600 h-1.5 rounded-full" style="width: 0%"></div>
                        </div>
                        <div class="text-xs text-center mt-1 text-gray-500">Mengupload...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
