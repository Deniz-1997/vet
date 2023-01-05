import {Injectable} from '@angular/core';
import {BehaviorSubject, Observable} from 'rxjs';

export class LayouteModel {
  title: string;
  description: string;
  type: string;
  format: string;
  path: string;
  visible: boolean;
  disabled: boolean;
}

@Injectable({
  providedIn: 'root'
})
export class AppointmentsPermissionService {

  layout: LayouteModel[];

  constructor() {
  }

  getAppointments(): boolean {

    const localItem = localStorage.getItem('appointments');

    if (localItem) {
      const currentAppointments = JSON.parse(localItem);
      const today = new Date();
      const currentDate = new Date(currentAppointments.date);

      if (currentDate.toDateString() === today.toDateString()) {
        this.layout = currentAppointments['appointments'];
        return true;

      } else {
        return false;
      }

    } else {
      return false;
    }
  }

  setAppointments(layout) {

    this.layout = layout;

    localStorage.setItem('appointments', JSON.stringify({
        date: new Date(),
        appointments: layout
      }
    ));
  }

  removeAppointments() {

    localStorage.removeItem('appointments');

  }

  isVisible(field: string): Observable<boolean> {

    const returnState: BehaviorSubject<boolean> = new BehaviorSubject(false);

    if (this.layout) {
      this.layout.map(
        item => {

          if (item.path === field) {
            return returnState.next(item.visible);
          }

        }
      );
    }

    return returnState;
  }

}
