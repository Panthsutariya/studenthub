(function () {
  const siteData = window.SiteUIData || { faqItems: [], slides: [] };

  const styles = `
    :root {
      --ui-bg: #f4f7fb;
      --ui-card: #ffffff;
      --ui-text: #1e293b;
      --ui-muted: #475569;
      --ui-primary: #2563eb;
      --ui-primary-dark: #1d4ed8;
      --ui-accent: #e2e8f0;
      --ui-border: rgba(148, 163, 184, 0.35);
      --ui-shadow: 0 12px 28px rgba(15, 23, 42, 0.08);
    } 

    body[data-theme="dark"] {
      --ui-bg: #0f172a;
      --ui-card: #111827;
      --ui-text: #e2e8f0;
      --ui-muted: #cbd5e1;
      --ui-primary: #60a5fa;
      --ui-primary-dark: #3b82f6;
      --ui-accent: #1e293b;
      --ui-border: rgba(148, 163, 184, 0.2);
      --ui-shadow: 0 12px 28px rgba(2, 6, 23, 0.45);
    }

    body {
      background: var(--ui-bg);
      color: var(--ui-text);
      transition: background 0.25s ease, color 0.25s ease;
    }

    .ui-theme-toggle,
    .ui-hamburger,
    .ui-close-btn,
    .ui-banner-close,
    .ui-slider-btn,
    .ui-modal-close,
    .ui-faq-question {
      border: none;
      cursor: pointer;
      font: inherit;
    }

    .ui-theme-toggle,
    .ui-hamburger {
      background: var(--ui-card);
      color: var(--ui-text);
      border: 1px solid var(--ui-border);
      border-radius: 999px;
      padding: 0.7rem 1rem;
      box-shadow: var(--ui-shadow);
    }

    .ui-header-actions {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      margin-left: auto;
    }

    .ui-banner {
      position: sticky;
      top: 0;
      z-index: 30;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
      background: linear-gradient(135deg, var(--ui-primary), var(--ui-primary-dark));
      color: #fff;
      padding: 0.9rem 1.2rem;
      box-shadow: var(--ui-shadow);
      font-weight: 600;
    }

    .ui-banner-close {
      background: rgba(255,255,255,0.15);
      color: #fff;
      border-radius: 50%;
      width: 2rem;
      height: 2rem;
    }

    .ui-mobile-nav {
      display: none;
      flex-direction: column;
      gap: 0.6rem;
      padding: 1rem;
      background: var(--ui-card);
      border-bottom: 1px solid var(--ui-border);
    }

    .ui-mobile-nav a,
    .ui-faq-item {
      color: var(--ui-text);
      text-decoration: none;
    }

    .ui-mobile-nav a {
      display: block;
      padding: 0.75rem 1rem;
      border-radius: 12px;
      background: var(--ui-accent);
    }

    .ui-mobile-nav.is-open {
      display: flex;
    }

    .ui-slider {
      position: relative;
      overflow: hidden;
      border-radius: 18px;
      border: 1px solid var(--ui-border);
      box-shadow: var(--ui-shadow);
      background: var(--ui-card);
      margin: 1.5rem 0;
    }

    .ui-slider-track {
      display: flex;
      transition: transform 0.5s ease;
    }

    .ui-slide {
      min-width: 100%;
      height: 360px;
      display: flex;
      align-items: end;
      padding: 2rem;
      background-size: cover;
      background-position: center;
      position: relative;
    }

    .ui-slide::before {
      content: "";
      position: absolute;
      inset: 0;
      background: linear-gradient(180deg, rgba(15,23,42,0.1), rgba(15,23,42,0.75));
    }

    .ui-slide-content {
      position: relative;
      z-index: 1;
      max-width: 460px;
      color: #fff;
    }

    .ui-slide-content h3 {
      margin: 0 0 0.5rem;
      font-size: clamp(1.8rem, 2vw, 2.5rem);
    }

    .ui-slide-content p {
      margin: 0;
      font-size: 1rem;
      line-height: 1.6;
    }

    .ui-slider-controls {
      position: absolute;
      inset: auto 1rem 1rem 1rem;
      z-index: 2;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
    }

    .ui-slider-nav {
      display: flex;
      gap: 0.7rem;
    }

    .ui-slider-btn {
      width: 2.5rem;
      height: 2.5rem;
      border-radius: 50%;
      background: rgba(255,255,255,0.18);
      color: #fff;
      backdrop-filter: blur(8px);
    }

    .ui-slider-dots {
      display: flex;
      gap: 0.45rem;
      align-items: center;
      justify-content: center;
      flex-wrap: wrap;
    }

    .ui-slider-dot {
      width: 0.7rem;
      height: 0.7rem;
      border-radius: 50%;
      background: rgba(255,255,255,0.45);
      border: none;
      cursor: pointer;
      padding: 0;
    }

    .ui-slider-dot.is-active {
      background: #fff;
      transform: scale(1.2);
    }

    .ui-faq {
      display: grid;
      gap: 0.9rem;
      margin: 2rem 0;
    }

    .ui-faq-item {
      background: var(--ui-card);
      border: 1px solid var(--ui-border);
      border-radius: 14px;
      box-shadow: var(--ui-shadow);
      overflow: hidden;
    }

    .ui-faq-question {
      width: 100%;
      text-align: left;
      background: transparent;
      color: var(--ui-text);
      padding: 1rem 1.2rem;
      font-weight: 600;
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 1rem;
    }

    .ui-faq-answer {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.25s ease;
      color: var(--ui-muted);
      padding: 0 1.2rem;
      line-height: 1.6;
    }

    .ui-faq-item.is-open .ui-faq-answer {
      max-height: 200px;
      padding: 0 1.2rem 1rem;
    }

    .ui-faq-toggle {
      font-size: 1.3rem;
      transition: transform 0.25s ease;
    }

    .ui-faq-item.is-open .ui-faq-toggle {
      transform: rotate(45deg);
    }

    .ui-modal {
      position: fixed;
      inset: 0;
      display: none;
      align-items: center;
      justify-content: center;
      background: rgba(15, 23, 42, 0.52);
      z-index: 60;
      padding: 1rem;
    }

    .ui-modal.is-open {
      display: flex;
    }

    .ui-modal-card {
      width: min(540px, 100%);
      background: var(--ui-card);
      border: 1px solid var(--ui-border);
      border-radius: 18px;
      box-shadow: var(--ui-shadow);
      padding: 1.4rem;
      position: relative;
    }

    .ui-modal-card h3 {
      margin-top: 0;
      font-size: 1.5rem;
    }

    .ui-modal-card p {
      color: var(--ui-muted);
      line-height: 1.7;
    }

    .ui-modal-close {
      position: absolute;
      top: 0.8rem;
      right: 0.8rem;
      background: var(--ui-accent);
      color: var(--ui-text);
      border-radius: 50%;
      width: 2rem;
      height: 2rem;
    }

    @media (max-width: 720px) {
      .ui-header-actions {
        margin-left: 0;
      }

      .ui-hamburger {
        display: inline-flex;
      }

      nav {
        display: none;
      }

      .ui-mobile-nav {
        display: none;
      }
    }
  `;

  function ensureStyles() {
    if (document.getElementById('ui-components-styles')) {
      return;
    }

    const styleTag = document.createElement('style');
    styleTag.id = 'ui-components-styles';
    styleTag.textContent = styles;
    document.head.appendChild(styleTag);
  }

  function setTheme(theme) {
    document.body.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);

    const toggleButton = document.querySelector('.ui-theme-toggle');
    if (toggleButton) {
      toggleButton.textContent = theme === 'dark' ? '☀️ Light' : '🌙 Dark';
    }
  }

  function createThemeToggle() {
    const existing = document.querySelector('.ui-theme-toggle');
    if (existing) {
      return existing;
    }

    const toggle = document.createElement('button');
    toggle.className = 'ui-theme-toggle';
    toggle.type = 'button';
    toggle.setAttribute('aria-label', 'Switch theme');
    toggle.textContent = '🌙 Dark';

    const headerActions = document.querySelector('header .ui-header-actions') || document.querySelector('header');
    if (headerActions && headerActions !== document.body) {
      if (headerActions.classList && !headerActions.classList.contains('ui-header-actions')) {
        const wrapper = document.createElement('div');
        wrapper.className = 'ui-header-actions';
        headerActions.appendChild(wrapper);
        wrapper.appendChild(toggle);
      } else {
        headerActions.appendChild(toggle);
      }
    } else {
      document.body.insertBefore(toggle, document.body.firstChild);
    }

    return toggle;
  }

  function createNotificationBanner() {
    const existing = document.querySelector('.ui-banner');
    if (existing) {
      return existing;
    }

    const banner = document.createElement('div');
    banner.className = 'ui-banner';
    banner.innerHTML = `
      <span>New announcements are now available for students.</span>
      <button class="ui-banner-close" type="button" aria-label="Close notification">×</button>
    `;

    document.body.insertBefore(banner, document.body.firstChild);
    return banner;
  }

  function createHamburgerMenu() {
    const nav = document.querySelector('nav');
    if (!nav) {
      return null;
    }

    const menuButton = document.querySelector('.ui-hamburger');
    if (menuButton) {
      return menuButton;
    }

    const button = document.createElement('button');
    button.className = 'ui-hamburger';
    button.type = 'button';
    button.textContent = '☰';
    button.setAttribute('aria-label', 'Open menu');

    const header = document.querySelector('header');
    const actions = header && header.querySelector('.ui-header-actions');

    if (header && actions) {
      actions.appendChild(button);
    } else if (header) {
      header.appendChild(button);
    } else {
      document.body.insertBefore(button, document.body.firstChild);
    }

    const mobileNav = document.createElement('div');
    mobileNav.className = 'ui-mobile-nav';
    mobileNav.innerHTML = nav.innerHTML;

    if (nav.parentNode) {
      nav.parentNode.insertBefore(mobileNav, nav.nextSibling);
    }

    return button;
  }

  function buildFaq() {
    const faqHost = document.getElementById('faq');
    if (!faqHost || !siteData.faqItems.length) {
      return;
    }

    faqHost.className = 'ui-faq';
    faqHost.innerHTML = siteData.faqItems.map((item, index) => `
      <div class="ui-faq-item ${index === 0 ? 'is-open' : ''}">
        <button class="ui-faq-question" type="button">
          <span>${item.question}</span>
          <span class="ui-faq-toggle">+</span>
        </button>
        <div class="ui-faq-answer">
          <p>${item.answer}</p>
        </div>
      </div>
    `).join('');
  }

  function buildSlider() {
    const sliderHost = document.getElementById('slider');
    if (!sliderHost || !siteData.slides.length) {
      return;
    }

    sliderHost.className = 'ui-slider';
    sliderHost.innerHTML = `
      <div class="ui-slider-track">
        ${siteData.slides.map((slide) => `
          <div class="ui-slide" style="background-image: url('${slide.image}')">
            <div class="ui-slide-content">
              <h3>${slide.title}</h3>
              <p>${slide.text}</p>
            </div>
          </div>
        `).join('')}
      </div>
      <div class="ui-slider-controls">
        <div class="ui-slider-nav">
          <button class="ui-slider-btn ui-slider-prev" type="button" aria-label="Previous slide">←</button>
          <button class="ui-slider-btn ui-slider-next" type="button" aria-label="Next slide">→</button>
        </div>
        <div class="ui-slider-dots">
          ${siteData.slides.map((_, index) => `
            <button class="ui-slider-dot ${index === 0 ? 'is-active' : ''}" type="button" aria-label="Show slide ${index + 1}" data-index="${index}"></button>
          `).join('')}
        </div>
      </div>
    `;

    let currentIndex = 0;
    const track = sliderHost.querySelector('.ui-slider-track');
    const dots = [...sliderHost.querySelectorAll('.ui-slider-dot')];

    function renderSlider(index) {
      currentIndex = (index + siteData.slides.length) % siteData.slides.length;
      track.style.transform = `translateX(-${currentIndex * 100}%)`;
      dots.forEach((dot, dotIndex) => dot.classList.toggle('is-active', dotIndex === currentIndex));
    }

    sliderHost.querySelector('.ui-slider-next').addEventListener('click', () => renderSlider(currentIndex + 1));
    sliderHost.querySelector('.ui-slider-prev').addEventListener('click', () => renderSlider(currentIndex - 1));
    dots.forEach((dot) => dot.addEventListener('click', () => renderSlider(Number(dot.dataset.index))));

    setInterval(() => renderSlider(currentIndex + 1), 5000);
  }

  function createModal() {
    const existing = document.getElementById('ui-modal');
    if (existing) {
      return existing;
    }

    const modal = document.createElement('div');
    modal.id = 'ui-modal';
    modal.className = 'ui-modal';
    modal.innerHTML = `
      <div class="ui-modal-card" role="dialog" aria-modal="true" aria-labelledby="ui-modal-title">
        <button class="ui-modal-close" type="button" aria-label="Close modal">×</button>
        <h3 id="ui-modal-title">Important Notice</h3>
        <p>Welcome to the student portal. Please review your attendance, timetable, and notifications regularly for updates from the academic office.</p>
      </div>
    `;

    document.body.appendChild(modal);
    return modal;
  }

  function bindEvents() {
    const savedTheme = localStorage.getItem('theme') || 'light';
    setTheme(savedTheme);

    const themeToggle = createThemeToggle();
    themeToggle.addEventListener('click', () => {
      const nextTheme = document.body.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
      setTheme(nextTheme);
    });

    const banner = createNotificationBanner();
    banner.querySelector('.ui-banner-close').addEventListener('click', () => banner.remove());

    const hamburger = createHamburgerMenu();
    if (hamburger) {
      const mobileNav = document.querySelector('.ui-mobile-nav');
      hamburger.addEventListener('click', () => {
        mobileNav.classList.toggle('is-open');
      });
    }

    document.querySelectorAll('.ui-faq-question').forEach((button) => {
      button.addEventListener('click', () => {
        const item = button.closest('.ui-faq-item');
        const isOpen = item.classList.contains('is-open');

        document.querySelectorAll('.ui-faq-item').forEach((faqItem) => {
          faqItem.classList.toggle('is-open', faqItem === item ? !isOpen : false);
        });
      });
    });

    const modalTrigger = document.getElementById('open-modal');
    const modal = createModal();
    const closeModal = modal.querySelector('.ui-modal-close');

    if (modalTrigger) {
      modalTrigger.addEventListener('click', () => modal.classList.add('is-open'));
    }

    closeModal.addEventListener('click', () => modal.classList.remove('is-open'));
    modal.addEventListener('click', (event) => {
      if (event.target === modal) {
        modal.classList.remove('is-open');
      }
    });

    const openModalOnLoad = document.getElementById('auto-open-modal');
    if (openModalOnLoad) {
      setTimeout(() => modal.classList.add('is-open'), 600);
    }
  }

  function init() {
    ensureStyles();
    buildFaq();
    buildSlider();
    bindEvents();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
function validateForm() {

    let name = document.querySelector('input[name="name"]').value.trim();
    let id = document.querySelector('input[name="id"]').value.trim();
    let email = document.querySelector('input[name="email"]').value.trim();
    let mobile = document.querySelector('input[name="mobile"]').value.trim();

    let password = document.getElementById("password").value;
    let confirmPassword = document.getElementById("confirmPassword").value;

    let nameRegex = /^[a-zA-Z ]{2,30}$/;
    let idRegex = /^[A-Z0-9]{6,12}$/;
    let emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    let mobileRegex = /^[6-9][0-9]{9}$/;
    let passwordRegex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[@#$%^&*!]).{8,}$/;

    if (!nameRegex.test(name)) {
        alert("Enter a valid name");
        return false;
    }

    if (!idRegex.test(id)) {
        alert("Enter a valid Student ID");
        return false;
    }

    if (!emailRegex.test(email)) {
        alert("Enter a valid email");
        return false;
    }

    if (!mobileRegex.test(mobile)) {
        alert("Enter a valid 10-digit mobile number");
        return false;
    }

    if (!passwordRegex.test(password)) {
        alert("Password must contain uppercase, lowercase, number, special character and 8 characters");
        return false;
    }

    if (password !== confirmPassword) {
        alert("Passwords do not match");
        return false;
    }

    let gender = document.querySelector('input[name="gender"]:checked');

    if (!gender) {
        alert("Please select gender");
        return false;
    }

    let courses = document.querySelectorAll('input[name="course"]:checked');

    if (courses.length === 0) {
        alert("Please select at least one course");
        return false;
    }

    if (!document.getElementById("terms").checked) {
        alert("Please accept the terms and conditions");
        return false;
    }

    alert("Registration successful!");
    return true;
}