<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8mb4">
    <link rel="stylesheet" href="design.css">
    <script src="local_brain.js"></script>
</head>
</html>
<?php
session_start();
$servername = "localhost";
$user = "root";
$password = "";
$db_name = "local_bank";

$conn = new mysqli($servername, $user, $password, $db_name);
$conn->set_charset("utf8mb4");

if ($conn->connect_error){
    die("ERROR :( - ".$conn->connect_error);
}
//connected
if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['client_number']) and isset($_POST['login_pass'])){
    login();
}
if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['register_name_form']) and isset($_POST['register_surname_form']) and isset($_POST['register_nickname_form']) and isset($_POST['register_email_form']) and isset($_POST['register_phone_form']) and isset($_POST['register_password1_form']) and isset($_POST['register_password2_form'])){
    register();
}
if (isset($_GET['action']) && $_GET['action'] == 'main_page') {
    main_page();
}
if (isset($_GET['action']) && $_GET['action'] == 'main_page_admin') {
    main_page_admin();
}
if (isset($_GET['action']) && $_GET['action'] == 'main_page_admin_delete') {
    main_page_admin_delete();
}
if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['card_name'])){
    add_card();
}
if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['card_id'])){
    show_stats($_POST['card_id']);
}
if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['account_add_money']) and $_POST['account_send_money'] === ""){
    money_transaction_plus($_SESSION["card_id"]);
}
if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['account_send_money']) and $_POST['account_add_money'] === ""){
    money_transaction_minus($_SESSION["card_id"]);
}
//login-------------------------------
function login(){
    global $conn;
    $client_number = isset($_POST["client_number"]) ? $_POST["client_number"] : "";
    $login_password = isset($_POST["login_pass"]) ? $_POST["login_pass"] : "";

    $sql_verify = "Select * from user where user_key='".$client_number."' and password='".$login_password."';";
    $result = $conn->query($sql_verify);
    $user_number = $result->fetch_assoc();
    if ($result->num_rows > 0 and $user_number["admin"] < 1) {
        $_SESSION['user_id'] = $user_number["user_id"];
        $_SESSION["user_key"] = $user_number["user_key"];
        $_SESSION["u_name"] = $user_number["user_name"];
        $_SESSION["u_surname"] = $user_number["user_surname"];
        $_SESSION["u_nickname"] = $user_number["nickname"];
        $_SESSION["u_email"] = $user_number["email"];
        $_SESSION["u_phone"] = $user_number["phone"];
        main_page();
    }
    elseif ($result->num_rows > 0 and $user_number["admin"] > 0){
        $_SESSION['user_id'] = $user_number["user_id"];
        $_SESSION["user_key"] = $user_number["user_key"];
        $_SESSION["u_name"] = $user_number["user_name"];
        $_SESSION["u_surname"] = $user_number["user_surname"];
        $_SESSION["u_nickname"] = $user_number["nickname"];
        $_SESSION["u_email"] = $user_number["email"];
        $_SESSION["u_phone"] = $user_number["phone"];
        main_page_admin();
    }
    else{
        failed_login();
    }
}
//register----------------------------
function register() {
    global $conn;
    $register_name = isset($_POST['register_name_form']) ? $_POST['register_name_form'] : "";
    $register_surname = isset($_POST['register_surname_form']) ? $_POST['register_surname_form'] : "";
    $register_nickname = isset($_POST['register_nickname_form']) ? $_POST['register_nickname_form'] : "";
    $register_email = isset($_POST['register_email_form']) ? $_POST['register_email_form'] : "";
    $register_phone = isset($_POST['register_phone_form']) ? $_POST['register_phone_form'] : "";
    $register_password = isset($_POST['register_password1_form']) ? $_POST['register_password1_form'] : "";
    $register_password2 = isset($_POST['register_password2_form']) ? $_POST['register_password2_form'] : "";

    //id_generator
    $id_char = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '!', '@', '#', '$', '%', '&', '*', 1, 2, 3, 4, 5, 6, 7, 8, 9];
    $id_generator_lengh = rand(4, 10);
    $id_generator_storage = [];
    for ($i = 0; $i <= $id_generator_lengh; $i++) {
        $random_generator = rand(0, 41);
        $id_generator_storage[$i] = $id_char[$random_generator];
    }
    $sql_control = "SELECT * FROM user WHERE user_key='".implode('', $id_generator_storage)."'";
    $result = $conn->query($sql_control);
    while ($result->num_rows > 0){
        $result = $conn->query($sql_control);
        for ($i = 0; $i <= $id_generator_lengh; $i++) {
            $random_generator = rand(0, 41);
            $id_generator_storage[$i] = $id_char[$random_generator];
        }
    }
    //registration algorithm
    $sql_verify_nickname = "SELECT * FROM user WHERE nickname = '".$register_nickname."';";
    $sql_verify_email = "SELECT * FROM user WHERE email = '".$register_email."';";
    $result_nickname = $conn->query($sql_verify_nickname);
    $result_email = $conn->query($sql_verify_email);
    if ($register_password === $register_password2 && $result_nickname->num_rows === 0 && $result_email->num_rows === 0){
        $sql_register = "INSERT INTO user(user_key,user_name, user_surname, nickname, email, phone, password) VALUES ('".implode('', $id_generator_storage)."','".$register_name."', '".$register_surname."', '".$register_nickname."', '".$register_email."', ".$register_phone.", '".$register_password."');";
        $result = $conn->query($sql_register);
        if ($result == False) {
            echo "Error " . $sql_register . "<br>" . $conn -> error;
        } else {
            readfile("register_confirm.html");
        }
    }
    elseif ($result_email->num_rows > 0){
        readfile("register.html");
        echo "<p style='position: absolute; top: 53.5%; right: 30.5%; font-size: 1.8vh;'>already in-use</p>";
        echo '<svg style="position:absolute; top:54%; right: 37%" xmlns="http://www.w3.org/2000/svg" width="2vh" height="2vh" fill="currentColor" class="bi bi-exclamation-circle" viewBox="0 0 16 16">
  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
  <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
</svg>';
    }
    elseif ($result_nickname->num_rows > 0) {
        readfile("register.html");
        echo "<p style='position: absolute; top: 48%; right: 30.5%; font-size: 1.8vh;'> already taken</p>";
        echo '<svg style="position:absolute; top:48.5%; right: 37%" xmlns="http://www.w3.org/2000/svg" width="2vh" height="2vh" fill="currentColor" class="bi bi-exclamation-circle" viewBox="0 0 16 16">
  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
  <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
</svg>';
    }
    //elseif (!$register_name){
        //readfile("register.html");
        //echo "<p style='position: absolute; top: 45%; right: 30.5%; font-size: 1.8vh;'>*can't live blank</p>";
    //}
}
//working_page---------------------------------
class card{
    protected $name;
    protected $info;
    protected $card_id;
    protected $user_id;

