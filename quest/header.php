<!-- Header -->
	<head>
		<title>LinuQuest GiraffeStyle.</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="../assets/css/main.css" />
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
		<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
		<!-- Scripts -->
			<script src="../assets/js/jquery.min.js"></script>
			<script src="../assets/js/jquery.scrolly.min.js"></script>
			<script src="../assets/js/skel.min.js"></script>
			<script src="../assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="../assets/js/main.js"></script>
	</head>
    <body>
    <section id="menu">
        <div id="left">
            <a href="../" class="scrolly"><img src="../images/logo_short_white.png" height=50px></a>
            <a href="../index.php#first" class="scrolly">ABOUT</a>
            <a href="../index.php#quest" class="scrolly">QUEST</a>
            <a href="../index.php#ranking" class="scrolly">RANKING</a>
            <a href="../index.php#rule" class="scrolly">RULE</a>
        </div>
        <div id="right">
            <?php
                if(!empty($access_token)){
                    echo "<div>";
                    echo "<ul id='profile'>";
                    echo "<li><a href='../logout.php'>Logout</a></li>";
                    echo "<li>${point}pt</li>";
                    echo "<li><a href='https://twitter.com/${screen_name}'>";
                    echo "<img src='${image_url}'>";
                    echo "@${screen_name}";
                    echo "</a></li>";
                    echo "</ul>";
                    echo "</div>";
                };
            ?>    
           
        </div>
    </section>
