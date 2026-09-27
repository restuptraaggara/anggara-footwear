import React, { useState, useRef, useEffect } from 'react';
import './HoldButton.css';

export default function HoldButton({
  label = 'Tahan untuk Memesan',
  completedLabel = '✓ Berhasil Dikonfirmasi!',
  holdTime = 1200, // ms
  variant = 'primary', // 'primary' | 'danger'
  onComplete,
  formId,
  className = '',
}) {
  const [progress, setProgress] = useState(0);
  const [isHolding, setIsHolding] = useState(false);
  const [isCompleted, setIsCompleted] = useState(false);

  const startTimeRef = useRef(null);
  const animationFrameRef = useRef(null);

  const startHold = (e) => {
    if (isCompleted) return;
    setIsHolding(true);
    startTimeRef.current = performance.now();

    const step = (time) => {
      const elapsed = time - startTimeRef.current;
      const pct = Math.min(100, (elapsed / holdTime) * 100);
      setProgress(pct);

      if (pct < 100) {
        animationFrameRef.current = requestAnimationFrame(step);
      } else {
        setIsCompleted(true);
        setIsHolding(false);
        if (onComplete) onComplete();
        if (formId) {
          const form = document.getElementById(formId);
          if (form) form.submit();
        }
      }
    };

    animationFrameRef.current = requestAnimationFrame(step);
  };

  const stopHold = () => {
    if (isCompleted) return;
    setIsHolding(false);
    cancelAnimationFrame(animationFrameRef.current);
    setProgress(0);
  };

  useEffect(() => {
    return () => cancelAnimationFrame(animationFrameRef.current);
  }, []);

  return (
    <div className={`hold-button-container ${className}`}>
      <button
        type="button"
        onMouseDown={startHold}
        onMouseUp={stopHold}
        onMouseLeave={stopHold}
        onTouchStart={startHold}
        onTouchEnd={stopHold}
        className={`hold-button ${variant === 'danger' ? 'danger' : ''}`}
      >
        {/* Progress Fill Layer */}
        <div 
          className="hold-progress-fill" 
          style={{ width: `${progress}%` }}
        />

        <div className="relative z-10 flex items-center justify-center gap-2">
          {isCompleted ? (
            <span>{completedLabel}</span>
          ) : (
            <>
              <span>{isHolding ? 'Menahan... ' + Math.round(progress) + '%' : label}</span>
              {!isHolding && <span className="text-[10px] opacity-70">(Tekan & Tahan)</span>}
            </>
          )}
        </div>
      </button>
    </div>
  );
}
