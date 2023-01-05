import {Component, Input, OnInit} from '@angular/core';
import {PetModel} from '../../../../../../../models/pet/pet.models';
import {PetsService} from '../../../../../../../services/pets.service';
import {select, Store} from '@ngrx/store';
import {OwnerModel} from '../../../../../../../models/owner/owner.models';
import {Observable} from 'rxjs';
import {CrudType} from 'src/app/common/crud-types';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelDeleteLoading} from 'src/app/api/api-connector/crud/crud.selectors';

declare var $: any;

@Component({
  selector: 'app-pets-view-profile-detail',
  templateUrl: './detail.component.html',
})
export class DetailComponent implements OnInit {
  @Input() pet: PetModel;
  type = CrudType.Pet;

  ownerRemove: OwnerModel;

  loadingRemove$: Observable<boolean>;

  constructor(
    private petsService: PetsService,
    private store: Store<CrudState>,
  ) {
    this.loadingRemove$ = this.store.pipe(select(getCrudModelDeleteLoading, {type: CrudType.PetToOwner}));
  }

  ngOnInit() {
  }

  setOwnerAsMain(owner) {
    this.petsService.setOwnerAsMain(owner.id).subscribe((res) => {
      if (res && res.status === true) {
        this.store.dispatch(new LoadGetAction({type: this.type, params: this.pet.id.toString()}));
      }
    });
  }

  deleteOwner($event) {
    if ($event) {
      $event.preventDefault();
    }
    const owner = this.ownerRemove;
    this.petsService.removeOwner(owner.id).subscribe((res) => {
      if (res && res.status === true) {
        this.store.dispatch(new LoadGetAction({type: this.type, params: this.pet.id.toString()}));
        $('[data-fancybox-close]').trigger('click');
      }
    });
  }

  /**
   * @param owner OwnerModel
   */
  setDeleteOwner(owner: OwnerModel) {
    this.ownerRemove = owner;
  }

}
