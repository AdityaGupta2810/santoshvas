<?php $title= "Login page"; require(__DIR__."/includes/header.php"); ?>

<div class="w-full min-h-screen flex justify-center items-center bg-gradient-to-r from-blue-600 to-purple-700 py-8">
    <div class="bg-white shadow-xl rounded-2xl max-w-md w-full mx-4 overflow-hidden">
        <!-- Header section -->
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-6 text-white">
            <h1 class="font-bold text-3xl text-center">Welcome Back</h1>
            <p class="text-center text-blue-100 mt-2">Sign in to continue to your account</p>
        </div>
        
        <!-- Form section -->
        <div class="p-8">
            <form method="post" action="../actions/logaction.php" class="space-y-6">
                <div class="space-y-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 outline-none"
                            type="email" 
                            id="email"
                            name="uemail" 
                            placeholder="Enter your email" 
                            required
                        >
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 outline-none"
                            type="password" 
                            id="password"
                            name="upass" 
                            placeholder="Enter your password"
                        >
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input 
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            type="checkbox"
                            id="check"
                            name="rem"
                        >
                        <label for="check" class="ml-2 block text-sm text-gray-700">Remember me</label>
                    </div>
                    
                    <!-- <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-800">Forgot password?</a> -->
                </div>
                
                <div>
                    <button 
                        type="submit"
                        class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium py-3 px-4 rounded-lg hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200"
                    >
                        Sign In
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Footer section -->
        <div class="bg-gray-50 px-8 py-6 border-t border-gray-200">
            <p class="text-center text-gray-600">
                Don't have an account? 
                <a href="reg.php" class="font-medium text-blue-600 hover:text-blue-800">Create one now</a>
            </p>
        </div>
    </div>
</div>

<?php require "./includes/footer.php"; ?>