<?php
$api_key = "b972c7ca44dda72a5b482052b1f5e13470e01477f3fb97c85d5313b3c112627073481104fec2fb1a0cc9d84c2212474c0cbe7d8e59d7b95c7cb32a1133f778abd1857bf934ba06647fda4f59e878d164";
$action  = null;
$word    = null;
if (isset($argv[1])) {
    $action = $argv[1];
}
if (isset($argv[2])) {
    $word = $argv[2];
}
dictionaryIndex($action, $word);

function dictionaryIndex($action, $word)
{
    if ($action == null && $word == null) {
        $word = wordGenerate();
        echo "\nRandom Word:\n " . $word . "\n";
        echo "\nDefinations:\n" . wordDefinations($word, -1);
        echo "\nSynonyms:\n" . wordSynonymsAndAntonyms($word, "synonym", -1);
        echo "\nAntonym:\n" . wordSynonymsAndAntonyms($word, "antonym", -1);
        echo "\nExamples:\n" . wordExamples($word);
        
        
    } else if (strtolower($action) == "play") {
        echo wordGame();
        
    } else if ($word == null) {
        echo "\nDefinations:\n" . wordDefinations($action, -1);
        echo "\nSynonyms:\n" . wordSynonymsAndAntonyms($action, "synonym", -1);
        echo "\nAntonym:\n" . wordSynonymsAndAntonyms($action, "antonym", -1);
        echo "\nExamples:\n" . wordExamples($action);
        
    } else {
        switch ($action) {
            case "defn":
                echo wordDefinations($word, -1);
                break;
            
            case "syn":
                echo wordSynonymsAndAntonyms($word, "synonym", -1);
                break;
            
            case "ant":
                echo wordSynonymsAndAntonyms($word, "antonym", -1);
                break;
            
            case "ex":
                echo wordExamples($word);
                break;
            
            
            default:
                echo "error";
                
        }
    }
}
function wordGame()
{
    $word = wordGenerate();
 //   echo "\nword\n" . $word;
    echo "\nDefinations:\n" . wordDefinations($word, 0);
    echo "\nSynonyms:\n" . wordSynonymsAndAntonyms($word, "synonym", 0);
    echo "\nAntonym:\n" . wordSynonymsAndAntonyms($word, "antonym", 0);
    return WordGamePlay($word, 0, 0, 0);
}
function validateWord($arr, $word, $flag_count)
{
    $count = 0;
    foreach ($arr as $a) {
        if ($count > $flag_count)
            if (strpos($word, $a) !== FALSE) {
                return true;
            }
        $count++;
    }
    return false;
}
function wordGamePlay($word, $defn_count, $syn_count, $ant_count)
{
    $arr = preg_split('/\r\n|\r|\n/', wordSynonymsAndAntonyms($word, "synonym", -1));
    array_pop($arr);
    echo "\nPlease guess the word\n";
    $user_word = trim(fgets(STDIN));
    if (validateWord($arr, $user_word, $syn_count) || $word == $user_word) {
        echo "** Success **";
        exit();
    } else {
        while (true) {
            echo "\nselect your choice";
            echo "\n1.Try agin";
            echo "\n2.Hint";
            echo "\n3.Quit\n";
            
            $ch = trim(fgets(STDIN));
            
            switch ($ch) {
                case 1:
                    wordGamePlay($word, $defn_count, $syn_count, $ant_count);
                    break;
                case 2:
                    while (true) {
                        
                        echo "\n1. Display the word randomly jumbled (cat => atc, tac, tca)\n2. Display another definition of the word\n3. Display another synonym of the word\n4. Display another antonym of the word\n5. Exit\n";
                        echo "\nselect choice\n";
                        
                        $choice = trim(fgets(STDIN));
                        switch ($choice) {
                            case 1:
                                $input      = $word;
                                $characters = array();
                                for ($i = 0; $i < strlen($input); $i++)
                                    $characters[] = $input[$i];
                                $permutations = array();
                                string_getpermutations("", $characters, $permutations);
                                echo "Hint:" . $permutations[rand(0, count($permutations) - 1)];
                                echo "\nEnter Word agin\n";
                                $user_word_one = trim(fgets(STDIN));
                                if (validateWord($arr, $user_word_one, $syn_count) || $word == $user_word_one) {
                                    echo "** success **";
                                    exit();
                                }
                                break;
                            case 2:
                                echo "Another Defination\n";
                                $defn_count++;
                                $response = wordDefinations($word, $defn_count);
                                echo $response;
                                echo "\nEnter Word agin\n";
                                $user_word_two = trim(fgets(STDIN));
                                if (validateWord($arr, $user_word_two, $syn_count) || $word == $user_word_two) {
                                    echo "** success **";
                                    exit();
                                }
                                break;
                            case 3:
                                echo "Another Synonym\n";
                                $syn_count++;
                                $response = wordSynonymsAndAntonyms($word, "synonym", $syn_count++);
                                echo $response;
                                echo "\nEnter Word agin\n";
                                $user_word_three = trim(fgets(STDIN));
                                if (validateWord($arr, $user_word_three, $syn_count) || $word == $user_word_three) {
                                    echo "** success **";
                                    exit();
                                }
                                break;
                            case 4:
                                echo "Another Antonym\n";
                                $ant_count++;
                                $response = wordSynonymsAndAntonyms($word, "antonym", $ant_count++);
                                echo $response;
                                echo "\nEnter Word agin\n";
                                $user_word_three = trim(fgets(STDIN));
                                if ($user_word_three == $word) {
                                    echo "** success **";
                                    exit();
                                }
                                break;
                            case 5:
                                echo "\n Word:\n " . $word . "\n";
                                echo "\nDefinations:\n" . wordDefinations($word, -1);
                                echo "\nSynonyms:\n" . wordSynonymsAndAntonyms($word, "synonym", -1);
                                echo "\nAntonym:\n" . wordSynonymsAndAntonyms($word, "antonym", -1);
                                echo "\nExamples:\n" . wordExamples($word);
                                exit();
                                break;
							default:
								echo "Invalid ";
                        }
                    }
                    break;
                case 3:
                    echo "\n Word:\n " . $word . "\n";
                    echo "\nDefinations:\n" . wordDefinations($word, -1);
                    echo "\nSynonyms:\n" . wordSynonymsAndAntonyms($word, "synonym", -1);
                    echo "\nAntonym:\n" . wordSynonymsAndAntonyms($word, "antonym", -1);
                    echo "\nExamples:\n" . wordExamples($word);
                    echo "** Quit **";
                    exit();
                    break;
					
				default:
					echo "Invalid ";
            }
        }
    }
    
}

