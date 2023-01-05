from locust import HttpLocust, TaskSet

import template
import theme
import emailmessage


class TemplateTest(TaskSet):
    tasks = {
        template.get_by_id,
        template.get_list,
        template.update,
        template.delete
    }

    def on_start(self):
        self.client.headers["Cookie"] \
            = "Authorization=Access-Token: YZkhSmrVMfX4z3MIE93Eq1eHcNk6k2Vyp7GKBehWMsgQCy6n4Q;"


class TemplateUser(HttpLocust):
    task_set = TemplateTest
    min_wait = 5000
    max_wait = 9000


class ThemeTest(TaskSet):
    tasks = {
        theme.get_by_id,
        theme.get_list,
        theme.update,
        theme.delete
    }

    def on_start(self):
        self.client.headers["Cookie"] \
            = "Authorization=Access-Token: YZkhSmrVMfX4z3MIE93Eq1eHcNk6k2Vyp7GKBehWMsgQCy6n4Q;"


class ThemeUser(HttpLocust):
    task_set = ThemeTest
    min_wait = 5000
    max_wait = 9000


class EmailTest(TaskSet):
    tasks = {
        emailmessage.get_by_id,
        emailmessage.get_list,
        emailmessage.create,
        emailmessage.update,
        emailmessage.delete
    }

    def on_start(self):
        self.client.headers["Cookie"] \
            = "Authorization=Access-Token: YZkhSmrVMfX4z3MIE93Eq1eHcNk6k2Vyp7GKBehWMsgQCy6n4Q;"


class EmailUser(HttpLocust):
    task_set = EmailTest
    min_wait = 5000
    max_wait = 9000
