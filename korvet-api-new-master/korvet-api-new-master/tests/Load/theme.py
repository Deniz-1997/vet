import json

headers = {
    "Content-Type": "application/json"
}


def get_by_id(l):
    l.client.get("/theme/1/")


def get_list(l):
    l.client.get("/theme/?offset=0&limit=20")


def update(l):
    l.client.put(
        "/theme/1/",
        headers=headers,
        data=json.dumps({
            "deleted": False
        })
    )


def delete(l):
    l.client.delete("/theme/1/")
