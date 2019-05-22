<?php
session_start();

//checks if logged in if not sends you back to the login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

if(isset($_POST['finish'])){
    session_destroy();
    session_start();
}

if(!isset($_SESSION['x'])&&!isset($_SESSION['o'])&&!isset($_SESSION['gamesPlayed'])){

    $_SESSION['t'] = 0;
    $_SESSION['x'] = 0;
    $_SESSION['o'] = 0;
    $_SESSION['gamesPlayed'] = 0;
    $_SESSION['turn']=0;
}

$winner = 'n';
$win = "";
$readonly = array_fill(0, 9, '');
$sqr = array_fill(0, 9, '');


if (isset($_POST['submit'])) {

    for ($i = 0; $i < 9; $i++) {

        $sqr[$i] = $_POST["box$i"];
        if ($sqr[$i] == 'x' || $sqr[$i] == 'o') {

            $readonly[$i] = 'readonly';
        }


    }
    if (count($place = location($sqr, 'x', 3)) == 3) {

        $winner = 'x';
        $win = 'x won';
        $_SESSION['x']++;
        $_SESSION['gamesPlayed']++;
    }


    /*$blank = 0;

    for ($i = 0; $i <= 8; $i++) {

        if ($sqr[$i] == '') {
            $blank = 1;

        }
    }

    if ($blank == 1 && $winner == 'n') {

        $i = rand(0, 8);
        while ($sqr[$i] != '') {
            $i = rand(0, 8);
        }
        
        $readonly[$i] = 'readonly';*/

       elseif (count($place = location($sqr, 'o', 3)) == 3) {

            $winner = 'o';
            $win = 'o won';
            $_SESSION['o']++;
            $_SESSION['gamesPlayed']++;
       // }

    } else if ($winner == 'n') {
        $winner = 't';
        echo "tie game";
        $_SESSION['t']++;
        $_SESSION['gamesPlayed']++;

    }
}
?>

<html>
<head>
    <style>
        body{
            background-image: url("ttc_pic.jpg");

        }
        .highlight{
            text-align: center;
            background-color: snow;
            color: maroon;
            font-size: 50px;
            border-style: wave;
            border-width: thick;
            border-color: mistyrose;
            opacity: 0.5;
            filter: alpha(opacity=50);

        }
        .winner{
            text-align: center;
            background-color: cadetblue;
            color: maroon;
            font-size: 50px;
            border-style: wave;
            border-width: thick;
            border-color: mistyrose;
            opacity: 0.5;
            filter: alpha(opacity=50);

        }
        .highlight2{
            color: snow;
            font-size: 25px;
        }
        .highlight3{
            color:cadetblue;
            font-size: 25px;
        }
    </style>
</head>
<body>

<form action="tic_tac_toe_2.php" method="post">

    <?php for($i=0; $i<9; $i++) { ?>
        <input class="highlight" type="text" pattern="[XOxo]{1}" name="box<?php echo $i; ?>" value="<?php echo $sqr[$i]; ?>" size="2" <?php echo $readonly[$i]; ?>>
        <?php if(($i+1)%3==0) echo "<br />"; ?>
    <?php } ?>

        <input type="submit" value="submit" name="submit">

    <button type="submit" name="reset" value="reset">play again</button>
    <button type="submit" name="finish" value="destroy">Reset</button>
    <button type="submit" name="friend" value="friend" formaction="tic_tac_toe.php">play with computer</button>
</form>

<p class="highlight3"><?php echo $win; ?></p>
<p class="highlight2"><?php
    echo "games x won:  ".$_SESSION['x']."<br />";
    echo "games o won:  ".$_SESSION['o']."<br />";
    echo "games played:  ".$_SESSION['gamesPlayed']."<br />";
    echo "games tied:  ".$_SESSION['t']."<br />";
    ?>
</p>
<p><a href="logout.php">Sign Out of Your Account</a></p>
        <script>
        var box = document.getElementsByClassName('highlight');
        <?php if(!empty($winner)) $place = location($sqr, $winner, 3); ?>
        var pstn = <?php echo json_encode($place); ?>
        
        if(pstn.length == 3){
            
            highlight(pstn, box);
        }
            function highlight(pstn, box){

        for(a=0; a<pstn.length; a++){
            let b = parseInt(pstn[a]);
            box[b].classList.toggle("winner");
        }
    }
    </script>
</body>
</html>
<?php

function location($pstn, $plyr, $sq){

    $countR = $countL = array();
    $r = $l = 0;

    for($i=0; $i<$sq; $i++){

        $x = $y = 0;

        for($j=0; $j<$sq; $j++){

            //checks horizontal lines
            if($pstn[$i*$sq+$j]==$plyr){
                $x += 1;
                $countX[$j] = ($i*$sq+$j);
            }

            //checks vertical lines
            if($pstn[$j*$sq+$i]==$plyr){
                $y += 1;
                $countY[$j] = ($j*$sq+$i);
            }

        }
        if($x == $sq) {
            return $countX;
        }elseif($y == $sq){
            return $countY;
        }else{
            $countX = $countY = array();
        }

        //checks right to left diagonal line
        if($pstn[$i*$sq+$i]==$plyr){
            $r += 1;
            $countR[$i] = ($i*$sq+$i);
        }

        //checks left to right diagonal line
        if($pstn[($sq-1)*($i+1)]==$plyr){
            $l += 1;
            $countL[$i] = ($sq-1)*($i+1);
        }
    }
    if($r == $sq){
        return $countR;
    }elseif($l == $sq){
        return $countL;
    }else{
        return $count = array();
    }

}

?>

