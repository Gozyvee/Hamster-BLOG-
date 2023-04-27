document.addEventListener("DOMContentLoaded", function(event) {
    ClassicEditor
      .create( document.querySelector( '#body' ) )
      .catch( error => {
        console.error( error );
      } );
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