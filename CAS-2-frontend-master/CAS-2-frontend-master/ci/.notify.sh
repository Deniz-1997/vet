#!/bin/bash

TIME="10"
URL="https://api.telegram.org/bot$TELEGRAM_BOT_TOKEN/sendMessage"
TEXT="${TEXT}Deploy status: $1%0AProject:+$CI_PROJECT_NAME%0AURL:+$CI_PROJECT_URL/pipelines/$CI_PIPELINE_ID/%0ABranch:+$CI_COMMIT_REF_SLUG%0AAuthor:+$GITLAB_USER_NAME"

echo $TEXT
echo $URL
echo $TELEGRAM_CHAT_ID

if [ -n "TELEGRAM_MESSAGE" ]
then
    TEXT="${TEXT}%0A%0A${TELEGRAM_MESSAGE}"
fi

curl -s --max-time $TIME -d "chat_id=$TELEGRAM_CHAT_ID&disable_web_page_preview=1&text=$TEXT" $URL > /dev/null
