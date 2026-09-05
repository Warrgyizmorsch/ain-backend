<?php
$content = file_get_contents(__DIR__ . '/resources/views/back-end/whatsapp/chat.blade.php');

$cleanJs = preg_replace('/@json\((?:[^()]|\([^()]*\))*\)/s', '""', $content);
$cleanJs = preg_replace('/\{\{[^}]*\}\}/s', '""', $cleanJs);
$cleanJs = preg_replace('/@if\((?:[^()]|\([^()]*\))*\)/s', 'if(true){', $cleanJs);
$cleanJs = preg_replace('/@elseif\((?:[^()]|\([^()]*\))*\)/s', '}else if(true){', $cleanJs);
$cleanJs = preg_replace('/@else\b/s', '}else{', $cleanJs);
$cleanJs = preg_replace('/@endif\b/s', '}', $cleanJs);
$cleanJs = preg_replace('/@foreach\((?:[^()]|\([^()]*\))*\)/s', 'for(let x of []){', $cleanJs);
$cleanJs = preg_replace('/@endforeach\b/s', '}', $cleanJs);
$cleanJs = preg_replace('/@unless\((?:[^()]|\([^()]*\))*\)/s', 'if(true){', $cleanJs);
$cleanJs = preg_replace('/@endunless\b/s', '}', $cleanJs);

preg_match_all('/<script\b[^>]*>(.*?)<\/script>/is', $cleanJs, $matches);

$allPassed = true;
foreach ($matches[1] as $idx => $script) {
    $tempFile = __DIR__ . "/temp_script_check_{$idx}.js";
    file_put_contents($tempFile, $script);
    exec("node -c \"{$tempFile}\" 2>&1", $out, $code);
    if ($code !== 0) {
        $allPassed = false;
        echo "Script #{$idx} ERROR:\n" . implode("\n", $out) . "\n";
    }
    @unlink($tempFile);
    $out = [];
}
if ($allPassed) {
    echo "ALL " . count($matches[1]) . " SCRIPTS SYNTAX VALID!\n";
}
