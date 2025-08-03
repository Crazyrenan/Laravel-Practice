<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Home</title>
  @vite('resources/css/app.css')
  <style>
    body {
      transition: background-color 0.3s, color 0.3s;
    }
  </style>




</head>
<script src="https://cdn.jsdelivr.net/npm/animejs@3.2.1/lib/anime.min.js"></script><!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
  <!-- Swiper CSS -->
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"
/>


<body class="min-h-screen bg-yellow-100 dark:bg-gray-900 text-gray-800 dark:text-white font-sans transition">

<!-- Background-->
<div class="fixed inset-0 z-0">
   <img src="{{ asset('img/88.jpg') }}" class="absolute inset-0 w-full h-full object-cover opacity-30"/>
    <div class="absolute inset-0 bg-black/40"></div> <!-- Dark overlay -->
</div>


<!-- Top Bar -->
<header class="transition-all duration-500 block relative z-20 flex justify-between items-center px-6 py-4
  bg-black/30 dark:bg-gray-900/30
  backdrop-blur-lg
  border border-white/10 dark:border-white/10
  rounded-xl
  shadow-lg
  text-white">

  <h1 class="text-xl font-semibold text-white hover:opacity-70 transition-opacity duration-300">
    📁 <a href="#cardsection">Dashboard</a>
  </h1>

  <button onclick="document.getElementById('cardsection').scrollIntoView({ behavior: 'smooth' })"
    class="mt-1 sm:mt-0 px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg shadow transition">
    🚀 Go to Cards
  </button>
</header>

<!-- Caroousel-->
<div class="w-full overflow-hidden py-6">
  <div class="flex animate-marquee whitespace-nowrap gap-8 mt-40">
      <img src="{{ asset('img/carousel 1.jpg') }}" class="h-40 rounded-xl shadow-lg" />
      <img src="{{ asset('img/carousel 2.jpg') }}" class="h-40 rounded-xl shadow-lg" /> 
      <img src="{{ asset('img/carousel 1.jpg') }}" class="h-40 rounded-xl shadow-lg" />
      <img src="{{ asset('img/carousel 3.jpg') }}" class="h-40 rounded-xl shadow-lg" /> 
      <img src="{{ asset('img/carousel 1.jpg') }}" class="h-40 rounded-xl shadow-lg" />
      <img src="{{ asset('img/carousel 4.jpg') }}" class="h-40 rounded-xl shadow-lg" />
  </div>
</div>
<!-- Tailwind Custom Animation -->
<style>
  @keyframes marquee {
    0%   { transform: translateX(100%); }
    100% { transform: translateX(-100%); }
  }

  .animate-marquee {
    animation: marquee 10s linear infinite;
  }
</style>

<main class="max-w-5xl mx-auto px-4 py-10">

<!-- Hero Section -->
<section class="relative z-0 flex flex-col items-center justify-center text-center px-6 py-24 min-h-[80vh]" data-aos="zoom-in">
  <!-- Optional glow -->
  <div class="absolute top-0 left-1/2 transform -translate-x-1/2 bg-orange-500 opacity-20 blur-[120px] rounded-full z-0"></div>

  <div class="relative z-10 text-center max-w-3xl">
    <h1 class="text-4xl sm:text-6xl font-extrabold leading-tight mb-6">
      Empower Your Data Experience
    </h1>
    <p class="text-lg sm:text-xl text-gray-300 mb-10">
      Centralize your vendor, order, and performance analytics in a seamless and interactive dashboard.
    </p>
    <a href="/vendor2" class="inline-flex items-center gap-2 px-6 py-3 bg-orange-600 hover:bg-orange-500 text-white font-semibold rounded-lg shadow-lg transition duration-300">
      🚀 Get Started
    </a>
  </div>
  
