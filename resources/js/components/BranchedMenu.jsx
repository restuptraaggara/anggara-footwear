import React, { useState } from 'react';
import { motion, AnimatePresence } from 'motion/react';
import './BranchedMenu.css';

export default function BranchedMenu({
  categories = [
    { id: '', name: 'Semua Koleksi', slug: '', count: 8, icon: '🌟' },
    { id: 'men', name: 'Men Shoes', slug: 'men', count: 3, icon: '👞' },
    { id: 'women', name: 'Women Shoes', slug: 'women', count: 3, icon: '👠' },
    { id: 'accessories', name: 'Aksesoris Ramah Lingkungan', slug: 'accessories', count: 2, icon: '🧦' }
  ],
  activeSlug = '',
  onSelect = (slug) => {},
}) {
  const [selected, setSelected] = useState(activeSlug);

  const handleSelect = (slug) => {
    setSelected(slug);
    if (onSelect) onSelect(slug);
    // Trigger global event if listeners exist
    window.dispatchEvent(new CustomEvent('category-filter-selected', { detail: { slug } }));
  };

  return (
    <div className="branched-menu-wrapper shadow-sm">
      <div className="text-[10px] font-bold uppercase tracking-widest text-[#9E8560] px-3 py-1 flex items-center gap-1">
        <span>🌿</span>
        <span>Filter Kategori:</span>
      </div>

      <div className="flex flex-wrap items-center gap-2">
        {categories.map((cat) => {
          const isActive = selected === cat.slug;
          return (
            <motion.button
              key={cat.slug || 'all'}
              type="button"
              whileHover={{ scale: 1.03 }}
              whileTap={{ scale: 0.97 }}
              onClick={() => handleSelect(cat.slug)}
              className={`branched-item ${isActive ? 'active' : ''}`}
            >
              <span>{cat.icon || '👟'}</span>
              <span>{cat.name}</span>
              {cat.count !== undefined && (
                <span className={`text-[10px] px-1.5 py-0.5 rounded-full ${isActive ? 'bg-white/20 text-white' : 'bg-black/5 text-[#806A49]'}`}>
                  {cat.count}
                </span>
              )}
            </motion.button>
          );
        })}
      </div>
    </div>
  );
}
