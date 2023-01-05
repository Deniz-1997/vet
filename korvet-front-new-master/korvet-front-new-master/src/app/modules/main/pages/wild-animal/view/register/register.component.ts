import {Component, OnDestroy, OnInit} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {select, Store} from '@ngrx/store';
import {Observable, Subject} from 'rxjs';
import {WildAnimalModel} from '../../../../../../models/wild/wild-animal.models';
import {takeUntil} from 'rxjs/operators';
import {BreadcrumbsService} from '../../../../../../services/breadcrumbs.service';
import {CrudType} from 'src/app/common/crud-types';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelStoreId, getCrudModelGetLoading} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({
  selector: 'app-wild-animal-register',
  templateUrl: './register.component.html',
})
export class RegisterComponent implements OnInit, OnDestroy {
  type = CrudType.WildAnimal;
  id: string;
  wildAnimal$: Observable<WildAnimalModel>;
  loading$: Observable<boolean>;
  private destroy$ = new Subject<any>();

  constructor(
    private store: Store<CrudState>,
    private route: ActivatedRoute,
    protected brdSrv: BreadcrumbsService
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
