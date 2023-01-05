import { Component, EventEmitter, Input, OnDestroy, OnInit, Output } from '@angular/core';
import { FormGroup } from '@angular/forms';
import { select, Store } from '@ngrx/store';
import { debounceTime, distinctUntilChanged, map, takeUntil } from 'rxjs/operators';
import { OwnerModel } from '../../../../models/owner/owner.models';
import { Observable, Subject } from 'rxjs';
import { CrudType } from 'src/app/common/crud-types';
import { PetModel } from 'src/app/models/pet/pet.models';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadMatchesAction, CompleteMatchesAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelMatches, getCrudModelMatchesLoading} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({
  selector: 'app-pet-search',
  templateUrl: './pet-search.component.html',
  styleUrls: ['./pet-search.component.css']
})
export class PetSearchComponent implements OnInit, OnDestroy {
  @Input() model: FormGroup;
  @Output() select: EventEmitter<any> = new EventEmitter();
  fields = ['breed', 'name'];
  minLength = { 'name': 3 };
  timer = null;
  matches$: Observable<PetModel[]>;
  matchesLoading$: Observable<boolean>;
  type = CrudType.Pet;
  currentParams: any;
  private destroy$ = new Subject<any>();

  constructor(
    private store: Store<CrudState>,
  ) {
    this.matches$ = store.pipe(select(getCrudModelMatches, { type: this.type }), map(data => <PetModel[]>data));
    this.matchesLoading$ = store.pipe(select(getCrudModelMatchesLoading, { type: this.type }));
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
      if (this.fields.hasOwnProperty(i) && this.fields[i] != 'breed') {
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
            if (value && (value.length >= this.minLength[this.fields[i]])) {
              this.findPet();
            } 
          });
      }
    }
  }

  findPet() {
    const findModel = {};
    for (const i in this.fields) {
      if (this.fields.hasOwnProperty(i)) {
        const value = this.model.get(this.fields[i]).value;
        if (value && (value.length >= this.minLength[this.fields[i]] || value.id)) {
          if (value.id) {
            PetSearchComponent.objectSetValue(findModel, this.fields[i], { "id": value.id });
          } else {
            PetSearchComponent.objectSetValue(findModel, this.fields[i], value);
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

  cleanSearchResult() {
    this.store.dispatch(new CompleteMatchesAction({
      type: this.type,
      params: null
    }));
  }

  addPet(pet: PetModel) {
    this.select.emit(pet);
  }

  ngOnDestroy(): void {
    this.cleanSearchResult();
    this.destroy$.next();
  }

  getOwnerInfo(pet: PetModel) {
    for (const i in pet.owners) {
      if (pet.owners[i].mainOwner) {
        return pet.owners[i].owner.name + ' ' + pet.owners[i].owner.phone;
      }
    }
  }

}