<!-- Image-->
<div class="w-full max-w-7xl mx-auto mt-10 px-6">
  <div class="flex justify-between flex-wrap gap-6 bg-white/10 backdrop-blur-md border border-white/20 p-6 rounded-3xl shadow-lg" data-aos="fade-up">

    {{-- Chart 1 – Vendors --}}
    <a href="{{ url('/vendormaster') }}" class="relative w-[22%] h-48 rounded-xl overflow-hidden group" data-aos="flip-up">
      <img src="{{ asset('img/vendor.jpg') }}"
           alt="Vendors"
           class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105 rounded-xl" />
      <div class="absolute inset-0 bg-black/40 flex flex-col justify-center items-center opacity-0 group-hover:opacity-100 transition-opacity duration-500">
        <h3 class="text-white text-lg font-semibold">Vendors</h3>
        <p class="text-gray-300 text-sm">Click to view vendors</p>
      </div>
    </a>

    {{-- Chart 2 – Projects --}}
    <a href="{{ url('/projectmaster') }}" class="relative w-[22%] h-48 rounded-xl overflow-hidden group" data-aos="flip-down">
      <img src="{{ asset('img/projects.jpg') }}"
           alt="Projects"
           class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105 rounded-xl" />
      <div class="absolute inset-0 bg-black/40 flex flex-col justify-center items-center opacity-0 group-hover:opacity-100 transition-opacity duration-500">
        <h3 class="text-white text-lg font-semibold">Projects</h3>
        <p class="text-gray-300 text-sm">View all projects</p>
      </div>
    </a>

    {{-- Chart 3 – Requests --}}
    <a href="{{ url('/requestmaster') }}" class="relative w-[22%] h-48 rounded-xl overflow-hidden group" data-aos="flip-up">
      <img src="{{ asset('img/request.jpg') }}"
           alt="Requests"
           class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105 rounded-xl" />
      <div class="absolute inset-0 bg-black/40 flex flex-col justify-center items-center opacity-0 group-hover:opacity-100 transition-opacity duration-500">
        <h3 class="text-white text-lg font-semibold">Requests</h3>
        <p class="text-gray-300 text-sm">Explore requests</p>
      </div>
    </a>

    {{-- Chart 4 – Pembelian --}}
    <a href="{{ url('/pembelianmaster') }}" class="relative w-[22%] h-48 rounded-xl overflow-hidden group" data-aos="flip-down">
      <img src="{{ asset('img/buy.jpg') }}"
           alt="Purchases"
           class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105 rounded-xl" />
      <div class="absolute inset-0 bg-black/40 flex flex-col justify-center items-center opacity-0 group-hover:opacity-100 transition-opacity duration-500">
        <h3 class="text-white text-lg font-semibold">Purchases</h3>
        <p class="text-gray-300 text-sm">View pembelian data</p>
      </div>
    </a>

  </div>
</div>




  </section> 

<!-- Builder Queries -->
<div id="cardsection" class="relative z-10 max-w-6xl mx-auto my-12 p-8 rounded-2xl bg-black/30 backdrop-blur-xl shadow-2xl border border-white/10 text-white" data-aos="fade-in">
  <section class="mt-10 bg-white/10 backdrop-blur-lg p-6 rounded-xl shadow-lg border border-white/20" data-aos="fade-up">
    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-6 text-center border-b border-gray-300 dark:border-gray-700 inline-block">🧱 Builder Queries</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 max-w-6xl mx-auto mt-6">

      <!-- Example Card Button -->
      <a href="/vendor" class="group bg-white/10 hover:bg-orange-500/20 backdrop-blur-lg border border-white/10 rounded-xl p-6 shadow-xl transition duration-300 hover:scale-[1.03] text-center text-white">
        <div class="text-3xl mb-2">🛒</div>
        <h3 class="text-lg font-semibold mb-1 group-hover:text-orange-400">Vendor</h3>
        <p class="text-sm text-gray-300 group-hover:text-white">Browse vendors and manage data</p>
      </a>

      <a href="/status" class="group bg-white/10 hover:bg-orange-500/20 backdrop-blur-lg border border-white/10 rounded-xl p-6 shadow-xl transition duration-300 hover:scale-[1.03] text-center text-white">
        <div class="text-3xl mb-2">📊</div>
        <h3 class="text-lg font-semibold mb-1 group-hover:text-orange-400">Status</h3>
        <p class="text-sm text-gray-300 group-hover:text-white">Check item delivery and approval</p>
      </a>

      <a href="/order" class="group bg-white/10 hover:bg-orange-500/20 backdrop-blur-lg border border-white/10 rounded-xl p-6 shadow-xl transition duration-300 hover:scale-[1.03] text-center text-white">
        <div class="text-3xl mb-2">🛍️</div>
        <h3 class="text-lg font-semibold mb-1 group-hover:text-orange-400">Order</h3>
        <p class="text-sm text-gray-300 group-hover:text-white">Track all purchase orders</p>
      </a>

      <a href="/product" class="group bg-white/10 hover:bg-orange-500/20 backdrop-blur-lg border border-white/10 rounded-xl p-6 shadow-xl transition duration-300 hover:scale-[1.03] text-center text-white">
        <div class="text-3xl mb-2">🏷️</div>
        <h3 class="text-lg font-semibold mb-1 group-hover:text-orange-400">Avg Product</h3>
        <p class="text-sm text-gray-300 group-hover:text-white">Monitor product averages</p>
      </a>

    </div>
  </section>
</div>

  <!-- Normal Queries -->

