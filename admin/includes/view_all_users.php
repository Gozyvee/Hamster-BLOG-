<table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Username</th>
                    <th>Firstname</th>
                    <th>Lastname</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Change to</th>
                    <th>Change to</th>
                    <th>Edit</th>
                    <th>Delete</th>

                </tr>
            </thead>
            <tbody>
                <?php  //display post query
                   $query = "SELECT * FROM users";
                   $stmt = mysqli_prepare($connection, $query);
                   mysqli_stmt_execute($stmt);
                   $result = mysqli_stmt_get_result($stmt);
                   
                   while ($row = mysqli_fetch_assoc($result)) {
                       $user_id = $row['user_id'];
                       $username = $row['username'];
                       $user_password = $row['user_password'];
                       $user_firstname = $row['user_firstname'];
                       $user_lastname = $row['user_lastname'];
                       $user_email = $row['user_email'];
                       $user_image = $row['user_image'];
                       $user_role = $row['user_role'];
                   
                         echo "<tr>";
                         echo "<td>{$user_id}</td>";
                         echo "<td>{$username}</td>";
                         echo "<td>{$user_firstname}</td>";                
                         echo "<td>{$user_lastname}</td>";
                         echo "<td>{$user_email}</td>";
                         echo "<td>{$user_role}</td>";
            
                         echo "<td> <a class='btn btn-success' href='users.php?change_to_admin={$user_id}'>Admin</a></td>";
                         echo "<td> <a class='btn btn-info' href='users.php?change_to_sub={$user_id}'>Sub</a></td>";
                         echo "<td> <a class='btn btn-warning' href='users.php?source=edit_user&edit_user={$user_id}'>Edit</a></td>";
                         echo "<td> <a class='btn btn-danger' href='users.php?delete={$user_id}'>Delete</a></td>";
                         echo "</tr>";
                     }
                ?>      
            </tbody>
        </table>

        <?php 
         
       change_to_admin();

       change_to_sub();

       delete_user();

        ?>