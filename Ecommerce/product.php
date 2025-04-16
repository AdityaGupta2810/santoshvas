<!-- Breadcrumb with improved styling -->
<div class="bg-gray-100 border-b">
    <div class="container mx-auto px-4 py-3">
        <nav class="text-sm">
            <ol class="list-none p-0 inline-flex items-center">
                <li class="flex items-center">
                    <a href="/santoshvas/Ecommerce/Home/landingpage.php" class="text-blue-600 hover:text-blue-800">Home</a>
                </li>
                <?php if(isset($product['tcat_id']) && isset($product['tcat_name'])): ?>
                <li class="flex items-center">
                    <svg class="w-4 h-4 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <a href="/santoshvas/Ecommerce/Home/landingpage.php?tcat_id=<?= $product['tcat_id'] ?>" class="text-blue-600 hover:text-blue-800">
                        <?= htmlspecialchars($product['tcat_name']) ?>
                    </a>
                </li>
                <?php endif; ?>
                <!-- ... other breadcrumb items remain the same ... -->
            </ol>
        </nav>
    </div>
</div>

<!-- Main Content with enhanced styling -->
<div class="container mx-auto px-4 py-8">
    <?php if(isset($success_message)): ?>
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r shadow-sm" role="alert">
        <div class="flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span><?= $success_message ?></span>
        </div>
    </div>
    <?php endif; ?>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="md:flex">
            <!-- Image Gallery with improved styling -->
            <div class="md:w-1/2 lg:w-3/5">
                <div class="product-gallery bg-gray-50">
                    <div class="main-image-container relative h-[500px] md:h-[600px] overflow-hidden">
                        <?php foreach($product_images as $index => $image): ?>
                        <div class="main-image absolute inset-0 transition-all duration-500 ease-in-out <?= $index === 0 ? 'opacity-100' : 'opacity-0' ?> cursor-zoom-in"
                             data-index="<?= $index ?>"
                             onclick="openLightbox(<?= $index ?>)">
                            <img src="/santoshvas/Ecommerce/admin/uploadimgs/<?= htmlspecialchars($image['photo']) ?>" 
                                 alt="<?= htmlspecialchars($product['p_name']) ?>" 
                                 class="w-full h-full object-contain">
                        </div>
                        <?php endforeach; ?>
                        <!-- Enhanced navigation buttons -->
                        <button class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white/80 hover:bg-white text-gray-800 p-3 rounded-full shadow-lg transition-all duration-300 focus:outline-none"
                                onclick="changeImage('prev')">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <button class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white/80 hover:bg-white text-gray-800 p-3 rounded-full shadow-lg transition-all duration-300 focus:outline-none"
                                onclick="changeImage('next')">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>
                    <!-- Enhanced thumbnail gallery -->
                    <?php if(count($product_images) > 1): ?>
                    <div class="thumbnail-container flex gap-2 p-4 bg-white border-t">
                        <?php foreach($product_images as $index => $image): ?>
                        <div class="thumbnail cursor-pointer transition-transform duration-300 hover:scale-105 <?= $index === 0 ? 'ring-2 ring-blue-500' : '' ?>"
                             onclick="selectImage(<?= $index ?>)">
                            <img src="/santoshvas/Ecommerce/admin/uploadimgs/<?= htmlspecialchars($image['photo']) ?>" 
                                 alt="Thumbnail" 
                                 class="h-24 w-24 object-cover rounded-lg">
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Product Info with enhanced styling -->
            <div class="md:w-1/2 lg:w-2/5 p-8 bg-white">
                <div class="space-y-6">
                    <h1 class="text-3xl font-bold text-gray-900"><?= htmlspecialchars($product['p_name']) ?></h1>
                    
                    <div class="flex items-center space-x-2">
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                            <?= htmlspecialchars($product['ecat_name']) ?>
                        </span>
                        <?php if(!empty($product['p_sku'])): ?>
                        <span class="px-3 py-1 bg-gray-100 text-gray-600 text-sm font-medium rounded-full">
                            SKU: <?= htmlspecialchars($product['p_sku']) ?>
                        </span>
                        <?php endif; ?>
                    </div>

                    <!-- Enhanced price display -->
                    <div class="price-section">
                        <?php if(isset($product['p_old_price']) && $product['p_old_price'] > 0): ?>
                            <div class="flex items-baseline space-x-4">
                                <span class="text-4xl font-bold text-blue-600">₹<?= number_format($product['p_current_price']) ?></span>
                                <span class="text-xl text-gray-400 line-through">₹<?= number_format($product['p_old_price']) ?></span>
                                <?php $discount_percent = round(($product['p_old_price'] - $product['p_current_price']) / $product['p_old_price'] * 100); ?>
                                <span class="px-3 py-1 bg-red-100 text-red-700 text-sm font-semibold rounded-full">
                                    <?= $discount_percent ?>% OFF
                                </span>
                            </div>
                        <?php else: ?>
                            <span class="text-4xl font-bold text-blue-600">₹<?= number_format($product['p_current_price']) ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Enhanced stock status -->
                    <div class="stock-status">
                        <?php if(isset($product['p_qty']) && $product['p_qty'] > 0): ?>
                        <div class="flex items-center space-x-2">
                            <span class="flex items-center px-3 py-1 bg-green-100 text-green-700 rounded-full">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                In Stock
                            </span>
                            <span class="text-sm text-gray-600">(<?= $product['p_qty'] ?> units available)</span>
                        </div>
                        <?php else: ?>
                        <span class="flex items-center px-3 py-1 bg-red-100 text-red-700 rounded-full">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            Out of Stock
                        </span>
                        <?php endif; ?>
                    </div>

                    <!-- Enhanced quantity selector and buttons -->
                    <?php if(isset($product['p_qty']) && $product['p_qty'] > 0): ?>
                    <form action="" method="post" class="space-y-6">
                        <div class="flex items-center space-x-4">
                            <label for="quantity" class="text-gray-700 font-medium">Quantity:</label>
                            <div class="custom-number-input h-10 w-32">
                                <div class="flex rounded-lg border border-gray-300 overflow-hidden">
                                    <button type="button" class="w-10 h-10 bg-gray-50 hover:bg-gray-100 text-gray-600 transition-colors duration-200"
                                            onclick="decrementQuantity()">−</button>
                                    <input type="number" id="quantity" name="quantity" 
                                           class="w-full text-center border-x border-gray-300 focus:outline-none"
                                           value="1" min="1" max="<?= $product['p_qty'] ?>">
                                    <button type="button" class="w-10 h-10 bg-gray-50 hover:bg-gray-100 text-gray-600 transition-colors duration-200"
                                            onclick="incrementQuantity(<?= $product['p_qty'] ?>)">+</button>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4">
                            <button type="submit" name="add_to_cart" 
                                    class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg
                                    transition duration-200 ease-in-out flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span>Add to Cart</span>
                            </button>
                            <button type="submit" name="buy_now" 
                                    class="flex-1 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg
                                    transition duration-200 ease-in-out flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                <span>Buy Now</span>
                            </button>
                        </div>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Enhanced Product Tabs -->
        <div class="border-t border-gray-200">
            <div class="product-tabs" x-data="{ activeTab: 'description' }">
                <div class="flex border-b bg-gray-50">
                    <button @click="activeTab = 'description'" 
                            :class="{ 'border-b-2 border-blue-600 text-blue-600 bg-white': activeTab === 'description' }"
                            class="px-6 py-3 font-medium text-gray-600 hover:text-blue-600 focus:outline-none transition-colors duration-200">
                        Description
                    </button>
                    <button @click="activeTab = 'features'" 
                            :class="{ 'border-b-2 border-blue-600 text-blue-600 bg-white': activeTab === 'features' }"
                            class="px-6 py-3 font-medium text-gray-600 hover:text-blue-600 focus:outline-none transition-colors duration-200">
                        Features
                    </button>
                    <button @click="activeTab = 'conditions'" 
                            :class="{ 'border-b-2 border-blue-600 text-blue-600 bg-white': activeTab === 'conditions' }"
                            class="px-6 py-3 font-medium text-gray-600 hover:text-blue-600 focus:outline-none transition-colors duration-200">
                        Shipping & Returns
                    </button>
                </div>
                <div class="p-6">
                    <!-- Tab content remains the same -->
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Related Products Section -->
    <?php if(mysqli_num_rows($related_result) > 0): ?>
    <div class="mt-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-8">Related Products</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <?php while($related = mysqli_fetch_assoc($related_result)): ?>
            <div class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                <a href="/santoshvas/Ecommerce/Home/product.php?p_id=<?= $related['p_id'] ?>" class="block relative">
                    <img src="/santoshvas/Ecommerce/admin/uploadimgs/<?= htmlspecialchars($related['p_featured_photo']) ?>" 
                         alt="<?= htmlspecialchars($related['p_name']) ?>" 
                         class="w-full h-56 object-cover">
                    <?php if(isset($related['p_old_price']) && $related['p_old_price'] > 0): ?>
                    <?php $discount = round(($related['p_old_price'] - $related['p_current_price']) / $related['p_old_price'] * 100); ?>
                    <span class="absolute top-2 right-2 bg-red-500 text-white text-sm font-bold px-2 py-1 rounded">
                        <?= $discount ?>% OFF
                    </span>
                    <?php endif; ?>
                </a>
                <div class="p-4">
                    <a href="/santoshvas/Ecommerce/Home/product.php?p_id=<?= $related['p_id'] ?>" class="block">
                        <h3 class="text-lg font-semibold text-gray-900 hover:text-blue-600 truncate">
                            <?= htmlspecialchars($related['p_name']) ?>
                        </h3>
                    </a>
                    <p class="text-sm text-gray-500 mt-1"><?= htmlspecialchars($related['ecat_name']) ?></p>
                    <div class="mt-2 flex items-baseline space-x-2">
                        <span class="text-lg font-bold text-blue-600">₹<?= number_format($related['p_current_price']) ?></span>
                        <?php if(isset($related['p_old_price']) && $related['p_old_price'] > 0): ?>
                        <span class="text-sm text-gray-400 line-through">₹<?= number_format($related['p_old_price']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Add this right after the opening <body> tag or before the closing </body> tag -->
<div id="lightbox" class="fixed inset-0 bg-black/90 z-50 hidden">
    <button class="absolute top-4 right-4 text-white hover:text-gray-300 z-50" onclick="closeLightbox()">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>
    <button class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 z-50" onclick="changeLightboxImage('prev')">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
    </button>
    <button class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 z-50" onclick="changeLightboxImage('next')">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </button>
    <div class="flex items-center justify-center h-full">
        <img id="lightbox-image" src="" alt="" class="max-h-[90vh] max-w-[90vw] object-contain">
    </div>
</div>

<!-- Add this JavaScript before the closing </body> tag -->
<script>
// Existing JavaScript remains the same

// Add these new functions for lightbox functionality
let lightboxOpen = false;

function openLightbox(index) {
    const lightbox = document.getElementById('lightbox');
    const lightboxImage = document.getElementById('lightbox-image');
    const images = document.querySelectorAll('.main-image img');
    
    lightboxImage.src = images[index].src;
    lightboxImage.alt = images[index].alt;
    lightbox.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    currentIndex = index;
    lightboxOpen = true;

    // Add keyboard event listener
    document.addEventListener('keydown', handleKeyPress);
}

function closeLightbox() {
    const lightbox = document.getElementById('lightbox');
    lightbox.classList.add('hidden');
    document.body.style.overflow = '';
    lightboxOpen = false;

    // Remove keyboard event listener
    document.removeEventListener('keydown', handleKeyPress);
}

function changeLightboxImage(direction) {
    const lightboxImage = document.getElementById('lightbox-image');
    const images = document.querySelectorAll('.main-image img');
    
    if (direction === 'next') {
        currentIndex = (currentIndex + 1) % images.length;
    } else {
        currentIndex = (currentIndex - 1 + images.length) % images.length;
    }
    
    lightboxImage.src = images[currentIndex].src;
    lightboxImage.alt = images[currentIndex].alt;
}

function handleKeyPress(event) {
    if (!lightboxOpen) return;
    
    switch(event.key) {
        case 'ArrowLeft':
            changeLightboxImage('prev');
            break;
        case 'ArrowRight':
            changeLightboxImage('next');
            break;
        case 'Escape':
            closeLightbox();
            break;
    }
}

// Add click outside to close
document.getElementById('lightbox').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLightbox();
    }
});

