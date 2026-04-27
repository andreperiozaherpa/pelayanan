<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Sistem Verifikasi Pelayanan Dokumen') }} - Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --color-primary-acorn: #3498db;
            --color-secondary-acorn: #2980b9;
        }
        body {
            background-color: #f8fafc;
        }
        .premium-card {
            background: white;
            border: 1px solid rgba(0, 0, 0, 0.03);
            box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.05);
            border-radius: 2.5rem;
        }
    </style>
</head>

<body class="font-sans antialiased text-slate-800 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <!-- Logo/Header -->
        <div class="text-center mb-10">
            <div class="inline-flex w-16 h-16 bg-[#3498db] rounded-2xl items-center justify-center shadow-xl shadow-[#3498db]/20 mb-6">
                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04 inter M12 21.355r 0 0 0 0-1.618-3.041z" />
                </svg>
            </div>
            <h1 class="text-xl font-black text-slate-800 tracking-tight uppercase">Sistem Verifikasi<br>Pelayanan Dokumen</h1>
            <p class="text-[10px] text-slate-400 mt-3 font-black uppercase tracking-[0.2em]">Selamat Datang Kembali</p>
        </div>

        <div class="premium-card p-10 sm:p-12">
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div class="space-y-2">
                    <label for="email" class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Alamat Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-300 group-focus-within:text-[#3498db] transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                            </svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="block w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-black/[0.03] rounded-2xl text-sm font-bold placeholder-slate-300 focus:ring-4 focus:ring-[#3498db]/10 focus:border-[#3498db] outline-none transition uppercase tracking-wider"
                            placeholder="ADMIN@PELAYANAN.TEST">
                    </div>
                    @error('email')
                        <p class="text-rose-500 text-[10px] font-black uppercase tracking-widest mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="password" class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Kata Sandi</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-300 group-focus-within:text-[#3498db] transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input id="password" type="password" name="password" required
                            class="block w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-black/[0.03] rounded-2xl text-sm font-bold placeholder-slate-300 focus:ring-4 focus:ring-[#3498db]/10 focus:border-[#3498db] outline-none transition"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between px-1">
                    <label class="flex items-center group cursor-pointer">
                        <input type="checkbox" name="remember"
                            class="w-4 h-4 rounded border-black/[0.05] bg-slate-50 text-[#3498db] focus:ring-[#3498db]/20 transition">
                        <span class="ml-2 text-[10px] font-black text-slate-400 uppercase tracking-widest group-hover:text-slate-600 transition">Ingat saya</span>
                    </label>
                    <a href="#" class="text-[10px] font-black text-[#3498db] uppercase tracking-widest hover:opacity-80 transition">Lupa sandi?</a>
                </div>

                <button type="submit"
                    class="w-full py-4 bg-[#3498db] hover:bg-[#2980b9] text-white font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl transition transform hover:-translate-y-0.5 active:scale-95 shadow-xl shadow-[#3498db]/20">
                    Masuk ke Dashboard
                </button>
            </form>
        </div>

        <p class="text-center mt-10 text-[9px] font-bold text-slate-400 uppercase tracking-[0.3em]">
            &copy; {{ date('Y') }} SISTEM VERIFIKASI PELAYANAN DOKUMEN.
        </p>
    </div>

</body>

</html>
