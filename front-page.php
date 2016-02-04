<?php include 'head.php'; ?>

<body class="home">
<div class="wrapper">

    <div class="start">
        <h1>Ouachita Hills Ministries</h1>

        <?php wp_page_menu(array(
            'depth' => 1,
            'show_home' => true
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

<script src="js/bootstrap.min.js"></script>
</body>
</html>