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


CREATE TRIGGER update_group_posts
    AFTER UPDATE OR INSERT ON public."group"
    FOR EACH ROW
    EXECUTE PROCEDURE delete_group_posts();


CREATE TRIGGER update_event_posts
    AFTER UPDATE OR INSERT ON public."event"
    FOR EACH ROW
    EXECUTE PROCEDURE update_event_posts();