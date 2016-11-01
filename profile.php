<!DOCTYPE HTML>
<!--
	Tessellate by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>LinuQuest GiraffeStyle.</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
		<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
	</head>
    <body>
    <?php include('./header.php'); ?>

		<!-- First -->
        <?php 
                try {
                    $pdo = new PDO($dsn,$db_user,$db_pass);
                    $stmt = $pdo ->query("SELECT * FROM QUEST_TABLE WHERE QUEST_ID='${screen_name}'");
                    while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                        $point = $row['POINT'];
                    };
                }catch (PDOException $e){
                    header('Content-Type: text/plain; charset=UTF-8', true,500);
                    echo "ERROR";
                };
        ?>
			<section id="first" class="main">
				<header>
					<div class="container">
                        <div id="Page Title" class="content style4">
                            <h2>@<?php echo $screen_name;?>> show profile</h2>
                            <img src="<?php echo $image_url; ?>">
                            <h3>Your point : <?php echo $point; ?>pt.</h3>
                        </div>

                        <div id="data" class="content style4 featured">
                        <form method="POST" action="tools/edit_profile.php">
                            <table id="status_table">
                                <tr>
                                <th>Your image icon</th>
                                <td>Refered by twitter.</td>
                                <td><button type="submit" name="button_action" value="update_img">Update image icon</button></td>
                                </tr>
                                <tr>
                                <th>Your quest servers status</th>
                                <td>Unknown</td>
                                <td><button type="submit" name="button_action" value="redeploy">Redeploy</button></td>
                                </tr>
                            </table> 
                        </form>
                            
                            <h3>Achivements</h3>

                            <table id="achivement_table">
                            <tr>
                                <th>No.</th>
                                <th>Quest Name</th>
                                <th>Status</th>
                            </tr>
                            </table>

                            <form method="POST" action="tools/delete_profile.php">
                                <button type="submit" name="button_action" value="delete">Delete your profile,,,</button>
                            </form> 
                                
                        </div>

                    </div>
                </header>
			</section>

		<!-- Footer -->
			<section id="footer">
				<ul class="icons">
					<li><a href="https://twitter.com/Kill_In_Sun" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
					<li><a href="https://www.facebook.com/Sefinannnnn" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
				</ul>
				<div class="copyright">
					<ul class="menu">
						<li>&copy; kill_in_sun. All rights reserved.</li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
					</ul>
				</div>
			</section>


	</body>
</html>
