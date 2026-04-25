<footer class="pt-10 pb-8 mt-20 border-t border-white/5 text-center">
    <div class="flex flex-col items-center gap-4">
        <div class="flex items-center gap-6 opacity-40">
            <span class="text-[9px] font-black text-slate-500 uppercase tracking-[0.3em]">
                &copy; {{ date('Y') }} {{ config('app.name') }}
            </span>
            <div class="h-3 w-px bg-white/10"></div>
            <div class="flex items-center gap-2">
                <span class="w-1 h-1 rounded-full bg-emerald-500"></span>
                <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">
                    v{{ config('app.version', '1.2.0') }}
                </span>
            </div>
            <div class="h-3 w-px bg-white/10"></div>
            <span class="text-[9px] font-medium text-slate-500 uppercase tracking-widest">Operational: <span class="text-emerald-500/80">Healthy</span></span>
        </div>

        <p class="text-[9px] text-slate-600 italic font-medium max-w-lg leading-relaxed opacity-60">
            "Securing Indonesian digital future through advanced poverty validation."
        </p>
    </div>
</footer>
