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

  // reset tab styles
  document
    .getElementById("tab-students")
    .classList.remove("border-blue-600", "text-blue-600");

  document
    .getElementById("tab-billing")
    .classList.remove("border-blue-600", "text-blue-600");

  // show selected tab
  document.getElementById(tab).classList.remove("hidden");

  // highlight active tab
  document
    .getElementById("tab-" + tab)
    .classList.add("border-blue-600", "text-blue-600");

  // ✅ IMPORTANT: change search target
  const form = document.getElementById("quickSearchForm");
  if (form) {
    form.action = tab === "students" ? "students.php" : "billing.php";
  }
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

function closeBillingModal() {
  console.log("Closing billing modal"); // 👈 DEBUG
  const modal = document.getElementById("billingModal");

  if (!modal) {
    console.error("Billing modal NOT FOUND");
    return;
  }

  // ✅ PROPERLY HIDE MODAL
  modal.classList.remove("flex");
  modal.classList.add("hidden");

  // ✅ RESTORE BODY SCROLL
  document.body.style.overflow = "";
  document.body.style.paddingRight = "";
}

function openBillingModal() {
  console.log("Opening billing modal"); // 👈 DEBUG

  const modal = document.getElementById("billingModal");
  if (!modal) {
    console.error("Billing modal NOT FOUND");
    return;
  }

  // ✅ SHOW MODAL PROPERLY
  modal.classList.remove("hidden");
  modal.classList.add("flex");

  // ✅ PREVENT BODY SCROLL
  document.body.style.overflow = "hidden";

  // ✅ PREVENT HORIZONTAL SCROLLBAR
  const scrollBarWidth =
    window.innerWidth - document.documentElement.clientWidth;
  document.body.style.paddingRight = scrollBarWidth + "px";

  // ✅ RESET FORM
  const form = document.getElementById("billingForm");
  if (form) form.reset();

  document.getElementById("billing_id_hidden").value = "";
  document.getElementById("billingTitle").innerText = "Add Billing";
  document.getElementById("billingSubmit").innerText = "Save Billing";

  // ✅ AUTO FOCUS
  setTimeout(() => {
    const feeInput = document.getElementById("fee_type");
    if (feeInput) feeInput.focus();
  }, 100);
}

function openEditBilling(id, bid, fee, status, bdate, ddate, amount) {
  const modal = document.getElementById("billingModal");

  modal.classList.remove("hidden");
  modal.classList.add("flex");

  document.getElementById("billing_id_hidden").value = id;
  document.getElementById("billing_id").value = bid;
  document.getElementById("fee_type").value = fee;
  document.getElementById("status").value = status;
  document.getElementById("billing_date").value = bdate;
  document.getElementById("due_date").value = ddate;
  document.getElementById("total_amount").value = amount;

  document.getElementById("billingTitle").innerText = "Edit Billing";
  document.getElementById("billingSubmit").innerText = "Update Billing";
}

document.addEventListener("DOMContentLoaded", function () {
  // ✅ DEFAULT TAB
  showTab("students");

  // ✅ GET CALENDAR ELEMENT
  const calendarEl = document.getElementById("calendar");

  if (calendarEl) {
    const calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: "dayGridMonth",
      height: "auto",
      events: events,

      // ✅ CLICK DATE → OPEN MODAL
      dateClick: function (info) {
        console.log("Calendar clicked:", info.dateStr); // 👈 DEBUG

        openBillingModal();

        // ✅ SET BILLING DATE
        const billingDate = document.getElementById("billing_date");
        if (billingDate) {
          billingDate.value = info.dateStr;
        }

        // ✅ AUTO DUE DATE (+2 DAYS)
        const dueDateInput = document.getElementById("due_date");
        if (dueDateInput) {
          let due = new Date(info.date);
          due.setDate(due.getDate() + 2);

          let yyyy = due.getFullYear();
          let mm = String(due.getMonth() + 1).padStart(2, "0");
          let dd = String(due.getDate()).padStart(2, "0");

          dueDateInput.value = `${yyyy}-${mm}-${dd}`;
        }

        // ✅ OPTIONAL DEFAULT VALUES
        const status = document.getElementById("status");
        if (status) status.value = "Pending";

        const fee = document.getElementById("fee_type");
        if (fee) fee.focus(); // auto focus input
      },
    });

    calendar.render();
  }
});
