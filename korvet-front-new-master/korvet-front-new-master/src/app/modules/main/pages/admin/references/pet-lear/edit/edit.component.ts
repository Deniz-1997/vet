import {Component, Inject, Input, Optional} from '@angular/core';
import {select, Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {NotifyService} from '../../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../../services/breadcrumbs.service';
import {CrudType} from '../../../../../../../common/crud-types';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {ReferenceItemModels} from '../../../../../../../models/reference/reference.item.models';
import {ReferencePetLearModel} from '../../../../../../../models/reference/reference.pet-lear.models';
import {Observable} from 'rxjs';
import {ReferenceBreedModel} from '../../../../../../../models/reference/reference.breed.models';
import {getReferenceBreedStore} from '../../../../../../../store/crud/crud.selectors';
import {ReferencePetTypeModel} from '../../../../../../../models/reference/reference.pet.type.models';
import {PetsService} from '../../../../../../../services/pets.service';
import {ReferencePetIdentifierTypeModel} from '../../../../../../../models/reference/reference.pet.identifier.type.models';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction, CompleteGetListAction} from 'src/app/api/api-connector/crud/crud.actions';


class Params {
  name?: string;
  '=chipNumber'?: string;
  breed?: {
    id?: number;
    type?: {
      id?: number;
    }
  };
}

@Component({templateUrl: './edit.component.html'})

export class EditComponent extends ReferenceItemModels {

  petTypesItems: Observable<ReferencePetTypeModel[]>;
  breedItems: Observable<ReferenceBreedModel[]>;
  crudType = CrudType;
  currentParams: Params;
  offset = 0;
  limit = 20;
  minChipNumberLength = 14;
  minIndNumberLength = 4;
  minNameLength = 2;
  @Input() identifiers: ReferencePetIdentifierTypeModel[];
  @Input() identifiersLoading: boolean;
  protected listNavigate = ['admin', 'references', 'pet-lear'];
  protected titleName = 'Масти животных';

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService,
    private petsService: PetsService,
    @Optional() public dialogRef: MatDialogRef<EditComponent>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: {openDialog: boolean , id: string}
  ) {
    super(CrudType.ReferencePetLear, ReferencePetLearModel, data.id, data.openDialog);
    this.item.breed = {id: null};
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceBreed, params: {'order': {'name': 'ASC'}, limit: 500}
    }));
    this.breedItems = this.store.pipe(select(getReferenceBreedStore));
    this.petTypesItems = this.petsService.getPetTypes10();
  }

  hasType() {
    return this.formGroup.controls.type.value && typeof this.formGroup.controls.type.value === 'object';
  }

  onChangeType() {
    const typeId = this.formGroup.value.type;
    if (typeId && typeId.id > 0) {
      this.breedItems = this.petsService.getBred10(typeId);
    } else {
      this.formGroup.controls.breed.setValue(null);
      this.breedItems = null;
    }
    if (this.isSearchingName() || this.isSearching()) {
      this.getMatches();
    } else {
      this.hasLastSearch('breed.type.id');
    }
    return this.breedItems;
  }

  onChangeBreed() {
    if (this.isSearchingName() || this.isSearching()) {
      this.getMatches();
    } else {
      this.hasLastSearch('breed.id');
    }
  }

  isSearching() {
    return (this.formGroup.value['chipNumber'] && this.formGroup.value['chipNumber'].length > this.minChipNumberLength)
      || this.isSearchingName();
  }

  isSearchingName() {
    const breed = this.formGroup.value['breed'];
    const type = this.formGroup.value['type'];
    return this.formGroup.value['name'] && this.formGroup.value['name'].length > this.minNameLength && type && breed;
  }

  getMatches(): void {
    const params: Params = {};
    const breed = this.formGroup.value['breed'];
    const type = this.formGroup.value['type'];

    if (this.formGroup.value['name'] && this.formGroup.value['name'].length > this.minNameLength) {
      params['~name'] = this.formGroup.value['name'].trim();
    }

    if (type && type.id) {
      params.breed = {
        type: {
          id: type.id
        }
      };
    }

    if (breed && breed.id) {
      params.breed.id = breed.id;
    }

    if (this.formGroup.value['chipNumber'] && this.formGroup.value['chipNumber'].length > this.minChipNumberLength) {
      params['=chipNumber'] = this.formGroup.value['chipNumber'].trim();
    }

    this.offset = 0;
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.Pet,
      params: <any>{
        order: {id: 'DESC'},
        filter: params,
        offset: this.offset,
        limit: this.limit
      }
    }));

    this.currentParams = params;

  }

  hasLastSearch(param: string) {
    if (this.currentParams && this.currentParams[param]) {
      if (this.isSearching()) {
        this.getMatches();
      } else {
        this.cleanSearchResalt();
      }
    }
  }

  cleanSearchResalt() {
    this.store.dispatch(new CompleteGetListAction({
      type: CrudType.Pet,
      params: null
    }));
  }

  protected setModel() {
    this.formGroup = new FormGroup({
      name: new FormControl(this.item.name, [Validators.required]),
      type: new FormControl(this.item.breed.type, [Validators.required]),
      breed: new FormControl(this.item.breed, [Validators.required]),
    });
  }

}
