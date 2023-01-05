import {Injectable} from '@angular/core';
import {BehaviorSubject} from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class NoAccessService {

  noAccess = new BehaviorSubject(false);

  constructor() {
  }
}
