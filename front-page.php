<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Ouachita Hills Ministries</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link rel="stylesheet" href="css/unslider.css">
    <link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_uri(); ?>" />

    <script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
    <script src="unslider/src/js/unslider.js"></script>
    <script src="js/main.js"></script>
</head>
<body class="home">
<div class="wrapper">

    <div class="start">
        <h1>Ouachita Hills Ministries</h1>

        <?php echo 'changes taken 5' ?>

        <?php wp_nav_menu(array(
            'depth' => 1,
            'container' => false,
            'items_wrap' => '%3$s'
        )) ?>
    </div>

    <div class="news">
        <div class="slider">
            <ul>
                <li>Please donate for our new duck hatchery. <a href="#">Click here.</a></li>
                <li>Administration Building turns 7!</li>
                <li>Students currently canvassing. Keep them in prayer!</li>
            </ul>
        </div>
    </div>

</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
</body>
</html>