<div id="cardsection" class="relative z-10 max-w-6xl mx-auto my-12 p-8 rounded-2xl bg-black/30 backdrop-blur-xl shadow-2xl border border-white/10 text-white" data-aos="fade-in">
  <section class="mt-10 bg-white/10 backdrop-blur-lg p-6 rounded-xl shadow-lg border border-white/20" data-aos="fade-up">
    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-6 text-center border-b border-gray-300 dark:border-gray-700 inline-block">✨ Normal Queries</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 max-w-6xl mx-auto mt-6">

      <a href="/vendor2" class="group bg-white/10 hover:bg-orange-500/20 backdrop-blur-lg border border-white/10 rounded-xl p-6 shadow-xl transition duration-300 hover:scale-[1.03] text-center text-white">
        <div class="text-3xl mb-2">🛒</div>
        <h3 class="text-lg font-semibold mb-1 group-hover:text-orange-400">Vendor</h3>
        <p class="text-sm text-gray-300 group-hover:text-white">Browse vendors and manage data</p>
      </a>

      <a href="/status2" class="group bg-white/10 hover:bg-orange-500/20 backdrop-blur-lg border border-white/10 rounded-xl p-6 shadow-xl transition duration-300 hover:scale-[1.03] text-center text-white">
        <div class="text-3xl mb-2">📊</div>
        <h3 class="text-lg font-semibold mb-1 group-hover:text-orange-400">Status</h3>
        <p class="text-sm text-gray-300 group-hover:text-white">Check item delivery and approval</p>
      </a>

      <a href="/order2" class="group bg-white/10 hover:bg-orange-500/20 backdrop-blur-lg border border-white/10 rounded-xl p-6 shadow-xl transition duration-300 hover:scale-[1.03] text-center text-white">
        <div class="text-3xl mb-2">🛍️</div>
        <h3 class="text-lg font-semibold mb-1 group-hover:text-orange-400">Order</h3>
        <p class="text-sm text-gray-300 group-hover:text-white">Track all purchase orders</p>
      </a>

      <a href="/product2" class="group bg-white/10 hover:bg-orange-500/20 backdrop-blur-lg border border-white/10 rounded-xl p-6 shadow-xl transition duration-300 hover:scale-[1.03] text-center text-white">
        <div class="text-3xl mb-2">🏷️</div>
        <h3 class="text-lg font-semibold mb-1 group-hover:text-orange-400">Avg Product</h3>
        <p class="text-sm text-gray-300 group-hover:text-white">Monitor product averages</p>
      </a>

    </div>
  </section>
</div>


<!-- Analytics Section -->
<div id="cardsection" class="relative z-10 max-w-6xl mx-auto my-16 p-8 rounded-2xl bg-black/30 backdrop-blur-xl shadow-2xl border border-white/10 text-white" data-aos="fade-in">
  <section class="bg-white/10 backdrop-blur-lg p-6 rounded-xl shadow-lg border border-white/20" data-aos="fade-up">
    <h2 class="text-2xl font-bold text-white text-center mb-10 border-b border-white/20 pb-4">📊 Charts & Analytics</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 max-w-5xl mx-auto mt-6">

      <a href="/vendor_chart" class="group bg-white/10 hover:bg-blue-600/30 backdrop-blur-md border border-white/10 rounded-xl p-6 shadow-lg transition hover:scale-[1.03] text-center">
        <div class="text-4xl mb-2">🌙</div>
        <h3 class="text-lg font-semibold mb-1 group-hover:text-blue-400">Vendor By Month</h3>
        <p class="text-sm text-gray-300 group-hover:text-white">Monthly vendor data in one view.</p>
      </a>

      <a href="/profit" class="group bg-white/10 hover:bg-cyan-600/30 backdrop-blur-md border border-white/10 rounded-xl p-6 shadow-lg transition hover:scale-[1.03] text-center">
        <div class="text-4xl mb-2">💸</div>
        <h3 class="text-lg font-semibold mb-1 group-hover:text-cyan-400">Profit</h3>
        <p class="text-sm text-gray-300 group-hover:text-white">See what earns the most profit.</p>
      </a>

      <a href="/profitcategory" class="group bg-white/10 hover:bg-green-600/30 backdrop-blur-md border border-white/10 rounded-xl p-6 shadow-lg transition hover:scale-[1.03] text-center">
        <div class="text-4xl mb-2">🤑</div>
        <h3 class="text-lg font-semibold mb-1 group-hover:text-green-400">Profit By Category</h3>
        <p class="text-sm text-gray-300 group-hover:text-white">Break down profit by category.</p>
      </a>

    </div>
  </section>
</div>


