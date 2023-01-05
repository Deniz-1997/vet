import { AfterViewInit, Component, ElementRef, OnInit } from '@angular/core';
import { MainNotificationModel } from 'src/app/models/notifications/notifications.models';
import { ModalAlertViewComponent } from 'src/app/modules/shared/components/modal-alert-view/modal.component';
import { MatDialog } from '@angular/material/dialog';
import { NotificationService } from 'src/app/services/notification.service';

declare var $: any;

@Component({
  selector: 'app-main-notification',
  templateUrl: './main-notification.component.html',
  styleUrls: ['./main-notification.component.css']
})
export class MainNotificationComponent implements OnInit, AfterViewInit {

  animateBell: boolean = false;
  messagesCount = 0;
  notifications: MainNotificationModel[] = [];
  increaseElements: number;
  loading = false;
  unreadedMessagesCount = 0;
  static UPDATE_TYPE_ID: number = 1;
  readonly UPDATE_DEFAULT_HEADER: string = 'Системное обновление';

  constructor(private notivicationService: NotificationService,
    private elementRef: ElementRef,
    private dialog: MatDialog) {
    this.increaseElements = 10;
    this.notivicationService.notificationChanged.subscribe((e) => {
      this.notifications = [];
      this.open(false);
    })
  }

  ngOnInit() {
    this.open();
  }

  close(e?: Event): void {
    if (e) {
      e.preventDefault();
    }
    $('.menu-column__ms-ico a').removeClass('active');
    $('.menu-column__ms-popup').fadeOut();
  }

  ngAfterViewInit() {
    this.elementRef.nativeElement.querySelector('#notifications')
      .addEventListener('scroll', this.onScroll.bind(this));
  }

  open(autoOpen = true) {
    if (this.notifications.length === 0) {
      this.loading = true;
      this.notivicationService.getNotificationsList(this.notifications.length, this.increaseElements, (res, count) => {
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
          setTimeout(_ => { this.animateBell = false }, 2800);
        }
      });
    }
  }

  onScroll(event) {
    if (this.loading || this.notifications.length >= this.messagesCount) {
      return;
    }
    if (event.target.scrollHeight < (event.target.scrollTop + event.target.offsetHeight + 20)) {
      this.loading = true;
      this.notivicationService.getNotificationsList(this.notifications.length, this.increaseElements, (res, count) => {
        this.loading = false;
        for (let i = 0; i < res.length; i++) {
          this.notifications.push(res[i]);
        }
        this.setUnreadedMessagesCount();
      });
    }
  }

  setUnreadedMessagesCount() {
    if (this.notifications) {
      this.unreadedMessagesCount = 0;
      this.notifications.forEach((item) => {
        if (!this.isNotificationViewed(item)) {
          this.unreadedMessagesCount++;
        }
      });
    }
  }

  showUpdateModal(notification: MainNotificationModel) {
    const dialogRef = this.dialog.open(ModalAlertViewComponent, {
      data: {
        header: notification.data.header ? notification.data.header : this.UPDATE_DEFAULT_HEADER,
        html: notification.data.template,
      },
    });
    dialogRef.afterClosed().subscribe(_ => {
      this.notivicationService.setNotificationViewed(notification, (notify) => {
        if (notify) {
          this.notifications[this.notifications.indexOf(notification)] = notify;
          this.setUnreadedMessagesCount();
        }
      });
    });
  }

  autoOpenUpdateModal() {
    if (this.notifications) {
      const lastUpdate = this.notifications.find(n => n.type.id === MainNotificationComponent.UPDATE_TYPE_ID && !this.isNotificationViewed(n));
      if (lastUpdate) {
        this.showUpdateModal(lastUpdate);
      }
    }
  }

  isNotificationViewed(notification: MainNotificationModel) {
    if (!this.notivicationService.userId) {
      return true;
    }
    for (const i in notification.toSend) {
      if (notification.toSend[i].value === this.notivicationService.userId) {
        return notification.toSend[i].viewed;
      }
    }
    return true;
  }

  static getChannelKorvet() {
    return { id: this.UPDATE_TYPE_ID, name: 'Korvet' };
  }

  getChannelKorvet() {
    return MainNotificationComponent.UPDATE_TYPE_ID;
  }
}
