import { Injectable } from '@angular/core';
import { Subject } from 'rxjs';

@Injectable({
  providedIn: 'root',
})

export class AuditionService {
  public dataOwner$ = new Subject<any>();
  public dataPet$ = new Subject<any>();
  public id$ = new Subject<any>();
  public openDialog$ = new Subject<any>();
  public reference$ = new Subject<any>();

  public dataOwner(data: any) {
    this.dataOwner$.next(data);
  }
  public dataPet(data: any) {
    this.dataPet$.next(data);
  }

  public id(data: any) {
    this.id$.next(data);
  }

  public openDialog(data: any) {
    this.openDialog$.next(data);
  }
  public reference(data: any) {
    this.reference$.next(data);
  }
}
