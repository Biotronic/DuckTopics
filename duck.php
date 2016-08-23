<?php
$command = $_REQUEST['command'];
$text = $_REQUEST['text'];
$token = $_REQUEST['token'];

if($token != 'nxpMexEFSuLSyUXCksZN1rAr'){ #replace this with the token from your slash command configuration page
  $msg = "The token for the slash command doesn't match. Check your script.";
  die($msg);
  echo $msg;
}

class ReplySet {
    public $matches = array();
    public $replies = array();
    
    public function IsMatch($text) {
        if (count($replies) == 0) return false;
        foreach ($this->matches as $match) {
            if (preg_match("/$match/i", $text)) {
                return true;
            }
        }
        return false;
    }
    
    public function GetReply() {
        return $this->replies[array_rand($this->replies)];
    }
}

$replySets = array();

function LoadreplySets($input) {
    global $replySets;
    $arr = explode("\n", $input);
    $reply = false;
    $set = new ReplySet();
    foreach ($arr as $line) {
        $head = substr($line, 0, 1);
        $tail = trim(substr($line, 1));
        if ($head == "-") {
            $reply = true;
        } else if ($head == "?") {
            if ($reply) {
                $reply = false;
                $replySets[] = $set;
                $set = new ReplySet();
            }
        } else {
            continue;
        }
        
        if ($reply) {
            $set->replies[] = $tail;
        } else {
            $set->matches[] = $tail;
        }
    }
    $replySets[] = $set;
}

LoadreplySets(file_get_contents("https://raw.githubusercontent.com/Biotronic/DuckTopics/master/Topics.txt"));

function Speak($input) {
    global $replySets;
    foreach ($replySets as $set) {
        if ($set->IsMatch($input)) {
            return $set->GetReply();
        }
    }
    return "Quack.";
}

function SpeakJson($input) {
    header('Content-Type: application/json');
    $reply = Speak($input);
    $json = array("response_type" => "in_channel", "text" => $reply);
    echo json_encode($json);
}

SpeakJson($text);

?>