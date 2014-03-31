
<?php header('Content-type: text/html; charset=utf-8'); ?>
<?php

$red = new Army($_GET['army1'], 'Blue Istudio Army');
$blue = new Army($_GET['army2'], 'Red Degordian Army');

function out($msg) {
    echo $msg . '<br>';
    flush();
    ob_flush();
}

$battle = new Battle($red, $blue);
$battle->begin();

class Battle {

    protected $red;
    protected $blue;

    function __construct(Army $red, Army $blue) {
        $this->red = $red;
        $this->blue = $blue;
    }

    function begin() {
        echo '<audio controls autoplay="autoplay" style="display:none">
              <source src="http://dl.dropboxusercontent.com/s/w432tyr0zl4lkop/start.mp3?dl=1&token_hash=AAH2w_Ae9Wxx4cdUlYB2V9SCvyBPbwfjJfX-Cz5upN4dhA" type="audio/mp3">
              </audio>';

        out('<h1>' . 'Battle has began!' . '</h1>');
        $turn = 1;
        while (true) {
            $this->makeTurn($turn);
            //Red army attacks
            $redAttack = $this->red->shoot();
            $this->blue->size = $this->blue->size - $redAttack['damage'];
            out($this->red->name . $redAttack['msg']);
            //Checks if army has sufficient soldiers left for fight
            if ($this->stats($this->blue, $this->red)) {
                $this->announceWin();
                break;
            }
            out($this->blue->name . " has " . $this->blue->size . " soldiers left. <br>");

            //Blue team attacks
            $blueAttack = $this->blue->shoot();
            $this->red->size = $this->red->size - $blueAttack['damage'];
            out($this->blue->name . $blueAttack['msg']);
            //Checks if army has sufficient soldiers left for fight
            if ($this->stats($this->red, $this->blue)) {
                $this->announceWin();
                break;
            }
            out($this->red->name . " has " . $this->red->size . " soldiers left. <br> ");

            sleep(1);
            out('<br>');
            $this->randomEncounter();
            $turn++;
        }
    }

    public function makeTurn($turn) {
        echo "------<div id=\"$turn\">Turn " . $turn . "</div>  ------ <br>";
        echo "<script>var rect = document.getElementById($turn).getBoundingClientRect(); window.scroll(0, rect.bottom + 10);</script>";
    }

    public function stats($army1, $army2) {
        if ($army1->size <= 0) {
            out('####################################### <br>' . $army1->name . ' suffered heavy casulties and have no more soldiers to fight with.');
            out("<h1 style='font-size:60px;'}>" . $army2->name . ' won the battle!' . "</h1>");
            echo "<img style:width=450px, height=450px; src='http://dl.dropboxusercontent.com/s/gbcuuauxc12nvpe/139619619749957.jpg?dl=1&token_hash=AAE7yxkwRhSv98_hRym9ftG9b-6TdXmrDvZpIBWl9llAtA'></img>";
            return true;
        }
    }

    public function announceWin() {
        echo '<audio controls autoplay="autoplay" style="display:none">
              <source src="http://dl.dropboxusercontent.com/s/qrk2a6brhl9ew65/win.mp3?dl=1&token_hash=AAGfr032YNG-iLPYDHt5kEsCdIovV17PvNhjrmLG6mjVjA" type="audio/mp3">
              </audio>';
    }

    public function randomEncounter() {
        $couldntseethiscoming = rand(0, 20);
        switch ($couldntseethiscoming) {
            case ($couldntseethiscoming >= 0 && $couldntseethiscoming <= 4):
                if (rand(0, 1)) {
                    out($this->red->name . " got reinforcements of 3 soldiers.");
                    $this->red->reinforcements(3);
                } else {
                    out($this->blue->name . " got reinforcements of 3 soldiers.");
                    $this->blue->reinforcements(3);
                }
                break;
            case ($couldntseethiscoming >= 5 && $couldntseethiscoming <= 8):
                if (rand(0, 1)) {
                    out($this->red->name . " got reinforcements of 7 soldiers.");
                    $this->red->reinforcements(7);
                } else {
                    out($this->blue->name . " got reinforcements of 7 soldiers.");
                    $this->blue->reinforcements(7);
                }
                break;
            case ($couldntseethiscoming >= 8 && $couldntseethiscoming <= 9):
                $goodInPeople = rand(0, 10);
                if ($goodInPeople == 10) {
                    out("<h2>" . "Something great flies above the battlefield. Soldiers suddenly start to feel bliss inside their hearths. Weapons started falling of their hands "
                            . "like overripe fig from the three in hot summer days. Battle seems to be over, at least for this bunch of lads. Unfortunatelly, divine intervention will not help them"
                            . " on the military court, but who's to blame that, but them. As for today, there will be no more fighting." . "</h2>");
                    exit;
                }
                out("Something great flies above the battlefield. Again, some say. But the bloodlust is stronger within the soldiers, than the potentinal divine intervention."
                        . " Sadly, soldiers keep on fighting.");
                break;
        }
    }

}

class Army {

    public $size = 0;
    public $name = null;
    public $luck;

    public function __construct($size, $name) {
        $this->size = $size;
        $this->name = $name;
    }

    public function reinforcements($soldiers) {
        $this->size = $this->size + $soldiers;
    }

    public function shoot() {
        $luck = rand(0, 15);
        switch ($luck) {
            case ($luck >= 0 && $luck <= 5):
                $result = array('msg' => '  attacks and kills 1 enemy.', 'damage' => 1);
                return $result;
                break;
            case ($luck >= 5 && $luck <= 8):
                $result = array('msg' => '  attacks and kills 2 enemies.', 'damage' => 2);
                return $result;
                break;
            case ($luck >= 9 && $luck <= 12 ):
                $result = array('msg' => '  attacks and kills 3 enemies.', 'damage' => 3);
                return $result;
                break;
            case ($luck >= 12 && $luck <= 14 ):
                $result = array('msg' => ' sharpshooter has got into the position and starts shooting. Four soldiers flee from battle.', 'damage' => '4');
                return $result;
                break;
            case ($luck == 15):
                $cannonPreparation = rand(0, 5);
                if ($cannonPreparation >= 0 && $cannonPreparation <= 3) {
                    $result = array('msg' => ' cannon finally in position. Devastating damage. Ten people down.', 'damage' => '10');
                } else {
                    $result = array('msg' => ' cannon finally in position. Cannon jams, no damage done.', 'damage' => '0');
                }
                return $result;
                break;
        }
    }

}
?>

