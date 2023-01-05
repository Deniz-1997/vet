import {Component, OnInit} from '@angular/core';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {ReferenceItemModels} from '../../../../../../../models/reference/reference.item.models';
import {NotifyService} from '../../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../../services/breadcrumbs.service';
import {CrudType} from '../../../../../../../common/crud-types';
import { LaboratoryModel } from 'src/app/models/laboratory/laboratory.model';
import { ProbeModel } from 'src/app/models/laboratory/probe.model';
import { EnumModel } from 'src/app/models/enum .models';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';

@Component({templateUrl: './edit.component.html'})

export class EditComponent extends ReferenceItemModels implements OnInit {
  protected listNavigate = ['admin', 'references', 'laboratory', 'probe'];
  protected titleName = 'Пробу';
  crudType = CrudType;
  defaultVatRateCode = 'VAT_20';
  VatRateEnum: EnumModel[];

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService
  ) {
    super(CrudType.Probe, ProbeModel);

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.Enum,
      params: {
        filter: {
          id: [
            'VatRateEnum',
          ]
        }
      },
      onSuccess: (res) => {
        res.response.map(
          item => {
            this[item.id] = item.items;
          }
        );
      }
    }));
  }

  protected setModel() {
    this.formGroup = new FormGroup({
      active: new FormControl(this.item.active !== undefined ? this.item.active : true, []),
      name: new FormControl(this.item.name, [Validators.required]),
      materialType: new FormControl(this.item && this.item.materialType ? { id: this.item.materialType.id, name: this.item.materialType.name} : null, [Validators.required]),
      packing: new FormControl(this.item && this.item.packing ? { id: this.item.packing.id, name: this.item.packing.name} : null, []),
      vatRate: new FormControl(this.item.vatRate && this.item.vatRate.code ? this.item.vatRate.code : this.defaultVatRateCode, [Validators.required]),
      price: new FormControl(this.item && this.item.price ? this.item.price : null, []),
    });
  }

  ngOnInit() {
    
    super.ngOnInit();
  }

}
