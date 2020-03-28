--INSERTS
    INSERT INTO "user" 
    VALUES (NULL, $name, $email, $password);

    INSERT INTO "regular_user"
    VALUES (NULL, $userId, $text);

    INSERT INTO "post"
    VALUES (NULL, $userId, $title, $body, current_timestamp, 0, 0, DEFAULT, DEFAULT, DEFAULT);

    INSERT INTO "comment"
    VALUES (NULL, $userId, $postId, DEFAULT, $body, current_timestamp, 0, 0);

    INSERT INTO "message"
    VALUES (NULL, $userId, $chatId, $body, current_timestamp);

    INSERT INTO "notification"
    VALUES (NULL, $userId, $notDescription, $link, current_timestamp);

    INSERT INTO "friend"
    VALUES ($userId, $userId2, DEFAULT);

