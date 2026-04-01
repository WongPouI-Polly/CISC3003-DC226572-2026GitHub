/* ============================================================
   nav.js — Hamburger menu toggle
   LW Creative Studio — CISC3003 Pair Assignment 2026
   ============================================================ */

document.addEventListener('DOMContentLoaded', function () {
  const hamburger = document.querySelector('.hamburger');
  const navMenu   = document.querySelector('.nav-menu');

  if (hamburger && navMenu) {
    hamburger.addEventListener('click', function () {
      hamburger.classList.toggle('is-open');
      navMenu.classList.toggle('nav-open');
    });

    // Close menu when a link is clicked (mobile)
    navMenu.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', function () {
        hamburger.classList.remove('is-open');
        navMenu.classList.remove('nav-open');
      });
    });
  }

  // Highlight active nav link based on current page
  const currentPage = window.location.pathname.split('/').pop();
  document.querySelectorAll('.nav-menu a').forEach(function (link) {
    const href = link.getAttribute('href').split('/').pop();
    if (href === currentPage || (currentPage === '' && href === 'cisc3003-PairAssgn.html')) {
      link.classList.add('active');
    }
  });
});
