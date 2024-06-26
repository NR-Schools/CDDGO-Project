<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/BoardGameService.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/guards/AuthGuard.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/utils/validator.php';

if (!AuthGuard::guard_route(Role::ADMIN)) {
    // Return to root
    header("Location: /");
}
?>


<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Perform Validation
    [$status, $error] = validate_many_inputs([
        ["GameName", $_POST['game_name'], [new MinLengthRule(1), new MaxLengthRule(40)]],
        ["GameDescription", $_POST['description'], [new MinLengthRule(1), new MaxLengthRule(400)]],
        ["GameImage", $_FILES['game_img'], [new ExistingFileRule()]],
        ["QuantityAvailable", $_POST['quantity_avail'], [new MinLengthRule(1)]],
        ["GameCategory", $_POST['game_category'], [new MinLengthRule(1), new MaxLengthRule(100)]],
    ]);


    if ($status)
    {
        $game_image = file_get_contents($_FILES['game_img']['tmp_name']); //event image
        $image_encoded = base64_encode($game_image);
    
        $boardgame = new BoardGame();
        $boardgame->GameName = $_POST['game_name'];
        $boardgame->GameDescription = $_POST['description'];
        $boardgame->GameImage = $image_encoded;
        $boardgame->QuantityAvailable = $_POST['quantity_avail'];
        $boardgame->GameCategory = $_POST['game_category'];
        $boardgame->GameStatus = "Available";
    
        [$status, $error] = BoardGameService::addNewBoardGame($boardgame);
        if ($status) {
            echo <<<SCRIPT
                <script>
                    alert('Board Game Added');
                    document.location.href = 'admin-manage_board_games.php';
                </script>
            SCRIPT;
        }
        else {
            echo <<<SCRIPT
                <script>
                    alert('Board Game Failed to Add: {$error}');
                    document.location.href = 'admin-manage_board_games.php';
                </script>
            SCRIPT;
        }
    

    }
    else {
        echo <<<EOD
        <script>
            alert('{$error}');
            document.location.href = '{$_SERVER['REQUEST_URI']}';
        </script>
        EOD;
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MTG - Add Board Game</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather+Sans:ital,wght@0,300..800;1,300..800&display=swap"
        rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="../css/admin-add_board_game.css">
</head>

<body>

    <!-- Include Header-->
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/components/header.php"; ?>

    <div class="content-container">
        <div class="add-board-title">
            ADD BOARD GAME
        </div>
        <form action="admin-add_board_game.php" method="POST" enctype="multipart/form-data" class="form-container">
            <!-- Board Game Name -->
            <div class="form-group">
                <label for="game_name">Board Game Name</label>
                <input type="text" name="game_name" id="game_name">
            </div>

            <!-- Board Game Image -->
            <div class="form-group">
                <label for="game_img">Board Game Image</label>
                <input type="file" id="game_img" name="game_img" class="form-control" onchange="onFileSelected(event)"
                    accept="image/*">
            </div>

            <!-- Description -->
            <div class="form-group">
                <label for="game_desc">Description</label>
                <textarea type="text" name="description" id="description" class="form-control" rows="6"></textarea>
            </div>

            <!-- Category -->
            <div class="form-group">
                <label for="game_category">Category</label>
                <select name="game_category" id="game_category">
                    <option value="No Category" disabled selected>Select Category</option>
                    <option value="Abstract Strategy">Abstract Strategy</option>
                    <option value="Area Control">Area Control</option>
                    <option value="Campaign">Campaign</option>
                    <option value="City Building">City Building</option>
                    <option value="Cooperative">Cooperative</option>
                    <option value="Deck Building">Deck Building</option>
                    <option value="Deduction">Deduction</option>
                    <option value="Dexterity">Dexterity</option>
                    <option value="Dungeon Crawler">Dungeon Crawler</option>
                    <option value="Economic">Economic</option>
                    <option value="Family">Family</option>
                    <option value="Fighting">Fighting</option>
                    <option value="Hand Management">Hand Management</option>
                    <option value="Kid">Kid</option>
                    <option value="Limited Communication">Limited Communication</option>
                    <option value="Party">Party</option>
                    <option value="Pick-Up and Deliver">Pick-Up and Deliver</option>
                    <option value="Programming">Programming</option>
                    <option value="Set Collection">Set Collection</option>
                    <option value="Storytelling">Storytelling</option>
                    <option value="Tower Defense">Tower Defense</option>
                    <option value="War">War</option>
                    <option value="Word">Word</option>
                    <option value="Worker Placement">Worker Placement</option>
                </select>
            </div>

            <!-- Quantity Available -->
            <div class="form-group">
                <label for="quantity_avail">Quantity Available</label>
                <input type="number" name="quantity_avail" id="quantity_avail">
            </div>

            <!-- Submit and Cancel Buttons -->
            <div class="form-group">
                <button type="submit" class="btn-submit">Add Board Game</button>
                <a href="admin-manage_board_games.php" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>

    <!-- Include Footer -->
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/components/footer.php"; ?>



</body>

</html>