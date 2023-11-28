<!DOCTYPE html>
<?php require "functions.php"; ?>
<html>
<head>
    <title>User ID Form</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }
        .form-container {
            width: 300px;
            background-color: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        input[type="text"], input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <form action="./submit-user-id.php" method="post">
            <label for="userId">Enter User ID:</label><br>
            <input type="text" id="userId" name="select" list="data" required><br>
            <datalist id="data">
                <?php
                $sr = mysqli_query($conn, "SELECT * FROM members");
                while ($user = mysqli_fetch_array($sr)) {
                    ?>
                    <option value="<?php echo $user['ID']; ?>"><?php echo $user['username']; ?> <?php echo $user['email']; ?> <?php echo $user['first_name']; ?></option>
                    <?php
                }
                ?>
            </datalist>

            <input type="submit" value="Submit">
        </form>
    </div>
</body>
</html>
