// animations.js

// Register GSAP plugin
gsap.registerPlugin(ScrollTrigger);

// Animate hero content
gsap.from(".hero .text", {
  opacity: 0,
  x: -100,
  duration: 1.2,
  ease: "power3.out"
});

gsap.from(".hero .bike-img", {
  opacity: 0,
  x: 100,
  duration: 1.2,
  delay: 0.3,
  ease: "power3.out"
});

// Animate all frames on scroll
gsap.utils.toArray(".frame").forEach((frame, i) => {
  gsap.from(frame, {
    scrollTrigger: {
      trigger: frame,
      start: "top 80%",
      toggleActions: "play none none reverse"
    },
    opacity: 0,
    y: 50,
    duration: 1,
    delay: i * 0.2,
    ease: "power2.out"
  });
});

// Animate category section frames
gsap.from(".categories-section .frame", {
  scrollTrigger: {
    trigger: ".categories-section",
    start: "top 80%",
    toggleActions: "play none none reset"
  },
  opacity: 0,
  y: 50,
  duration: 0.6,
  stagger: 0.15,
  ease: "power3.out"
});


 const track = document.querySelector(".testimonial-track");
  const cards = document.querySelectorAll(".testimonial-card");
  const cardsPerView = 4;

  // Clone first and last set of cards
  for (let i = 0; i < cardsPerView; i++) {
    const firstClone = cards[i].cloneNode(true);
    const lastClone = cards[cards.length - 1 - i].cloneNode(true);
    track.appendChild(firstClone);
    track.insertBefore(lastClone, track.firstChild);
  }

  let position = cardsPerView; // start from actual first card set
  const totalCards = document.querySelectorAll(".testimonial-card").length;
  const moveWidth = 100; // 100% shift per slide

  const setPosition = () => {
    track.style.transition = "none";
    track.style.transform = `translateX(-${position * (100 / 4)}%)`;
  };

  // Initial position
  setPosition();

  function slideTestimonials(dir) {
    if (track.style.transition === "none") {
      track.style.transition = "transform 0.5s ease-in-out";
    }

    position += dir;
    track.style.transform = `translateX(-${position * (100 / 4)}%)`;

    // After transition, loop logic
    track.addEventListener("transitionend", () => {
      if (position <= 0) {
        position = totalCards - (cardsPerView * 2);
        setPosition();
      } else if (position >= totalCards - cardsPerView) {
        position = cardsPerView;
        setPosition();
      }
    }, { once: true });
  }

 window.addEventListener("DOMContentLoaded", () => {
      const cards = document.querySelectorAll('.category-card');
      cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
      });
    });