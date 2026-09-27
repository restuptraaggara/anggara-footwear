import React, { useState } from 'react';
import { motion } from 'motion/react';
import './FlipCard.css';

export default function FlipCard({
  product = {},
  onAddToCart,
  onToggleWishlist,
  isWishlisted = false,
}) {
  const [isFlipped, setIsFlipped] = useState(false);
  const [selectedColorIndex, setSelectedColorIndex] = useState(0);

  const colors = product.colors || [];
  const activeImage = colors[selectedColorIndex]?.image_url || product.image_url;

  return (
    <div 
      className={`flip-card-wrapper ${isFlipped ? 'flipped' : ''}`}
      onMouseLeave={() => setIsFlipped(false)}
    >
      <div className="flip-card-inner">
        
        {/* FRONT FACE */}
        <div className="flip-card-face bg-white border border-[#EDE4D3] shadow-sm flex flex-col justify-between p-0">
          
          {/* Top Image Container */}
          <div className="relative aspect-square overflow-hidden bg-[#FAF8F5]">
            <a href={`/products/${product.slug}`} className="block w-full h-full">
              <img 
                src={activeImage} 
                alt={product.name}
                className="w-full h-full object-cover transition-transform duration-500 hover:scale-105"
                loading="lazy"
              />
            </a>

            {/* Quick Flip Button */}
            <button 
              type="button"
              onClick={(e) => { e.preventDefault(); e.stopPropagation(); setIsFlipped(true); }}
              className="absolute top-3 left-3 px-2.5 py-1 rounded-full bg-black/60 hover:bg-black backdrop-blur-md text-white text-[10px] tracking-wider uppercase font-semibold transition-all flex items-center gap-1 shadow-sm"
              title="Lihat Info Material & Ukuran"
            >
              <span>Info Detail</span>
              <svg className="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
              </svg>
            </button>

            {/* Wishlist Button */}
            <button
              type="button"
              onClick={(e) => {
                e.preventDefault();
                e.stopPropagation();
                if (window.dispatchEvent) {
                  window.dispatchEvent(new CustomEvent('toggle-wishlist-item', { detail: product }));
                }
              }}
              className="absolute top-3 right-3 p-2.5 rounded-full bg-white/90 hover:bg-white text-gray-400 hover:text-red-500 hover:scale-110 shadow-sm transition-all duration-200 z-10"
              title="Wishlist"
            >
              <svg className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
              </svg>
            </button>

            {product.is_featured && (
              <span className="absolute bottom-3 left-3 bg-[#4A3D2C]/90 backdrop-blur-sm text-white text-[10px] tracking-widest uppercase font-medium px-2.5 py-1 rounded-md">
                Featured
              </span>
            )}
          </div>

          {/* Product Info */}
          <div className="p-5 flex-1 flex flex-col justify-between">
            <div>
              <p className="text-[11px] tracking-wider uppercase text-[#9E8560] font-medium mb-1">
                {product.category || 'Footwear'}
              </p>
              <h3 className="text-sm font-semibold text-gray-900 mb-1">
                <a href={`/products/${product.slug}`} className="hover:text-[#806A49] transition-colors">
                  {product.name}
                </a>
              </h3>
              <p className="text-xs text-gray-500 font-light mb-3 line-clamp-1">{product.material_type}</p>

              {/* Interactive Color Swatches */}
              <div className="flex items-center gap-1.5 mb-2">
                {colors.map((c, idx) => (
                  <button
                    key={c.name || idx}
                    type="button"
                    onClick={() => setSelectedColorIndex(idx)}
                    style={{ backgroundColor: c.hex }}
                    title={c.name}
                    className={`w-5 h-5 rounded-full cursor-pointer transition-all border-2 ${selectedColorIndex === idx ? 'border-gray-900 scale-125' : 'border-transparent hover:scale-110'}`}
                  />
                ))}
              </div>
            </div>

            {/* Price and Details */}
            <div className="pt-3 border-t border-[#EDE4D3] flex items-center justify-between">
              <p className="text-sm font-semibold text-gray-900">{product.price_formatted}</p>
              <a 
                href={`/products/${product.slug}`}
                className="text-xs font-semibold text-[#806A49] hover:text-gray-900 uppercase tracking-wider underline underline-offset-4"
              >
                Lihat →
              </a>
            </div>
          </div>
        </div>

        {/* BACK FACE (Material & Sustainable Story Info) */}
        <div className="flip-card-face flip-card-back bg-[#FAF8F5] border border-[#DDD0B8] shadow-md p-6 flex flex-col justify-between text-left">
          <div>
            <div className="flex items-center justify-between pb-3 border-b border-[#EDE4D3]">
              <span className="text-[10px] tracking-widest uppercase font-bold text-[#806A49]">Bahan & Keberlanjutan</span>
              <button 
                type="button"
                onClick={() => setIsFlipped(false)}
                className="text-xs text-gray-400 hover:text-gray-900 font-semibold"
              >
                ✕ Balik
              </button>
            </div>

            <h4 className="text-base font-semibold text-gray-900 mt-4 mb-2">{product.name}</h4>
            <p className="text-xs text-gray-600 font-light leading-relaxed mb-4">
              {product.description || 'Dibuat dari bahan terbarukan dengan standar emisi karbon terendah untuk kenyamanan sehari-hari.'}
            </p>

            <div className="space-y-2.5 bg-white p-3.5 rounded-xl border border-[#EDE4D3] text-xs">
              <div className="flex items-center gap-2">
                <span>🌿</span>
                <span className="text-gray-700 font-medium">Material: {product.material_type}</span>
              </div>
              <div className="flex items-center gap-2">
                <span>🐑</span>
                <span className="text-gray-700 font-medium">Insole: ZQ Wool & Sugarcane</span>
              </div>
              <div className="flex items-center gap-2">
                <span>♻️</span>
                <span className="text-gray-700 font-medium">100% Karbon Netral</span>
              </div>
            </div>
          </div>

          <div className="pt-4 border-t border-[#EDE4D3] space-y-2">
            <a 
              href={`/products/${product.slug}`}
              className="block w-full py-3 bg-gray-900 hover:bg-[#4A3D2C] text-white text-center text-xs tracking-widest uppercase font-semibold rounded-xl transition-colors shadow-sm"
            >
              Beli Sekarang ({product.price_formatted})
            </a>
            <button 
              type="button"
              onClick={() => setIsFlipped(false)}
              className="block w-full text-center text-xs text-gray-500 hover:text-gray-900 font-medium"
            >
              ← Kembali ke Foto
            </button>
          </div>
        </div>

      </div>
    </div>
  );
}
