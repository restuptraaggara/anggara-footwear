import React from 'react';
import { createRoot } from 'react-dom/client';

// Import all 6 React Bits components
import Aurora from './components/Aurora';
import FlipCard from './components/FlipCard';
import BranchedMenu from './components/BranchedMenu';
import HoldButton from './components/HoldButton';
import ScrollExpand from './components/ScrollExpand';
import AccordionGallery from './components/AccordionGallery';

document.addEventListener('DOMContentLoaded', () => {
  // 1. Mount Global Aurora WebGL Background
  const auroraEl = document.getElementById('aurora-background');
  if (auroraEl) {
    const colorStops = auroraEl.dataset.colors 
      ? JSON.parse(auroraEl.dataset.colors) 
      : ['#0f291e', '#1b4332', '#1e293b'];
    const blend = parseFloat(auroraEl.dataset.blend || '0.6');
    const amplitude = parseFloat(auroraEl.dataset.amplitude || '1.2');
    const speed = parseFloat(auroraEl.dataset.speed || '0.4');

    createRoot(auroraEl).render(
      <Aurora colorStops={colorStops} blend={blend} amplitude={amplitude} speed={speed} />
    );
  }

  // 2. Mount ScrollExpand Showcase Island
  const scrollExpandEl = document.getElementById('hero-scroll-expand');
  if (scrollExpandEl) {
    const title = scrollExpandEl.dataset.title || "Signature Anggara Collection";
    const subtitle = scrollExpandEl.dataset.subtitle || "Dibuat dari wol merino ZQ dan serat eucalyptus alami dengan kenyamanan ekstra.";
    const imageUrl = scrollExpandEl.dataset.image || "https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=1600&q=85";
    const ctaText = scrollExpandEl.dataset.ctaText || "Jelajahi Koleksi";
    const ctaLink = scrollExpandEl.dataset.ctaLink || "/products";

    createRoot(scrollExpandEl).render(
      <ScrollExpand 
        title={title} 
        subtitle={subtitle} 
        imageUrl={imageUrl} 
        ctaText={ctaText} 
        ctaLink={ctaLink} 
      />
    );
  }

  // 3. Mount BranchedMenu Category Filter Island
  const branchMenuEl = document.getElementById('category-branched-menu');
  if (branchMenuEl) {
    const categories = branchMenuEl.dataset.categories 
      ? JSON.parse(branchMenuEl.dataset.categories) 
      : undefined;
    const activeSlug = branchMenuEl.dataset.activeSlug || '';

    createRoot(branchMenuEl).render(
      <BranchedMenu 
        categories={categories} 
        activeSlug={activeSlug} 
      />
    );
  }

  // 4. Mount AccordionGallery Showcase Island
  const galleryEl = document.getElementById('product-accordion-gallery');
  if (galleryEl) {
    const items = galleryEl.dataset.items 
      ? JSON.parse(galleryEl.dataset.items) 
      : undefined;

    createRoot(galleryEl).render(<AccordionGallery items={items} />);
  }

  // 5. Mount Multiple FlipCard Islands
  const flipCards = document.querySelectorAll('.react-flip-card');
  flipCards.forEach((el) => {
    try {
      const product = JSON.parse(el.dataset.product || '{}');
      createRoot(el).render(<FlipCard product={product} />);
    } catch (e) {
      console.warn("Failed to mount FlipCard:", e);
    }
  });

  // 6. Mount Multiple HoldButton Islands
  const holdButtons = document.querySelectorAll('.react-hold-button');
  holdButtons.forEach((el) => {
    const label = el.dataset.label || 'Tahan untuk Melanjutkan';
    const completedLabel = el.dataset.completedLabel || '✓ Berhasil!';
    const variant = el.dataset.variant || 'primary';
    const formId = el.dataset.formId || null;
    const holdTime = parseInt(el.dataset.holdTime || '1200', 10);
    const actionName = el.dataset.action || null;

    const onComplete = () => {
      if (actionName) {
        window.dispatchEvent(new CustomEvent('hold-button-completed', { detail: { action: actionName } }));
      }
    };

    createRoot(el).render(
      <HoldButton
        label={label}
        completedLabel={completedLabel}
        variant={variant}
        formId={formId}
        holdTime={holdTime}
        onComplete={onComplete}
      />
    );
  });
});
