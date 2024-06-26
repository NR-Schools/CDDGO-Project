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
    if (isset($_POST["edit"])) {
        $gameID = $_POST['gameID'];
        $game = BoardGameService::getBoardGameById($gameID);

        // Perform Validation
        [$status, $error] = validate_many_inputs([
            ["GameName", $_POST['game_name'], [new MinLengthRule(1), new MaxLengthRule(40)]],
            ["GameDescription", $_POST['description'], [new MinLengthRule(1), new MaxLengthRule(400)]],
            ["QuantityAvailable", $_POST['quantity_avail'], [new MinLengthRule(1)]],
            ["GameCategory", $_POST['game_category'], [new MinLengthRule(1), new MaxLengthRule(100)]],
        ]);

        if ($status) {
            $game->GameName = htmlspecialchars($_POST['game_name']);
            $game->GameDescription = htmlspecialchars($_POST['description']);
            if (boolval($_FILES['game_img']['error'] === 0)) {
                $game_image = file_get_contents($_FILES['game_img']['tmp_name']);
                $image_encoded = base64_encode($game_image);
                $game->GameImage = $image_encoded;
            }
            $game->QuantityAvailable = $_POST['quantity_avail'];
            $game->GameCategory = htmlspecialchars($_POST['game_category']);
            
            BoardGameService::updateExistingBoardGame($game);

            echo <<<EOD
            <script>
                alert('Board Game Updated');
                document.location.href = 'admin-manage_board_games.php';
            </script>"
            EOD;
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

    if (isset($_POST["delete"])) {
        $gameID = $_POST['gameID'];
        BoardGameService::deleteExistingBoardGame($gameID);

        echo "<script> alert('Board Game Deleted');
                document.location.href = 'admin-manage_board_games.php';
                </script>";
    }

    if(isset($_POST["back"])) {
        echo <<<EOD
        <script>
            document.location.href = 'admin-manage_board_games.php';
        </script>"
        EOD;
    }

}


// Load Board Game Information
if (isset($_GET["gameId"])) {
    $gameID = $_GET["gameId"];

    $game = BoardGameService::getBoardGameById($gameID);
    if ($game == null) {
        echo "No game.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MTG - Edit Board Game</title>
    <link type="text/css" rel="stylesheet" href="../css/admin-add_board_game.css">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather+Sans:ital,wght@0,300..800;1,300..800&display=swap"
        rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
</head>

<body>

    <!-- Include Header -->
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/components/header.php"; ?>

    <!--Form-->
    <div class="content-container">
        <div class="add-board-title">
            EDIT BOARD GAME
        </div>
        <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="multipart/form-data">
            <!--Board Game Name-->
            <div class="form-group">
                <label for="game_name">Board Game Name</label>
                <input type="text" name="game_name" id="game_name" value="<?php echo $game->GameName ?>">
            </div>

            <!--Board Game Image-->
            <div class="form-group">
                <label for="game_img">Board Game Image</label>
                <input type="file" id="game_img" name="game_img" class="form-control" onchange="onFileSelected(event)"
                    style="height: 45px;" accept="image/*">
            </div>


            <!--Description-->
            <div class="form-group">
                <label for="game_desc">Description</label><br>
                <textarea type="text" name="description" id="description" class="form-control"
                    style="height: 200px;"><?php echo htmlspecialchars($game->GameDescription); ?></textarea>
            </div>


            <!--Category-->
            <div class="form-group">
                <label for="game_category">Category</label>
                <select name="game_category">
                    <option value="Abstract Strategy" <?php echo ($game->GameCategory == "Abstract Strategy") ? 'selected' : ''; ?>>Abstract Strategy</option>
                    <option value="Area Control" <?php echo ($game->GameCategory == "Area Control") ? 'selected' : ''; ?>>
                        Area Control</option>
                    <option value="Campaign" <?php echo ($game->GameCategory == "Campaign") ? 'selected' : ''; ?>>Campaign
                    </option>
                    <option value="City Building" <?php echo ($game->GameCategory == "City Building") ? 'selected' : ''; ?>>City Building</option>
                    <option value="Cooperative <?php echo ($game->GameCategory == "Cooperative") ? 'selected' : ''; ?>">
                        Cooperative</option>
                    <option
                        value="Deck Building <?php echo ($game->GameCategory == "Deck Building") ? 'selected' : ''; ?>">
                        Deck Building</option>
                    <option value="Deduction" <?php echo ($game->GameCategory == "Deduction") ? 'selected' : ''; ?>>
                        Deduction</option>
                    <option value="Dexterity" <?php echo ($game->GameCategory == "Dexterity") ? 'selected' : ''; ?>>
                        Dexterity</option>
                    <option value="Dungeon Crawler" <?php echo ($game->GameCategory == "Dungeon Crawler") ? 'selected' : ''; ?>>Dungeon Crawler</option>
                    <option value="Economic" <?php echo ($game->GameCategory == "Economic") ? 'selected' : ''; ?>>Economic
                    </option>
                    <option value="Family" <?php echo ($game->GameCategory == "Family") ? 'selected' : ''; ?>>Family
                    </option>
                    <option value="Fighting <?php echo ($game->GameCategory == "Fighting") ? 'selected' : ''; ?>">
                        Fighting</option>
                    <option
                        value="Hand Management <?php echo ($game->GameCategory == "Hand Management") ? 'selected' : ''; ?>">
                        Hand Management</option>
                    <option value="Kid <?php echo ($game->GameCategory == "Kid") ? 'selected' : ''; ?>">Kid</option>
                    <option value="Limited Communication" <?php echo ($game->GameCategory == "Limited Communication") ? 'selected' : ''; ?>>Limited Communication</option>
                    <option value="Party" <?php echo ($game->GameCategory == "Party") ? 'selected' : ''; ?>>Party</option>
                    <option value="Pick-Up and Deliver" <?php echo ($game->GameCategory == "Pick-Up and Deliver") ? 'selected' : ''; ?>>Pick-Up and Deliver</option>
                    <option value="Programming" <?php echo ($game->GameCategory == "Programming") ? 'selected' : ''; ?>>
                        Programming</option>
                    <option value="Set Collection" <?php echo ($game->GameCategory == "Set Collection") ? 'selected' : ''; ?>>Set Collection</option>
                    <option value="Storytelling" <?php echo ($game->GameCategory == "Storytelling") ? 'selected' : ''; ?>>
                        Storytelling</option>
                    <option value="Tower Defense" <?php echo ($game->GameCategory == "Tower Defense") ? 'selected' : ''; ?>>Tower Defense</option>
                    <option value="War" <?php echo ($game->GameCategory == "War") ? 'selected' : ''; ?>>War</option>
                    <option value="Word" <?php echo ($game->GameCategory == "Word") ? 'selected' : ''; ?>>Word</option>
                    <option value="Worker Placement" <?php echo ($game->GameCategory == "Worker Placement") ? 'selected' : ''; ?>>Worker Placement</option>
                </select>
            </div>

            <!--Quantity Available-->
            <div class="form-group">
                <label for="quantity_avail">Quantity Available</label>
                <input type="number" name="quantity_avail" id="quantity_avail"
                    value="<?php echo $game->QuantityAvailable ?>">
            </div>

            <!--Submit and Delete Button-->
            <div class="form-group">
                <input type="hidden" name="gameID" value="<?php echo $gameID; ?>">
                <button type="submit" class="btn-submit" name="edit" value="edit">Edit Board Game</button>
                <button type="submit" class="btn-cancel" name="delete" value="delete">Delete Board Game</button>
                <button type="submit" class="btn-cancel" name="back" value="back">Cancel</button>
            </div>
        </form>

        ;
    </div>

    <!-- Include Footer -->
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/components/footer.php"; ?>

</body>
<html>