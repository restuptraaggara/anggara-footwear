import React, { useState, useEffect, useRef } from 'react';
import { motion, useScroll, useTransform } from 'motion/react';
import './ScrollExpand.css';

export default function ScrollExpand({
  title = "Signature Anggara Collection",
  subtitle = "Kenyamanan Maksimal Dari Serat Alam & Teknologi Tebu SweetFoam®",
  imageUrl = "https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=1600&q=85",
  ctaText = "Jelajahi Koleksi",
  ctaLink = "/products",
}) {
  const containerRef = useRef(null);
  const { scrollYProgress } = useScroll({
    target: containerRef,
    offset: ["start end", "end start"]
  });

  // Transform scale and border-radius as user scrolls into view
  const scale = useTransform(scrollYProgress, [0, 0.5, 1], [0.88, 1, 0.95]);
  const borderRadius = useTransform(scrollYProgress, [0, 0.5, 1], ["2.5rem", "1.5rem", "2rem"]);

  return (
    <section ref={containerRef} className="scroll-expand-section max-w-7xl mx-auto px-6 lg:px-8">
      <motion.div 
        style={{ scale, borderRadius }}
        className="scroll-expand-card relative aspect-[16/9] md:aspect-[21/9] w-full overflow-hidden bg-gray-900"
      >
        {/* Background Image */}
        <img 
          src={imageUrl} 
          alt={title}
          className="w-full h-full object-cover object-center opacity-80"
        />

        {/* Gradient Overlay */}
        <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent flex flex-col justify-end p-8 md:p-16">
          <div className="max-w-2xl space-y-3">
            <span className="inline-block text-[11px] tracking-widest uppercase font-bold text-amber-400 bg-black/40 backdrop-blur-md px-3 py-1 rounded-full border border-amber-400/30">
              🌿 Koleksi Pilihan Anggara Footwear
            </span>
            <h2 className="text-2xl sm:text-4xl lg:text-5xl font-light text-white tracking-tight leading-tight">
              {title}
            </h2>
            <p className="text-xs sm:text-sm text-gray-300 font-light leading-relaxed max-w-xl">
              {subtitle}
            </p>
            <div className="pt-2">
              <a
                href={ctaLink}
                className="inline-block px-8 py-3.5 bg-white hover:bg-[#FAF8F5] text-gray-900 rounded-xl text-xs font-semibold uppercase tracking-widest transition-all duration-200 shadow-lg hover:scale-105"
              >
                {ctaText} →
              </a>
            </div>
          </div>
        </div>
      </motion.div>
    </section>
  );
}
