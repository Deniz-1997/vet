import {Component, EventEmitter, Input, OnDestroy, OnInit, Output} from '@angular/core';
import {FormGroup} from '@angular/forms';
import {select, Store} from '@ngrx/store';
import {debounceTime, distinctUntilChanged, map, takeUntil} from 'rxjs/operators';
import {OwnerModel} from '../../../../models/owner/owner.models';
import {Observable, Subject} from 'rxjs';
import {CrudType} from 'src/app/common/crud-types';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadMatchesAction, CompleteMatchesAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelMatches, getCrudModelMatchesLoading} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({
  selector: 'app-owner-search',
  templateUrl: './owner-search.component.html',
  styleUrls: ['./owner-search.component.css']
})
export class OwnerSearchComponent implements OnInit, OnDestroy {
  @Input() model: FormGroup;
  @Output() select: EventEmitter<any> = new EventEmitter();
  fields = ['fullName.name', 'fullName.lastName', 'fullName.middleName', 'email', 'phone'];
  minLength = {'fullName.name': 2, 'fullName.lastName': 2, 'fullName.middleName': 2, 'email': 5, 'phone': 10};
  timer = null;
  matches$: Observable<OwnerModel[]>;
  matchesLoading$: Observable<boolean>;
  type = CrudType.Owner;
  currentParams: any;
  private destroy$ = new Subject<any>();

  constructor(
    private store: Store<CrudState>,
  ) {
    this.matches$ = store.pipe(select(getCrudModelMatches, {type: this.type}), map(data => <OwnerModel[]>data));
    this.matchesLoading$ = store.pipe(select(getCrudModelMatchesLoading, {type: this.type}));
  }

  static objectSetValue(layer: object, path: string, value: any): object {
    const arPath = path.split('.');
    for (let i = 0; i < arPath.length; i++) {
      if (value != null && i + 1 === arPath.length) {
        layer[arPath[i]] = value;
      } else {
        if (!layer.hasOwnProperty(arPath[i])) {
          layer[arPath[i]] = {};
        }
      }
      layer = layer[arPath[i]];
    }
    return layer;
  }

  ngOnInit() {
    for (const i in this.fields) {
      if (this.fields.hasOwnProperty(i)) {
        this.model.get(this.fields[i])
          .valueChanges
          .pipe(
            debounceTime(1000),
            distinctUntilChanged()
          )
          .pipe(
            takeUntil(this.destroy$)
          )
          .subscribe((value) => {
            if (value && value.length >= this.minLength[this.fields[i]]) {
              this.findOwner();
            } else {
              this.hasLastSearch(this.fields[i]);
            }
          });
      }
    }
  }

  findOwner() {
    const findModel = {};
    for (const i in this.fields) {
      if (this.fields.hasOwnProperty(i)) {
        const value = this.model.get(this.fields[i]).value;
        if (value && value.length >= this.minLength[this.fields[i]]) {
          if (this.fields[i] === 'phone') {
            const phone = value.substring(1);
            OwnerSearchComponent.objectSetValue(findModel, this.fields[i], phone);
          } else {
            OwnerSearchComponent.objectSetValue(findModel, this.fields[i], value);
          }
        }
      }
    }

    if (Object.keys(findModel).length) {
      this.store.dispatch(new LoadMatchesAction({
        type: this.type,
        params: {
          filter: findModel
        },
        onSuccess: () => this.currentParams = findModel
      }));
    } else {
      this.cleanSearchResult();
    }
  }

  hasLastSearch(field: string) {
    if (this.currentParams) {
      if (field.indexOf('.') > 0) {
        field = field.split('.')[1];
        if (this.currentParams.fullName && this.currentParams.fullName[field]) {
          this.findOwner();
        }
      } else if (!!this.currentParams[field]) {
        this.findOwner();
      }
    }
  }

  cleanSearchResult() {
    this.store.dispatch(new CompleteMatchesAction({
      type: this.type,
      params: null
    }));
  }

  addOwner(owner: OwnerModel) {
    this.select.emit(owner);
  }

  ngOnDestroy(): void {
    this.cleanSearchResult();
    this.destroy$.next();
  }

}
