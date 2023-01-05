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
export class LeavingPermissionService {

  layout: LayouteModel[];

  constructor() {
  }

  getLeaving(): boolean {

    const localItem = localStorage.getItem('leaving');

    if (localItem) {
      const currentLeaving = JSON.parse(localItem);
      const today = new Date();
      const currentDate = new Date(currentLeaving.date);

      if (currentDate.toDateString() === today.toDateString()) {
        this.layout = currentLeaving['leaving'];
        return true;

      } else {
        return false;
      }

    } else {
      return false;
    }
  }

  setLeaving(layout) {

    this.layout = layout;

    localStorage.setItem('leaving', JSON.stringify({
        date: new Date(),
        leaving: layout
      }
    ));
  }

  removeLeaving() {

    localStorage.removeItem('leaving');

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
