export enum NotificationTypes {
  ERROR = 'error',
  SUCCESS = 'success',
}

export interface Notification {
  text: string;
  title: string;
  list?: string[];
  type?: NotificationTypes;
}
