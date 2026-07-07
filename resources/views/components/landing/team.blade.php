{{-- Team Members Component --}}
{{-- $team: Collection<CmsTeam>|null --}}
@if ($team && $team->isNotEmpty())
    <section id="team" class="py-24 bg-transparent">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <p class="text-[#7b323b] font-bold text-xs uppercase tracking-widest mb-3">Profil Pejabat</p>
                <h2 class="text-3xl md:text-4xl font-black text-[#5d1e25]">Struktur Organisasi & Pimpinan</h2>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @foreach ($team as $member)
                    @php $socials = $member->social_links ?? []; @endphp
                    <div class="group text-center">
                        <div class="relative mx-auto w-32 h-32 mb-4">
                            <div
                                class="absolute inset-0 bg-[#5d1e25] rounded-full scale-110 group-hover:scale-125 transition-transform duration-300 opacity-10">
                            </div>
                            @if ($member->image)
                                <img src="{{ asset('storage/' . $member->image) }}" alt="{{ $member->name }}"
                                    class="w-32 h-32 rounded-full object-cover border-4 border-[#f4edd8] shadow-lg relative z-10"
                                    loading="lazy">
                            @else
                                <div
                                    class="w-32 h-32 rounded-full bg-white/40 backdrop-blur-md flex items-center justify-center relative z-10 border-4 border-[#f4edd8] shadow-lg">
                                    <iconify-icon icon="lucide:user" class="text-4xl text-[#5d1e25]/40"></iconify-icon>
                                </div>
                            @endif
                        </div>
                        <h4 class="font-bold text-[#5d1e25]">{{ $member->name }}</h4>
                        <p class="text-xs text-[#7b323b] font-bold uppercase tracking-wider mb-3">
                            {{ $member->position }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
