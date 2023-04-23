<?php
error_reporting(E_ALL);
require "globals.ini.php";

$goodLeters = array("A", "R", "N", "D", "B", "C", "E", "Q", "Z", "G", "H", "I", "L", "K", "M", "F", "P", "S", "T", "W", "Y", "V", "U", "X");

$mode = $_POST['mode'];

// $element = $_POST['fastatext'];
$output_format = $_POST['output_format'];

$tempname = uniqId('fasta');
$tempFile = $tempDir . "/" . $tempname;

// if (file_exists($_FILES['file']['tmp_name'])) {
//    $tmp_name = $_FILES['file']['tmp_name'];
//    $location = $tempFile.".query.fasta";
//    $moved = move_uploaded_file($tmp_name, $location);
// }

// Delete (if exists) a file that has the same name
if (file_exists($tempFile . ".clustalo.out")) {
    unlink($tempFile . ".clustalo.out");
}

switch ($mode) {
    case "runfastatext":
	$element = $_POST['fastatext'];
        $elements_list = explode(PHP_EOL, $element);
        $i = 0;
        $ids_num = array();
        $ids = array();
        foreach($elements_list as $line){
            if($line != '') {
                if (mb_substr($line, 0, 1) == '>') {
                    array_push($ids_num, $i);
                    array_push($ids, $line);
                    // echo $line;
                    if (in_array($i-1, $ids_num)) {
                        echo '<div class="p-3 mb-2 bg-danger text-white">';
                        echo nl2br("Missing sequence of '".ltrim($ids[count($ids)-2], '>')."'\r\n"); 
                        echo '</div>';
                    }
                } else {
                    $line = strtoupper($line);
                    foreach(str_split($line) as $letter){
                        if(!in_array($letter, $goodLeters) and $letter!=' ') {
                            echo '<div class="p-3 mb-2 bg-warning text-white">';
                            echo nl2br("Perhaps '".$letter."' should not be in the '".ltrim(end($ids), '>')."' sequence in line ".($i+1)."?\r\n"); 
                            echo '</div>';
                        }
                    }
                }
                $i+=1;
            }
        }
        // echo $elements_list;
        // Open temporary file and store query FASTA
        $ff = fopen($tempFile . ".query.fasta", 'wr') or die("Unable to open file!");
        fwrite($ff, $element);
        fclose($ff);
        break;

    case "rununiprotid":
        $element = $_POST['fastatext'];
        $prefix = "https://rest.uniprot.org/uniprotkb/";
        $array_ids = preg_split("/\r\n|\n|\r/", $element);
        $ff = fopen($tempFile . ".query.fasta", 'a') or die("Unable to open file!");
        foreach ($array_ids as $id) {
            $id = trim($id);
            if ($id != '') {
                if (substr($id, -1) == ',') { $id = substr($id, 0, -1); }
                if (mb_substr($id, 0, 1) == '>') { $id = ltrim($id, '>'); }
                $url = $prefix.$id.".fasta";
                $data = @file($url);
                if (!$data) {
                    echo '<div class="p-3 mb-2 bg-danger text-white">';
                    echo nl2br("'".$id."' is not a valid UniProtID. Skipped.\r\n"); 
                    echo '</div>';
                } else {
                    $headers = explode("\t", $data[0]);
                    $i = 0;
                    while ($i <= sizeof($data)) {
                        if(isset($data[$i])) {
                            fwrite($ff, $data[$i]);
                            }
                        $i += 1;
                        }
                    }
                }
            }
        fclose($ff);
        break;

    case "runfastafile":
	if (file_exists($_FILES['file']['tmp_name'])) {
    	    $tmp_name = $_FILES['file']['tmp_name'];
    	    $location = $tempFile.".query.fasta";
    	    $moved = move_uploaded_file($tmp_name, $location);
	}
        // In this case, do nothing. The file is already created and uploaded to tmp.
        $handle = fopen($tempFile.".query.fasta", "r");
        if ($handle) {
            $i = 0;
            $ids_num = array();
            $ids = array();
            while (($line = fgets($handle)) !== false) {
                $line = trim(preg_replace('/\s\s+/', ' ', $line));
                if($line != '') {
                    if (mb_substr($line, 0, 1) == '>') {
                        array_push($ids_num, $i);
                        array_push($ids, $line);
                        // echo $line;
                        if (in_array($i-1, $ids_num)) {
                            echo '<div class="p-3 mb-2 bg-danger text-white">';
                            echo nl2br("Missing sequence of '".ltrim($ids[count($ids)-2], '>')."'\r\n"); 
                            echo '</div>';
                        }
                    } else {
                        $line = strtoupper($line);
                        foreach(str_split($line) as $letter){
                            if(!in_array($letter, $goodLeters) and $letter!=' ') {
                                echo '<div class="p-3 mb-2 bg-warning text-white">';
                                echo nl2br("Maybe '".$letter."' should not be in the '".ltrim(end($ids), '>')."' sequence in line ".($i+1)."?\r\n"); 
                                echo '</div>';
                            }
                        }
                    } 
                }
                $i+=1;
            }
        }
        break;

}


// execute clustalo, command line prefix set in globals.inc.php
$cmd = $clustaloCmdLine . " --in=" . $tempFile . ".query.fasta --out=" . $tempFile . ".clustalo.out --outfmt=" . $output_format; 
exec($cmd);


// Cleaning temporary files
if (file_exists($tempFile . ".query.fasta")) {
    unlink($tempFile . ".query.fasta");
}

$handle = @fopen($tempFile . ".clustalo.out", "r");
if ($handle) {
    ?>
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="info-box  mb-4"  style="text-align: left; padding-left: 30px; padding-right: 30px;">

                    <p><a download="<?php echo "DOWNLOAD_".$tempname.".txt"; ?>" href="<?php echo "tmp/".$tempname.".clustalo.out"; ?>" ><div style="text-align: right;"><button id="" class="button-clustal">Download</button></div></a></p>

                    <h3>Alignment</h3>
                    <hr>
                    <?php
                    $alignment = "";
                    while (($line = fgets($handle)) !== false) {
                        $alignment .= $line;
                    }
                    ?>
                    <pre><?php echo $alignment; ?></pre>

                </div>
            </div>
        </div>
    <?php

fclose($handle);
}

// This will delete all the output files older than 12 hours every time clustal is executed.
// Can be lowered down in case the page is very used or if alignments are very large
$files = scandir($tempDir);
foreach ($files as $file) {
    $file = $tempDir.'/'.$file;
    if (strpos($file, ".clustalo.out") !== false ) {
        if (filectime($file) < (time() - 3600*12*1)) {
            unlink($file);
        }
    }
}

?>
