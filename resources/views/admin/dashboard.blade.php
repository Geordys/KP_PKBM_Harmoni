@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('styles')
<style>
    .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
    
    .card-gradient {
        border: none; border-radius: 20px; padding: 24px; color: #fff;
        position: relative; overflow: hidden; display: flex; flex-direction: column;
        justify-content: space-between; min-height: 140px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05); transition: all 0.3s ease;
    }
    .card-gradient:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1); }
    .card-gradient .label { font-size: 13px; font-weight: 600; opacity: 0.9; margin-bottom: 8px; text-transform: uppercase; }
    .card-gradient .value { font-size: 36px; font-weight: 800; margin: 0; line-height: 1; }
    .card-gradient .icon-small {
        position: absolute; right: 20px; bottom: 20px; background: rgba(255, 255, 255, 0.2);
        width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center;
        justify-content: center; backdrop-filter: blur(5px);
    }

    .grad-blue { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); }
    .grad-teal { background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); }
    .grad-purple { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); }
    .grad-orange { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .grad-slate { background: linear-gradient(135deg, #64748b 0%, #475569 100%); }
    .grad-green { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }

    .charts-grid { display: grid; grid-template-columns: 1.5fr 1fr; gap: 24px; margin-top: 24px; }
    .panel h3 { font-size: 14px; margin: 0 0 4px; color: var(--text); }
    .panel .sub { font-size: 13px; color: var(--muted); margin-bottom: 24px; }

    @media (max-width: 1100px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .charts-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 600px) {
        .stats-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="dashboard-header" style="margin-bottom: 32px;">
    <p style="margin: 0 0 8px; font-weight: 600; color: var(--primary);">Halo, Selamat Datang Admin.</p>
    <h1 style="font-size: 28px; margin: 0; letter-spacing: -0.02em;">Dashboard Ringkasan</h1>
</div>

<div class="stats-grid">
    <div class="card-gradient grad-blue">
        <div class="card-content">
            <div class="label">Total Pendaftar</div>
            <div class="value" id="tTotal">-</div>
        </div>
        <div class="icon-small">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
            </svg>
        </div>
    </div>
    <div class="card-gradient grad-teal">
        <div class="card-content">
            <div class="label">Paket B (SMP)</div>
            <div class="value" id="tB">-</div>
        </div>
        <div class="icon-small">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
            </svg>
        </div>
    </div>
    <div class="card-gradient grad-purple">
        <div class="card-content">
            <div class="label">Paket C (SMA)</div>
            <div class="value" id="tC">-</div>
        </div>
        <div class="icon-small">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 1-3.3-2.3z"></path>
                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
            </svg>
        </div>
    </div>
    <div class="card-gradient grad-orange">
        <div class="card-content">
            <div class="label">Menunggu Verifikasi</div>
            <div class="value" id="tMenunggu">-</div>
        </div>
        <div class="icon-small">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
        </div>
    </div>
    <div class="card-gradient grad-slate">
        <div class="card-content">
            <div class="label">Diverifikasi</div>
            <div class="value" id="tVerif">-</div>
        </div>
        <div class="icon-small">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
            </svg>
        </div>
    </div>
    <div class="card-gradient grad-green">
        <div class="card-content">
            <div class="label">Siswa Diterima</div>
            <div class="value" id="tTerima">-</div>
        </div>
        <div class="icon-small">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
        </div>
    </div>
</div>

<div class="charts-grid">
    <section class="card panel">
        <h3>Statistik Pendaftaran</h3>
        <p class="sub">Jumlah pendaftar baru per bulan.</p>
        <div style="height: 300px;">
            <canvas id="chartPendaftar"></canvas>
        </div>
    </section>

    <section class="card panel">
        <h3>Status Pendaftaran</h3>
        <p class="sub">Distribusi status siswa saat ini.</p>
        <div style="height: 300px;">
            <canvas id="chartStatus"></canvas>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
    const API_STATS = "{{ url('/api/admin/stats') }}";
    const tokenAdmin = localStorage.getItem("token_admin");

    async function fetchData() {
        try {
            const res = await fetch(API_STATS, {
                headers: { "Authorization": "Bearer " + tokenAdmin }
            });
            const data = await res.json();

            if (!res.ok) throw new Error(data.message);

            // Update Stats
            document.getElementById("tTotal").textContent = data.total || 0;
            document.getElementById("tB").textContent = data.paket?.B || 0;
            document.getElementById("tC").textContent = data.paket?.C || 0;
            document.getElementById("tMenunggu").textContent = data.status?.MENUNGGU || 0;
            document.getElementById("tVerif").textContent = data.status?.DIVERIFIKASI || 0;
            document.getElementById("tTerima").textContent = data.status?.DITERIMA || 0;

            // Render Charts
            renderMainChart(data.chartData);
            renderStatusChart(data.status);
        } catch (err) {
            console.error("Failed to fetch stats:", err);
            Swal.fire('Error', 'Gagal memuat data statistik.', 'error');
        }
    }

    function renderMainChart(chartData) {
        const labels = Object.keys(chartData || {});
        const values = Object.values(chartData || {});
        
        new Chart(document.getElementById('chartPendaftar'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendaftar',
                    data: values,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    function renderStatusChart(status) {
        const labels = ['MENUNGGU', 'DIVERIFIKASI', 'DITERIMA'];
        const values = [status?.MENUNGGU || 0, status?.DIVERIFIKASI || 0, status?.DITERIMA || 0];
        
        new Chart(document.getElementById('chartStatus'), {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: ['#f59e0b', '#2563eb', '#16a34a'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { padding: 20, usePointStyle: true } }
                },
                cutout: '70%'
            }
        });
    }

    fetchData();
</script>
@endsection
