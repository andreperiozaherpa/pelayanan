<!-- HOUSEHOLD RESULT -->
<template x-if="resultType === 'HOUSEHOLD'">
    <div class="space-y-6">
        <!-- Household Info Card -->
        <div class="premium-card p-8">
            <div class="flex flex-col md:flex-row gap-8 items-center">
                <div
                    class="w-20 h-20 rounded-2xl bg-primary-acorn/10 text-primary-acorn flex items-center justify-center text-3xl">
                    <iconify-icon icon="lucide:home"></iconify-icon>
                </div>
                <div class="flex-grow text-center md:text-left">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">DATA
                        KARTU KELUARGA</p>
                    <h2 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight uppercase"
                        x-text="'NO. KK: ' + (result?.household?.no_kk || '-')"></h2>
                    <div class="mt-3 flex flex-wrap justify-center md:justify-start gap-6">
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                Kepala
                                Keluarga</p>
                            <p class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase"
                                x-text="result?.household?.head_name"></p>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                Alamat
                            </p>
                            <p class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase"
                                x-text="result?.household?.address"></p>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">RT/RW
                            </p>
                            <p class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase"
                                x-text="(result?.household?.rt || '-') + ' / ' + (result?.household?.rw || '-')"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Family Members List -->
        <div class="premium-card overflow-hidden">
            <div class="p-6 border-b border-black/[0.03] dark:border-white/[0.03]">
                <h3 class="text-[10px] font-black text-slate-800 dark:text-white uppercase tracking-widest">
                    Daftar Anggota Keluarga</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50 dark:bg-slate-900/50">
                            <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                NIK</th>
                            <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                Nama Lengkap</th>
                            <th
                                class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest text-center">
                                Status</th>
                            <th
                                class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest text-right">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/[0.03] dark:divide-white/[0.03]">
                        <template x-for="member in (result?.members || [])" :key="member.nik">
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.02] transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-xs font-bold text-slate-500" x-text="member.nik"></span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-black text-slate-800 dark:text-white uppercase"
                                        x-text="member.nama_lengkap"></span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex flex-col items-center gap-1.5">
                                        <span
                                            :class="{
                                                'bg-emerald-500': member.poverty_status === 'ACTIVE',
                                                'bg-rose-500': member.poverty_status === 'EXPIRED',
                                                'bg-amber-500': member.poverty_status === 'PENDING',
                                                'bg-rose-600': member.poverty_status === 'REJECTED',
                                                'bg-slate-500': member.poverty_status === 'UNREGISTERED'
                                            }"
                                            class="px-2 py-0.5 rounded text-[7px] font-black text-white uppercase tracking-tighter w-20 inline-block"
                                            x-text="'POV: ' + member.poverty_status">
                                        </span>
                                        <span
                                            :class="{
                                                'bg-blue-500': member.domicile_status === 'ACTIVE',
                                                'bg-rose-500': member.domicile_status === 'EXPIRED',
                                                'bg-amber-500': member.domicile_status === 'PENDING',
                                                'bg-rose-600': member.domicile_status === 'REJECTED',
                                                'bg-slate-500': member.domicile_status === 'UNREGISTERED'
                                            }"
                                            class="px-2 py-0.5 rounded text-[7px] font-black text-white uppercase tracking-tighter w-20 inline-block"
                                            x-text="'DOM: ' + member.domicile_status">
                                        </span>
                                        <span
                                            :class="{
                                                'bg-emerald-500': member.move_status === 'ACTIVE',
                                                'bg-rose-500': member.move_status === 'EXPIRED',
                                                'bg-amber-500': member.move_status === 'PENDING',
                                                'bg-rose-600': member.move_status === 'REJECTED',
                                                'bg-slate-500': member.move_status === 'UNREGISTERED'
                                            }"
                                            class="px-2 py-0.5 rounded text-[7px] font-black text-white uppercase tracking-tighter w-20 inline-block"
                                            x-text="'MOV: ' + member.move_status">
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button @click="nik = member.nik; verifyNik('NIK', false)"
                                        class="bg-emerald-600 hover:bg-emerald-500 dark:bg-emerald-500 dark:hover:bg-emerald-400 text-white px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest shadow-md hover:shadow-emerald-500/30 hover:-translate-y-0.5 transition-all active:scale-95 inline-flex items-center gap-2 ml-auto">
                                        Detail Individu
                                        <iconify-icon icon="lucide:arrow-right"
                                            class="text-base opacity-50"></iconify-icon>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
