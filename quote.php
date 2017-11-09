<?php
function rand_line($fileName, $maxLineLength = 4096) {
    $handle = @fopen($fileName, "r");
    if ($handle) {
        $random_line = null;
        $line = null;
        $count = 0;
        while (($line = fgets($handle, $maxLineLength)) !== false) {
            $count++;
            // P(1/$count) probability of picking current line as random line
            if(rand() % $count == 0) {
              $random_line = $line;
            }
        }
        if (!feof($handle)) {
            echo "Error: unexpected fgets() fail\n";
            fclose($handle);
            return null;
        } else {
            fclose($handle);
        }
        return $random_line;
    }
}
echo rand_line("quote.txt");
?>

<?php
// array
$array = Array (
    "0" => Array (
        "id" => "USR1",
        "name" => "Steve Jobs",
        "company" => "Apple"
    ),
    "1" => Array (
        "id" => "USR2",
        "name" => "Bill Gates",
        "company" => "Microsoft"
    ),
    "2" => Array (
        "id" => "USR3",
        "name" => "Mark Zuckerberg",
        "company" => "Facebook"
    )
);
// encode array to json
$json = json_encode(array('data' => $array));
// write json to file
if (file_put_contents("data.json", $json))
    echo "File JSON sukses dibuat...";
else 
    echo "Oops! Terjadi error saat membuat file JSON...";
// data.json
// {"data":[{"id":"USR1","name":"Steve Jobs","company":"Apple"},{"id":"USR2","name":"Bill Gates","company":"Microsoft"},{"id":"USR3","name":"Mark Zuckerberg","company":"Facebook"}]}
?>
