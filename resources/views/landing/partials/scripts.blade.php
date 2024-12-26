<!-- Jquery-js-Link -->
<script src="{{ asset('assets-front') }}/js/jquery.js"></script>
<!-- owl-js-Link -->
<script src="{{ asset('assets-front') }}/js/owl.carousel.min.js"></script>
<!-- bootstrap-js-Link -->
<script src="{{ asset('assets-front') }}/js/bootstrap.min.js"></script>
<!-- aos-js-Link -->
<script src="{{ asset('assets-front') }}/js/aos.js"></script>
<!-- Typed Js Cdn -->
<script src='{{ asset('assets-front') }}/js/typed.min.js'></script>
<!-- main-js-Link -->
<script src="{{ asset('assets-front') }}/js/main.js"></script>

<script>
    $("#typed").typed({
     strings: ["Connect with stores effortlessly"],
     typeSpeed: 100,
     startDelay: 0,
     backSpeed: 60,
     backDelay: 2000,
     loop: true,
     cursorChar: "|",
     contentType: 'html'
   });

   // Fixed Discount Dish JS
   $(document).ready(function () {
     let cardBlock = document.querySelectorAll('.task_block');
     let topStyle = 120;

     cardBlock.forEach((card) => {
       card.style.top = `${topStyle}px`;
       topStyle += 30;
     })

   }
   );

   // Scroll Down Window 
   $(document).ready(function () {
     // Attach a click event handler to the button
     $('#scrollButton').click(function () {
       // Scroll down smoothly 200 pixels from the current position
       $('html, body').animate({ scrollTop: $(window).scrollTop() + 600 }, 800); // Adjust the speed (800ms) as needed
     });
   });

</script>