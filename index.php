<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Children of Fatima School, Inc. | CFSI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet" />
    <!-- Upgraded font pairing: Playfair Display (headings) + DM Sans (body) -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="style.css" />
    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: {
              display: ["Playfair Display", "serif"],
              sans: ["DM Sans", "sans-serif"],
            },
          },
        },
      };
    </script>
  </head>
 
  <body class="w-full min-h-screen bg-white">
 
    <!-- ===== NAVBAR ===== -->
    <header id="navbar" class="fixed top-0 left-0 w-full z-50 transition-all duration-500 navbar-glass">
      <div class="px-6 md:px-12 flex items-center justify-between h-16 md:h-20">
 
        <!-- Logo Group -->
        <a href="#home" class="flex items-center gap-3 cursor-pointer group">
          <div class="logo-ring">
            <img
              src="https://storage.readdy-site.link/project_files/e527a0c9-ac9b-4655-b294-1c9f04bf2da0/8ce5cf64-2063-4c46-8d4c-1d89754a7d16_cfsi-logo.png?v=b0327958f3048bf909fbeb15d1cbef6a"
              alt="CFSI Logo"
              class="h-9 md:h-11 w-auto object-contain flex-shrink-0"
            />
          </div>
          <div class="flex flex-col leading-tight">
            <span class="block sm:hidden text-xs font-bold text-[#1a3a6b] tracking-widest uppercase">CFSI</span>
            <span class="hidden sm:block text-[0.7rem] md:text-[0.75rem] font-700 text-[#1a3a6b] tracking-wider uppercase leading-tight">
              Children of Fatima School
            </span>
            <span class="hidden sm:block text-[0.6rem] font-400 text-[#4a6fa5] tracking-widest uppercase">
              of Sto. Tomas, Inc.
            </span>
          </div>
          <img
            src="https://storage.readdy-site.link/project_files/e527a0c9-ac9b-4655-b294-1c9f04bf2da0/f0506993-a140-429c-bc7d-9601301251d1_logo-cfsi-shs.png?v=64f17a68c8b9666b05c73f9b4cde2641"
            alt="CFSI SHS Logo"
            class="hidden md:block h-10 md:h-11 w-auto object-contain ml-2 opacity-90"
          />
        </a>
 
        <!-- Desktop Nav Links -->
        <nav class="hidden md:flex items-center gap-1">
          <button onclick="scrollToSection('home')" class="nav-link">Home</button>
          <button onclick="scrollToSection('about-cfsi')" class="nav-link">About</button>
          <button onclick="scrollToSection('contact')" class="nav-link">Contact</button>
        </nav>
 
        <!-- Desktop Buttons -->
        <div class="hidden md:flex items-center gap-3">
          <button onclick="openRegister()" class="btn-outline-green">
            <i class="ri-user-add-line text-sm"></i> Sign Up
          </button>
          <button onclick="openLogin()" class="btn-primary">
            <i class="ri-user-line text-sm"></i> Sign In
          </button>
        </div>
 
        <!-- Hamburger -->
        <button id="hamburger" class="md:hidden w-9 h-9 flex items-center justify-center rounded-lg text-[#1a3a6b] hover:bg-blue-50 transition" onclick="toggleMenu()">
          <i id="hamburger-icon" class="ri-menu-line text-xl"></i>
        </button>
      </div>
 
      <!-- Mobile Menu -->
      <div id="mobile-menu" class="hidden md:hidden mobile-menu px-6 py-5 flex flex-col gap-2">
        <button onclick="scrollToSection('home'); closeMenu();" class="mobile-nav-link"><i class="ri-home-4-line mr-3 text-[#2a5298]"></i>Home</button>
        <button onclick="scrollToSection('about-cfsi'); closeMenu();" class="mobile-nav-link"><i class="ri-information-line mr-3 text-[#2a5298]"></i>About</button>
        <button onclick="scrollToSection('contact'); closeMenu();" class="mobile-nav-link"><i class="ri-phone-line mr-3 text-[#2a5298]"></i>Contact</button>
        <div class="border-t border-gray-100 my-2"></div>
        <button onclick="openRegister(); closeMenu();" class="btn-outline-green w-full"><i class="ri-user-add-line mr-2"></i>Sign Up</button>
        <button onclick="openLogin(); closeMenu();" class="btn-primary w-full"><i class="ri-user-line mr-2"></i>Sign In</button>
      </div>
    </header>
 
    <!-- ===== HERO SECTION ===== -->
    <section
      id="home"
      class="relative w-full min-h-screen flex items-center justify-center overflow-hidden pt-20"
      style="
        background-image: url('https://storage.readdy-site.link/project_files/e527a0c9-ac9b-4655-b294-1c9f04bf2da0/93a9bf06-e0a3-4fb6-a1a3-df1dd1ca8519_backgroundcfsi.png?v=85e9285e39bd5292d7b47892046e55a3');
        background-size: cover;
        background-position: center top;
      "
    >
      <!-- Multi-layer overlay for depth -->
      <div class="absolute inset-0 hero-overlay"></div>
      <!-- Decorative gradient orbs -->
      <div class="absolute top-1/4 left-1/4 w-96 h-96 rounded-full blur-3xl opacity-20" style="background: radial-gradient(circle, #3b82f6, transparent)"></div>
      <div class="absolute bottom-1/4 right-1/4 w-64 h-64 rounded-full blur-3xl opacity-15" style="background: radial-gradient(circle, #f59e0b, transparent)"></div>
 
      <!-- Quote Banner -->
      <div class="absolute top-16 md:top-20 left-0 w-full z-10">
        <div class="quote-banner py-2.5 px-6 text-center">
          <p class="text-white/90 text-xs md:text-sm font-400 italic tracking-wide">
            "We don't just school our learners, We educate them. Fatimanians apply <span class="text-yellow-300 font-600 not-italic">VALUES</span>"
          </p>
        </div>
      </div>
 
      <!-- Hero Content -->
      <div class="relative z-10 w-full max-w-4xl mx-auto px-6 md:px-10 text-center hero-content">
        <!-- Logo with glow -->
        <div class="flex justify-center mb-8">
          <div class="logo-glow">
            <img
              src="https://storage.readdy-site.link/project_files/e527a0c9-ac9b-4655-b294-1c9f04bf2da0/8ce5cf64-2063-4c46-8d4c-1d89754a7d16_cfsi-logo.png?v=b0327958f3048bf909fbeb15d1cbef6a"
              alt="CFSI Logo"
              class="h-24 md:h-32 w-auto object-contain"
            />
          </div>
        </div>
 
        <!-- Eyebrow label -->
        <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-1.5 mb-5 backdrop-blur-sm">
          <span class="w-1.5 h-1.5 rounded-full bg-yellow-400 animate-pulse"></span>
          <span class="text-white/80 text-xs font-500 tracking-widest uppercase">Est. 1995 · Sto. Tomas, Pampanga</span>
        </div>
 
        <h1 class="font-display text-4xl md:text-6xl lg:text-7xl font-800 text-white leading-tight mb-5 tracking-tight">
          Welcome to <br class="hidden md:block"/>
          <span class="hero-gradient-text">CFSI</span>
        </h1>
 
        <p class="text-base md:text-lg max-w-lg mx-auto mb-10 leading-relaxed text-white/75 font-300">
          Supporting and empowering children through <span class="text-white/90 font-500">care</span>, <span class="text-white/90 font-500">faith</span>, and <span class="text-white/90 font-500">service</span>.
        </p>
 
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <button onclick="scrollToSection('contact')" class="btn-hero-primary">
            <i class="ri-mail-send-line mr-2"></i>Contact Us
          </button>
          <button onclick="scrollToSection('about-cfsi')" class="btn-hero-ghost">
            <i class="ri-book-open-line mr-2"></i>Learn More
          </button>
        </div>
      </div>
 
      <!-- Scroll hint -->
      <div class="scroll-hint absolute" style="bottom: 2rem; left: 50%;">
        <div class="flex flex-col items-center gap-1.5">
          <span class="text-[10px] tracking-widest uppercase text-white/40 font-500">Scroll</span>
          <div class="w-px h-8 bg-gradient-to-b from-white/30 to-transparent"></div>
          <i class="ri-arrow-down-s-line text-white/40 text-lg"></i>
        </div>
      </div>
    </section>
 
    <!-- ===== ABOUT SECTION ===== -->
    <section id="about" class="w-full py-28 px-6 md:px-10 about-bg">
      <div class="max-w-6xl mx-auto">
 
        <!-- Section Header -->
        <div class="text-center mb-16">
          <span class="section-eyebrow">Our Story</span>
          <h2 class="font-display text-4xl md:text-5xl font-700 text-white mt-3 mb-4">About <span class="text-yellow-300">CFSI</span></h2>
          <div class="w-16 h-0.5 bg-gradient-to-r from-yellow-400 to-yellow-200 mx-auto"></div>
        </div>
 
        <div class="space-y-8">
 
          <!-- About Card -->
          <div id="about-cfsi" class="info-card fade-up">
            <div class="info-card-accent"></div>
            <div class="p-8 md:p-10">
              <div class="flex items-start gap-5">
                <div class="card-icon-box">
                  <i class="ri-school-line text-2xl text-yellow-400"></i>
                </div>
                <div>
                  <h3 class="font-display text-2xl font-700 text-white mb-3">About CFSI</h3>
                  <p class="text-gray-300 leading-relaxed font-300 text-base">
                    Children of Fatima School, Inc. (CFSI) is committed to nurturing
                    learners with faith, values, and academic excellence. Its programs
                    are designed to educate, empower, and shape the future of its learners.
                  </p>
                </div>
              </div>
            </div>
          </div>
 
          <!-- History Card -->
          <div class="info-card fade-up">
            <div class="info-card-accent"></div>
            <div class="p-8 md:p-10">
              <div class="flex items-start gap-5">
                <div class="card-icon-box">
                  <i class="ri-history-line text-2xl text-yellow-400"></i>
                </div>
                <div>
                  <h3 class="font-display text-2xl font-700 text-yellow-300 mb-3">Brief History</h3>
                  <p class="text-gray-300 leading-relaxed font-300 text-base">
                    Founded in October 1995 in Dau, Mabalacat, Pampanga, CFSI has grown
                    steadily through the years. With strong leadership and community
                    support, the school expanded to different areas in Pampanga
                    including Mabalacat and Sto. Tomas. Today, CFSI continues to promote
                    academic excellence and service to the community.
                  </p>
                </div>
              </div>
            </div>
          </div>
 
          <!-- Grid: Hymn + Mission/Vision -->
          <div class="grid md:grid-cols-2 gap-8">
            <!-- Hymn -->
            <div class="info-card fade-up h-full">
              <div class="info-card-accent"></div>
              <div class="p-8 h-full">
                <div class="flex items-center gap-3 mb-5">
                  <div class="card-icon-box">
                    <i class="ri-music-2-line text-2xl text-yellow-400"></i>
                  </div>
                  <h3 class="font-display text-2xl font-700 text-yellow-300">CFSI Hymn</h3>
                </div>
                <p class="text-gray-300 whitespace-pre-line leading-loose text-sm font-300 italic border-l-2 border-yellow-400/30 pl-4">