    public function __construct($name, $info, $card_id){
        $this->name = $name;
        $this->info = $info;
        $this->card_id = $card_id;
        $this->user_id = $_SESSION['user_id'];
    }
    public function do_card(){
        echo "<div class='main_panel_card'>
                <form action='local_brain.php' method='post' id='card_form".$this->card_id."'>
                    <input type='hidden' name='card_id' value='".$this->card_id."'>
                    <input type='hidden' name='user_id' value='".$this->user_id."'>
                </form>
                <a href='#' onclick='document.getElementById(\"card_form".$this->card_id."\").submit();'>
                    <img src='oYiTqum.jpg' alt='' class='main_panel_card_img'>
                    <div class='main_panel_card_bottom'>
                        <div class='main_panel_card_bottom_h1'>
                            <h1>".$this->name."</h1>
                        </div>
                        <div class='main_panel_card_bottom_p'>
                            <p>".$this->info."</p>
                        </div>
                    </div>
                </a>
        </div>";
    }
}
class graph{
    protected $xValues;
    protected $yValues;
    protected $yValues_money;

    public function __construct($xValues, $yValues){
        $this->xValues = $xValues;
        $this->yValues = $yValues;
        $this->yValues_money = $_SESSION["show_money"];
    }

    public function graph(){
        echo "<script type='text/javascript'>
var xValues = [".$this->xValues."];
var yValues = [".$this->yValues.",".$this->yValues_money."];
console.log(xValues);
console.log(yValues);
new Chart('myChart', {
    type: 'line',
    data: {
        labels: xValues,
        datasets: [{
            fill: false,
            lineTension: 0,
            backgroundColor: 'rgba(0,0,255,1.0)',
            borderColor: 'rgba(0,0,255,0.1)',
            data: yValues
        }]
    },
    options: {
        legend: {display: false},
        scales: {
            yAxes: [{ticks: {min: 6, max:16}}],
        }
    }
});</script>";
    }
}
function main_page(){
    global $conn;
    echo "<div id='welcome_top' class='welcome_top'>
<h1>Welcome ".$_SESSION['u_nickname']."</h1>
</div>";
    readfile("main_panel.html");
    $sql = "SELECT * FROM `cards` WHERE user_id = '".$_SESSION["user_id"]."'";
    $result = $conn->query($sql);
    echo "
<div class='main_panel_row'>
        <div class='main_panel_addcard'>
            <a href='#' class='main_panel_addcard_href' id='add_card'>
                <h1>+<p>Add card</p></h1>
            </a>
        </div>";
    while($row = $result->fetch_assoc()){
        $show_card = new card($row["name"], $row["info"], $row['card_id']);
        $show_card->do_card();
    }
    echo "</div>";
    echo "    <!--Footer-->
    <div class='index_footer'>
        <div class='index_footer_logo_align'>
            <img class='index_footer_logo' src='Untitled-4.png' alt='whitefox'>
        </div>
        <div class='index_footer_text'>
            <p>© 2023 Whitefox</p>
            <p><br>If you would like to find out more about WhiteFox, follow <a href='https://www.instagram.com/tarelunga_daniel/?hl=en' style='color: white' target='_blank'>@tarelunga_daniel</a> on instagram. If you have any other questions, please reach out to us via our mobile number: +420 775 696 129. WhiteFox is a website established in the Czech Republic, registered address: Višňová 4, Moravany U Brna, 664 48, Czech Republic, number of registration: none. WhiteFox is a project by Daniel Tarelunga and regulated by EducaNET Brno High-school. WhiteFox provides services that include: statistics, review, control and management of your money</p>
            <p><br>Insurance distribution service is not provided by anyone, any data breach or data leaking that might occur, WhiteFox is not responsible. Please follow our recommendations for using our website. That includes: do not have the same password on another platform, do not share your Client number with anyone, do not visit WhiteFox website on public networks, try using VPN if possible.</p>
            <p><br>WhiteFox is authorised by High-school EducaNET Brno under the Laws of the Czech Republic. EducaNet High-school address: Jánská 22, 602 00 Brno-střed, Czech Republic. School related-products the same as WhiteFox customers are provided by EducaNet Brno High-school which is authorised by the Ministry of Education to control the progress of the project and by WhiteFox.</p>
        </div>
    </div>";
}

