// Lightweight liveliness JS for hover/parallax interactions
// Respects prefers-reduced-motion

(function () {
  if (typeof window === 'undefined') return;

  const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (prefersReduced) return;

  document.addEventListener('DOMContentLoaded', () => {

    // Parallax: elements with .lively-parallax and child .parallax-inner
    document.querySelectorAll('.lively-parallax').forEach(container => {
      const inner = container.querySelector('.parallax-inner') || container;
      let rect = null;
      function onMove(e) {
        rect = rect || container.getBoundingClientRect();
        const x = (e.clientX ?? (e.touches && e.touches[0].clientX)) - (rect.left + rect.width / 2);
        const y = (e.clientY ?? (e.touches && e.touches[0].clientY)) - (rect.top + rect.height / 2);
        const rx = clamp((-y / (rect.height / 2)) * 6, -8, 8); // rotateX degrees
        const ry = clamp((x / (rect.width / 2)) * 6, -8, 8);   // rotateY degrees
        container.style.setProperty('--lp-rot-x', rx + 'deg');
        container.style.setProperty('--lp-rot-y', ry + 'deg');
        inner.style.transform = `rotateX(${rx}) rotateY(${ry})`;
      }
      function onLeave() {
        container.style.setProperty('--lp-rot-x', '0deg');
        container.style.setProperty('--lp-rot-y', '0deg');
        inner.style.transform = `rotateX(0deg) rotateY(0deg)`;
        rect = null;
      }
      container.addEventListener('mousemove', onMove);
      container.addEventListener('touchmove', onMove, { passive: true });
      container.addEventListener('mouseleave', onLeave);
      container.addEventListener('touchend', onLeave);
    });

    // Ensure lively-img elements react to touch/keyboard as well as mouse
    document.querySelectorAll('.lively-img').forEach(el => {
      // add toggle class for touch and keyboard focus
      el.addEventListener('touchstart', handleEnter, { passive: true });
      el.addEventListener('mouseenter', handleEnter);
      el.addEventListener('focus', handleEnter);
      el.addEventListener('mouseleave', handleLeave);
      el.addEventListener('touchend', handleLeave);
      el.addEventListener('blur', handleLeave);

      function handleEnter() {
        el.classList.add('is-hover');
      }
      function handleLeave() {
        el.classList.remove('is-hover');
      }
    });

    // Trigger wiggle on keyboard focus for elements with .lively-wiggle
    document.querySelectorAll('.lively-wiggle').forEach(el => {
      el.addEventListener('focus', () => triggerWiggle(el));
      // for touch devices, trigger when tapped
      el.addEventListener('touchstart', () => triggerWiggle(el), { passive: true });
    });

    function triggerWiggle(el) {
      // add wiggle-anim class and remove after animation ends
      el.classList.add('wiggle-anim');
      function cleanup() {
        el.classList.remove('wiggle-anim');
        el.removeEventListener('animationend', cleanup);
      }
      el.addEventListener('animationend', cleanup);
      // safety remove after 1s if animationend not fired
      setTimeout(() => el.classList.remove('wiggle-anim'), 1000);
    }

    // Utility
    function clamp(v, a, b) { return Math.max(a, Math.min(b, v)); }
  });
})();