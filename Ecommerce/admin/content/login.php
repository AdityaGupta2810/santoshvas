<?php 
include('../../user/includes/header.php');
?>
<main class=" transition-all duration-300 ease-in-out">
  
<section class="w-full  p-4 h-screen flex  bg-cover justify-between bg-center items-center">
 <div class="w-full md:m-40 lg:w-5/12 lg:m-0 border-0 rounded-lg shadow-lg p-10 pr-20 md:pr-28 lg:pr-32 flex flex-col justify-start   ">
     <div class="flex justify-start flex-col w-full mb-4">
      <p class="w-full block text-gray-700 font-bold text-3xl pt-3 px-3  ">Admin Login</p>
      <p class="w-full block text-gray-700  p-3 font-medium text-[18px] ">Enter your email and password to sign in</p>
      </div>

<form class="flex justify-start flex-col w-full ">
   
<input class=" w-full p-3 mb-5  border-2 border-gray-400 rounded-lg placeholder:font-medium outline-none  focus:ring-blue-500 focus:ring-1" id="user" type="text" placeholder="Email">
 <input class=" w-full p-3 mb-5  border-2 border-gray-400 rounded-lg placeholder:font-medium outline-none focus:ring-blue-500 focus:ring-1" id="pass" type="password" placeholder="Password">

 <div class="flex gap-5  mb-5 font-medium text-xl text-gray-500 items-center">
   <input class="h-5 w-5 outline-none focus:ring-2 ring-offset-2 focus:ring-blue-500 rounded-xl" type="checkbox" id="checkbox" > 
  <label for="checkbox" >Remember me</label>
 </div>

 <button class=" p-3 mb-5 bg-blue-600 rounded-lg border-0 w-full outline-none focus:ring-2 focus:ring-blue-500 ring-offset-2" type="button">Login</button>

   
</form>
   
 </div>



 <div class="relative  m-3 hidden lg:block  mb-24 w-1/2 h-[90%] overflow-hidden rounded-xl bg-cover bg-[url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/signin-ill.jpg')] ">

    <!-- Gradient Overlay -->
    <span class="absolute top-0 left-0 w-full h-full bg-center bg-cover bg-gradient-to-tl from-blue-500 to-violet-500 opacity-60"></span>

    <!-- Text Content -->
    <div class="absolute bottom-20 left-0 w-full text-center z-10 pb-10 px-4">
        <h4 class="font-bold text-xl text-white">"Attention is the new currency"</h4>
        <p class="text-base text-white">The more effortless the writing looks, the more effort the writer actually put into the process.</p>
    </div>

</div>

</section>

    
</main>
<?php
include('../../user/includes/footer.php');
?>