// Add mouse wheel zoom functionality
document.getElementById('lightbox-image').addEventListener('wheel', function(e) {
    e.preventDefault();
    
    const image = this;
    const rect = image.getBoundingClientRect();
    const mouseX = e.clientX - rect.left;
    const mouseY = e.clientY - rect.top;
    
    // Get current scale
    const currentScale = image.style.transform ? 
        parseFloat(image.style.transform.replace('scale(', '').replace(')', '')) : 1;
    
    // Calculate new scale
    let newScale = currentScale;
    if (e.deltaY < 0) {
        newScale = Math.min(currentScale * 1.1, 3); // Zoom in, max 3x
    } else {
        newScale = Math.max(currentScale / 1.1, 1); // Zoom out, min 1x
    }
    
    // Apply transform
    image.style.transform = `scale(${newScale})`;
    
    // Adjust position to zoom towards mouse pointer
    if (newScale > 1) {
        const offsetX = (mouseX / rect.width) * (rect.width * (newScale - 1));
        const offsetY = (mouseY / rect.height) * (rect.height * (newScale - 1));
        image.style.transformOrigin = `${mouseX}px ${mouseY}px`;
    } else {
        image.style.transformOrigin = 'center center';
    }
});

// Add touch gestures for mobile
let touchStartX = 0;
let touchEndX = 0;

