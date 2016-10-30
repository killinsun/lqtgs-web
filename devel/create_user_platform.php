<?php 
    require "./data.php";

    #Set user data from POST.
    $user_name = "kill_in_sun";
    $project_name = "${user_name}@LQTGS";
    $user_password = create_passwd();
    
    #Insert user data from POST to var file.
    $var_file = "/opt/local/lqtGS/vars/vars.yml";
    $input_data = "projectName: ${project_name} \n";
    $input_data .= "userName: $user_name \n";
    $input_data .= "userPassword: $user_password \n";
    file_put_contents($var_file,$input_data);
    
    #Insert enviroment data to clouds.yml file.
    $clouds_file = "/opt/local/lqtGS/clouds.yml";
    $input_data = file_get_contents($clouds_file);
    $input_data .= "  ${project_name}: \n";
    $input_data .= "    auth: \n";
    $input_data .= "      auth_url: ${auth_url} \n";
    $input_data .= "      username: ${user_name} \n";
    $input_data .= "      password: ${user_password} \n";
    $input_data .= "      project_name: ${project_name} \n";
    file_put_contents($clouds_file,$input_data);

    #Set ansible yml file.
    $input_data = file("/opt/local/lqtGS/site.yml");
    #unset($input_data[count($input_data)]);
    array_pop($input_data);
    array_push($input_data,"   - deleteProject");
    #array_push($input_data,"   - makeProject");
    file_put_contents("/opt/local/lqtGS/site.yml",$input_data);

    $command = "echo $sudo_pass | sudo -S /opt/python2.7.10/bin/ansible-playbook /opt/local/lqtGS/site.yml -C -vvvv";

    $output = array();
    $re=null;

    exec($command,$output,$ret);

    echo "<pre>";
    print_r($output);
    echo "</pre>";

    #Reset enviroment data to clouds.yml file.
    $clouds_file = "/opt/local/lqtGS/clouds.yml";
    $input_data = null;
    $input_data .= "clouds: \n";
    $input_data .= "  ansible: \n";
    $input_data .= "    auth: \n";
    $input_data .= "      auth_url: ${auth_url} \n";
    $input_data .= "      username: ${master_user_name} \n";
    $input_data .= "      password: ${master_user_pass} \n";
    $input_data .= "      project_name: ${master_project_name} \n";
    file_put_contents($clouds_file,$input_data);

    #Reset user data from POST to var file.
    $input_data = null;
    file_put_contents($var_file,$input_data);

/*
 *
 * 英数小文字8ケタのパスワードを生成する
 * @params $length: 
 */
function create_passwd( $length = 8 ){
    //vars
    $pwd = array();
    $pwd_strings = array(
        "sletter" => range('a', 'z'),
        "cletter" => range('A', 'Z'),
        "number"  => range('0', '9'),
        "symbol"  => array_merge(range('!', '/'), range(':', '?'), range('{', '~')),
    );

    //logic
    while (count($pwd) < $length) {
        // 4種類必ず入れる
        if (count($pwd) < 4) {
            $key = key($pwd_strings);
            next($pwd_strings);
        } else {
        // 後はランダムに取得
            $key = array_rand($pwd_strings);
        }
        $pwd[] = $pwd_strings[$key][array_rand($pwd_strings[$key])];
    }
    // 生成したパスワードの順番をランダムに並び替え
    shuffle($pwd);

    return implode($pwd);
}

  

?>
