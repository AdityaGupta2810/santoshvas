<?php
require_once './user/includes/header.php';
?>

<!-- Welcome Banner (Optional) -->
<div class="bg-blue-900 text-white py-2 px-4 text-center">
    <p class="text-sm">Welcome to Santosh Vastralay - Your Premium Fabric Destination</p>
</div>

    <!-- Welcome Section
    <section class="text-center  text-white bg-blue-900 py-4 rounded-2xl">
        <h2 class="text-2xl font-semibold ">Welcome to Santosh Vastralay</h2>
        <p class=" font-semibold mt-2">Your one-stop shop for all fabric needs!</p>
    </section> -->
    <!-- Offer Banner Section -->
    <!-- <section class="pt-1.5 text-center">
        <div class="bg-pink-900 text-white p-4 rounded-lg shadow-lg text-lg font-semibold">
            Limited Time Offer! Up to 50% off on selected fabrics. Shop Now!
        </div>
    </section> -->

   

    <!-- Carousel Section with proper spacing -->
    <div > 
        <div id="carousel" class="relative w-full max-h-[600px] overflow-hidden ">
            <div class="carousel-inner flex transition-transform duration-500 ease-in-out h-full">
                <div class="carousel-item w-full flex-shrink-0 relative">
                    <img src="Home/images/carosel1.webp" class="w-full h-full object-fit" alt="Premium Saree Collection">
                    <!-- <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/50 to-transparent"></div>
                    <div class="carousel-caption absolute bottom-15 left-0 right-0 p-8 text-center">
                        <h5 class="text-2xl font-bold text-white mb-4 animate-fadeIn">Exclusive Saree Collection</h5>
                        <p class="text-xl text-white mb-6">Discover our premium range of traditional and designer sarees</p>
                        
                    </div> -->
                </div>
                <div class="carousel-item w-full flex-shrink-0 relative">
                    <img src="Home/images/carousel2.webp" class="w-full h-full object-fit" alt="Designer Suits">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                    <!-- <div class="carousel-caption absolute bottom-15 left-0 right-0 p-8 text-center">
                        <h5 class="text-2xl font-bold text-white mb-4 animate-fadeIn">Designer Suit Fabrics</h5>
                        <p class="text-xl text-white mb-6">Premium fabrics for your perfect outfit</p>
                        
                    </div> -->
                </div>
                <div class="carousel-item w-full flex-shrink-0 relative">
                    <img src="Home/images/carousel3.webp" class="w-full h-full object-fit" alt="Home Furnishing">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                    <!-- <div class="carousel-caption absolute bottom-15 left-0 right-0 p-8 text-center">
                        <h5 class="text-2xl font-bold text-white mb-4 animate-fadeIn">Home Furnishing Collection</h5>
                        <p class="text-xl text-white mb-6">Transform your home with our exclusive range</p>
                        
                    </div> -->
                </div>
            </div>
            
            <!-- Navigation Buttons with adjusted z-index -->
            <button id="carousel-prev" class="carousel-control absolute top-1/2 left-4 transform -translate-y-1/2 bg-black/50 text-white w-12 h-12 rounded-full flex items-center justify-center hover:bg-black/70 transition-all z-20">
                <i class="fas fa-chevron-left text-2xl"></i>
            </button>
            <button id="carousel-next" class="carousel-control absolute top-1/2 right-4 transform -translate-y-1/2 bg-black/50 text-white w-12 h-12 rounded-full flex items-center justify-center hover:bg-black/70 transition-all z-20">
                <i class="fas fa-chevron-right text-2xl"></i>
            </button>

            <!-- Carousel Indicators with adjusted position -->
            <div class="absolute bottom-8 left-0 right-0 flex justify-center space-x-2 z-20">
                <button class="w-3 h-3 rounded-full bg-white/50 hover:bg-white transition-all indicator active"></button>
                <button class="w-3 h-3 rounded-full bg-white/50 hover:bg-white transition-all indicator"></button>
                <button class="w-3 h-3 rounded-full bg-white/50 hover:bg-white transition-all indicator"></button>
            </div>
        </div>
    </div>

  

    

    <!-- Hero Banner Section -->
    <section class="relative w-full max-w-8xl mx-auto py-1.5  mt-1">
        <img src="Home/images/bridal.jpg" alt="Bridal Collection" class="w-full h-auto rounded-lg shadow-lg">
        <img src="Home/images/mid_coruoser.jpg" alt="Mid Carousel" class="w-full max-h-[450px] object-fit rounded-lg shadow-lg mt-4">
    </section>

    <!-- Product Categories Section -->

    <h1 class="text-4xl font-semibold text-blue-900 text-center my-8 underline">Shop by Categories</h1>
    <section class="p-6 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <a href="Home/landingpage.php?category=sarees" class="group">
            <div class="bg-white p-4 rounded-lg shadow-lg transition-transform duration-300 hover:scale-105">
                <img src="Home/images/saree.jpg" alt="Sarees" class="w-full h-64 object-cover rounded-lg">
                <h3 class="text-xl font-semibold mt-4 text-center group-hover:text-blue-900">Sarees</h3>
                <p class="text-gray-600 text-center mt-2">Traditional & Designer Collections</p>
            </div>
        </a>
        <a href="Home/landingpage.php?category=suits" class="group">
            <div class="bg-white p-4 rounded-lg shadow-lg transition-transform duration-300 hover:scale-105">
                <img src="Home/images/suit.jpg" alt="Suits" class="w-full h-64 object-cover rounded-lg">
                <h3 class="text-xl font-semibold mt-4 text-center group-hover:text-blue-900">Suits</h3>
                <p class="text-gray-600 text-center mt-2">Designer Suit Materials</p>
            </div>
        </a>
        <a href="Home/landingpage.php?category=lehenga" class="group">
            <div class="bg-white p-4 rounded-lg shadow-lg transition-transform duration-300 hover:scale-105">
                <img src="https://5.imimg.com/data5/SELLER/Default/2023/4/297448710/UW/CX/SX/53170449/bridal-lehenga-1000x1000.jpg" alt="Lehengas" class="w-full h-64 object-fit rounded-lg">
                <h3 class="text-xl font-semibold mt-4 text-center group-hover:text-blue-900">Lehengas</h3>
                <p class="text-gray-600 text-center mt-2">Bridal & Party Wear</p>
            </div>
        </a>
        <a href="Home/landingpage.php?category=shirts" class="group">
            <div class="bg-white p-4 rounded-lg shadow-lg transition-transform duration-300 hover:scale-105">
                <img src="https://image.made-in-china.com/2f0j00JvCVRnlkZtzu/Luxury-Cotton-Oxford-Casual-Shirt-Mens-Formal-Dress-Shirts.webp" alt="Shirt Fabrics" class="w-full h-64 object-fit rounded-lg">
                <h3 class="text-xl font-semibold mt-4 text-center group-hover:text-blue-900">Shirt Fabrics</h3>
                <p class="text-gray-600 text-center mt-2">Premium Shirt Materials</p>
            </div>
        </a>
        <a href="Home/landingpage.php?category=curtains" class="group">
            <div class="bg-white p-4 rounded-lg shadow-lg transition-transform duration-300 hover:scale-105">
                <img src="Home/images/curtain.gif" alt="Curtains" class="w-full h-64 object-cover rounded-lg">
                <h3 class="text-xl font-semibold mt-4 text-center group-hover:text-blue-900">Curtains</h3>
                <p class="text-gray-600 text-center mt-2">Home Decor Collection</p>
            </div>
        </a>
        <a href="Home/landingpage.php?category=bedsheets" class="group">
            <div class="bg-white p-4 rounded-lg shadow-lg transition-transform duration-300 hover:scale-105">
                <img src="Home/images/bedsheet.jpg" alt="Bedsheets" class="w-full h-64 object-cover rounded-lg">
                <h3 class="text-xl font-semibold mt-4 text-center group-hover:text-blue-900">Bedsheets</h3>
                <p class="text-gray-600 text-center mt-2">Premium Bedding Collection</p>
            </div>
        </a>
        <a href="Home/landingpage.php?category=blankets" class="group">
            <div class="bg-white p-4 rounded-lg shadow-lg transition-transform duration-300 hover:scale-105">
                <img src="Home/images/blanket.jpg" alt="Blankets" class="w-full h-64 object-cover rounded-lg">
                <h3 class="text-xl font-semibold mt-4 text-center group-hover:text-blue-900">Blankets</h3>
                <p class="text-gray-600 text-center mt-2">Comfort & Warmth</p>
            </div>
        </a>
        <a href="Home/landingpage.php?category=shawls" class="group">
            <div class="bg-white p-4 rounded-lg shadow-lg transition-transform duration-300 hover:scale-105">
                <img src="https://pashtush.in/cdn/shop/products/pashtush-pashmina-pashtush-women-pure-wool-woolmark-certified-shawl-ethnic-weave-design-multicolour-31038810390582.jpg?v=1657368239&width=1080" alt="Shawls" class="w-full h-64 object-fit rounded-lg">
                <h3 class="text-xl font-semibold mt-4 text-center group-hover:text-blue-900">Shawls</h3>
                <p class="text-gray-600 text-center mt-2">Elegant Collection</p>
            </div>
        </a>
    </section>

    <!-- Special Collections Section -->
    <section class="py-12 bg-gray-100">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-semibold text-blue-900 text-center mb-8">Special Collections</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Bridal Collection -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <img src="https://medias.utsavfashion.com/media/wysiwyg/promotions/2024/2811/wedding-fashion-page-28nov_04.jpg" alt="Bridal Collection" class="w-full h-80 object-fit">
                    <div class="p-6">
                        <h3 class="text-2xl font-semibold mb-2">Bridal Collection</h3>
                        <p class="text-gray-600 mb-4">Exclusive fabrics for your special day</p>
                        <a href="Home/landingpage.php?category=bridal" class="inline-block bg-blue-900 text-white px-6 py-2 rounded-lg hover:bg-blue-800">Explore Now</a>
                    </div>
                </div>
                <!-- Festival Collection -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <img src="https://apisap.fabindia.com/medias/wmn-clp-sec07-11apr25-01.jpg?context=bWFzdGVyfGltYWdlc3w2MDI3NXxpbWFnZS9qcGVnfGFEQmtMMmhoWkM4NU5qZzNNek0zTmpnd09EazVNQzkzYlc0dFkyeHdMWE5sWXpBM0xURXhZWEJ5TWpVdE1ERXVhbkJufDUxOTY0ZmM3N2MwZmY0YjgyNTY0ZjE3MzdmNjQ0ZmFiM2Q1YzIzYTg5ZThkZDdjOGJiZDAyNzc4MDkzMTRjNzU" alt="Festival Collection" class="w-full h-80 object-fit">
                    <div class="p-6">
                        <h3 class="text-2xl font-semibold mb-2">Festival Collection</h3>
                        <p class="text-gray-600 mb-4">Celebrate in style with our festive range</p>
                        <a href="Home/landingpage.php?category=festival" class="inline-block bg-blue-900 text-white px-6 py-2 rounded-lg hover:bg-blue-800">Shop Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-8">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-center">
                <div class="p-4">
                    <i class="fas fa-truck text-4xl text-blue-900 mb-4"></i>
                    <h3 class="text-lg font-semibold">Free Shipping</h3>
                    <p class="text-gray-600">On orders above ₹999</p>
                </div>
                <div class="p-4">
                    <i class="fas fa-medal text-4xl text-blue-900 mb-4"></i>
                    <h3 class="text-lg font-semibold">Premium Quality</h3>
                    <p class="text-gray-600">Guaranteed authentic fabrics</p>
                </div>
                <div class="p-4">
                    <i class="fas fa-exchange-alt text-4xl text-blue-900 mb-4"></i>
                    <h3 class="text-lg font-semibold">Easy Returns</h3>
                    <p class="text-gray-600">7-day return policy</p>
                </div>
                <div class="p-4">
                    <i class="fas fa-headset text-4xl text-blue-900 mb-4"></i>
                    <h3 class="text-lg font-semibold">24/7 Support</h3>
                    <p class="text-gray-600">Dedicated customer service</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer Section -->


    <footer class="bg-gray-400 text-black py-8">
        <div class="container mx-auto px-6 lg:px-8">
            <!-- Grid Layout -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Contact Us -->
                <div class="mb-6 md:mb-0">
                    <h2 class="text-xl font-bold mb-4">Contact Us</h2>
                    <ul class="space-y-2 text-black">
                        <li><i class="fas fa-map-marker-alt mr-2"></i>Bishunpura, Gopalganj, Bihar 841407, India</li>
                        <li><i class="fas fa-phone-alt mr-2"></i>+91 9006645817</li>
                        <li><i class="fas fa-envelope mr-2"></i>santoshvastraly@gmail.com</li>
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
                    <p>&copy; 2025 Santosh Vastralay. All rights reserved.</p>
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

    

  

    <!-- Carousel Script -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const carouselInner = document.querySelector('.carousel-inner');
        const carouselItems = document.querySelectorAll('.carousel-item');
        const prevButton = document.getElementById('carousel-prev');
        const nextButton = document.getElementById('carousel-next');
        const indicators = document.querySelectorAll('.indicator');
        
        let currentIndex = 0;
        const totalItems = carouselItems.length;

        // Function to update carousel position and indicators
        const updateCarousel = () => {
            const offset = -currentIndex * 100;
            carouselInner.style.transform = `translateX(${offset}%)`;
            
            // Update indicators
            indicators.forEach((indicator, index) => {
                if (index === currentIndex) {
                    indicator.classList.add('active');
                } else {
                    indicator.classList.remove('active');
                }
            });

            // Animate caption text
            carouselItems.forEach((item, index) => {
                const caption = item.querySelector('.carousel-caption');
                if (index === currentIndex) {
                    caption.style.opacity = '0';
                    setTimeout(() => {
                        caption.style.opacity = '1';
                        caption.querySelector('h5').style.animation = 'fadeIn 0.5s ease-out forwards';
                        caption.querySelector('p').style.animation = 'fadeIn 0.5s ease-out 0.2s forwards';
                        caption.querySelector('a').style.animation = 'fadeIn 0.5s ease-out 0.4s forwards';
                    }, 100);
                }
            });
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

        // Indicator functionality
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                currentIndex = index;
                updateCarousel();
            });
        });

        // Auto-play carousel with smooth transitions
        let autoplayInterval = setInterval(() => {
            currentIndex = (currentIndex + 1) % totalItems;
            updateCarousel();
        }, 5000);

        // Pause autoplay on hover
        carouselInner.addEventListener('mouseenter', () => {
            clearInterval(autoplayInterval);
        });

        // Resume autoplay when mouse leaves
        carouselInner.addEventListener('mouseleave', () => {
            autoplayInterval = setInterval(() => {
                currentIndex = (currentIndex + 1) % totalItems;
                updateCarousel();
            }, 5000);
        });

        // Initial setup
        updateCarousel();
    });
    </script>
    
    <!-- Update the style section -->
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fadeIn {
            animation: fadeIn 0.5s ease-out forwards;
        }

        .carousel-item {
            transition: transform 0.5s ease-in-out;
        }

        .carousel-caption {
            background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.6) 50%, transparent 100%);
            padding-top: 100px; /* Increased padding for better text visibility */
        }

        .indicator.active {
            background-color: white;
            width: 24px;
            transition: all 0.3s ease;
        }

        /* Ensure carousel is positioned correctly */
        #carousel {
            position: relative;
            z-index: 10;
        }

        /* Improve navigation button visibility */
        .carousel-control {
            opacity: 0.7;
            transition: opacity 0.3s ease;
        }

        .carousel-control:hover {
            opacity: 1;
        }

        /* Ensure proper stacking context */
        .carousel-inner {
            z-index: 1;
        }

        /* Improve text readability */
        .carousel-caption h5 {
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }

        .carousel-caption p {
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        }
    </style>
    
<?php
require './user/includes/footer.php';
?>