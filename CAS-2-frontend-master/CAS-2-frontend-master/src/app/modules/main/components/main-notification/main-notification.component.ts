import {AfterViewInit, Component, ElementRef, OnInit} from '@angular/core';
import {MatDialog} from '@angular/material/dialog';
import {MainNotificationModel} from 'src/app/models/notifications/notifications.models';
import {ModalAlertViewComponent} from 'src/app/modules/shared/components/modal-alert-view/modal.component';
import {NotificationService} from 'src/app/services/notification.service';

declare var $: any;

@Component({
  selector: 'app-main-notification',
  templateUrl: './main-notification.component.html',
  styleUrls: ['./main-notification.component.css']
})
export class MainNotificationComponent implements OnInit {

  constructor(private notificationService: NotificationService,
              private dialog: MatDialog,
              private elementRef: ElementRef) {
    this.increaseElements = 10;
    this.notificationService.notificationChanged.subscribe(() => {
      this.notifications = [];
      this.open(false);
    });
  }

  static UPDATE_TYPE_ID = 1;
  iconBellName = 'notifications';

  animateBell = false;
  messagesCount = 0;
  notifications: Array<MainNotificationModel> = [];
  increaseElements: number;
  loading = false;
  unreadedMessagesCount = 0;
  readonly UPDATE_DEFAULT_HEADER: string = 'Системное обновление';

  static getChannelKorvet(): {id: number, name: string} {
    return {id: this.UPDATE_TYPE_ID, name: 'Korvet'};
  }

  ngOnInit(): void {
    this.open();
  }

  open(autoOpen: boolean = true): void {
    if (this.notifications.length === 0) {
      this.loading = true;
      this.notificationService.getNotificationsList(this.notifications.length, this.increaseElements, (res, count) => {
        if (res) {
          this.notifications = res;
        }
        if (count > 0) {
          this.messagesCount = count;
        }
        this.setUnreadedMessagesCount();
        this.loading = false;
        if (autoOpen) {
          this.autoOpenUpdateModal();
        } else {
          this.animateBell = true;
          setTimeout(_ => {
            this.animateBell = false;
          }, 2800);
        }
      });
    }
  }

  showMore(event: any): void {
    event.stopPropagation();
    if (!this.loading) {
      this.loading = true;
      this.notificationService.getNotificationsList(this.notifications.length, this.increaseElements, (res) => {
        this.loading = false;
        for (const item of res) {
          this.notifications.push(item);
        }
        this.setUnreadedMessagesCount();
      });
    }
  }

  setUnreadedMessagesCount(): void {
    if (this.notifications) {
      this.unreadedMessagesCount = 0;
      this.notifications.forEach((item) => {
        if (!this.isNotificationViewed(item)) {
          this.unreadedMessagesCount++;
        }
      });
    }
  }

  showUpdateModal(notification: MainNotificationModel): void {
    const dialogRef = this.dialog.open(ModalAlertViewComponent, {
      width: '1000px',
      height: '100% - 50px',
      data: {
        header: notification.data.header ? notification.data.header : this.UPDATE_DEFAULT_HEADER,
        html: notification.data.template,
      },
    });
    dialogRef.afterClosed().subscribe(_ => {
      this.notificationService.setNotificationViewed(notification, (notify) => {
        if (notify) {
          this.notifications[this.notifications.indexOf(notification)] = notify;
          this.setUnreadedMessagesCount();
        }
      });
    });
  }

  autoOpenUpdateModal(): void {
    if (this.notifications) {
      const lastUpdate = this.notifications.find(n => n.type.id === MainNotificationComponent.UPDATE_TYPE_ID && !this.isNotificationViewed(n));
      if (lastUpdate) {
        this.showUpdateModal(lastUpdate);
      }
    }
  }

  isNotificationViewed(notification: MainNotificationModel): boolean {
    if (!this.notificationService.userId) {
      return true;
    }
    for (const i in notification.toSend) {
      if (notification.toSend[i].value === this.notificationService.userId) {
        return notification.toSend[i].viewed;
      }
    }
    return true;
  }

  getChannelKorvet(): number {
    return MainNotificationComponent.UPDATE_TYPE_ID;
  }
}
