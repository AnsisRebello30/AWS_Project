<?php
header('Content-Type: application/json');

$xml = simplexml_load_file('votes.xml');
$votes = [];

foreach ($xml->item as $item) {
    $id = (string)$item['id'];
    $votes[$id] = [
        'upvotes' => (int)$item->upvotes,
        'downvotes' => (int)$item->downvotes,
        'timestamp' => (string)$item->timestamp
    ];
}

echo json_encode($votes);
?>
