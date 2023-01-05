import {Injectable} from '@angular/core';
import {BehaviorSubject} from 'rxjs';

@Injectable()
export class ReportDataService {
  private data = new BehaviorSubject<any>(undefined);
  currentData = this.data.asObservable();

  constructor() {

  }

  public setData(data?: any): void {
    this.data.next(data);
  }

}
