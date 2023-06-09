<?php include "includes/admin_header.php"; ?>

    <div id="wrapper">
 <?php 
    //  $session = session_id();
    //  $time = time();
    //  $time_out_in_seconds = 02;
    //  $time_out = $time - $time_out_in_seconds;
 
    //  $query = "SELECT * FROM users_online WHERE session = '$session'";
    //  $send_query = mysqli_query($connection, $query);
    //  $count = mysqli_num_rows($send_query);
 
    //  if($count == NULL) {
    //      mysqli_query($connection, "INSERT INTO users_online(session, time) VALUES('$session', '$time')");
    //  }else{
    //      mysqli_query($connection, "UPDATE users_online SET time = '{$time}' WHERE session = '{$session}'"); 
    //  }
 
    //  $users_online_query = mysqli_query($connection, "SELECT * FROM users_online WHERE time > '{$time_out}'");
    //  $count_user = mysqli_num_rows( $users_online_query);
    
?> 

 <?php include "includes/admin_navigation.php" ?>
     <div id="page-wrapper">

         <div class="container-fluid">
                <!-- Page Heading -->
             <div class="row">
                 <div class="col-lg-12">
                     <h1 class="page-header">
                     Here, are your stats
                          <small><?php echo get_user_name(); ?></small>
                     </h1>
                 </div>
            </div>
            
                
    <div class="row">
        <div class="col-lg-4 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-file-text fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                        <?php ?>
                        <div class='huge'><?php echo $post_count = personal_record_count(loggedInUserId()); ?></div>
                    
                        <div>Posts</div>
                        </div>
                    </div>
                </div>
                <a href="posts.php">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="panel panel-green">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-comments fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class='huge'><?php echo $comment_count = recordCount('comments');
                            // echo $comment_count = count_records(post_user_comments_count());
                            ?></div>
                        <div>Comments</div>
                        </div>
                    </div>
                </div>
                <a href="comments.php">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="panel panel-red">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-list fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class='huge'><?php echo $category_count = count_records(get_all_user_categories()); ?></div>
                            <div>Categories</div>
                        </div>
                    </div>
                </div>
                <a href="categories.php">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
    </div>
 </div>
 <?php 
    $publish_count = count_records(get_all_user_published_posts());
    $post_draft_count = count_records(get_all_user_draft_posts());
    $unapproved_comment_count = checkStatus('comments', 'comment_status', 'unapproved');
    $subscriber_count = checkStatus('users', 'user_role', 'subscriber');
      
 ?>
 <div class="">
    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Data', 'Count'],

          <?php
          
             $element_text  = ['All posts', 'Active Posts', 'Draft Posts', 'Comments', 'Unapproved Comments', 'Categories'];
             $element_count  = [$post_count, $publish_count, $post_draft_count, $comment_count, $unapproved_comment_count, $category_count];

             for($i = 0; $i < 6; $i++){
                echo "['{$element_text[$i]}'" . "," . "{$element_count[$i]}],";
             }
          ?>
        ]);

        var options = {
          chart: {
            title: '',
            subtitle: '',
          }
        };

        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
    </script>
    <div id="columnchart_material" style="width: '100px'; height: 500px;"></div>
 </div>
 <?php include "includes/admin_footer.php"?>



