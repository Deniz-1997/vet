import {Component, OnDestroy, OnInit} from '@angular/core';
import {select, Store} from '@ngrx/store';
import {ActivatedRoute} from '@angular/router';
import {Observable, Subject} from 'rxjs';
import {WildAnimalModel} from '../../../../../../models/wild/wild-animal.models';
import {takeUntil} from 'rxjs/operators';
import {BreadcrumbsService} from '../../../../../../services/breadcrumbs.service';
import {CrudType} from 'src/app/common/crud-types';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelStoreId, getCrudModelGetLoading} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({
  templateUrl: './profile.component.html'
})
export class ProfileComponent implements OnInit, OnDestroy {
  type = CrudType.WildAnimal;
  id: string;
  wildAnimal$: Observable<WildAnimalModel>;
  loading$: Observable<boolean>;
  private destroy$ = new Subject<any>();

  constructor(
    private store: Store<CrudState>,
    private route: ActivatedRoute,
    private brdSrv: BreadcrumbsService,
  ) {
  }

  ngOnInit() {
    this.id = this.route.snapshot.paramMap.get('id');
    this.store.dispatch(new LoadGetAction({type: this.type, params: this.id}));
    this.wildAnimal$ = this.store.pipe(select(getCrudModelStoreId, {type: this.type, params: this.id}));
    this.loading$ = this.store.pipe(select(getCrudModelGetLoading, {type: this.type}));
    this.wildAnimal$
      .pipe(
        takeUntil(this.destroy$)
      )
      .subscribe(wildAnimal => {
        if (wildAnimal && wildAnimal.id) {
          this.brdSrv.deleteIndex(3);
          if (typeof this.id !== 'undefined') {
            this.brdSrv.replaceLabelByIndex(wildAnimal.type.name + ' ' + wildAnimal.breed.name, 2);
          }
        }
      });
  }

  ngOnDestroy(): void {
    this.destroy$.next();
  }
}
