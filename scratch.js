const fs = require('fs');
const htmlContent = fs.readFileSync('public/admin_old/kelola_beranda.html', 'utf8');

// Extract the content
const startIndex = htmlContent.indexOf('<div class="page-header">');
const endIndex = htmlContent.indexOf('<!-- Sidebar Overlay -->');

if (startIndex > -1 && endIndex > -1) {
    let innerHtml = htmlContent.substring(startIndex, endIndex);
    
    // We also need to strip out </main> </div> that are inside this block
    innerHtml = innerHtml.replace('</main>', '').replace('</div>\n\n  <!-- Guru Modal', '<!-- Guru Modal');
    
    // Handle asset paths
    innerHtml = innerHtml.replace(/src="\/assets\//g, 'src="{{ asset(\'assets/');
    innerHtml = innerHtml.replace(/\.jfif"/g, '.jfif\') }}"');
    innerHtml = innerHtml.replace(/\.jpg"/g, '.jpg\') }}"');
    innerHtml = innerHtml.replace(/\.png"/g, '.png\') }}"');

    const bladeContent = `@extends('layouts.admin')

@section('title', 'Kelola Beranda')
@section('sidebar_subtitle', 'Manajemen Beranda')

@section('head')
<link rel="stylesheet" href="{{ asset('assets/css/kelola-beranda.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
@endsection

@section('topbar_actions')
<div class="top-tabs">
    <button id="topTabMedia" class="top-tab active">Edit Foto & Poster</button>
    <button id="topTabText" class="top-tab">Edit Teks</button>
    <button id="topTabGuru" class="top-tab">Manajemen Guru</button>
</div>
@endsection

@section('content')
` + innerHtml + `
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script src="{{ asset('assets/js/kelola-beranda.js') }}?v={{ time() }}"></script>
<script>
    // Additional logic for panel switching (synced with tabs)
    const tabMedia = document.getElementById('topTabMedia');
    const tabText = document.getElementById('topTabText');
    const tabGuru = document.getElementById('topTabGuru');
    
    const panelMedia = document.getElementById('panel-media');
    const panelText = document.getElementById('panel-text');
    const panelGuru = document.getElementById('panel-guru');

    function switchPanel(panelToShow, activeTab) {
        [panelMedia, panelText, panelGuru].forEach(p => p.style.display = 'none');
        [tabMedia, tabText, tabGuru].forEach(t => t.classList.remove('active'));
        
        panelToShow.style.display = 'block';
        activeTab.classList.add('active');
    }

    tabMedia.onclick = () => switchPanel(panelMedia, tabMedia);
    tabText.onclick = () => switchPanel(panelText, tabText);
    tabGuru.onclick = () => switchPanel(panelGuru, tabGuru);
</script>
@endsection
`;
    fs.writeFileSync('resources/views/admin/kelola-beranda.blade.php', bladeContent);
    console.log('Successfully updated kelola-beranda.blade.php');
} else {
    console.log('Indexes not found!');
}
