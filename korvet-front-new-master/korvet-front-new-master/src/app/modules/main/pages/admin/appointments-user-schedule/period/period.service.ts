import {Injectable} from '@angular/core';
import {interval, Subscription} from 'rxjs';
import {switchMap} from 'rxjs/operators';
import {Urls} from '../../../../../../common/urls';
import {AsyncStatus} from '../../../cash/cash.service';
import {HttpClient} from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class PeriodService {
  period = 1000;
  asyncSubscription: Subscription;

  constructor(
    private http: HttpClient,
  ) {
  }

  getAsyncResult(correlationId, callback?) {
    this.asyncSubscription = interval(this.period).pipe(
      switchMap(() => this.http.get<AsyncStatus>(Urls.apiAsyncResult + correlationId + '/', {})),
    ).subscribe(data => {
      if (data.asyncStatus === 'ERROR' || data.asyncStatus === 'DONE') {
        if (callback) {
          callback(data);
        }
        this.asyncSubscription.unsubscribe();
      }
    }, (err) => {
      if (callback) {
        callback(err);
      }
    });
  }
}
