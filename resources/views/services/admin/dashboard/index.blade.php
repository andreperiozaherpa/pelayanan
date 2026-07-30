@extends('layouts.app')

@section('title', 'Dashboard Overview')

@section('content')
    <div class="space-y-8">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">
                    Welcome, <span class="text-primary-acorn">{{ Auth::user()->name }}</span>
                </h1>
                <p class="text-xs text-slate-500 font-medium tracking-tight mt-1">
                    Ringkasan performa verifikasi dokumen dan aktivitas pelayanan sistem.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <div
                    class="px-4 py-2.5 bg-white dark:bg-slate-900 border border-black/[0.03] dark:border-white/[0.03] rounded-xl shadow-sm flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">System Operational</span>
                </div>
            </div>
        </div>

        <!-- Stats Widgets -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-4 gap-2 lg:gap-4 xl:gap-6">
            <x-stats-card label="Total Verifikasi" :value="$stats['total_verifications']" icon="lucide:database" color="primary" />
            <x-stats-card label="Status Valid" :value="$stats['status_distribution']['active']" icon="lucide:check-circle-2" color="emerald" />
            <x-stats-card label="Kadaluarsa" :value="$stats['status_distribution']['expired']" icon="lucide:clock" color="amber" />
            <x-stats-card label="Pending" :value="$stats['status_distribution']['pending']" icon="lucide:alert-triangle" color="rose" />
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Line Chart: Tren Pelayanan -->
            <div class="lg:col-span-2 premium-card p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-wider">Tren
                                Pelayanan Publik</h3>
                            <p class="text-[10px] text-slate-400 font-medium mt-0.5">Jumlah pengajuan permohonan dalam 6
                                bulan terakhir</p>
                        </div>
                    </div>
                    <div id="trendChart" class="w-full h-72"></div>
                </div>
            </div>

            <!-- Donut Chart: Komposisi Layanan -->
            <div class="premium-card p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-wider">Jenis
                                Pelayanan</h3>
                            <p class="text-[10px] text-slate-400 font-medium mt-0.5">Distribusi pengajuan berdasarkan jenis
                                layanan</p>
                        </div>
                    </div>
                    <div id="typeChart" class="w-full h-72 flex items-center justify-center"></div>
                </div>
            </div>
        </div>

        <!-- Detail Arsip Aktif -->
        <div class="space-y-6">
            <h3 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-wider px-1">
                Data Arsip Dokumen Terdaftar
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="premium-card p-4 flex flex-col justify-between border-l-4 border-primary-acorn">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Surat Miskin</span>
                    <span class="text-2xl font-black text-slate-800 dark:text-white mt-2 tabular-nums">
                        {{ number_format($stats['records_breakdown']['poverty']) }}
                    </span>
                    <span class="text-[9px] text-slate-500 mt-1 font-semibold uppercase">Poverty Records</span>
                </div>
                <div class="premium-card p-4 flex flex-col justify-between border-l-4 border-emerald-500">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Domisili</span>
                    <span class="text-2xl font-black text-slate-800 dark:text-white mt-2 tabular-nums">
                        {{ number_format($stats['records_breakdown']['domicile']) }}
                    </span>
                    <span class="text-[9px] text-slate-500 mt-1 font-semibold uppercase">Domicile Records</span>
                </div>
                <div class="premium-card p-4 flex flex-col justify-between border-l-4 border-blue-500">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Pindah Datang</span>
                    <span class="text-2xl font-black text-slate-800 dark:text-white mt-2 tabular-nums">
                        {{ number_format($stats['records_breakdown']['move']) }}
                    </span>
                    <span class="text-[9px] text-slate-500 mt-1 font-semibold uppercase">Move Records</span>
                </div>
                <div class="premium-card p-4 flex flex-col justify-between border-l-4 border-rose-500">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Surat Kematian</span>
                    <span class="text-2xl font-black text-slate-800 dark:text-white mt-2 tabular-nums">
                        {{ number_format($stats['records_breakdown']['death']) }}
                    </span>
                    <span class="text-[9px] text-slate-500 mt-1 font-semibold uppercase">Death Records</span>
                </div>
            </div>
        </div>

        <!-- Content Grid (Village Breakdown & Recent Activity) -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Village Performance -->
            <div class="xl:col-span-2 space-y-6">
                <div class="flex items-center justify-between px-1">
                    <h3 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-wider">Performa Wilayah
                    </h3>
                    <a href="{{ route('citizens.index') }}"
                        class="text-[10px] font-black text-primary-acorn uppercase tracking-widest hover:opacity-80 transition">Lihat
                        Semua Registry</a>
                </div>

                <x-card padding="p-0">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-900 border-b border-black/[0.03] dark:border-white/[0.03]">
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama
                                    Desa</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kode
                                </th>
                                <th
                                    class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">
                                    Verifikasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/[0.03] dark:divide-white/[0.03]">
                            @foreach ($stats['village_breakdown'] as $village)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition group">
                                    <td class="px-6 py-4">
                                        <span
                                            class="text-xs font-bold text-slate-700 dark:text-slate-200 group-hover:text-primary-acorn transition uppercase tracking-tight">{{ $village->name }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">{{ $village->code }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span
                                            class="text-xs font-black text-slate-900 dark:text-white tabular-nums">{{ number_format($village->verifications_count) }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </x-card>
            </div>

            <!-- Quick Actions & Feed -->
            <div class="space-y-8">
                <!-- Data Command Card -->
                <div class="bg-slate-900 rounded-2xl p-8 text-white relative overflow-hidden group shadow-2xl">
                    <div class="absolute -right-8 -top-8 w-32 h-32 bg-primary-acorn/20 rounded-full blur-3xl"></div>

                    <div class="relative z-10 space-y-6">
                        <div>
                            <h3 class="text-lg font-black tracking-tight leading-none uppercase">Data Export</h3>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-[0.2em] mt-3">Pelayanan Dokumen
                                Engine</p>
                        </div>

                        <div class="grid grid-cols-1 gap-3">
                            <a href="{{ route('reports.citizens') }}"
                                class="flex items-center justify-between p-4 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl transition duration-300 group/btn">
                                <span class="text-[10px] font-black uppercase tracking-widest">Download Registry</span>
                                <iconify-icon icon="lucide:arrow-right"
                                    class="text-xl text-primary-acorn group-hover/btn:translate-x-1 transition"></iconify-icon>
                            </a>
                            <a href="{{ route('reports.audit') }}"
                                class="flex items-center justify-between p-4 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl transition duration-300 group/btn">
                                <span class="text-[10px] font-black uppercase tracking-widest">Audit Timeline</span>
                                <iconify-icon icon="lucide:arrow-right"
                                    class="text-xl text-primary-acorn group-hover/btn:translate-x-1 transition"></iconify-icon>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="space-y-6">
                    <h3 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-wider px-1">Aktivitas
                        Terkini</h3>
                    <div class="space-y-4">
                        @foreach ($stats['recent_requests'] as $req)
                            <div class="premium-card p-4 flex items-center gap-4 group">
                                <div
                                    class="h-10 w-10 rounded-lg bg-slate-50 dark:bg-slate-900 flex items-center justify-center text-[10px] font-black text-slate-400 group-hover:text-primary-acorn transition">
                                    {{ strtoupper(substr($req->service_type->value, 0, 2)) }}
                                </div>
                                <div class="flex-grow">
                                    <p
                                        class="text-[11px] font-black text-slate-700 dark:text-slate-200 truncate uppercase tracking-tight">
                                        {{ $req->citizen->name }}</p>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">
                                        {{ $req->service_type }}</p>
                                </div>
                                <span
                                    class="text-[8px] font-black text-slate-300 uppercase whitespace-nowrap">{{ $req->created_at->diffForHumans(null, true) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#94a3b8' : '#64748b';
            const borderColor = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';

            // 1. Line Chart: Tren Pelayanan
            const trendOptions = {
                chart: {
                    type: 'area',
                    height: 280,
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    },
                    fontFamily: 'Mulish, sans-serif'
                },
                series: [{
                    name: 'Jumlah Permohonan',
                    data: {!! json_encode(array_values($stats['monthly_trend'])) !!}
                }],
                xaxis: {
                    categories: {!! json_encode(array_keys($stats['monthly_trend'])) !!},
                    labels: {
                        style: {
                            colors: textColor
                        }
                    },
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: textColor
                        }
                    }
                },
                grid: {
                    borderColor: borderColor,
                    strokeDashArray: 4
                },
                colors: ['#33ac1b'],
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.45,
                        opacityTo: 0.05,
                        stops: [0, 100]
                    }
                },
                dataLabels: {
                    enabled: false
                },
                tooltip: {
                    theme: isDark ? 'dark' : 'light'
                }
            };

            const trendChart = new ApexCharts(document.querySelector("#trendChart"), trendOptions);
            trendChart.render();

            // 2. Donut Chart: Komposisi Layanan
            const typeOptions = {
                chart: {
                    type: 'donut',
                    height: 280,
                    fontFamily: 'Mulish, sans-serif'
                },
                series: {!! json_encode(array_values($stats['service_type_counts'])) !!},
                labels: {!! json_encode(array_keys($stats['service_type_counts'])) !!},
                colors: ['#33ac1b', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6'],
                legend: {
                    position: 'bottom',
                    labels: {
                        colors: textColor
                    }
                },
                dataLabels: {
                    enabled: false
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '75%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'TOTAL',
                                    formatter: function(w) {
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                    },
                                    style: {
                                        fontSize: '12px',
                                        fontWeight: '900',
                                        color: isDark ? '#ffffff' : '#1e293b'
                                    }
                                }
                            }
                        }
                    }
                },
                tooltip: {
                    theme: isDark ? 'dark' : 'light'
                }
            };

            const typeChart = new ApexCharts(document.querySelector("#typeChart"), typeOptions);
            typeChart.render();
        });
    </script>
@endpush
