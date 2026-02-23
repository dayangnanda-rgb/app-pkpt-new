<?php
function updateFile($file, $regex, $replace) {
    if (!file_exists($file)) return false;
    $content = file_get_contents($file);
    $newContent = preg_replace($regex, $replace, $content);
    if ($newContent !== null && $newContent !== $content) {
        file_put_contents($file, $newContent);
        return true;
    }
    return false;
}

// Update ProgramKerjaModel.php
$fileModel = 'app/Models/ProgramKerjaModel.php';
// Match: if ($onlyForUser) { $query->whereIn(...) }
// We want to replace it with groupStart logic
$regexModel = '/if \(\$onlyForUser\) \{\s+\$query->whereIn\(\'program_kerja\.id\', function\(\$builder\) use \(\$onlyForUser, \$peran\) \{/';
$replaceModel = 'if ($onlyForUser) {
            $query->groupStart();
                $query->where(\'program_kerja.created_by\', $onlyForUser);
                $query->orWhereIn(\'program_kerja.id\', function($builder) use ($onlyForUser, $peran) {';

if (updateFile($fileModel, $regexModel, $replaceModel)) {
    echo "Updated ProgramKerjaModel.php (Start block)\n";
} else {
    echo "Failed to update ProgramKerjaModel.php (Start block)\n";
}

// Close groupEnd
$regexClose = '/return \$builder;\s+\}\);\s+\}/';
$replaceClose = 'return $builder;
            });
            $query->groupEnd();
        }';

if (updateFile($fileModel, $regexClose, $replaceClose)) {
    echo "Updated ProgramKerjaModel.php (Close block)\n";
}

// Update ProgramKerja.php
$fileCtrl = 'app/Controllers/ProgramKerja.php';
$regexSimpan = '/\'alasan_tidak_terlaksana\' => in_array\(\$status, \[\'Tidak Terlaksana\', \'Dibatalkan\'\]\) \? \$this->request->getPost\(\'alasan_tidak_terlaksana\'\) : null,/';
$replaceSimpan = '\'alasan_tidak_terlaksana\' => in_array($status, [\'Tidak Terlaksana\', \'Dibatalkan\']) ? $this->request->getPost(\'alasan_tidak_terlaksana\') : null,
            \'created_by\'         => session()->get(\'user.pegawai_detail.nama\') ?? session()->get(\'user.name\'),';

// We only want to update the one in simpan() (the first occurrence)
$content = file_get_contents($fileCtrl);
$content = preg_replace($regexSimpan, $replaceSimpan, $content, 1);
file_put_contents($fileCtrl, $content);
echo "Updated ProgramKerja.php\n";
