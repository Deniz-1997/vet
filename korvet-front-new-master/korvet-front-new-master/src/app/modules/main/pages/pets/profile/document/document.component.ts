import {Component, Input, OnDestroy, OnInit} from '@angular/core';
import {BehaviorSubject, Observable, Subscription} from 'rxjs';
import {FilesService} from '../../../../../../services/files.service';
import {AppointmentModel} from '../../../../../../models/appointment/appointment.models';
import {CrudType} from 'src/app/common/crud-types';
import {ApiResponse} from 'src/app/api/api-connector/api-connector.models';

@Component({selector: 'app-pets-profile-document', templateUrl: './document.component.html'})

export class DocumentComponent implements OnInit, OnDestroy {
  @Input() petId: Observable<number>;
  @Input() appointments: Observable<AppointmentModel[]>;
  @Input() isLoad: Observable<boolean>;
  @Input() limitRow = 0;
  isFirstLoaded = false;
  loading$ = new BehaviorSubject(true);
  type = CrudType.File;
  filter = {};
  sort: { [columnName: string]: 'ASC' | 'DESC' } = {'date': 'ASC'};
  limit = 500;
  offset = 0;
  items = new BehaviorSubject([]);
  query = '';
  private subscriptions: Subscription[] = [];

  constructor(
    private apiFilesService: FilesService,
  ) {
  }

  @Input()
  set loadOne(isLoad: boolean) {
    if (isLoad === true && this.isFirstLoaded === false) {
      this.isFirstLoaded = true;
      this.getLoad();
    }
  }

  ngOnInit() {
    this.filter = {pet: {id: this.petId}};
  }

  ngOnDestroy() {
    this.subscriptions
      .forEach(s => s.unsubscribe());
  }

  getLoad() {
    this.loading$.next(true);
    const s = this.apiFilesService.Get(null, {
      order: this.sort,
      offset: this.offset,
      limit: this.limit,
      filter: this.filter
    })
      .subscribe((res: ApiResponse) => {
          if (res && res.status === true) {
            this.items.next(res.response.items);
            this.loading$.next(false);
          }
        },
        () => this.loading$.next(false));
    this.subscriptions.push(s);
    return s;
  }

  updateList() {
    return this.getLoad();
  }

  getDate(date: string) {
    return date.substr(0, 10);
  }

  getTime(date: string) {
    return date.substr(11, 5);
  }

  showAll($event) {
    if ($event) {
      $event.preventDefault();
    }
    this.limitRow = 0;
  }

  removeFile(id: number, event) {
    event.preventDefault();
    return this.apiFilesService.removeFile(id).subscribe((res: ApiResponse) => {
      if (res && res.status === true) {
        return this.getLoad();
      }
    });
  }
}
