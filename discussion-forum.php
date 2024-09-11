<?php
session_start();
require_once 'includes/db.php';
include 'includes/version.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$profile_picture = $_SESSION['profile_picture'];

// Fetch previous messages
$messages = [];
$sql = "SELECT users.username, users.profile_picture, messages.id, messages.user_id, messages.message, messages.created_at 
        FROM messages 
        JOIN users ON messages.user_id = users.id 
        ORDER BY messages.created_at ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Room</title>
    <link rel="stylesheet" href="assets/css/styles.css?v=<?php echo $version; ?>">
    <link rel="icon" type="image/png" href="https://saifali.sirv.com/favicon/favicon-32x32.png">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <?php include 'includes/header.php'; ?>
    </header>
    <main class="chat-container">
     <h2>Discussion Forum</h2>
      <p>Note: all messages are deleted within 4hrs</p>
        <div id="chat-container">
            <div id="chat-messages">
                <?php foreach ($messages as $message): ?>
                    <div class="message <?php echo $message['user_id'] == $user_id ? 'sent' : 'received'; ?>" data-message-id="<?php echo $message['id']; ?>">
                        <img src="uploads/profile_picture/<?php echo $message['profile_picture']; ?>" alt="<?php echo $message['username']; ?>" class="avatar">
                        <div class="message-content">
                            <strong><?php echo $message['username']; ?>:</strong>
                            <p><?php echo $message['message']; ?></p>
                            <span class="timestamp"><?php echo date('m-d H:i', strtotime($message['created_at'])); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div id="chat-input">
                <input type="text" id="message-input" placeholder="Type your message...">
                <button id="send-button">Send</button>
            </div>
        </div>
    </main>

    <script>
        let lastMessageId = <?php echo !empty($messages) ? end($messages)['id'] : 0; ?>;

        // Long polling to fetch new messages
        function fetchMessages() {
            $.get('fetch_messages.php', { last_message_id: lastMessageId }, function(data) {
                const messages = JSON.parse(data);
                if (messages.length > 0) {
                    messages.forEach(function(message) {
                        const messageClass = message.user_id == <?php echo $user_id; ?> ? 'sent' : 'received';
                        $('#chat-messages').append(`
                            <div class="message ${messageClass}" data-message-id="${message.id}">
                                <img src="uploads/profile_picture/${message.profile_picture}" alt="${message.username}" class="avatar">
                                <div class="message-content">
                                    <strong>${message.username}</strong>
                                    <p>${message.message}</p>
                                    <span class="timestamp">${new Date(message.created_at).toLocaleString()}</span>
                                </div>
                            </div>
                        `);
                        lastMessageId = message.id;
                    });
                    $('#chat-messages').scrollTop($('#chat-messages')[0].scrollHeight);  // Auto scroll to bottom
                }
                setTimeout(fetchMessages, 1000);  // Poll every second
            });
        }

        fetchMessages();

        // Send a new message
        $('#send-button').click(function() {
            const message = $('#message-input').val();
            if (message.trim() !== '') {
                $.post('send_message.php', { message: message }, function(response) {
                    const messageData = JSON.parse(response);
                    const messageClass = messageData.user_id == <?php echo $user_id; ?> ? 'sent' : 'received';
                    $('#chat-messages').append(`
                        <div class="message ${messageClass}" data-message-id="${messageData.id}">
                            <img src="uploads/profile_picture/${messageData.profile_picture}" alt="${messageData.username}" class="avatar">
                            <div class="message-content">
                                <strong>${messageData.username}</strong>
                                <p>${messageData.message}</p>
                                <span class="timestamp">${new Date().toLocaleString()}</span>
                            </div>
                        </div>
                    `);
                    $('#chat-messages').scrollTop($('#chat-messages')[0].scrollHeight);  // Auto scroll to bottom
                    $('#message-input').val('');  // Clear message input
                    lastMessageId = messageData.id;  // Update last message ID
                });
            }
        });

        // Handle "Enter" key press to send message
        $('#message-input').keypress(function (e) {
            if (e.which === 13) {
                $('#send-button').click();
                return false;  // Prevent the default action
            }
        });
    </script>
</body>
</html>