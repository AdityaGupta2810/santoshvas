<?php
$title="Register page";
require "C:/xampp/htdocs/santoshvas/Ecommerce/user/includes/header.php";
?>
<body class="container flex justify-center items-center bg-[url('/santoshvas/Ecommerce/Home/images/logiback.webp')] bg-cover bg-center " >
    <div class="bg-gradient-to-r from-white to-red-200 box-content py-4 sm:w-1/2 xl:w-1/3  w-full  border-0 border-solid rounded-xl border-black mt-12 m-7">
        
       
            <h1 class=" font-semibold text-5xl my-10 text-center rounded font-sans">SignUp</h1>
            <div>
                <p class="text-2xl text-center font-semibold text-gray-600 p-4">Already have an account? <a href="#" class="text-blue-700 text-xl ">Login Now</a></p>
            </div>
    <div class=" text-xl font-semibold m-8  ">
         <form method="post" action="../actions/regaction.php">
        <input class="border-b-2  w-full my-2 py-1 px-2 border-gray-500 outline-none" name="uname" placeholder="User Name" required>
         <input class="  border-b-2  w-full px-2 py-1 outline-none my-2 border-gray-500" type="email" name="umail" placeholder="Email Id" required> <br>
         
         <input class=" border-b-2 w-full px-2 py-1  outline-none my-4 border-gray-500" type="password" name="upass" placeholder="Password" required> <br>
         <div class="flex items-center space-x-2 my-3">
            <input class="w-5 h-5 border-2 border-green-500 rounded accent-green-500 focus:ring focus:ring-green-300" 
                   type="checkbox" 
                   name="rem" 
                   id="check">
            <label for="check" class="font-semibold">Remember me</label>
         </div>
       
         <button class="border-0 py-2 px-6 text-white rounded-2xl bg-green-500 shadow-lg hover:bg-green-700 transition-all duration-300"> Sign Up </button>
        
        </form>
     </div>
  
</div>
</body>
<?php
require "C:/xampp/htdocs/santoshvas/Ecommerce/user/includes/header.php";
?>