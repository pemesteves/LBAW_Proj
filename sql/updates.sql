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

--UPDATES
    UPDATE "friend"
    SET TYPE = 'accepted'
    WHERE ("friend_id1" = $userId AND "friend_id2" = $userId2);

    UPDATE "post"
    SET TYPE = 'deleted'
    WHERE "post_id" = $postId;
    
    UPDATE "post"
    SET TYPE = 'blocked'
    WHERE "post_id" = $postId;

    UPDATE "user"
    SET TYPE = 'deleted'
    WHERE "user_id" = $userId;
    
    UPDATE "user"
    SET TYPE = 'blocked'
    WHERE "user_id" = $userId;

    UPDATE "organization"
    SET "approval" = TRUE
    WHERE "organization_id" = $orgId;

    UPDATE "group"
    SET TYPE = 'deleted'
    WHERE "group_id" = $groupId;
    
    UPDATE "group"
    SET TYPE = 'blocked'
    WHERE "group_id" = $groupId;

    UPDATE "notified_user"
    SET "seen" = TRUE
    WHERE ("notification_id" = $notId AND "user_notified" = $userId);

    UPDATE "report"
    SET "approval" = TRUE
    WHERE "report_id" = $reportId;

    UPDATE "report"
    SET "approval" = FALSE
    WHERE "report_id" = $reportId;

--DELETES
    --DELETE FROM "report"
    --WHERE "approval" = FALSE;

    DELETE FROM "comment"
    WHERE "comment_id" = $commentId;
    
    DELETE FROM "chat"
    WHERE "chat_id" = $chatId;

    DELETE FROM "message"
    WHERE "message_id" = $messageId;

    DELETE FROM "user_interested_in_event"
    WHERE ("user_id" = $userId AND "event_id" = $eventId);