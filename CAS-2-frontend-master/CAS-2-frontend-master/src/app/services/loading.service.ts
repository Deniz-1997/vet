import {Injectable} from '@angular/core';
import {BehaviorSubject} from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class LoadingService {
  $loading = new BehaviorSubject(false);

  next(value: boolean): void {
    this.$loading.next(value);
  }
}
