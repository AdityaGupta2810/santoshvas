<?php
require_once './user/includes/header.php';
?>


    <!-- Welcome Section -->
    <section class="text-center  text-black bg-rose-300 py-4">
        <h2 class="text-3xl font-semibold ">Welcome to Santosh Vastralay</h2>
        <p class=" font-semibold mt-2">Your one-stop shop for all fabric needs!</p>
    </section>
    <!-- Offer Banner Section -->
    <!-- <section class="pt-1.5 text-center">
        <div class="bg-pink-900 text-white p-4 rounded-lg shadow-lg text-lg font-semibold">
            Limited Time Offer! Up to 50% off on selected fabrics. Shop Now!
        </div>
    </section> -->

   

    <!-- Carousel Section -->
   
    <div id="carousel" class="relative w-full overflow-hidden ">
        <div class="carousel-inner flex transition-transform duration-500 ease-in-out">
            <div class="carousel-item w-full flex-shrink-0">
                <img src="Home/images/carosel1.webp" class="w-full" alt="Slide 1">
                <div class="carousel-caption absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white p-4">
                    <h5 class="text-xl">First Slide Label</h5>
                    <p>Some representative placeholder content for the first slide.</p>
                </div>
            </div>
            <div class="carousel-item w-full flex-shrink-0">
                <img src="Home/images/carousel2.webp" class="w-full" alt="Slide 2">
                <div class="carousel-caption absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white p-4">
                    <h5 class="text-xl">Second Slide Label</h5>
                    <p>Some representative placeholder content for the second slide.</p>
                </div>
            </div>
            <div class="carousel-item w-full flex-shrink-0">
                <img src="Home/images/carousel3.webp" class="w-full" alt="Slide 3">
                <div class="carousel-caption absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white p-4">
                    <h5 class="text-xl">Third Slide Label</h5>
                    <p>Some representative placeholder content for the third slide.</p>
                </div>
            </div>
        </div>
        <button id="carousel-prev" class="carousel-control-prev absolute top-1/2 left-4 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button id="carousel-next" class="carousel-control-next absolute top-1/2 right-4 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>

  

    

    <!-- Hero Banner Section -->
    <section class="relative w-full max-w-8xl mx-auto py-1.5  mt-6">
        <img src="Home/images/bridal.jpg" alt="Bridal Collection" class="w-full h-auto rounded-lg shadow-lg">
        <img src="Home/images/mid_coruoser.jpg" alt="Mid Carousel" class="w-full max-h-[410px] object-cover rounded-lg shadow-lg mt-4">
    </section>

    <!-- Product Categories Section -->

    <h1 class="text-4xl font-semibold text-blue-900 text-center m-3 underline" >Shop by Categories</h1>
    <section class="p-6 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded shadow">
            <img src="Home/images/saree.jpg" alt="Sarees" class="w-full h-40 object-cover rounded">
            <h3 class="text-lg font-semibold mt-2">Sarees</h3>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <img src="Home/images/suit.jpg" alt="Suits" class="w-full h-40 object-cover rounded">
            <h3 class="text-lg font-semibold mt-2">Suits</h3>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <img src="Home/images/suit.jpg" alt="Shirt Fabric" class="w-full h-40 object-cover rounded">
            <h3 class="text-lg font-semibold mt-2">Shirt Fabric</h3>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <img src="Home/images/bedsheet.jpg" alt="Bedsheets" class="w-full h-40 object-cover rounded">
            <h3 class="text-lg font-semibold mt-2">Bedsheets</h3>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <img src="./Home/images/mens.gif" alt="Men's Collection" class="w-full h-50 object-cover rounded">
            <h3 class="text-lg font-semibold mt-2">Men's Collection</h3>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <img src="Home/images/saree.gif" alt="Saree Collection" class="w-full h-50 object-cover rounded">
            <h3 class="text-lg font-semibold mt-2">Saree Collection</h3>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <img src="Home/images/curtain.gif" alt="Curtains" class="w-full h-50 object-cover rounded">
            <h3 class="text-lg font-semibold mt-2">Curtains</h3>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <img src="Home/images/lahenga.gif" alt="Lehengas" class="w-full h-50 object-cover rounded">
            <h3 class="text-lg font-semibold mt-2">Lehengas</h3>
        </div>
    </section>

    <!-- Men's Kurta Section -->
    <section class="p-6 bg-gray-200 text-center">
        <h2 class="text-3xl font-semibold text-blue-900">Men's Kurta Collection</h2>
        <div class="flex flex-wrap justify-center items-center mt-4">
            <div class="w-full md:w-1/2 lg:w-1/3 p-4">
                <img src="Home/images/mens_kurta.webp" alt="Men's Kurta" class="w-full h-auto rounded-lg shadow-lg">
            </div>
            <div class="w-full md:w-1/2 lg:w-1/3 p-4 text-left">
                <p class="text-gray-700 text-lg">Discover our premium collection of men's kurtas, perfect for every occasion. From casual to festive, our designs blend tradition with modern elegance.</p>
                <button class="bg-blue-900 text-white px-4 py-2 rounded mt-4">Shop Now</button>
            </div>
        </div>
    </section>

    <section class="p-6 bg-gray-200 text-center">
        <h2 class="text-3xl font-semibold text-blue-900">TRENDING NOW</h2>
        <div class="flex flex-wrap justify-center items-center mt-4">
            <div class="w-full md:w-1/2 lg:w-1/3 p-4">
                <img src="https://images-static.nykaa.com/uploads/a312663b-caaa-464a-b5f1-e3522a636e26.jpg?tr=w-1200,cm-pad_resize" alt="Men's Kurta" class="w-full h-auto rounded-lg shadow-lg">
            </div>
            <div class="w-full md:w-1/2 lg:w-1/3 p-4 text-left">
                <p class="text-gray-700 text-lg">Discover our premium collection of men's kurtas, perfect for every occasion. From casual to festive, our designs blend tradition with modern elegance.</p>
                <button class="bg-blue-900 text-white px-4 py-2 rounded mt-4">Shop Now</button>
            </div>
        </div>
    </section>

    <!-- Express Shipping Banner -->
    <!-- <section class="p-6 text-center">
        <img src="https://images-static.nykaa.com/uploads/bf163ec1-190b-4e25-99df-93c400c971fa.gif?tr=w-1200,cm-pad_resize" alt="Express Shipping" class="w-full h-auto rounded-lg shadow-lg">
    </section> -->

    <!-- Shipping Banner -->
    <div class="m-3 p-4">
        <img src="https://images-static.nykaa.com/uploads/976c27dd-1f62-4a47-90ec-cd3171dbe1bf.gif?tr=w-1200,cm-pad_resize" class="w-full h-auto rounded-lg shadow-lg" alt="Shipping Banner">
    </div>

    <!-- Product Showcase Section -->
    <div class="flex flex-wrap justify-center gap-3 p-4">
        <div class="w-35 sm:w-48 md:w-56 lg:w-50 shadow-lg rounded-lg overflow-hidden">
            <img src="https://images-static.nykaa.com/uploads/325e9399-4978-4565-a8fb-8fb98c343dec.jpg?tr=w-240,cm-pad_resize" class="w-full h-auto" alt="Product 1">
        </div>
        <div class="w-35 sm:w-48 md:w-56 lg:w-50 shadow-lg rounded-lg overflow-hidden">
            <img src="https://images-static.nykaa.com/uploads/c0410067-a229-4fa9-aa0d-38f8f67364d8.jpg?tr=w-240,cm-pad_resize" class="w-full h-auto" alt="Product 2">
        </div>
        <div class="w-35 sm:w-48 md:w-56 lg:w-50 shadow-lg rounded-lg overflow-hidden">
            <img src="https://images-static.nykaa.com/uploads/3031e2b6-fb85-42df-a219-b022bede91d9.jpg?tr=w-240,cm-pad_resize" class="w-full h-auto" alt="Product 3">
        </div>
        <div class="w-35 sm:w-48 md:w-56 lg:w-50 shadow-lg rounded-lg overflow-hidden">
            <img src="https://images-static.nykaa.com/uploads/f1d678bc-a71e-4b9e-9062-c69e20f3c279.jpg?tr=w-240,cm-pad_resize" class="w-full h-auto" alt="Product 4">
        </div>
        <div class="w-35 sm:w-48 md:w-56 lg:w-50 shadow-lg rounded-lg overflow-hidden">
            <img src="https://images-static.nykaa.com/uploads/4e5b5719-6a32-4443-acb0-46db0be1a18d.jpg?tr=w-240,cm-pad_resize" class="w-full h-auto" alt="Product 5">
        </div>
    </div>

    <!-- Footer Section -->


    <footer class="bg-gray-400 text-black py-8">
        <div class="container mx-auto px-6 lg:px-8">
            <!-- Grid Layout -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Contact Us -->
                <div class="mb-6 md:mb-0">
                    <h2 class="text-xl font-bold mb-4">Contact Us</h2>
                    <ul class="space-y-2 text-black">
                        <li><i class="fas fa-map-marker-alt mr-2"></i>123 Fabric Street, Textile City, India</li>
                        <li><i class="fas fa-phone-alt mr-2"></i>+91 123 456 7890</li>
                        <li><i class="fas fa-envelope mr-2"></i>info@santoshvastralay.com</li>
                    </ul>
                </div>
    
                <!-- Services -->
                <div class="mb-6 md:mb-0">
                    <h2 class="text-xl font-bold mb-4">Services</h2>
                    <ul class="space-y-2 text-black">
                        <li><a href="#" class="hover:text-white">Custom Tailoring</a></li>
                        <li><a href="#" class="hover:text-white">Bulk Orders</a></li>
                        <li><a href="#" class="hover:text-white">Fabric Consultation</a></li>
                        <li><a href="#" class="hover:text-white">Home Delivery</a></li>
                        <li><a href="#" class="hover:text-white">Returns & Exchanges</a></li>
                    </ul>
                </div>
    
                <!-- Follow Us -->
                <div class="mb-6 md:mb-0">
                    <h2 class="text-xl font-bold mb-4">Follow Us</h2>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-900 hover:text-white"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-gray-900 hover:text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-900 hover:text-white"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-gray-900 hover:text-white"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="text-gray-900 hover:text-white"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
    
                <!-- Newsletter -->
                <div>
                    <h2 class="text-xl font-bold mb-4">Subscribe to Newsletter</h2>
                    <p class="text-black mb-4">Get the latest updates on new products and exclusive offers.</p>
                    <form class="flex">
                        <input type="email" placeholder="Enter your email" class="p-2 rounded-l w-full bg-gray-800 text-white border border-gray-700 focus:outline-none">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-r hover:bg-blue-700 transition-colors">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
    
            <!-- Divider -->
            <hr class="border-white my-6">
    
            <!-- Bottom Footer -->
            <div class="text-center text-black">
                <!-- Payment Gateway Icons and Copyright Text -->
                <div class="flex flex-wrap justify-center items-center gap-8">
                    
                    <!-- Copyright Text -->
                    <p>&copy; 2024 Santosh Vastralay. All rights reserved.</p>
                    <!-- Designed By -->
                    <p>Designed by <a href="#" class="text-blue-600 hover:text-blue-500">Aditya Gupta   </a>    </p>
                    <!-- Payment Icons -->

                    <div class="flex space-x-4 pl-40"> |
                        <i class="fab fa-cc-visa text-2xl text-blue-600 hover:text-blue-500"></i>
                        | <i class="fab fa-cc-mastercard text-2xl text-red-600 hover:text-red-500"></i>
                       | <i class="fab fa-cc-paypal text-2xl text-blue-500 hover:text-blue-400"></i>
                       | <i class="fab fa-cc-amex text-2xl text-green-600 hover:text-green-500"></i>
                       | <i class="fab fa-cc-discover text-2xl text-orange-600 hover:text-orange-500"></i>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- <script>
        // Toggle sidebar for small screens
        const sidebar = document.getElementById("sidebar");
        const sidebarToggle = document.getElementById("sidebarToggle");

        sidebarToggle.addEventListener("click", () => {
            sidebar.classList.toggle("-translate-x-full");
        });

        // Toggle user dropdown on hover for larger screens
        const userMenuButton = document.getElementById("userMenuButton");
        const userMenuDropdown = document.getElementById("userMenuDropdown");

        userMenuButton.addEventListener("mouseenter", () => {
            userMenuDropdown.classList.remove("hidden");
        });

        userMenuButton.addEventListener("mouseleave", () => {
            userMenuDropdown.classList.add("hidden");
        });


        // Toggle user dropdown on click for mobile
        const userMenuButtonMobile = document.getElementById("userMenuButtonMobile");
        const userMenuDropdownMobile = document.getElementById("userMenuDropdownMobile");

        userMenuButtonMobile.addEventListener("click", () => {
            userMenuDropdownMobile.classList.toggle("hidden");
        });
    </script> -->

  

    <!-- Carousel Script -->
    <script>
        const carouselInner = document.querySelector('.carousel-inner');
        const carouselItems = document.querySelectorAll('.carousel-item');
        const prevButton = document.getElementById('carousel-prev');
        const nextButton = document.getElementById('carousel-next');

        let currentIndex = 0;
        const totalItems = carouselItems.length;

        // Function to update carousel position
        const updateCarousel = () => {
            const offset = -currentIndex * 100;
            carouselInner.style.transform = `translateX(${offset}%)`;
        };

        // Next button functionality
        nextButton.addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % totalItems;
            updateCarousel();
        });

        // Previous button functionality
        prevButton.addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + totalItems) % totalItems;
            updateCarousel();
        });

        // Auto-play carousel
        setInterval(() => {
            currentIndex = (currentIndex + 1) % totalItems;
            updateCarousel();
        }, 5000); // Change slide every 5 seconds
    </script>
    
<?php
require './user/includes/footer.php';
?>