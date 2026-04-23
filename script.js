function scrollToSection(id) {
  const element = document.getElementById(id);
  const navbar = document.getElementById("navbar");

  if (element && navbar) {
    const navbarHeight = navbar.offsetHeight;

    const elementPosition = element.getBoundingClientRect().top;
    const offsetPosition = elementPosition + window.pageYOffset - navbarHeight;

    window.scrollTo({
      top: offsetPosition,
      behavior: "smooth",
    });
  }
}

function toggleMenu() {
  const menu = document.getElementById("mobile-menu");
  const icon = document.getElementById("hamburger-icon");
  menu.classList.toggle("hidden");
  icon.classList.toggle("ri-menu-line");
  icon.classList.toggle("ri-close-line");
}

function closeMenu() {
  const menu = document.getElementById("mobile-menu");
  const icon = document.getElementById("hamburger-icon");
  menu.classList.add("hidden");
  icon.classList.add("ri-menu-line");
  icon.classList.remove("ri-close-line");
}

// ===== LOGIN MODAL =====
function openLogin() {
  const modal = document.getElementById("login-modal");
  modal.classList.remove("hidden");
  modal.classList.add("flex");
  document.body.style.overflow = "hidden";
}

function closeLogin() {
  const modal = document.getElementById("login-modal");
  modal.classList.add("hidden");
  modal.classList.remove("flex");
  document.body.style.overflow = "";
}

// ===== REGISTER MODAL =====
function openRegister() {
  const modal = document.getElementById("register-modal");
  modal.classList.remove("hidden");
  modal.classList.add("flex");
  document.body.style.overflow = "hidden";
}

function closeRegister() {
  const modal = document.getElementById("register-modal");
  modal.classList.add("hidden");
  modal.classList.remove("flex");
  document.body.style.overflow = "";
}

// ===== SWITCH MODALS =====
function switchToRegister() {
  closeLogin();
  openRegister();
}

function switchToLogin() {
  closeRegister();
  openLogin();
}

// ===== CLOSE LOGIN WHEN CLICK OUTSIDE =====
document.addEventListener("click", function (event) {
  const modal = document.getElementById("login-modal");
  if (event.target === modal) {
    closeLogin();
  }
});

// ===== ESC KEY CLOSE =====
document.addEventListener("keydown", function (event) {
  if (event.key === "Escape") {
    closeLogin();
    closeRegister();
  }
});

const faders = document.querySelectorAll(".fade-up");

const appearOnScroll = new IntersectionObserver(
  function (entries, observer) {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;

      entry.target.classList.add("show");
      observer.unobserve(entry.target);
    });
  },
  {
    threshold: 0.2,
  },
);

faders.forEach((fader) => {
  appearOnScroll.observe(fader);
});
