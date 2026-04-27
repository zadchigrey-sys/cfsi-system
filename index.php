<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Children of Fatima School, Inc. | CFSI</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Remix Icons CDN -->
    <link
      href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css"
      rel="stylesheet"
    />
    <!-- Google Fonts -->
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
      rel="stylesheet"
    />
    <!-- CSS File -->
    <link rel="stylesheet" href="style.css" />
    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: { sans: ["Inter", "sans-serif"] },
          },
        },
      };
    </script>
  </head>

  
  <body class="w-full min-h-screen bg-white">

    <?php if (isset($_GET['success'])): ?>
<script>alert("Registered successfully! Please login.");</script>
<?php endif; ?>

<?php if (isset($_GET['error']) && $_GET['error'] == 'email_exists'): ?>
<script>alert("Email already exists!");</script>
<?php endif; ?>

    <!-- ===== NAVBAR ===== -->
    <header
      id="navbar"
      class="fixed top-0 left-0 w-full z-50 transition-all duration-300 bg-[#eaf1ff] shadow-md"
    >
      <div class="px-6 md:px-10 flex items-center justify-between h-16 md:h-20">
        <!-- Logo Group (left) - FIXED FOR MOBILE -->
        <a href="#home" class="flex items-center gap-2 cursor-pointer">
          <img
            src="https://storage.readdy-site.link/project_files/e527a0c9-ac9b-4655-b294-1c9f04bf2da0/8ce5cf64-2063-4c46-8d4c-1d89754a7d16_cfsi-logo.png?v=b0327958f3048bf909fbeb15d1cbef6a"
            alt="CFSI Logo"
            class="h-9 md:h-12 w-auto object-contain flex-shrink-0"
          />
          <!-- SCHOOL NAME - NOW VISIBLE ON MOBILE -->
          <span class="block sm:hidden text-xs font-bold text-[#1a3a6b]">
            CFSI
          </span>

          <span
            class="hidden sm:block text-sm md:text-base font-bold text-[#1a3a6b]"
          >
            CHILDREN OF FATIMA SCHOOL OF STO. TOMAS, INC.
          </span>

          <!-- SHS Logo - Hidden on mobile -->
          <img
            src="https://storage.readdy-site.link/project_files/e527a0c9-ac9b-4655-b294-1c9f04bf2da0/f0506993-a140-429c-bc7d-9601301251d1_logo-cfsi-shs.png?v=64f17a68c8b9666b05c73f9b4cde2641"
            alt="CFSI SHS Logo"
            class="hidden md:block h-10 md:h-12 w-auto object-contain ml-1"
          />
        </a>
        <!-- Desktop Nav -->
        <nav class="hidden md:flex items-center gap-1">
          <button
            onclick="scrollToSection('home')"
            class="nav-btn nav-hover px-4 py-2 rounded-md text-sm font-medium tracking-wide cursor-pointer whitespace-nowrap text-[#1a3a6b]"
          >
            Home
          </button>
          <button
            onclick="scrollToSection('about-cfsi')"
            class="nav-btn nav-hover px-4 py-2 rounded-md text-sm font-medium tracking-wide cursor-pointer whitespace-nowrap text-[#1a3a6b]"
          >
            About
          </button>
          <button
            onclick="scrollToSection('contact')"
            class="nav-btn nav-hover px-4 py-2 rounded-md text-sm font-medium tracking-wide cursor-pointer whitespace-nowrap text-[#1a3a6b]"
          >
            Contact
          </button>
        </nav>
        <!-- Desktop Nav Buttons - WITH REGISTER -->
        <div class="hidden md:flex items-center gap-3">
          <!-- REGISTER BUTTON -->
          <button
            onclick="openRegister()"
            class="px-4 py-2 rounded-full text-sm font-semibold border-2 border-green-600 text-green-600 hover:bg-green-50 hover:shadow-md transition-all duration-200 whitespace-nowrap"
          >
            Sign Up
          </button>
          <!-- LOGIN BUTTON -->
          <button
            onclick="openLogin()"
            id="login-btn"
            class="flex items-center gap-2 px-5 py-2 rounded-full text-sm font-semibold border-2 border-[#1a3a6b] text-[#1a3a6b] hover:bg-[#1a3a6b] hover:text-white transition-all duration-200 whitespace-nowrap"
          >
            <i class="ri-user-line text-base"></i> Sign In
          </button>
        </div>
        <!-- Mobile Hamburger -->
        <button
          id="hamburger"
          class="md:hidden text-xl cursor-pointer w-8 h-8 flex items-center justify-center text-[#1a3a6b]"
          onclick="toggleMenu()"
        >
          <i id="hamburger-icon" class="ri-menu-line"></i>
        </button>
      </div>
      <!-- Mobile Menu - FULL VERSION WITH REGISTER -->
      <div
        id="mobile-menu"
        class="hidden md:hidden bg-white border-t border-gray-100 px-6 py-4 flex flex-col gap-3 shadow-lg"
      >
        <!-- Navigation Links -->
        <button
          onclick="
            scrollToSection('home');
            closeMenu();
          "
          class="nav-hover text-sm font-medium text-[#1a3a6b] text-left cursor-pointer whitespace-nowrap py-3 px-3 rounded-lg transition-all duration-200"
        >
          <i class="ri-home-line mr-3"></i>Home
        </button>
        <button
          onclick="
            scrollToSection('about-cfsi');
            closeMenu();
          "
          class="nav-hover text-sm font-medium text-[#1a3a6b] text-left cursor-pointer whitespace-nowrap py-3 px-3 rounded-lg transition-all duration-200"
        >
          <i class="ri-information-line mr-3"></i>About
        </button>
        <button
          onclick="
            scrollToSection('contact');
            closeMenu();
          "
          class="nav-hover text-sm font-medium text-[#1a3a6b] text-left cursor-pointer whitespace-nowrap py-3 px-3 rounded-lg transition-all duration-200"
        >
          <i class="ri-phone-line mr-3"></i>Contact
        </button>

        <!-- Divider -->
        <div class="border-t border-gray-200 my-3"></div>

        <!-- REGISTER & LOGIN BUTTONS -->
        <button
          onclick="
            openRegister();
            closeMenu();
          "
          class="w-full px-5 py-3 rounded-xl text-sm font-semibold border-2 border-green-600 text-green-600 hover:bg-green-600 hover:text-white hover:shadow-md transition-all duration-200"
        >
          <i class="ri-user-add-line mr-2"></i>Sign Up
        </button>
        <button
          onclick="
            openLogin();
            closeMenu();
          "
          class="w-full px-5 py-3 rounded-xl text-sm font-semibold border-2 border-[#1a3a6b] text-[#1a3a6b] hover:bg-[#1a3a6b] hover:text-white transition-all duration-200"
        >
          <i class="ri-user-line mr-2"></i>Sign in
        </button>
      </div>
    </header>
    <!-- ===== HERO SECTION ===== -->
    <section
      id="home"
      class="relative w-full min-h-screen flex items-center justify-center overflow-hidden pt-24"
      style="
        background-image: url(&quot;https://storage.readdy-site.link/project_files/e527a0c9-ac9b-4655-b294-1c9f04bf2da0/93a9bf06-e0a3-4fb6-a1a3-df1dd1ca8519_backgroundcfsi.png?v=85e9285e39bd5292d7b47892046e55a3&quot;);
        background-size: cover;
        background-position: center top;
      "
    >
      <!-- Overlay -->
      <div
        class="absolute inset-0"
        style="
          background: linear-gradient(
            to bottom,
            rgba(13, 31, 60, 0.7),
            rgba(13, 31, 60, 0.5),
            rgba(13, 31, 60, 0.75)
          );
        "
      ></div>
      <!-- Quote Banner -->
      <div class="absolute top-20 left-0 w-full z-10">
        <div
          class="py-2 px-6 text-center"
          style="background: rgba(42, 82, 152, 0.8); backdrop-filter: blur(4px)"
        >
          <p
            class="text-white text-xs md:text-sm font-medium italic tracking-wide"
          >
            "We don't just school our learners, We educate them. Fatimanians
            apply VALUES"
          </p>
        </div>
      </div>
      <!-- Content -->
      <div
        class="relative z-10 w-full max-w-4xl mx-auto px-6 md:px-10 text-center"
      >
        <div class="flex justify-center mb-8">
          <img
            src="https://storage.readdy-site.link/project_files/e527a0c9-ac9b-4655-b294-1c9f04bf2da0/8ce5cf64-2063-4c46-8d4c-1d89754a7d16_cfsi-logo.png?v=b0327958f3048bf909fbeb15d1cbef6a"
            alt="CFSI Logo"
            class="h-24 md:h-32 w-auto object-contain"
            style="filter: drop-shadow(0 10px 30px rgba(0, 0, 0, 0.5))"
          />
        </div>
        <h1
          class="text-4xl md:text-6xl font-bold text-white leading-tight mb-4 tracking-tight"
        >
          Welcome to <span style="color: #7eb3ff">CFSI</span>
        </h1>
        <p
          class="text-base md:text-lg max-w-xl mx-auto mb-12 leading-relaxed"
          style="color: rgba(255, 255, 255, 0.8)"
        >
          Supporting and empowering children through care, faith, and service.
        </p>
        <!-- SINGLE CONTACT BUTTON - FULL WIDTH ON MOBILE -->
        <div class="flex justify-center">
          <button
            onclick="scrollToSection('contact')"
            class="px-12 py-4 rounded-full border-2 text-white font-bold text-lg md:text-xl transition-all duration-300 cursor-pointer whitespace-nowrap hover:shadow-2xl hover:scale-105"
            style="
              border-color: rgba(255, 255, 255, 0.8);
              background: rgba(255, 255, 255, 0.15);
              backdrop-filter: blur(10px);
            "
            onmouseover="this.style.background = 'rgba(255,255,255,0.25)'"
            onmouseout="this.style.background = 'rgba(255,255,255,0.15)'"
          >
            <i class="ri-mail-line mr-3"></i>Contact Us
          </button>
        </div>
      </div>
      <!-- Scroll Hint -->
      <div
        class="scroll-hint absolute"
        style="
          bottom: 2rem;
          left: 50%;
          display: flex;
          flex-direction: column;
          align-items: center;
          gap: 0.5rem;
        "
      >
        <span
          class="text-xs tracking-widest uppercase"
          style="color: rgba(255, 255, 255, 0.5)"
          >Scroll</span
        >
        <i
          class="ri-arrow-down-line text-lg"
          style="color: rgba(255, 255, 255, 0.5)"
        ></i>
      </div>
    </section>
    <!-- ===== ABOUT SECTION ===== -->
    <section
      id="about"
      class="w-full py-24 px-6 md:px-10"
      style="background: #0d1f3c"
    >
      <div class="max-w-6xl mx-auto mt-12 space-y-10">
        <!-- ABOUT CFSI -->
        <div
          class="bg-[#2a3f6e] p-8 rounded-xl border-l-4 border-yellow-400 shadow-lg"
        >
          <h3 id="about-cfsi" class="text-2xl font-bold text-white mb-3">
            About CFSI
          </h3>
          <p class="text-gray-200 leading-relaxed">
            Children of Fatima School, Inc. (CFSI) is committed to nurturing
            learners with faith, values, and academic excellence. Its programs
            are designed to educate, empower, and shape the future of its
            learners.
          </p>
        </div>

        <!-- BRIEF HISTORY -->
        <div
          class="bg-[#2a3f6e] p-8 rounded-xl border-l-4 border-yellow-400 shadow-lg"
        >
          <h3 class="text-2xl font-bold text-yellow-300 mb-3">
            Brief History of CFSI
          </h3>
          <p class="text-gray-200 leading-relaxed">
            Founded in October 1995 in Dau, Mabalacat, Pampanga, CFSI has grown
            steadily through the years. With strong leadership and community
            support, the school expanded to different areas in Pampanga
            including Mabalacat and Sto. Tomas. Today, CFSI continues to promote
            academic excellence and service to the community.
          </p>
        </div>

        <!-- GRID SECTION (Hymn + Mission/Vision) -->
        <div class="grid md:grid-cols-2 gap-8">
          <!-- HYMN -->
          <div
            class="bg-[#2a3f6e] p-8 rounded-xl border-l-4 border-yellow-400 shadow-lg"
          >
            <h3 class="text-2xl font-bold text-yellow-300 mb-3">CFSI Hymn</h3>
            <p
              class="text-gray-200 whitespace-pre-line leading-relaxed text-sm"
            >
              Like a bright morning star Fatimanians came from afar United by
              one vision Strengthened by will and conviction O, Children of
              Fatima Arise, accomplish the mission We entrust to you our
              ambition With united hearts and minds Lead everyone to the right
              path
            </p>
          </div>

          <!-- MISSION & VISION -->
          <div
            class="bg-[#2a3f6e] p-8 rounded-xl border-l-4 border-yellow-400 shadow-lg"
          >
            <h3 class="text-2xl font-bold text-yellow-300 mb-3">Mission</h3>
            <p class="text-gray-200 text-sm mb-4">
              CFSI commits to providing quality education in a safe and
              motivating environment, where teachers and staff nurture learners
              toward lifelong success.
            </p>

            <h3 class="text-xl font-bold text-yellow-300 mb-2">Vision</h3>
            <p class="text-gray-200 text-sm">
              CFSI envisions students who are competent, value-driven, and
              capable of contributing positively to society.
            </p>
          </div>
        </div>
      </div>
    </section>
    <!-- ===== CONTACT SECTION ===== -->
    <section
      id="contact"
      class="w-full py-20 px-6 md:px-10"
      style="background: #0d1f3c"
    >
      <div class="max-w-6xl mx-auto space-y-8">
        <!-- TITLE -->
        <h2 class="text-3xl md:text-4xl font-bold text-white text-center">
          Contact Us
        </h2>

        <!-- CONTACT CARD -->
        <div
          class="bg-[#2a3f6e] p-6 md:p-8 rounded-xl border-t-4 border-yellow-400 shadow-lg fade-up"
        >
          <div class="grid md:grid-cols-2 gap-6">
            <!-- LEFT: DETAILS -->
            <div class="space-y-3 text-gray-200 text-sm">
              <p><strong>Email:</strong> info@cfsi.edu</p>
              <p><strong>Phone:</strong> +63 912 345 6789</p>
              <p>
                <strong>Address:</strong> 123 Fatima St., Manila, Philippines
              </p>
              <p><strong>Office Hours:</strong> Mon-Fri 8:00 AM - 4:00 PM</p>

              <div class="mt-4">
                <p class="font-semibold text-yellow-300">Follow Us</p>
                <a href="https://www.facebook.com/profile.php?id=100057246438734" 
                target="_blank"
                class="text-blue-400 hover:text-blue-600 underline flex items-center gap-1">
                <i class="ri-facebook-fill"></i> Facebook
                </a>
              </div>
            </div>

            <!-- RIGHT: MAP -->
            <div
              class="w-full h-[250px] md:h-[300px] rounded-lg overflow-hidden"
            >
              <iframe
                src="https://maps.google.com/maps?q=Children%20of%20Fatima%20School&t=&z=15&ie=UTF8&iwloc=&output=embed"
                class="w-full h-full border-0"
                loading="lazy"
              ></iframe>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- ===== LOGIN MODAL ===== -->
    <div
      id="login-modal"
      class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4"
    >
      <div
        class="bg-white w-full max-w-md mx-4 rounded-2xl shadow-2xl p-8 relative animate-in fade-in zoom-in duration-200"
      >
        <button
          onclick="closeLogin()"
          class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-xl p-1 rounded-full hover:bg-gray-100 transition"
        >
          <i class="ri-close-line"></i>
        </button>
        <h2
          class="text-2xl md:text-3xl font-bold text-[#1a3a6b] mb-8 text-center"
        >
          Sign in your account
        </h2>
        <form action="login.php" method="POST" class="space-y-6">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2"
              >Email Address</label
            >
            <input
              type="email"
              id="login-email"
              name="email"
              placeholder="Enter your email"
              required
              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2a5298] focus:border-transparent transition"
            />
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2"
              >Password</label
            >
            <input
              type="password"
              id="login-password"
              name="password"
              placeholder="Enter your password"
              required
              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2a5298] focus:border-transparent transition"
            />
          </div>
          <button
            type="submit"
            class="w-full bg-gradient-to-r from-[#2a5298] to-[#1a3a6b] text-white py-4 rounded-xl font-bold text-lg hover:shadow-lg hover:scale-[1.02] transition-all duration-200"
          >
            <i class="ri-login-box-line mr-2"></i>Sign In
          </button>
          <div class="text-center pt-4">
            <p class="text-sm text-gray-600">
              Don't have an account?
              <button
                type="button" onclick="switchToRegister()"
                class="text-[#2a5298] font-semibold hover:underline"
              >
                Create one
              </button>
            </p>
          </div>
        </form>
      </div>
    </div>

    <!-- ===== REGISTER MODAL ===== -->
    <div
      id="register-modal"
      class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4"
    >
      <div
        class="bg-white w-full max-w-md mx-4 rounded-2xl shadow-2xl p-8 relative animate-in fade-in zoom-in duration-200"
      >
        <button
          onclick="closeRegister()"
          class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-xl p-1 rounded-full hover:bg-gray-100 transition"
        >
          <i class="ri-close-line"></i>
        </button>
        <h2
          class="text-2xl md:text-3xl font-bold text-[#1a3a6b] mb-8 text-center"
        >
          Create Account
        </h2>
        <form action="register.php" method="POST" class="space-y-6">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2"
              >Full Name</label
            >
             <input
             type="text"
              id="register-name"
              name="name"
              placeholder="Enter your full name"
              required
              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2a5298] focus:border-transparent transition"
            />
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2"
              >Email Address</label
            >
            <input
              type="email"
              id="register-email"
              name="email"
              placeholder="Enter your email"
              required
              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2a5298] focus:border-transparent transition"
            />
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2"
              >Password</label
            >
            <input
              type="password"
              id="register-password"
              name="password"
              placeholder="Create a password"
              required
              minlength="6"
              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2a5298] focus:border-transparent transition"
            />
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Role
            </label>
            <select
              id="register-role"
              name="role"
              required
              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2a5298] focus:border-transparent transition"
            >
              <option value="" disabled selected hidden>Select Role</option>
              <option value="registrar">Registrar</option>
              <option value="administrator">Administrator</option>
            </select>
          </div>
          <button
            type="submit"
            class="w-full bg-gradient-to-r from-green-600 to-green-700 text-white py-4 rounded-xl font-bold text-lg hover:shadow-lg hover:scale-[1.02] transition-all duration-200"
          >
            <i class="ri-user-add-line mr-2"></i>Sign Up
          </button>
          <div class="text-center pt-4">
            <p class="text-sm text-gray-600">
              Already have an account?
              <button
                type="button" onclick="switchToLogin()"
                class="text-[#2a5298] font-semibold hover:underline"
              >
                Sign in
              </button>
            </p>
          </div>
        </form>
      </div>
    </div>

    <!-- ===== FOOTER ===== -->
    <footer class="w-full bg-[#1a3a6b] text-white text-center py-4 mt-0">
      <p class="text-sm font-medium">
        © 2026 Children of Fatima School, Inc. All rights reserved.
      </p>
      <p class="text-xs mt-1 text-gray-200">
        Dau, Mabalacat City, Pampanga | SHS Mabiga, Mabalacat City, Pampanga |
        San Francisco, Mabalacat City, Pampanga | Sto. Tomas, Pampanga
      </p>
    </footer>

    <!-- Javascript File -->
    <script src="script.js"></script>
  </body>
</html>
