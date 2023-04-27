 <?php include "db.php"; ?>
  <?php session_start(); ?>

  <?php



    if (isset($_POST['login'])) {

        $username = $_POST['username'];
        $password = $_POST['password'];

        $username = mysqli_real_escape_string($connection, $username);
        $password = mysqli_real_escape_string($connection, $password);

        $user_password = password_hash($password, PASSWORD_ARGON2I);
        $password_verify = password_verify($password, $user_password);

        $stmt = $connection->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
          $row = $result->fetch_assoc();
          $db_user_id = $row['user_id'];
          $db_username = $row['username'];
          $db_user_firstname = $row['user_firstname'];
          $db_user_lastname = $row['user_lastname'];
          $db_user_password = $row['user_password'];
          $db_user_role = $row['user_role'];

          

          if (password_verify($password, $db_user_password)) {
            // login successful, set session variables and redirect to admin page
            $session_token = bin2hex(random_bytes(16));
            $expiry_time = time() + 1800; // Set expiry time to one hour from now
            $stmt = $connection->prepare("INSERT INTO sessions (user_id, session_token, expiry_time) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $db_user_id, $session_token, $expiry_time);
            $stmt->execute();

            $_SESSION['username'] = $db_username;
            $_SESSION['firstname'] = $db_user_firstname;
            $_SESSION['lastname'] = $db_user_lastname;
            $_SESSION['user_role'] = $db_user_role;
            
            header("Location: ../admin");
            exit();
            } else {
                // login failed, redirect to index page
                  header("Location: ../index.php");
                  exit();
            }
      }

  
  }

  ?>