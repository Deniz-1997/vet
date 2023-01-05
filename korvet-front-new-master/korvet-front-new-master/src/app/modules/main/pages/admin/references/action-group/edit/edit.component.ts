import {Component, Inject, Optional} from '@angular/core';
import {ReferenceItemModels} from '../../../../../../../models/reference/reference.item.models';
import {select, Store} from '@ngrx/store';
import {ActivatedRoute, Params, Router} from '@angular/router';
import {NotifyService} from '../../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../../services/breadcrumbs.service';
import {CrudType} from 'src/app/common/crud-types';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {ActionGroupModel} from '../../../../../../../models/action/action.group.models';
import {Observable} from 'rxjs';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({templateUrl: './edit.component.html'})

export class EditComponent extends ReferenceItemModels {
  actionGroupItems$: Observable<ActionGroupModel[]>;
  protected listNavigate = ['admin', 'references', 'action-group'];
  protected titleName = 'Группы действий';

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService,
    @Optional() public dialogRef: MatDialogRef<EditComponent>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: {openDialog: boolean , id: string}
  ) {
    super(CrudType.ActionGroup, ActionGroupModel, data.id, data.openDialog);
  }

  submit(): void {
    const model = {...this.formGroup.value};

    if (model.parent && !model.parent.id) {
      model.parent = null;
    }

    super.submit(null, model);
  }

  protected setModel() {
    this.formGroup = new FormGroup({
      name: new FormControl(this.item.name, [Validators.required]),
      parent: new FormGroup({
        id: new FormControl(this.item.parent ? this.item.parent.id : null)
      }),
      code: new FormControl(this.item.code),
    });


    this.actionGroupItems$ = this.store.pipe(select(getCrudModelData, {type: CrudType.ActionGroup}));
    const params: Params = {};
    params.fields = {0: 'id', 1: 'name'};
    params.order = {surname: 'ASC'};

    if (this.id !== 'create') {
      params.filter = {'!id': this.id};
    }

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ActionGroup,
      params: params
    }));

  }
}
