document.addEventListener("DOMContentLoaded", function(event) {
    ClassicEditor
      .create( document.querySelector( '#body' ) )
      
  });

  $(document).ready(function(){
    $('#selectAllBoxes').click(function(event){
        if(this.checked) {
            $('.CheckBoxes').each(function(){
                this.checked = true;
            });
        } else {
            $('.CheckBoxes').each(function(){
                this.checked = false;
            });
        }
    });
  });

// create the div elements
// var loadScreen = document.createElement("div");
// loadScreen.id = "load-screen";
// var loading = document.createElement("div");
// loading.id = "loading";
// loadScreen.appendChild(loading);

// // add the div elements to the beginning of the body element
// var body = document.body;
// body.insertBefore(loadScreen, body.firstChild);

// // hide the loading screen after a delay using CSS transitions
// loadScreen.style.transition = "opacity 0.6s";
// loadScreen.style.opacity = "0";
// setTimeout(function() {
//   body.removeChild(loadScreen);
// }, 7000);
