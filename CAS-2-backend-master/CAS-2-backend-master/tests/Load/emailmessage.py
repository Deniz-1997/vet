import json

headers = {
    "Content-Type": "application/json"
}


def get_by_id(l):
    l.client.get("/email/2/")


def get_list(l):
    l.client.get("/email/?offset=0&limit=20")


def create(l):
    l.client.post(
        "/email/",
        headers=headers,
        json={
            "emailFrom": "emailFrom@example.com",
            "emailTo": "emailTo@example.com",
            "subject": "string",
            "body": "string",
            "theme": {
                "id": 3
            },
            "replyTo": "replyTo@example.com",
            "cc": [
                "email@example.com",
                "anotheremail@example.com"
            ],
            "bcc": [
                "email@example.com",
                "anotheremail@example.com"
            ]
        }
    )


def update(l):
    l.client.put(
        "/email/2/",
        headers=headers,
        data=json.dumps({
            "subject": "Hello world.",
            "deleted": False
        })
    )


def delete(l):
    l.client.delete("/email/2/")
