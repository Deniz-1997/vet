import {Component, OnInit} from '@angular/core';
import {select, Store} from '@ngrx/store';
import {BehaviorSubject, Observable} from 'rxjs';
import {ViewService} from '../view.service';
import {PersonType} from '../../../../../../utils/person-type';
import {ActivatedRoute, Router} from '@angular/router';
import {MonitoredObjectModel} from '../../../../../../models/owner/monitored-object.models';
import {CrudType} from 'src/app/common/crud-types';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData, getCrudModelGetListLoading, getCrudModelLoaded} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({templateUrl: './objects.component.html'})

export class ObjectsComponent implements OnInit {

  monitoredObjects$: Observable<MonitoredObjectModel[]>;
  monitoredObjectsLoading$: Observable<boolean>;
  monitoredObjectsLoaded$: Observable<boolean>;
  monitoredObjects = new BehaviorSubject<MonitoredObjectModel[]>([]);

  constructor(
    private store: Store<CrudState>,
    private service: ViewService,
    private router: Router,
    private route: ActivatedRoute,
  ) {
    this.monitoredObjects$ = store.pipe(select(getCrudModelData, {type: CrudType.MonitoredObject}));
    this.monitoredObjectsLoading$ = store.pipe(select(getCrudModelGetListLoading, {type: CrudType.MonitoredObject}));
    this.monitoredObjectsLoaded$ = store.pipe(select(getCrudModelLoaded, {type: CrudType.MonitoredObject}));
  }

  get owner$() {
    return this.service.owner$;
  }

  ngOnInit() {
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.MonitoredObject,
      params: {filter: {owner: {id: this.service.id}}}
    }));
    this.monitoredObjects$.subscribe(this.monitoredObjects);
    this.owner$.subscribe(owner => {
      if (owner.type === PersonType.INDIVIDUAL_PERSON) {
        this.router.navigate(['profile'], {relativeTo: this.route.parent.parent});
      }
    });
  }

}
