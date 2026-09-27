import React, { useState, useEffect, useRef } from 'react';
import gsap from 'gsap';
import './AccordionGallery.css';

const DEFAULT_ITEMS = [
  {
    id: 1,
    title: "Tree Runner",
    subtitle: "Serat Eucalyptus Alami & Ringan",
    price: "Rp 1.650.000",
    image: "https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=1000&q=85",
    link: "/products/tree-runner",
  },
  {
    id: 2,
    title: "Wool Runner",
    subtitle: "100% ZQ Merino Wool Termoregulasi",
    price: "Rp 1.850.000",
    image: "https://images.unsplash.com/photo-1600269452121-4f2416e55c28?w=1000&q=85",
    link: "/products/wool-runner",
  },
  {
    id: 3,
    title: "Tree Dasher",
    subtitle: "Bantalan SweetFoam® Tebu Manis",
    price: "Rp 1.950.000",
    image: "https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=1000&q=85",
    link: "/products/tree-dasher",
  },
  {
    id: 4,
    title: "Tree Breezers",
    subtitle: "Slip-On Nyaman & Sejuk Harian",
    price: "Rp 1.450.000",
    image: "https://images.unsplash.com/photo-1604671801908-6f0c6a092c05?w=1000&q=85",
    link: "/products/tree-breezers",
  },
];

export default function AccordionGallery({ items = DEFAULT_ITEMS }) {
  const [activeIndex, setActiveIndex] = useState(0);
  const containerRef = useRef(null);

  const displayItems = items && items.length > 0 ? items : DEFAULT_ITEMS;

  useEffect(() => {
    if (containerRef.current) {
      gsap.fromTo(
        containerRef.current.children,
        { opacity: 0, y: 20 },
        { opacity: 1, y: 0, stagger: 0.1, duration: 0.6, ease: "power2.out" }
      );
    }
  }, []);

  return (
    <div className="w-full py-8">
      <div className="text-center mb-8">
        <p className="text-xs tracking-widest uppercase text-[#9E8560] mb-2 font-semibold">Galeri Interaktif</p>
        <h2 className="text-3xl font-light text-gray-900">Showcase Koleksi Anggara Footwear</h2>
      </div>

      <div ref={containerRef} className="accordion-gallery-container bg-[#FAF8F5] border border-[#EDE4D3] shadow-sm">
        {displayItems.map((item, idx) => {
          const isActive = activeIndex === idx;
          return (
            <div
              key={item.id || idx}
              onClick={() => setActiveIndex(idx)}
              onMouseEnter={() => setActiveIndex(idx)}
              className={`accordion-slide ${isActive ? 'active' : ''}`}
            >
              <img src={item.image} alt={item.title} />

              <div className="accordion-slide-overlay">
                <div className="text-white">
                  <p className="text-[10px] tracking-widest uppercase text-amber-300 font-bold mb-1">
                    {item.price || 'Anggara Footwear'}
                  </p>
                  <h3 className="text-lg sm:text-2xl font-semibold tracking-tight leading-snug">
                    {item.title}
                  </h3>
                  
                  {isActive && (
                    <div className="mt-2 space-y-3 transition-opacity duration-300">
                      <p className="text-xs text-gray-300 font-light max-w-xs line-clamp-2">
                        {item.subtitle}
                      </p>
                      <a
                        href={item.link || `/products/${item.slug || ''}`}
                        className="inline-block px-5 py-2 bg-white text-gray-900 rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-[#FAF8F5] transition-colors shadow"
                      >
                        Beli Sekarang →
                      </a>
                    </div>
                  )}
                </div>
              </div>
            </div>
          );
        })}
      </div>
    </div>
  );
}
