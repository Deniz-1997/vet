import {Component, Inject, Optional} from '@angular/core';
import {CrudType} from 'src/app/common/crud-types';
import {Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {Observable} from 'rxjs';
import {ReferencePetTypeModel} from '../../../../../../../models/reference/reference.pet.type.models';
import {ReferenceItemModels} from '../../../../../../../models/reference/reference.item.models';
import {NotifyService} from '../../../../../../../services/notify.service';
import {PetsService} from '../../../../../../../services/pets.service';
import {BreadcrumbsService} from '../../../../../../../services/breadcrumbs.service';
import {ReferenceBreedModel} from '../../../../../../../models/reference/reference.breed.models';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';

@Component({templateUrl: './edit.component.html'})

export class EditComponent extends ReferenceItemModels {
  public petTypesItems: Observable<ReferencePetTypeModel[]>;
  crudType = CrudType;
  protected listNavigate = ['admin', 'references', 'breed'];
  protected titleName = 'Порода животных';

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    private petsService: PetsService,
    protected brdSrv: BreadcrumbsService,
    @Optional() public dialogRef: MatDialogRef<EditComponent>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: {openDialog: boolean , id: string}
  ) {
    super(CrudType.ReferenceBreed, ReferenceBreedModel, data.id, data.openDialog);
    this.item.type = {id: null};
    this.petTypesItems = this.petsService.getPetTypes();
  }

  protected setModel() {

    this.formGroup = new FormGroup({
      name: new FormControl(this.item.name, [Validators.required]),
      type: new FormControl(this.item.type, [Validators.required]),
    });
  }


}
