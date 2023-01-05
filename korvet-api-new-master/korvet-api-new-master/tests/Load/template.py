import json

headers = {
    "Content-Type": "application/json"
}


def get_by_id(l):
    l.client.get("/template/1/")


def get_list(l):
    l.client.get("/template/?offset=0&limit=20")


def update(l):
    l.client.put(
        "/template/1/",
        headers=headers,
        data=json.dumps({
            "deleted": False
        })
    )


def delete(l):
    l.client.delete("/template/1/")
