import { Component, EventEmitter, Input, Output } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { select, Store } from '@ngrx/store';
import { ActivatedRoute, Router } from '@angular/router';
import { ReferenceItemModels } from '../../../../../../../models/reference/reference.item.models';
import { NotifyService } from '../../../../../../../services/notify.service';
import { BreadcrumbsService } from '../../../../../../../services/breadcrumbs.service';
import { CrudType } from '../../../../../../../common/crud-types';
import { LaboratoryModel } from 'src/app/models/laboratory/laboratory.model';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadPatchAction, LoadCreateAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelCreatePatchLoading, getCrudModelCreateLoading} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({
  selector: 'app-laboratory-form',
  templateUrl: './edit.component.html'
})

export class EditComponent extends ReferenceItemModels {
  @Input() isModal = false;
  @Output() afterSave = new EventEmitter<number>();
  protected listNavigate = ['admin', 'references', 'laboratory', 'laboratory'];
  protected titleName = 'Лабораторию';
  crudType = CrudType;

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService
  ) {
    super(CrudType.Laboratory, LaboratoryModel);
  }

  protected setModel() {
    this.formGroup = new FormGroup({
      name: new FormControl(this.item.name, [Validators.required]),
      coordinates: new FormControl(this.item.coordinates, []),
      address: new FormControl(this.item.address, [Validators.required]),
      email: new FormControl(this.item.email, []),
      phone: new FormControl(this.item.phone, []),
      stock: new FormControl(this.item.stock, []),
      website_url: new FormControl(this.item.website_url, []),
      external: new FormControl(this.item.external, []),
    }, this.laboratoryValidator);
  }

  laboratoryValidator(group: FormGroup): { [s: string]: boolean } {
    if (group.controls['external'] && !group.controls['external'].value
      && group.controls['stock'] && !group.controls['stock'].value) {
      group.controls['stock'].setErrors({ required: true });
    } else {
      group.controls['stock'].setErrors(null);
    }
    return null;
  }

  submit($event?, value?: any): void {
    if (this.formGroup.valid) {
      const model = value ? value : { ...this.formGroup.value };
      const action = this.item.id ? LoadPatchAction : LoadCreateAction;


      if (this.item.id) {
        model.id = this.item.id;
      }

      this.loading$ = this.store.pipe(select(this.item.id ? getCrudModelCreatePatchLoading : getCrudModelCreateLoading, { type: this.type }));

      this.store.dispatch(new action({
        type: this.type,
        params: <any>model, onSuccess: (res) => {
          if (res.status === true && res.response && res.response.id) {
            const n = JSON.parse(JSON.stringify(this.listNavigate));
            if (!this.isModal) {
              this.router.navigate(n).then();
            } else {
              this.afterSave.emit(res.response.id);
            }
          }
        }
      }));
    } else {
      this.notify.handleMessage('Заполните обязательные поля', 'warning');
      this.showError = true;
    }
  }

}
