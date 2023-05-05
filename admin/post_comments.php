<?php include "includes/admin_header.php" ?>
<?php include "includes/admin_navigation.php" ?>

<div id="wrapper">
    <div id="page-wrapper">

        <div class="container-fluid">
            <div class="col-lg-12">
                <h1 class="page-header">
                    Welcome to admin
                    <small><?php echo $_SESSION['username']; ?></small>
                </h1>
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
                        $the_id = $_GET['id'];
                        $query = "SELECT * FROM comments WHERE comment_post_id = ?";
                        $stmt = mysqli_prepare($connection, $query);
                        mysqli_stmt_bind_param($stmt, 'i', $the_id);
                        mysqli_stmt_execute($stmt);
                        $select_comments = mysqli_stmt_get_result($stmt);
                        while ($row = mysqli_fetch_assoc($select_comments)) {
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

                            $query = "SELECT * FROM posts WHERE post_id = ?";
                            $stmt = mysqli_prepare($connection, $query);
                            mysqli_stmt_bind_param($stmt, 'i', $comment_post_id);
                            mysqli_stmt_execute($stmt);
                            $select_post_id_query = mysqli_stmt_get_result($stmt);
                            while ($row = mysqli_fetch_assoc($select_post_id_query)) {
                                $post_id = $row['post_id'];
                                $post_title = $row['post_title'];

                                echo "<td> <a href='../post.php?p_id=$post_id'> $post_title</a> </td>";
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

                if (isset($_GET['delete'])) {
                    $the_comment_id = $_GET['delete'];
            
                    $query = "DELETE FROM comments WHERE comment_id = ?";
                    $stmt = mysqli_prepare($connection, $query);
                    mysqli_stmt_bind_param($stmt, 'i', $the_comment_id);
                    mysqli_stmt_execute($stmt);
                    header("location: post_comments.php");
                }

                ?>
            </div>


