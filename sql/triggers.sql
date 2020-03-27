CREATE FUNCTION update_group_posts() RETURNS TRIGGER AS
$BODY$
BEGIN
    UPDATE FROM public."post" SET public."post".TYPE = public."group".TYPE WHERE public."post"."group_id" = public."group"."group_id"
END
$BODY$
LANGUAGE plpgsql;

CREATE FUNCTION update_event_posts() RETURNS TRIGGER AS
$BODY$
BEGIN
    UPDATE FROM public."post" SET public."post".TYPE = public."event".TYPE WHERE public."post"."event_id" = public."event"."event_id"
END
$BODY$
LANGUAGE plpgsql;






--Trigger user errado no chat

CREATE FUNCTION throw_exception_user_chat() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF NOT EXISTS (SELECT * FROM public."user_in_chat" WHERE public."user_in_chat"."id_user" = NEW.public."message"."sender_id")
    THEN RAISE EXCEPTION 'An user must be in the chat to send a message.';
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;


CREATE TRIGGER except_user_chat
    BEFORE INSERT ON public."message"
    FOR EACH ROW
    EXECUTE PROCEDURE throw_exception_user_chat();

-- _____________________


--Trigger adicionar amigo

CREATE FUNCTION add_friend() RETURNS TRIGGER AS 
$BODY$
BEGIN
    INSERT INTO public."friend" values (public."friend"."friend_id2", public."friend_id1", 'accepted');
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;


CREATE TRIGGER add_friend
    AFTER UPDATE OR INSERT ON public."friend"
    --WHEN (public."friend"."friendship_status" = 'accepted')
    EXECUTE PROCEDURE add_friend();

--___________________________ TENHO QUE CORRIGIR, NAO PERCEBO :|


CREATE TRIGGER update_group_posts
    AFTER UPDATE OR INSERT ON public."group"
    FOR EACH ROW
    EXECUTE PROCEDURE delete_group_posts();


CREATE TRIGGER update_event_posts
    AFTER UPDATE OR INSERT ON public."event"
    FOR EACH ROW
    EXECUTE PROCEDURE update_event_posts();

