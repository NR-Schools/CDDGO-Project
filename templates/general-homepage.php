<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapua Table Top Gamers</title>
    <link type="text/css" rel="stylesheet" href="../css/general-homepage.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather+Sans:ital,wght@0,300..800;1,300..800&display=swap"
        rel="stylesheet">
</head>

<body>
    <!-- Include Header -->
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/components/header.php"; ?>

    <!--Banner Start -->
    <div class="banner">
        <div class="banner-content">
            <h1 style="font-size: 4rem; font-weight: bold;">LEARN.</h1>
            <h2 style="font-size: 3.5rem; font-weight: bold;">PLAY.</h2>
            <h3 style="font-weight: bold;">REPEAT.</h3>
            <p>Tabletop board games have never been this fun!</p>
            <a href="sign-up.php" class="banner-button">JOIN</a>
        </div>
        <div class="banner-image">
            <img src="../assets/banner-pic.png" alt="Banner Image">
        </div>
    </div>
    <!--banner end-->

    <!--content start-->
    <div class="home-content">
        <div class="mtg-logo">
            <img src="../assets/mtg-logo.jpg" alt="MTG Logo">
        </div>
        <div class="divider"></div>
        <div class="mtg-bg">
            <p>Mapúa Tabletop Gamers (MTG) began as a student club with the goal of serving the recreational needs of
                its student body. MTG aims to provide a place where people can unwind and have fun away from the
                demanding academics of university life. Game nights, tournaments, and enlightening workshops are just a
                few of the planned events that MTG hosts to encourage social and community service among its members.
                MTG fosters a sense of community among the school population while providing a hub for students looking
                for a break from their academic obligations thanks to its wide selection of games and welcoming
                atmosphere.</p>
            <div class="mtg-learn-button">
                <a href="about-us.php" class="learn-button">Learn more</a>
            </div>
        </div>
    </div>

    <!-- Include Footer -->
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/components/footer.php"; ?>

</body>

</html>