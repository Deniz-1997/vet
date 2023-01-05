import {Component, Inject, Optional} from '@angular/core';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {select, Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {ReferencePetTypeModel} from '../../../../../../../models/reference/reference.pet.type.models';
import {ReferenceItemModels} from '../../../../../../../models/reference/reference.item.models';
import {NotifyService} from '../../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../../services/breadcrumbs.service';
import {CrudType} from '../../../../../../../common/crud-types';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {Observable} from 'rxjs';
import {ReferenceIconModel} from '../../../../../../../models/reference/icon.model';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({templateUrl: './edit.component.html'})

export class EditComponent extends ReferenceItemModels {
  protected listNavigate = ['admin', 'references', 'pets-type'];
  protected titleName = 'Вид животных';
  protected crudType = CrudType;
  icons$: Observable<ReferenceIconModel[]>;
  field = {0: 'id', 1: 'name', 3: 'code', 4: 'subclass'};
  iconCode: string;

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService,
    @Optional() public dialogRef: MatDialogRef<EditComponent>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: {openDialog: boolean , id: string}
  ) {
    super(CrudType.ReferencePetType, ReferencePetTypeModel, data.id, data.openDialog);
    this.icons$ = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceIcon}));
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceIcon,
      params: {
        fields:{0: 'id', 1: 'name', 3: 'code', 4: 'subclass'},
        filter: {subclass: 'ANIMAL'}
      }
      }));
  }

  protected setModel() {
    this.formGroup = new FormGroup({
      name: new FormControl(this.item.name, [Validators.required]),
      icon: new FormControl(this.item.icon, [Validators.required]),
    });
  }

}