document.getElementById('lightbox').addEventListener('touchstart', function(e) {
    touchStartX = e.changedTouches[0].screenX;
});

document.getElementById('lightbox').addEventListener('touchend', function(e) {
    touchEndX = e.changedTouches[0].screenX;
    handleSwipe();
});

function handleSwipe() {
    const swipeThreshold = 50;
    const diff = touchEndX - touchStartX;
    
    if (Math.abs(diff) > swipeThreshold) {
        if (diff > 0) {
            changeLightboxImage('prev');
        } else {
            changeLightboxImage('next');
        }
    }
}

// Add loading indicator
function showLoading(imageElement) {
    imageElement.style.opacity = '0.5';
    // Add loading spinner if needed
}

function hideLoading(imageElement) {
    imageElement.style.opacity = '1';
}

// Preload images
function preloadImages() {
    const images = document.querySelectorAll('.main-image img');
    images.forEach(img => {
        const preloadLink = document.createElement('link');
        preloadLink.href = img.src;
        preloadLink.rel = 'preload';
        preloadLink.as = 'image';
        document.head.appendChild(preloadLink);
    });
}

// Call preload on page load
window.addEventListener('load', preloadImages);
</script>

<!-- Add these styles to your CSS -->
<style>
.cursor-zoom-in {
    cursor: zoom-in;
}

#lightbox {
    transition: opacity 0.3s ease-in-out;
}

#lightbox.hidden {
    opacity: 0;
    pointer-events: none;
}

#lightbox:not(.hidden) {
    opacity: 1;
    animation: fadeIn 0.3s ease-in-out;
}

#lightbox-image {
    transition: transform 0.3s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* Add smooth transition for zoom */
.main-image img {
    transition: transform 0.3s ease-in-out;
}

/* Add hover effect */
.main-image:hover img {
    transform: scale(1.05);
}
</style> 