Like a bright morning star
Fatimanians came from afar
United by one vision
Strengthened by will and conviction
 
O, Children of Fatima
Arise, accomplish the mission
We entrust to you our ambition
With united hearts and minds
Lead everyone to the right path</p>
              </div>
            </div>
 
            <!-- Mission & Vision -->
            <div class="info-card fade-up h-full">
              <div class="info-card-accent"></div>
              <div class="p-8 h-full flex flex-col gap-6">
                <div>
                  <div class="flex items-center gap-3 mb-3">
                    <div class="card-icon-box">
                      <i class="ri-compass-3-line text-2xl text-yellow-400"></i>
                    </div>
                    <h3 class="font-display text-2xl font-700 text-yellow-300">Mission</h3>
                  </div>
                  <p class="text-gray-300 text-sm leading-relaxed font-300">
                    CFSI commits to providing quality education in a safe and
                    motivating environment, where teachers and staff nurture learners
                    toward lifelong success.
                  </p>
                </div>
                <div class="border-t border-white/10 pt-6">
                  <div class="flex items-center gap-3 mb-3">
                    <div class="card-icon-box">
                      <i class="ri-eye-line text-2xl text-yellow-400"></i>
                    </div>
                    <h3 class="font-display text-2xl font-700 text-yellow-300">Vision</h3>
                  </div>
                  <p class="text-gray-300 text-sm leading-relaxed font-300">
                    CFSI envisions students who are competent, value-driven, and
                    capable of contributing positively to society.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
 
    <!-- ===== CONTACT SECTION ===== -->
    <section id="contact" class="w-full py-24 px-6 md:px-10 contact-bg">
      <div class="max-w-5xl mx-auto">
 
        <!-- Section Header -->
        <div class="text-center mb-12">
          <span class="section-eyebrow">Get in Touch</span>
          <h2 class="font-display text-4xl md:text-5xl font-700 text-white mt-3 mb-4">Contact <span class="text-yellow-300">Us</span></h2>
          <div class="w-16 h-0.5 bg-gradient-to-r from-yellow-400 to-yellow-200 mx-auto"></div>
        </div>
 
        <div class="info-card fade-up">
          <div class="info-card-accent"></div>
          <div class="p-8 md:p-10">
            <div class="grid md:grid-cols-2 gap-8 items-start">
 
              <!-- Contact Details -->
              <div class="space-y-5">
                <h3 class="font-display text-xl font-700 text-yellow-300 mb-6">Reach Us Directly</h3>
 
                <div class="contact-detail-row">
                  <div class="contact-icon"><i class="ri-mail-line"></i></div>
                  <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-500 mb-0.5">Email</p>
                    <p class="text-white font-400">info@cfsi.edu</p>
                  </div>
                </div>
 
                <div class="contact-detail-row">
                  <div class="contact-icon"><i class="ri-phone-line"></i></div>
                  <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-500 mb-0.5">Phone</p>
                    <p class="text-white font-400">+63 912 345 6789</p>
                  </div>
                </div>
 
                <div class="contact-detail-row">
                  <div class="contact-icon"><i class="ri-map-pin-line"></i></div>
                  <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-500 mb-0.5">Address</p>
                    <p class="text-white font-400">Sto. Tomas, Pampanga, Philippines</p>
                  </div>
                </div>
 
                <div class="contact-detail-row">
                  <div class="contact-icon"><i class="ri-time-line"></i></div>
                  <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-500 mb-0.5">Office Hours</p>
                    <p class="text-white font-400">Mon–Fri · 8:00 AM – 4:00 PM</p>
                  </div>
                </div>
 
                <div class="pt-4 border-t border-white/10">
                  <p class="text-xs text-gray-400 uppercase tracking-wider font-500 mb-3">Follow Us</p>
                  <a href="https://www.facebook.com/profile.php?id=100057246438734" target="_blank" class="btn-facebook">
                    <i class="ri-facebook-fill text-base"></i>
                    <span>Facebook Page</span>
                    <i class="ri-arrow-right-up-line ml-auto text-sm opacity-60"></i>
                  </a>
                </div>
              </div>
 
              <!-- Map -->
              <div class="map-container">
                <iframe
                  src="https://maps.google.com/maps?q=Children%20of%20Fatima%20School&t=&z=15&ie=UTF8&iwloc=&output=embed"
                  class="w-full h-full border-0"
                  loading="lazy"
                ></iframe>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
 
    <!-- ===== LOGIN MODAL ===== -->
    <div id="login-modal" class="fixed inset-0 bg-black/70 hidden items-center justify-center z-50 p-4 backdrop-blur-sm">
      <div class="modal-card w-full max-w-md mx-4 relative animate-in fade-in zoom-in duration-200">
        <div class="modal-header">
          <div class="modal-logo-wrap">
            <img src="https://storage.readdy-site.link/project_files/e527a0c9-ac9b-4655-b294-1c9f04bf2da0/8ce5cf64-2063-4c46-8d4c-1d89754a7d16_cfsi-logo.png?v=b0327958f3048bf909fbeb15d1cbef6a" alt="CFSI" class="h-10 w-auto"/>
          </div>
          <h2 class="font-display text-2xl font-700 text-[#0d1f3c] mt-4 mb-1">Welcome Back</h2>
          <p class="text-sm text-gray-500 font-300">Sign in to your CFSI account</p>
        </div>
        <button onclick="closeLogin()" class="modal-close"><i class="ri-close-line"></i></button>
        <form action="login.php" method="POST" class="p-8 space-y-5">
          <div class="form-group">
            <label class="form-label">Email Address</label>
            <div class="input-wrap">
              <i class="ri-mail-line input-icon"></i>
              <input type="email" name="email" placeholder="your@email.com" required class="form-input" />
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Password</label>
            <div class="input-wrap">
              <i class="ri-lock-line input-icon"></i>
              <input type="password" name="password" placeholder="Enter your password" required class="form-input" />
            </div>
          </div>
          <button type="submit" class="btn-submit-primary w-full mt-2">
            <i class="ri-login-box-line mr-2"></i>Sign In
          </button>
          <p class="text-center text-sm text-gray-500 pt-2">
            Don't have an account?
            <button type="button" onclick="switchToRegister()" class="text-[#2a5298] font-600 hover:underline ml-1">Create one</button>
          </p>
        </form>
      </div>
    </div>
 
    <!-- ===== REGISTER MODAL ===== -->
    <div id="register-modal" class="fixed inset-0 bg-black/70 hidden items-center justify-center z-50 p-4 backdrop-blur-sm">
      <div class="modal-card w-full max-w-md mx-4 relative animate-in fade-in zoom-in duration-200">
        <div class="modal-header">
          <div class="modal-logo-wrap">
            <img src="https://storage.readdy-site.link/project_files/e527a0c9-ac9b-4655-b294-1c9f04bf2da0/8ce5cf64-2063-4c46-8d4c-1d89754a7d16_cfsi-logo.png?v=b0327958f3048bf909fbeb15d1cbef6a" alt="CFSI" class="h-10 w-auto"/>
          </div>
          <h2 class="font-display text-2xl font-700 text-[#0d1f3c] mt-4 mb-1">Create Account</h2>
          <p class="text-sm text-gray-500 font-300">Join the CFSI portal today</p>
        </div>
        <button onclick="closeRegister()" class="modal-close"><i class="ri-close-line"></i></button>
        <form action="register.php" method="POST" class="p-8 space-y-4">
          <div class="form-group">
            <label class="form-label">Full Name</label>
            <div class="input-wrap">
              <i class="ri-user-line input-icon"></i>
              <input type="text" name="name" placeholder="Your full name" required class="form-input" />
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Email Address</label>
            <div class="input-wrap">
              <i class="ri-mail-line input-icon"></i>
              <input type="email" name="email" placeholder="your@email.com" required class="form-input" />
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Password</label>
            <div class="input-wrap">
              <i class="ri-lock-line input-icon"></i>
              <input type="password" name="password" placeholder="Min. 6 characters" required minlength="6" class="form-input" />
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Role</label>
            <div class="input-wrap">
              <i class="ri-shield-user-line input-icon"></i>
              <select name="role" required class="form-input form-select">
                <option value="" disabled selected hidden>Select your role</option>
                <option value="registrar">Registrar</option>
                <option value="administrator">Administrator</option>
              </select>
            </div>
          </div>
          <button type="submit" class="btn-submit-green w-full mt-2">
            <i class="ri-user-add-line mr-2"></i>Create Account
          </button>
          <p class="text-center text-sm text-gray-500 pt-2">
            Already have an account?
            <button type="button" onclick="switchToLogin()" class="text-[#2a5298] font-600 hover:underline ml-1">Sign in</button>
          </p>
        </form>
      </div>
    </div>
 
    <!-- ===== FOOTER ===== -->
    <footer class="w-full footer-bg text-white py-8 px-6 text-center">
      <div class="max-w-4xl mx-auto">
        <div class="flex justify-center mb-4">
          <img src="https://storage.readdy-site.link/project_files/e527a0c9-ac9b-4655-b294-1c9f04bf2da0/8ce5cf64-2063-4c46-8d4c-1d89754a7d16_cfsi-logo.png?v=b0327958f3048bf909fbeb15d1cbef6a" alt="CFSI" class="h-8 w-auto opacity-80" />
        </div>
        <p class="text-sm font-500 text-white/90">© 2026 Children of Fatima School, Inc. All rights reserved.</p>
        <div class="w-16 h-px bg-white/20 mx-auto my-3"></div>
        <p class="text-xs text-white/50 font-300 leading-relaxed">
          Dau, Mabalacat City, Pampanga &nbsp;·&nbsp; SHS Mabiga, Mabalacat City, Pampanga<br/>
          San Francisco, Mabalacat City, Pampanga &nbsp;·&nbsp; Sto. Tomas, Pampanga
        </p>
      </div>
    </footer>
 
    <script src="script.js"></script>
  </body>
</html>