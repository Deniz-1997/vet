<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CountTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE OR REPLACE FUNCTION public.update_unreaded_notify_count() RETURNS TRIGGER AS $$
        DECLARE
            _notification_id INTEGER;
            _user_id INTEGER;
            _notify_event_id INTEGER;
            _unviewed INTEGER;
            BEGIN
            IF (TG_OP = 'DELETE') THEN
                _notify_event_id = OLD.notify_event_id;
                _user_id = OLD.user_id;
                ELSE 
                _notify_event_id = NEW.notify_event_id;
                _user_id = NEW.user_id;
            END IF;
            _notification_id = (select nte.notifications_id from notifications.model_notifications_sends nts join notifications.model_notifications_events nte on nts.notify_event_id = nte.id where nts.notify_event_id = _notify_event_id limit 1);
            _unviewed = (select count(nts.viewed) from notifications.model_notifications_sends nts where user_id = _user_id and notify_event_id = _notify_event_id  and nts.send = true
                 and nts.viewed = false);
            update channels.model_channels_notifications_counts
            set count=_unviewed
            where user_id = _user_id
            and notification_id =_notification_id;
            RETURN NULL;
            END;
        $$ LANGUAGE plpgsql;");

        DB::statement("DROP TRIGGER IF EXISTS notifications_items_count ON notifications.model_notifications_sends;");

        DB::statement("CREATE TRIGGER notifications_items_count
        AFTER INSERT OR DELETE OR UPDATE 
        ON notifications.model_notifications_sends
        FOR EACH ROW
        EXECUTE FUNCTION public.update_unreaded_notify_count();");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP TRIGGER IF EXISTS notifications_items_count ON notifications.model_notifications_sends;");
        DB::statement("DROP FUNCTION IF EXISTS public.update_unreaded_notify_count();");
    }
}
