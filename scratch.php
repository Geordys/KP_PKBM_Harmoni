<?php
$htmlContent = file_get_contents('public/admin_old/kelola_beranda.html');

$startIndex = strpos($htmlContent, '<div class="page-header">');
$endIndex = strpos($htmlContent, '<!-- Sidebar Overlay -->');

if ($startIndex !== false && $endIndex !== false) {
    $innerHtml = substr($htmlContent, $startIndex, $endIndex - $startIndex);
    
    // Strip out </main> </div>
    $innerHtml = str_replace('</main>', '', $innerHtml);
    $innerHtml = str_replace("</div>\n\n  <!-- Guru Modal", "<!-- Guru Modal", $innerHtml);
    
    // Handle asset paths
    $innerHtml = preg_replace('/src="\/assets\//', 'src="{{ asset(\'assets/', $innerHtml);
    $innerHtml = str_replace('.jfif"', '.jfif\') }}"', $innerHtml);
    $innerHtml = str_replace('.jpg"', '.jpg\') }}"', $innerHtml);
    $innerHtml = str_replace('.png"', '.png\') }}"', $innerHtml);

    $bladeContent = "@extends('layouts.admin')\n\n" .
        "@section('title', 'Kelola Beranda')\n" .
        "@section('sidebar_subtitle', 'Manajemen Beranda')\n\n" .
        "@section('head')\n" .
        '<link rel="stylesheet" href="{{ asset(\'assets/css/kelola-beranda.css\') }}">' . "\n" .
        '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />' . "\n" .
        "@endsection\n\n" .
        "@section('topbar_actions')\n" .
        '<div class="top-tabs">' . "\n" .
        '    <button id="topTabMedia" class="top-tab active">Edit Foto & Poster</button>' . "\n" .
        '    <button id="topTabText" class="top-tab">Edit Teks</button>' . "\n" .
        '    <button id="topTabGuru" class="top-tab">Manajemen Guru</button>' . "\n" .
        "</div>\n" .
        "@endsection\n\n" .
        "@section('content')\n" .
        $innerHtml . "\n" .
        "@endsection\n\n" .
        "@section('scripts')\n" .
        '<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>' . "\n" .
        '<script src="{{ asset(\'assets/js/kelola-beranda.js\') }}?v={{ time() }}"></script>' . "\n" .
        "<script>\n" .
        "    // Additional logic for panel switching (synced with tabs)\n" .
        "    const tabMedia = document.getElementById('topTabMedia');\n" .
        "    const tabText = document.getElementById('topTabText');\n" .
        "    const tabGuru = document.getElementById('topTabGuru');\n    \n" .
        "    const panelMedia = document.getElementById('panel-media');\n" .
        "    const panelText = document.getElementById('panel-text');\n" .
        "    const panelGuru = document.getElementById('panel-guru');\n\n" .
        "    function switchPanel(panelToShow, activeTab) {\n" .
        "        [panelMedia, panelText, panelGuru].forEach(p => p.style.display = 'none');\n" .
        "        [tabMedia, tabText, tabGuru].forEach(t => t.classList.remove('active'));\n        \n" .
        "        panelToShow.style.display = 'block';\n" .
        "        activeTab.classList.add('active');\n" .
        "    }\n\n" .
        "    tabMedia.onclick = () => switchPanel(panelMedia, tabMedia);\n" .
        "    tabText.onclick = () => switchPanel(panelText, tabText);\n" .
        "    tabGuru.onclick = () => switchPanel(panelGuru, tabGuru);\n" .
        "</script>\n" .
        "@endsection\n";
        
    file_put_contents('resources/views/admin/kelola-beranda.blade.php', $bladeContent);
    echo "Successfully updated kelola-beranda.blade.php\n";
} else {
    echo "Indexes not found!\n";
}
