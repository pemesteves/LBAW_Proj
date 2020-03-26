CREATE FUNCTION update_group_posts() RETURNS TRIGGER AS
$BODY$
BEGIN
    UPDATE FROM public."post" SET public."post".TYPE = public."group".TYPE WHERE public."post"."group_id" = public."group"."group_id"
END
$BODY$
LANGUAGE plppgsql;

CREATE FUNCTION update_event_posts() RETURNS TRIGGER AS
$BODY$
BEGIN
    UPDATE FROM public."post" SET public."post".TYPE = public."event".TYPE WHERE public."post"."event_id" = public."event"."event_id"
END
$BODY$
LANGUAGE plppgsql;

CREATE FUNCTION throw_exception_user_chat() RETURNS TRIGGER AS
$BODY$
    IF NOT EXISTS (SELECT * FROM public."user_in_chat" WHERE public."user_in_chat"."id_user" = NEW.public."message"."sender_id")
    THEN RAISE EXCEPTION 'An user must be in the chat to send a message.';

$BODY$
LANGUAGE plppgsql;


CREATE TRIGGER update_group_posts
    AFTER UPDATE OR INSERT ON public."group"
    FOR EACH ROW
    EXECUTE PROCEDURE delete_group_posts();


CREATE TRIGGER update_event_posts
    AFTER UPDATE OR INSERT ON public."event"
    FOR EACH ROW
    EXECUTE PROCEDURE update_event_posts();


CREATE TRIGGER except_user_chat
    BEFORE INSERT ON public."message"
    FOR EACH ROW
    EXECUTE PROCEDURE update_event_posts();