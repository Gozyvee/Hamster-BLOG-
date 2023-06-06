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
                <a class="navbar-brand" href="/cms2/index">Hamster</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <?php 
                        $query = "SELECT * FROM categories";
                        $select_all_categories = mysqli_query($connection, $query);
                    
                        while($row = mysqli_fetch_assoc($select_all_categories)) {
                        $cat_id = $row['cat_id'];
                        $cat_title = $row['cat_title'];

                        $category_class = '';
                        $registration_class = '';

                        $pageName = basename($_SERVER['PHP_SELF']);
                        $registration = '/cms2/registration';

                        if(isset($_GET['category']) && $_GET['category'] == $cat_id ){
                            $category_class = 'active';
                        }else if($pageName == $registration) {
                            $registration_class = 'active';
                        } 
                        
                        echo "<li class='{$category_class}'><a href ='/cms2/category/{$cat_id}'>{$cat_title}</a></li>";
                         }  
                    
                    ?>   
                
                      <li>
                      <?php 
                          if(isLoggedin('admin')) {
                              // User is authorized, allow access to the nav content
                              if(isset($_GET['p_id'])) {
                                  $the_post_id = $_GET['p_id'];
                                  echo "<a href='/cms2/admin/posts.php?source=edit_post&p_id={$the_post_id}'> Edit Post</a>";
                              }
                          }
                      ?>
                  </li>

                  <?php if(isLoggedin('admin')): ?>
                    <li>
                        <a href='/cms2/admin'>Admin</a>
                    </li>
                    
                    <li>
                        <a href='/cms2/includes/logout.php'>Logout</a>
                    </li>
                    
                    <?php else: ?>
                        <li class=""><a href="/cms2/login">Login</a></li>
                  <?php endif; ?>

                  <li class='<?php echo $registration_class; ?>'><a href="/cms2/registration">Registration</a> </li>
              </ul>
          </div>
      </div>
  </nav>
  
