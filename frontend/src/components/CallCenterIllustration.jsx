import React from 'react';

const CallCenterIllustration = ({ className = "w-full h-auto" }) => {
  return (
    <svg
      viewBox="0 0 500 500"
      fill="none"
      xmlns="http://www.w3.org/2000/svg"
      className={className}
    >
      {/* Background Soft Glow */}
      <circle cx="250" cy="250" r="180" fill="#FFE0B2" fillOpacity="0.4" />
      <circle cx="280" cy="200" r="100" fill="#FFCC80" fillOpacity="0.3" />

      {/* ===== FLOATING UI ELEMENTS (Flat Line Art) ===== */}

      {/* Sound waves – kiri */}
      <g stroke="#2C1810" strokeLinecap="round" fill="none">
        <path d="M130 200 C120 210 120 225 130 235" strokeWidth="2.5" />
        <path d="M115 190 C100 210 100 235 115 250" strokeWidth="2.5" />
        <path d="M100 180 C78 210 78 245 100 265" strokeWidth="2" opacity="0.5" />
      </g>

      {/* Ikon Mikrofon – kiri atas */}
      <g>
        <rect x="75" y="100" width="18" height="26" rx="9" fill="#FFF3E0" stroke="#2C1810" strokeWidth="2.5" />
        <path d="M84 126 L84 137" stroke="#2C1810" strokeWidth="2.5" strokeLinecap="round" />
        <path d="M74 137 L94 137" stroke="#2C1810" strokeWidth="2.5" strokeLinecap="round" />
        <path d="M66 117 Q60 127 66 137" fill="none" stroke="#FF7043" strokeWidth="2" strokeLinecap="round" />
        <path d="M58 110 Q48 127 58 145" fill="none" stroke="#FF7043" strokeWidth="2" strokeLinecap="round" />
      </g>

      {/* Balon Percakapan – kanan atas */}
      <g>
        <path d="M335 80 C335 65 395 65 395 80 C395 95 378 98 368 105 L356 95 C345 95 335 93 335 80 Z" fill="#FFF3E0" stroke="#2C1810" strokeWidth="2.5" strokeLinejoin="round" />
        <circle cx="354" cy="85" r="3" fill="#2C1810" />
        <circle cx="366" cy="85" r="3" fill="#2C1810" />
        <circle cx="378" cy="85" r="3" fill="#2C1810" />
      </g>

      {/* Incoming Call Badge – tengah atas */}
      <g>
        <circle cx="250" cy="48" r="22" fill="#FFE0B2" stroke="#2C1810" strokeWidth="2.5" />
        <path d="M242 42 C242 42 246 46 249 50 C252 54 256 56 256 56 L252 60 C249 57 246 54 243 50 C240 46 237 44 237 44 Z" fill="#2C1810" />
        <path d="M232 36 Q228 30 234 26" fill="none" stroke="#E65100" strokeWidth="2" strokeLinecap="round" />
        <path d="M268 36 Q272 30 266 26" fill="none" stroke="#E65100" strokeWidth="2" strokeLinecap="round" />
        <circle cx="226" cy="24" r="2" fill="#E65100" opacity="0.6" />
        <circle cx="274" cy="24" r="2" fill="#E65100" opacity="0.6" />
      </g>

      {/* Notifikasi badge – mengambang kanan */}
      <g>
        <circle cx="390" cy="65" r="9" fill="#FF8A65" stroke="#2C1810" strokeWidth="2" />
        <text x="390" y="69" textAnchor="middle" fill="#2C1810" fontSize="10" fontWeight="bold" fontFamily="sans-serif">3</text>
      </g>

      {/* Aksen kecil dekoratif */}
      <circle cx="108" cy="80" r="3" fill="#FF8A65" opacity="0.6" />
      <circle cx="412" cy="112" r="2" fill="#FF8A65" opacity="0.4" />
      <rect x="98" y="155" width="6" height="6" rx="1" fill="#FFE0B2" stroke="#2C1810" strokeWidth="1.5" opacity="0.5" />

      {/* ===== CHARACTER (Continuous Line Art) ===== */}
      <g stroke="#2C1810" strokeWidth="3.5" strokeLinecap="round" strokeLinejoin="round">
        
        {/* Rambut Belakang */}
        <path d="M190 200 C180 140 320 140 310 200" fill="#3E2723" fillOpacity="0.1" />

        {/* Kepala & Wajah */}
        <path d="M205 210 C205 280 295 280 295 210 C295 160 205 160 205 210 Z" fill="#FFF3E0" />
        
        {/* Senyum Warm Smile */}
        <path d="M235 240 Q250 255 265 240" fill="none" strokeWidth="3" />
        
        {/* Mata (Friendly Closed/Smiling Eyes) */}
        <path d="M225 215 Q232 208 240 215" fill="none" strokeWidth="3" />
        <path d="M260 215 Q268 208 276 215" fill="none" strokeWidth="3" />

        {/* Leher & Kerah */}
        <path d="M235 272 L235 300 L210 320" />
        <path d="M265 272 L265 300 L290 320" />
        
        {/* Pakaian / Badan (Baju Warm Orange Accent) */}
        <path 
          d="M170 420 C170 340 210 310 250 310 C290 310 330 340 330 420" 
          fill="#FF7043" 
          fillOpacity="0.2"
        />

        {/* Headset Band */}
        <path d="M198 210 C195 150 305 150 302 210" fill="none" strokeWidth="4" />
        
        {/* Headset Earpad Kiri */}
        <rect x="190" y="195" width="14" height="30" rx="7" fill="#2C1810" />
        
        {/* Headset Earpad Kanan & Microphone Boom */}
        <rect x="296" y="195" width="14" height="30" rx="7" fill="#2C1810" />
        <path d="M303 215 C305 250 280 255 268 255" fill="none" strokeWidth="3" />
        {/* Mic Tip */}
        <circle cx="264" cy="255" r="5" fill="#E65100" stroke="none" />

        {/* Tangan Memegang Headset / Menyapa */}
        <path d="M330 400 C320 340 315 270 305 230" fill="none" />
      </g>
    </svg>
  );
};

export default CallCenterIllustration;