<!-- JOIN -->
<div id="cardsection" class="relative z-10 max-w-6xl mx-auto my-16 p-8 rounded-2xl bg-black/30 backdrop-blur-xl shadow-2xl border border-white/10 text-white" data-aos="fade-in">
  <section class="bg-white/10 backdrop-blur-lg p-6 rounded-xl shadow-lg border border-white/20" data-aos="fade-up">
    <h2 class="text-2xl font-bold text-white text-center mb-10 border-b border-white/20 pb-4">🤝 Joins And Expand</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 max-w-5xl mx-auto mt-6">

      <a href="/vendorjoin" class="group bg-white/10 hover:bg-blue-600/30 backdrop-blur-md border border-white/10 rounded-xl p-6 shadow-lg transition hover:scale-[1.03] text-center">
        <div class="text-4xl mb-2">👨‍💼</div>
        <h3 class="text-lg font-semibold mb-1 group-hover:text-blue-400">Vendor Details</h3>
        <p class="text-sm text-gray-300 group-hover:text-white">Monthly vendor detail in one view.</p>
      </a>

      <a href="/profit" class="group bg-white/10 hover:bg-cyan-600/30 backdrop-blur-md border border-white/10 rounded-xl p-6 shadow-lg transition hover:scale-[1.03] text-center">
        <div class="text-4xl mb-2">💸</div>
        <h3 class="text-lg font-semibold mb-1 group-hover:text-cyan-400">Profit</h3>
        <p class="text-sm text-gray-300 group-hover:text-white">See what earns the most profit.</p>
      </a>

      <a href="/profitcategory" class="group bg-white/10 hover:bg-green-600/30 backdrop-blur-md border border-white/10 rounded-xl p-6 shadow-lg transition hover:scale-[1.03] text-center">
        <div class="text-4xl mb-2">🤑</div>
        <h3 class="text-lg font-semibold mb-1 group-hover:text-green-400">Profit By Category</h3>
        <p class="text-sm text-gray-300 group-hover:text-white">Break down profit by category.</p>
      </a>

    </div>
  </section>
</div>








<footer class="relative z-0 flex flex-col items-center justify-center text-center px-6 py-24 min-h-[40vh]" data-aos="zoom-in">
  <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-8">
    
    <!-- Logo & Tagline -->
    <div>
      <h2 class="text-white text-2xl font-bold mb-2">Gallery</h2>
      <p class="text-sm text-gray-400">Empowering insights through intelligent dashboards.</p>
    </div>

    <!-- Quick Links -->
    <div>
      <h3 class="text-white font-semibold mb-3">Quick Links</h3>
      <ul class="space-y-2 text-sm">
        <li><a href="/about" class="hover:text-orange-400 transition">About</a></li>
        <li><a href="/contact" class="hover:text-orange-400 transition">Contact</a></li>
        <li><a href="/blog" class="hover:text-orange-400 transition">Blog</a></li>
      </ul>
    </div>

    <!-- Legal -->
    <div>
      <h3 class="text-white font-semibold mb-3">Legal</h3>
      <ul class="space-y-2 text-sm">
        <li><a href="/privacy" class="hover:text-orange-400 transition">Privacy Policy</a></li>
        <li><a href="/terms" class="hover:text-orange-400 transition">Terms of Service</a></li>
      </ul>
    </div>

    <!-- Social Media -->
    <div>
      <h3 class="text-white font-semibold mb-3">Follow Us</h3>
      <div class="flex space-x-4">
        <a href="https://github.com/Crazyrenan" target="_blank" class="hover:text-white transition">GitHub</a>
        <a href="https://linkedin.com" target="_blank" class="hover:text-white transition">LinkedIn</a>
        <a href="https://twitter.com" target="_blank" class="hover:text-white transition">Twitter</a>
      </div>
    </div>
  </div>

  <div class="mt-10 border-t border-gray-700 pt-6 text-center text-sm text-gray-500">
    © 2025 Gallery. All rights reserved.
  </div>
</footer>


  </main>

    <!-- AOS Animation -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
      AOS.init({
        once: true, // animation only once
        duration: 800, // animation duration in ms
      });
    </script>

<script>
  const swiper = new Swiper(".mySwiper", {
    loop: true,
    autoplay: {
      delay: 4000,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
  });
</script>

<script>
  function goToCards() {
    const hero = document.getElementById('heroView');
    const cards = document.getElementById('cardsView');

    anime({
      targets: hero,
      opacity: [1, 0],
      duration: 500,
      easing: 'easeInOutQuad',
      complete: () => {
        hero.classList.add('hidden');
        cards.classList.remove('hidden');
        anime({
          targets: cards,
          opacity: [0, 1],
          translateY: [50, 0],
          duration: 800,
          easing: 'easeOutExpo'
        });
      }
    });
  }
</script>
</body>
</html>