//mainpage_admin------------------------------------------------
function main_page_admin(){
    global $conn;
    echo "<div id='welcome_top' class='welcome_top'>
<h1>Welcome ".$_SESSION['u_nickname']."</h1>
</div>";
    readfile("main_panel_admin.html");
    $sql = "SELECT * FROM `user`;";
    $result = $conn->query($sql);
    echo "<div class='main_panel_admin_all_control'>
              <div class='main_panel_admin_table_control'>";
    echo "<table>
<tr>
    <th>User key</th>
    <th>Username</th>
    <th>Nickname</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Admin</th>
    <th></th>
    <th></th>
    <th></th>
</tr>";
    $i = 0;
    while ($row = $result->fetch_assoc()){
        echo "<tr>";
        echo "<td>".$row["user_key"]."</td>
              <td>".$row["user_name"]." ".$row["user_surname"]."</td>
              <td>".$row["nickname"]."</td>
              <td>".$row["email"]."</td>
              <td>".$row["phone"]."</td>";
        if ($row["admin"] > 0){
            echo "<td>YES</td>";
        } else {
            echo "<td>NO</td>";
        }
        $row_delete[$i] = $row["user_key"];
        echo "<td><a href='#'>EDIT</a></td>
              <td><a href='#'>BAN</a></td>
              <td><a href='#' onclick=\"window.location.href='local_brain.php?action=main_page_admin_delete(".$row["user_key"].")'\">DELETE</a></td>";
        echo "</tr>";
        $i++;
    }
    echo "</table>";
    echo "</div>";
    echo "</div>";
    echo "    <!--Footer-->
    <div class='index_footer'>
        <div class='index_footer_logo_align'>
            <img class='index_footer_logo' src='Untitled-4.png' alt='whitefox'>
        </div>
        <div class='index_footer_text'>
            <p>© 2023 Whitefox</p>
            <p><br>If you would like to find out more about WhiteFox, follow <a href='https://www.instagram.com/tarelunga_daniel/?hl=en' style='color: white' target='_blank'>@tarelunga_daniel</a> on instagram. If you have any other questions, please reach out to us via our mobile number: +420 775 696 129. WhiteFox is a website established in the Czech Republic, registered address: Višňová 4, Moravany U Brna, 664 48, Czech Republic, number of registration: none. WhiteFox is a project by Daniel Tarelunga and regulated by EducaNET Brno High-school. WhiteFox provides services that include: statistics, review, control and management of your money</p>
            <p><br>Insurance distribution service is not provided by anyone, any data breach or data leaking that might occur, WhiteFox is not responsible. Please follow our recommendations for using our website. That includes: do not have the same password on another platform, do not share your Client number with anyone, do not visit WhiteFox website on public networks, try using VPN if possible.</p>
            <p><br>WhiteFox is authorised by High-school EducaNET Brno under the Laws of the Czech Republic. EducaNet High-school address: Jánská 22, 602 00 Brno-střed, Czech Republic. School related-products the same as WhiteFox customers are provided by EducaNet Brno High-school which is authorised by the Ministry of Education to control the progress of the project and by WhiteFox.</p>
        </div>
    </div>";
}
function failed_login(){
    echo "<p style='position: absolute; text-align: center ; width: 100%; top: 25%'>Unexistent client or incorrect password!</p>";
    readfile("login_page.html");
}
function add_card(){
    global $conn;
    $card_name = isset($_POST['card_name']) ? $_POST['card_name'] : "";
    $card_info = isset($_POST['card_info']) ? $_POST['card_info'] : "";
    $sql = "INSERT INTO cards(user_id, name, info) VALUES ('".$_SESSION['user_id']."','".$card_name."','".$card_info."');";
    $result = $conn->query($sql);
    main_page();
}
function show_stats($card_id){
    $_SESSION["show_money"] = 0;
    $_SESSION["card_id"] = $card_id;
    global $conn;

    readfile("account.html");
    $sql = "SELECT * FROM `money` WHERE card_id = ".$card_id." and user_id = ".$_SESSION['user_id'].";";
    $result = $conn->query($sql);

    $sql2 = "SELECT * FROM `cards` WHERE card_id = '".$card_id."'";
    $result2 = $conn->query($sql2);
    $row_name = $result2->fetch_assoc();

    $show_money = 0;
    echo '<div class="account_all_control">
        <h1 class="account_card_name"><b>'.$row_name["name"].'</b></h1>
        <div class="account_maincard">
            <div class="account_maincard_top">
                <div class="account_maincard_top_money">';
    while($row = $result->fetch_assoc()){
        $show_money = explode(".",$row["money"]);
        echo '<h1>CZK '.$show_money[0].'</h1><p>.'.$show_money[1].'</p>';
        $_SESSION["show_money"] = $row["money"];
    }
    if ($show_money[0] === NULL){
        echo '<h1>CZK 0</h1><p>.00</p>';
    }
    echo '</div>
                <div class="account_maincard_top_buttons">
                    <button id="account_button_add_money" onclick="add_money_show()">+ Add Money</button>
                    <button id="account_button_send_money" onclick="">- Send Money</button>
                    <form action="local_brain.php" method="post">
                        <input type="number" step="any" id="account_add_money" name="account_add_money"><button class="account_button_add_money_php" id="account_button_add_money1">+ Add Money</button>
                        <input type="number" step="any" id="account_send_money" name="account_send_money"><button class="account_button_send_money_php" id="account_button_send_money1">- Send Money</button>
                    </form>
                </div>
            </div>
            <div class="account_maincard_mid">
                <div class="account_maincard_mid_graph_control1">
                    <div class="account_maincard_mid_graph_control2">
                        <canvas id="myChart" class="account_graph"></canvas>
                    </div>
                </div>
                <div class="account_maincrad_mid_tittle">
                    <p>Transactions</p>
                    <a href="#">See all</a>
                </div>';
    $sql = 'SELECT * FROM `transaction` WHERE card_id = '.$_SESSION["card_id"].' and user_id = '.$_SESSION["user_id"].' ORDER BY `transaction_id` DESC;';
    $result = $conn->query($sql);
    echo '<div class="account_maincard_mid_transactions">
                    <div class="account_maincard_mid_transactions_card">';
    $i = 4;
    while ($row = $result->fetch_assoc() and $i > 0) {
        echo '<div class="account_maincard_mid_transactions_card_bubble">
                <a href="#">Lidl<p class="account_maincard_mid_transactions_card_bubble_p1">'.$row["suma"].'</p><p class="account_maincard_mid_transactions_card_bubble_p2">CZK</p></a>
              </div>';
        $i--;
    }
                    echo '</div>
                </div>
            </div>
        </div>
    </div>';
    info_graph($_SESSION["show_money"]);
}
function money_transaction_plus($card_id){
    global $conn;

    $money = isset($_POST["account_add_money"]) ? $_POST["account_add_money"] : "";
    $money_test = isset($_POST["account_send_money"]) ? $_POST["account_send_money"] : "";
    $calculate = $_SESSION["show_money"] + $money;
    $sql = "SELECT * FROM `money` WHERE card_id = ".$card_id.";";
    $result = $conn->query($sql);
    if ($row = $result->fetch_assoc()){
        $sql2 = "UPDATE `money` SET `money`= ".$calculate.",`date`=curdate() WHERE card_id = ".$card_id." and user_id = ".$_SESSION["user_id"].";";
        $result2 = $conn->query($sql2);
        show_stats($card_id);
    } else{
        $sql3 = "INSERT INTO `money`(`card_id`, `user_id`, `money`, `date`) VALUES (".$card_id.",".$_SESSION["user_id"].",0,curdate());";
        $result3 = $conn->query($sql3);
        money_transaction_plus($card_id);
    }
}
function money_transaction_minus($card_id){
    global $conn;

    $money = isset($_POST["account_send_money"]) ? $_POST["account_send_money"] : "";
    $calculate = $_SESSION["show_money"] - $money;
    $sql = "SELECT * FROM `money` WHERE card_id = ".$card_id.";";
    $result = $conn->query($sql);
    if ($row = $result->fetch_assoc()){
        $sql2 = "UPDATE `money` SET `money`= ".$calculate.",`date`=curdate() WHERE card_id = ".$card_id." and user_id = ".$_SESSION["user_id"].";";
        $result2 = $conn->query($sql2);
        show_stats($card_id);
    } else{
        $sql3 = "INSERT INTO `money`(`card_id`, `user_id`, `money`, `date`) VALUES (".$card_id.",".$_SESSION["user_id"].",0,curdate());";
        $result3 = $conn->query($sql3);
        money_transaction_plus($card_id);
    }
}
function info_graph($money){
    global $conn;

    $sql = "SELECT * FROM `transaction` WHERE user_id = ".$_SESSION["user_id"]." and card_id = ".$_SESSION["card_id"]." ORDER BY `transaction_id` DESC;";
    $result = $conn->query($sql);
    $i = 5;
    $j = 0;
    $graph2 = NULL;
    $graphX = NULL;
    $graphY = NULL;
    while ($row = $result->fetch_assoc() and $i > 0) {
        $graph2[$j] = $row["money"];
        $j++;
        $i--;
    }
    if ($graph2 != NULL) {
        $reverse_graphY = array_reverse($graph2);
        $graph1 = [1, 2, 3, 4, 5, 6];
        $graphX = implode(",", $graph1);
        $graphY = implode(",", $reverse_graphY);
    }
    $graph_construct = new graph($graphX, $graphY);
    $graph_maker = $graph_construct->graph();
}
function main_page_admin_delete(){
    global $conn;

    $sql = "DELETE FROM `user` WHERE user_key = '".$_SESSION["user_key_delete"]."';";
    var_dump($sql);
}
//if (isset($_SESSION["u_id"])){
    //echo $_SESSION["u_id"];
//}
?>