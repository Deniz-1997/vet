import {Injectable} from '@angular/core';
import {NotifierService} from 'angular-notifier';

declare var $: any;

@Injectable({
  providedIn: 'root'
})
export class NotifyService {

  // constructor(private notifier: NotifierService) {
  // }
  //
  // handleErrors(errors: ErrorResponseInterface[]): void {
  //   if (errors && errors.length) {
  //     errors.forEach(error => this.handleMessage(error.message, 'danger', 10000));
  //   }
  // }
  //
  // closeAllMessage() {
  //   $('.alert').addClass('fadeOutUp');
  // }
  //
  // handleMessage(
  //   message: string,
  //   type: 'danger' | 'info' | 'success' | 'warning' = 'danger',
  //   timeout: number | false = false,
  //   icon: string = null
  // ): void {
  //   $.notify({message: message}, {type: type});
  // }
}
