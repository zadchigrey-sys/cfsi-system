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

function showTab(tab) {
  // hide all
  document.getElementById("students").classList.add("hidden");
  document.getElementById("billing").classList.add("hidden");

  // reset styles
  document
    .getElementById("tab-students")
    .classList.remove("border-blue-600", "text-blue-600");
  document
    .getElementById("tab-billing")
    .classList.remove("border-blue-600", "text-blue-600");

  // show selected
  document.getElementById(tab).classList.remove("hidden");

  // highlight tab
  document
    .getElementById("tab-" + tab)
    .classList.add("border-blue-600", "text-blue-600");
}

function showTab(tab) {
  document
    .getElementById("students")
    .classList.toggle("hidden", tab !== "students");
  document
    .getElementById("billing")
    .classList.toggle("hidden", tab !== "billing");

  // CHANGE FORM ACTION BASED ON TAB
  const form = document.getElementById("quickSearchForm");

  if (tab === "students") {
    form.action = "students.php";
  } else {
    form.action = "billing.php";
  }
}

function testClick() {
  alert("JS is working!");
}

function openAddModal() {
  const modal = document.getElementById("studentModal");
  modal.classList.remove("hidden");
  modal.classList.add("flex");

  // ✅ RESET FORM
  document.getElementById("studentForm").reset();

  // ✅ CLEAR HIDDEN ID (IMPORTANT)
  document.getElementById("student_id_hidden").value = "";

  // ✅ CHANGE TITLE + BUTTON
  document.getElementById("modalTitle").innerText = "Add Student";
  document.getElementById("submitBtn").innerText = "Save Student";

  // ✅ SET FORM ACTION
  document.getElementById("studentForm").action = "save_student.php";
}

function openEditModal(id, student_id, name, grade, section) {
  const modal = document.getElementById("studentModal");
  modal.classList.remove("hidden");
  modal.classList.add("flex");

  // ✅ SET VALUES
  document.getElementById("student_id_hidden").value = id;
  document.getElementById("student_id").value = student_id;
  document.getElementById("full_name").value = name;
  document.getElementById("grade_level").value = grade;
  document.getElementById("section").value = section;

  // ✅ CHANGE TITLE + BUTTON
  document.getElementById("modalTitle").innerText = "Edit Student";
  document.getElementById("submitBtn").innerText = "Update Student";

  // ✅ SET FORM ACTION
  document.getElementById("studentForm").action = "save_student.php";
}

function closeModal() {
  const modal = document.getElementById("studentModal");

  modal.classList.add("hidden");
  modal.classList.remove("flex");
}
