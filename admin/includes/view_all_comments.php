<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>id</th>
            <th>Author</th>
            <th>Comment</th>
            <th>Email</th>
            <th>Status</th>
            <th>In Response to</th>
            <th>Date</th>
            <th>Approve</th>
            <th>Disapprove</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        <?php  //display comment query
      $query = "SELECT * FROM comments";
      $stmt = $connection->prepare($query);
      $stmt->execute();
      $result = $stmt->get_result();
      
      while ($row = $result->fetch_assoc()) {
         $comment_id = $row['comment_id'];
         $comment_post_id = $row['comment_post_id'];
         $comment_author = $row['comment_author'];
         $comment_email = $row['comment_email'];
         $comment_content = $row['comment_content'];
         $comment_status = $row['comment_status'];
         $comment_date = $row['comment_date'];
      
          echo "<tr>";
           echo "<td>{$comment_id}</td>";
           echo "<td>{$comment_author}</td>";
           echo "<td>{$comment_content}</td>";
           echo "<td>{$comment_email}</td>";
           echo "<td>{$comment_status}</td>";
   
           $query = "SELECT post_id, post_title FROM posts WHERE post_id = ?";
           $stmt = $connection->prepare($query);
           $stmt->bind_param("i", $comment_post_id);
           $stmt->execute();
           $result = $stmt->get_result();
           
           if ($result->num_rows > 0) {
               $row = $result->fetch_assoc();
               $post_id = $row['post_id'];
               $post_title = $row['post_title'];
           
               echo "<td> <a href='../post.php?p_id=$post_id'> $post_title</a> </td>";
           } else {
               echo "<td>Post not found</td>";
           }
           
           echo "<td>{$comment_date}</td>";
           echo "<td> <a href='comments.php?approve={$comment_id}'>Approve</a></td>";
           echo "<td> <a href='comments.php?disapprove={$comment_id}'>Disapprove</a></td>";
           echo "<td> <a href='comments.php?delete={$comment_id}'>Delete</a></td>";
           echo "</tr>";
       }
        ?>
    </tbody>
</table>

<?php

approve_comment();

disapprove_comment();

delete();

?>