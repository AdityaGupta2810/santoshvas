<?php $title="Register page"; require "./includes/header.php"; ?>

<div class="min-h-screen flex justify-center items-center bg-gradient-to-br from-indigo-700 via-blue-600 to-purple-800 py-12 px-4">
    <div class="w-full max-w-md bg-white rounded-xl shadow-2xl overflow-hidden">
        <!-- Top decorative bar -->
        <div class="h-2 bg-gradient-to-r from-gold-400 via-gold-500 to-gold-400"></div>
        
        <div class="px-8 pt-8 pb-10">
            <!-- Elegant header with decorative elements -->
            <div class="text-center mb-8">
                <h1 class="font-serif text-4xl mb-2 text-gray-800">Become a Member</h1>
                <div class="flex items-center justify-center">
                    <div class="h-px w-12 bg-gradient-to-r from-transparent via-gray-400 to-transparent"></div>
                    <span class="mx-3 text-gold-500 text-xl">&diams;</span>
                    <div class="h-px w-12 bg-gradient-to-r from-transparent via-gray-400 to-transparent"></div>
                </div>
                <p class="text-gray-600 mt-6 italic font-serif">Discover exclusive benefits</p>
            </div>
            
            <!-- Form area with refined styling -->
            <form method="post" action="../actions/regaction.php" class="space-y-6">
                <div class="relative">
                    <input class="w-full border-b-2 border-gray-300 px-3 py-3 focus:border-gold-500 transition-colors outline-none text-gray-700 bg-gray-50 rounded-t-md" name="uname" placeholder="Full Name" required>
                    <div class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                
                <div class="relative">
                    <input class="w-full border-b-2 border-gray-300 px-3 py-3 focus:border-gold-500 transition-colors outline-none text-gray-700 bg-gray-50 rounded-t-md" type="email" name="uemail" placeholder="Email Address" required>
                    <div class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                        </svg>
                    </div>
                </div>
                
                <div class="relative">
                    <input class="w-full border-b-2 border-gray-300 px-3 py-3 focus:border-gold-500 transition-colors outline-none text-gray-700 bg-gray-50 rounded-t-md" type="password" name="upass" placeholder="Password" required>
                    <div class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                
                <div class="flex items-center space-x-3 mt-6">
                    <input class="w-4 h-4 border-2 border-gold-400 rounded accent-gold-500 focus:ring focus:ring-gold-300" type="checkbox" name="rem" id="check">
                    <label for="check" class="text-gray-600 font-medium">Remember me</label>
                </div>
                
                <button class="w-full py-3 px-6 text-white font-medium rounded-md bg-gradient-to-r from-blue-600 to-purple-600 shadow-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-300 uppercase tracking-wide">
                    Register Account
                </button>
            </form>
            
            <!-- Elegantly styled login link -->
            <div class="text-center mt-8 border-t pt-6 border-gray-200">
                <p class="text-gray-600">Already have an account?</p>
                <a href="login.php" class="mt-2 inline-block text-blue-600 hover:text-blue-800 font-medium transition-colors duration-300 border-b border-blue-400 hover:border-blue-600">Sign In &rarr;</a>
            </div>
        </div>
    </div>
</div>

<?php require "./includes/footer.php"; ?>