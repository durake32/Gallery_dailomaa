<style>

#myBtn {
  display: none;
  position: fixed;
  bottom: 50px;
  right: 50px;
  z-index: 99;
  font-size: 18px;
  border: none;
  outline: none;
  background-color: #20245ce0;
  color: white;
  cursor: pointer;
  padding: 15px;
  border-radius:50%;
}

#myBtn:hover {
  background-color: #555;
}

</style>

 
    
       <button onclick="topFunction()" id="myBtn" title="Go to top">Top</button>

<script>


// Get the button
let mybutton = document.getElementById("myBtn");

// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    mybutton.style.display = "block";
  } else {
    mybutton.style.display = "none";
  }
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
  document.body.scrollTop = 0;
  document.documentElement.scrollTop = 0;
}
</script>