function string_getpermutations($prefix, $characters, &$permutations)
{
    if (count($characters) == 1)
        $permutations[] = $prefix . array_pop($characters);
    else {
        for ($i = 0; $i < count($characters); $i++) {
            $tmp = $characters;
            unset($tmp[$i]);
            
            string_getpermutations($prefix . $characters[$i], array_values($tmp), $permutations);
        }
    }
}
function wordGenerate()
{
    
    $api = $GLOBALS['api_key'];
    $url = "https://fourtytwowords.herokuapp.com/words/randomWord?api_key=$api";
    $res = curlCallRequest($url);
    $res = json_decode($res);
    if (isset($res->error)) {
        return $res->error;
    }
    
    if (isset($res->word)) {
        return $res->word;
    } else {
        return null;
    }
}
function wordExamples($word)
{
    $api = $GLOBALS['api_key'];
    $url = "https://fourtytwowords.herokuapp.com/word/$word/examples?api_key=$api";
    $res = curlCallRequest($url);
    $res = json_decode($res);
    if (isset($res->error)) {
        return $res->error;
    }
    
    $response = "";
    foreach ($res->examples as $row) {
        foreach ($row as $key => $val) {
            $response .= $val . "\n";
        }
    }
    return $response;
}
function wordSynonymsAndAntonyms($word, $action, $flag)
{
    
    $api      = $GLOBALS['api_key'];
    $url      = "https://fourtytwowords.herokuapp.com/word/$word/relatedWords?api_key=$api";
    $res      = curlCallRequest($url);
    $res      = json_decode($res);
    $response = "";
    if (isset($res->error)) {
        return $res->error;
    }
    if (isset($res[0]->relationshipType)) {
        if ($res[0]->relationshipType == $action) {
            $flag_count = 0;
            foreach ($res[0]->words as $item) {
                
                $response .= $item . "\n";
                if ($flag != -1 && $flag_count == $flag) {
                    return $item;
                    break;
                }
                $flag_count++;
            }
            return $response;
            
        }
        
    }
    if (isset($res[1]->relationshipType)) {
        if ($res[1]->relationshipType == $action) {
            $flag_count = 0;
            
            foreach ($res[1]->words as $item) {
                $response .= $item . "\n";
                if ($flag != -1 && $flag_count == $flag) {
                    return $item;
                    break;
                }
                $flag_count++;
            }
            return $response;
            
        }
        
    }
    
    
    
}
function wordDefinations($word, $flag)
{
    $api      = $GLOBALS['api_key'];
    $url      = "https://fourtytwowords.herokuapp.com/word/$word/definitions?api_key=$api";
    $res      = curlCallRequest($url);
    $res      = json_decode($res, true);
    $response = "";
    if (isset($res->error)) {
        return $res->error;
    }
    $flag_count = 0;
    foreach ($res as $item) {
        
        if (isset($item['text'])) {
            $response .= $item['text'] . "\n";
            if ($flag != -1 && $flag_count == $flag) {
                return $item['text'];
                break;
            }
            
            $flag_count++;
        } else {
            echo "word not found";
            break;
        }
        
    }
    return $response;
}

function curlCallRequest($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    // This is what solved the issue (Accepting gzip encoding)
    curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}
?>
