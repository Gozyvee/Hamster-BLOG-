<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Hamster</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <?php 
                        $query = "SELECT * FROM categories";
                        $select_all_categories = mysqli_query($connection, $query);
                    
                        while($row = mysqli_fetch_assoc($select_all_categories)) {
                        $cat_title = $row['cat_title'];
                    
                         echo "<li><a href ='#'>{$cat_title}</a></li>";
                         }  
                    
                    ?>   
                      <li>
                      <?php 
                          if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') {
                              // User is authorized, allow access to the nav content
                              if(isset($_GET['p_id'])) {
                                  $the_post_id = $_GET['p_id'];
                                  echo "<a href='admin/posts.php?source=edit_post&p_id={$the_post_id}'> Edit Post</a>";
                              }
                          }
                      ?>
                  </li>
                  <li>
                      <?php 
                          if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') {
                              // User is authorized, allow access to the nav content
                              echo "<a href='admin'>Admin</a>";
                              
                          }
                      ?>
                  </li>
                  <li><a href="registration.php">Registration</a> </li>
                 
              </ul>
          </div>
      </div>
  </nav>
  
