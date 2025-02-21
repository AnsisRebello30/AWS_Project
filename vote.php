<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $itemId = $_POST['item_id'];
    $voteType = $_POST['vote_type'];
    $timestamp = date('Y-m-d H:i:s');

    // Load votes.xml and update the vote counts
    $xml = simplexml_load_file('votes.xml');

    foreach ($xml->item as $item) {
        if ($item['id'] == $itemId) {
            if ($voteType == 'up') {
                $item->upvotes = (int)$item->upvotes + 1;
            } else if ($voteType == 'down') {
                $item->downvotes = (int)$item->downvotes + 1;
            }
            $item->timestamp = $timestamp;  // Update the timestamp in votes.xml
            break;
        }
    }
    $xml->asXML('votes.xml');  // Save the updated vote counts

    // Append the voting action to vote_log.xml
    $logXml = simplexml_load_file('vote_log.xml');
    $logEntry = $logXml->addChild('entry');
    $logEntry->addChild('item_id', $itemId);
    $logEntry->addChild('vote_type', $voteType);
    $logEntry->addChild('timestamp', $timestamp);
    $logXml->asXML('vote_log.xml');  // Save the new entry to vote_log.xml

    // Respond with updated counts and timestamp
    $response = array(
        'upvotes' => (int)$item->upvotes,
        'downvotes' => (int)$item->downvotes,
        'timestamp' => $timestamp
    );

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
