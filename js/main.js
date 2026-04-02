$(document).ready(function () {

  // ── Hamburger toggle ──
  $('#menu-btn').click(function () {
      $(this).toggleClass('fa-times');
      $('.navbar').toggleClass('active');
  });

  // ── Close navbar on scroll ──
  $(window).on('scroll', function () {
      $('#menu-btn').removeClass('fa-times');
      $('.navbar').removeClass('active');

      if ($(window).scrollTop() > 60) {
          $('header').addClass('header-active');
      } else {
          $('header').removeClass('header-active');
      }
  });

  // ── Mobile: Services dropdown toggle on click ──
  $('.navbar .dropdown > a').on('click', function (e) {
      if ($(window).width() <= 768) {
          e.preventDefault();
          $(this).closest('.dropdown').toggleClass('active');
      }
  });

  // ── Mobile: submenu toggle on click ──
  $('.navbar .dropdown-submenu > a').on('click', function (e) {
      if ($(window).width() <= 768) {
          e.preventDefault();
          $(this).closest('.dropdown-submenu').toggleClass('active');
      }
  });

});

// ── Smooth scroll for anchor links ──
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
          target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
  });
});

// ── Detect page background → set navbar text color ──
function detectPageBackground() {
  const header = document.querySelector('header');
  if (!header) return;

  const bg = window.getComputedStyle(document.body).backgroundColor;

  const lightBgs = [
      'rgb(255, 255, 255)',
      'rgb(245, 246, 248)',
      'rgb(244, 247, 248)',
      'rgb(244, 244, 244)',
      'rgb(249, 249, 249)',
      'rgb(242, 243, 245)',
      'rgb(242, 242, 242)',
  ];

  if (lightBgs.includes(bg)) {
      header.classList.add('header-light');
  } else {
      header.classList.remove('header-light');
  }
}

window.addEventListener('DOMContentLoaded', detectPageBackground);

// ── Gallery: pause scroll on hover ──
const gallery = document.querySelector('.gallery-images');
if (gallery) {
  gallery.addEventListener('mouseover', () => {
      gallery.style.animationPlayState = 'paused';
  });
  gallery.addEventListener('mouseleave', () => {
      gallery.style.animationPlayState = 'running';
  });
}

// ── Blog boxes: fade in on scroll ──
const boxes = document.querySelectorAll('.blog .box-container .box');
if (boxes.length) {
  const observer = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
          if (entry.isIntersecting) {
              entry.target.classList.add('visible');
              observer.unobserve(entry.target);
          }
      });
  }, { threshold: 0.3 });

  boxes.forEach(box => observer.observe(box));
}





(function() {
    const track  = document.getElementById('reviewTrack');
    const dotsEl = document.getElementById('reviewDots');
    if (!track) return;

    const boxes      = track.querySelectorAll('.box');
    const total      = boxes.length;
    let   current    = 0;
    let   perPage    = getPerPage();

    function getPerPage() {
        if (window.innerWidth <= 576)  return 1;
        if (window.innerWidth <= 991)  return 2;
        return 4;
    }

    function totalPages() { return Math.ceil(total / perPage); }

    // Build dots
    function buildDots() {
        dotsEl.innerHTML = '';
        for (let i = 0; i < totalPages(); i++) {
            const dot = document.createElement('span');
            if (i === 0) dot.classList.add('active');
            dot.addEventListener('click', () => goTo(i));
            dotsEl.appendChild(dot);
        }
    }

    function updateDots() {
        dotsEl.querySelectorAll('span').forEach((d, i) => {
            d.classList.toggle('active', i === current);
        });
    }

    function goTo(page) {
        current = Math.max(0, Math.min(page, totalPages() - 1));
        const boxWidth  = boxes[0].offsetWidth + 20; // gap
        track.style.transform = `translateX(-${current * perPage * boxWidth}px)`;
        updateDots();
    }

    window.moveReview = function(dir) {
        goTo(current + dir);
    };

    window.addEventListener('resize', () => {
        perPage = getPerPage();
        current = 0;
        buildDots();
        goTo(0);
    });

    buildDots();
    goTo(0);
})();





// Get the button
document.addEventListener('DOMContentLoaded', function () {
    const scrollTopBtn = document.getElementById('scrollTopBtn');

    if (scrollTopBtn) {
      window.addEventListener('scroll', function () {
        if (window.scrollY > 300) {
          scrollTopBtn.style.display = 'flex';
        } else {
          scrollTopBtn.style.display = 'none';
        }
      });

      scrollTopBtn.addEventListener('click', function () {
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });
    }
  });