<?php
$file = 'app/Controllers/ProgramKerja.php';
$content = file_get_contents($file);

// Add created_by in simpan method (around line 335)
$target = "'alasan_tidak_terlaksana' => in_array(\$status, ['Tidak Terlaksana', 'Dibatalkan']) ? \$this->request->getPost('alasan_tidak_terlaksana') : null,";
$replace = $target . "\n            'created_by'         => session()->get('user.pegawai_detail.nama') ?? session()->get('user.name'),";

// We want to replace only the occurrence in simpan()
// simpan() starts around line 286, perbarui() starts around line 430.
// Let's use a more specific target for simpan.
$simpan_part = substr($content, strpos($content, 'public function simpan()'), 1000);
if (strpos($simpan_part, $target) !== false) {
    $new_simpan_part = str_replace($target, $replace, $simpan_part);
    $content = str_replace($simpan_part, $new_simpan_part, $content);
}

file_put_contents($file, $content);
echo "Successfully updated ProgramKerja.php\n";

// Update ProgramKerjaModel.php
$fileModel = 'app/Models/ProgramKerjaModel.php';
$contentModel = file_get_contents($fileModel);

$modelTarget = "if (\$onlyForUser) {\n            \$query->whereIn('program_kerja.id', function(\$builder) use (\$onlyForUser, \$peran) {";
$modelReplace = "if (\$onlyForUser) {\n            \$query->groupStart();\n                \$query->where('program_kerja.created_by', $onlyForUser);\n                \$query->orWhereIn('program_kerja.id', function(\$builder) use (\$onlyForUser, \$peran) {";

// There are two occurrences, one in ambilSemuaData and one in cariProgramKerja
$contentModel = str_replace($modelTarget, $modelReplace, $contentModel);

// Also need to close groupStart
$closeTarget = "return \$builder;\n            });\n        }";
$closeReplace = "return \$builder;\n            });\n            \$query->groupEnd();\n        }";
$contentModel = str_replace($closeTarget, $closeReplace, $contentModel);

file_put_contents($fileModel, $contentModel);
echo "Successfully updated ProgramKerjaModel.php\n";
