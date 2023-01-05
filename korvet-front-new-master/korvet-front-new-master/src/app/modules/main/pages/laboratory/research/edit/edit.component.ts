import { DatePipe } from '@angular/common';
import { ValueConverter } from '@angular/compiler/src/render3/view/template';
import { Component, OnInit } from '@angular/core';
import { FormArray, FormControl, FormGroup, Validators } from '@angular/forms';
import { MatDialog } from '@angular/material/dialog';
import { ActivatedRoute, Router } from '@angular/router';
import { select, Store } from '@ngrx/store';
import { Observable } from 'rxjs';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetAction, LoadPatchAction, LoadCreateAction, LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelGetLoading, getCrudModelCreateLoading, getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';
import {UserModel} from 'src/app/api/auth/auth.models';
import { CrudType } from 'src/app/common/crud-types';
import { FileMonitoredObjectModel } from 'src/app/models/file/file.monitored.object.models';
import { ProbeItemModel } from 'src/app/models/laboratory/probe-item.model';
import { ResearchDocumentModel } from 'src/app/models/laboratory/research-document.model';
import { PetModel } from 'src/app/models/pet/pet.models';
import { ReferenceProductModel } from 'src/app/models/reference/reference.product.models';
import { ModalFileAddFormComponent } from 'src/app/modules/shared/components/modal-file-add-form/modal-file-add-form.component';
import { AuthService } from 'src/app/services/auth.service';
import { NotifyService } from 'src/app/services/notify.service';
import { PetsService } from 'src/app/services/pets.service';

@Component({ templateUrl: './edit.component.html', styleUrls: ['./edit.component.css'] })

export class ResearchDocumentComponent implements OnInit {
  id: string;
  public formGroup: FormGroup;
  public model: ResearchDocumentModel = new ResearchDocumentModel();
  showError = false;
  crudType = CrudType;
  loading$: Observable<boolean>;
  type = CrudType.ResearchDocument;
  title: string;
  getLoading: Observable<boolean>;
  showExtraButton: boolean = true;
  editor: UserModel;
  fileTypes$: Observable<FileMonitoredObjectModel[]>;
  petFiles = [];
  fileLoading: boolean;
  productStockFilter = {"active":1,"paymentObject":"COMMODITY","productStock":{"stock":{"id":2},"!=quantity":0},"existQuantity":1};
  corruptedTime = this.datePipe.transform(new Date(), 'HH:mm');

  constructor(protected store: Store<CrudState>, private authService: AuthService,
    private router: Router, private route: ActivatedRoute, protected petsService: PetsService,
    protected notify: NotifyService, private datePipe: DatePipe, private dialog: MatDialog,
  ) {
    this.id = route.snapshot.paramMap.get('id');
    this.loading$ = this.store.pipe(select(getCrudModelGetLoading, { type: this.type }));
    this.getLoading = this.store.pipe(select(getCrudModelCreateLoading, { type: this.type }));
  }

  ngOnInit() {
    this.title = 'Исследование';
    this.authService.user$.subscribe((res) => {
      this.editor = res?.user;
    });
    this.fileTypes$ = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceFileType}));
    if (this.id) {
      this.store.dispatch(new LoadGetAction({
        type: this.type, params: this.id,
        onSuccess: (res) => {
          if (res.response && res.status == true) {
            this.model = res.response;
            this.corruptedTime = this.datePipe.transform(this.model.probeItem.corrupted ? new Date(this.model.probeItem.corruptedDate.replace(/(\d+).(\d+).(\d+)/, '$3/$2/$1')):new Date(), 'HH:mm');
            this.setModel();
            this.loadFiles();
          }
        }
      }));
    }
  }

  goListUrl() {
    return '/laboratory/research-document';
  }

  protected setModel() {
    this.formGroup = new FormGroup({
      id: new FormControl(this.model.number, [Validators.required]),
      research: new FormControl(this.model.research, [Validators.required]),
      researchReason: new FormControl(this.model.researchReason, []),
      researchPriority: new FormControl(this.model.researchPriority, []),
      probeItem: this.getProbeControl(this.model.probeItem),
      date: new FormControl(this.model.date ? this.model.date : new Date(), [Validators.required]),
      researchEquipment: new FormControl(this.model.researchEquipment ? this.model.researchEquipment : null, []),
      result: new FormControl(this.model.result ? this.model.result : '', []),
      status: new FormControl(this.model?.status && this.model?.status['code'] != null ? this.model?.status : { code: "CREATE" }, []),
      documentProducts: new FormArray([]),
    });
    if (this.id) {
      const docs = this.formGroup.get('documentProducts') as FormArray;
      for (const i in this.model.documentProducts) {
        docs.push(this.getDefaultProduct(this.model.documentProducts[i]));
      }
    }
    this.productStockFilter = {"active":1,"paymentObject":"COMMODITY","productStock":{"stock":{"id":this.model.laboratory?.stock?.id},"!=quantity":0},"existQuantity":1};
  }

  addDocumentProduct() {
    if (!this.model.documentProducts) {
      this.model.documentProducts = [];
    }
    const documents = this.formGroup.get('documentProducts') as FormArray;
    const item = this.getDefaultProduct();
    documents.push(item);
  }

  getDefaultProduct(doc = null): FormGroup {
    const group = new FormGroup({
      product: new FormControl(doc?.product ? doc?.product  : null, [Validators.required]),
      quantity: new FormControl(doc?.quantity ? doc?.quantity : 1, []),
    });
    if (doc) {
      group.addControl('id', new FormControl(doc?.id, []));
    }
    return group;
  }

  removeDocumentProduct(index) {
    const documents = this.formGroup.get('documentProducts') as FormArray;
    documents.removeAt(index);
  }

  submit(finish = false) {
    this.showError = true;
    if (this.formGroup.valid) {
      const model = { ...this.formGroup.value };
      model.editor = this.editor;
      if (this.probeCorrupted()) {
        model.status = { code: 'CORRUPTED' };
        model.probeItem.corruptedDate = this.datePipe.transform(new Date(this.formGroup.get('probeItem').get('corruptedDate').value.replace(/(\d+).(\d+).(\d+)/, '$3/$2/$1')), 'dd.MM.yyyy') + ' ' + this.formGroup.get('probeItem').get('corruptedTime').value + ':00';
      } else {
        switch (this.model.status.code) {
          case 'SENT' : model.status = { code: 'RECEIVED' }; break;
          case 'RECEIVED' : model.status = { code: 'PROCESSING' }; break;
          default:  model.status = { code: 'PROCESSING' };
        }
      }

      this.store.dispatch(new LoadPatchAction({
        type: this.type,
        params: model,
        onSuccess: (res) => { 
          this.model = res.response;
          if(finish) {
            this.changeResearchState('REGISTERED');
          } else {
            this.router.navigateByUrl('/', {skipLocationChange: true}).then(() => {
              this.router.navigate(['laboratory', 'research-document', res.response.number]).then();
            });
          }
        }
      }));
    } else {
      this.notify.handleMessage('Заполните обязательные поля', 'warning');
      this.showError = true;
    }
  }

  changeResearchState(code: string) {
    this.store.dispatch(new LoadCreateAction({
      type: CrudType.ResearchState,
      params: {
        code: code,
        id: this.model.number,
      },
      onSuccess: (res) => {
        if (res.status === true && res.response && res.response.state) {
          this.router.navigateByUrl('/', {skipLocationChange: true}).then(() => {
            this.router.navigate(['laboratory', 'research-document', this.model.number]).then();
          });
        }
      }
    }));
  }

  getProbeControl(probe: ProbeItemModel): FormGroup {
    const group = new FormGroup({
      probe: new FormControl(probe?.probe, [Validators.required]),
      code: new FormControl(probe?.code, []),
      price: new FormControl(probe?.price ? probe?.price : 0, []),
      quantity: new FormControl(probe?.quantity ? probe?.quantity : 0, []),
      packing: new FormControl(probe?.packing, [Validators.required]),
      corrupted: new FormControl(probe?.corrupted, []),
      corruptedDate: new FormControl(probe?.corruptedDate ? probe?.corruptedDate : this.datePipe.transform(new Date(), 'dd.MM.yyyy'), []),
      corruptedTime: new FormControl(this.corruptedTime, []),
      corruptReason: new FormControl(probe?.corruptReason, []),
    }, [this.corruptValidator]);
    if (probe) {
      group.addControl('id', new FormControl(probe?.id, []));
    }
    return group;
  }

  probeCorrupted() {
    return this.formGroup.get('probeItem').get('corrupted').value;
  }

  getSaveButtonName() {
    if (this.probeCorrupted()) {
      return "Сохранить";
    } else {
      switch (this.formGroup.get('status').value?.code) {
        case 'SENT' : return "Принять пробу";
        case 'RECEIVED': return "Начать исследование";
        default: return "Сохранить";
      }
    }
  }

  corruptValidator(group: FormGroup): { [s: string]: boolean } {
    if (group.controls['corrupted'] && group.controls['corrupted'].value
      && group.controls['corruptedDate'] && !group.controls['corruptedDate'].value) {
      group.controls['corruptedDate'].setErrors({required: true});
    } else {
      group.controls['corruptedDate'].setErrors(null);
    }
    if (group.controls['corrupted'] && group.controls['corrupted'].value
      && group.controls['corruptReason'] && !group.controls['corruptReason'].value) {
      group.controls['corruptReason'].setErrors({required: true}) 
    } else {
      group.controls['corruptReason'].setErrors(null);
    }
    return null;
  }

  getProductStockCount(product: ReferenceProductModel) {
    if (product && product.productStock) {
      let productStock = product.productStock.find(n => n.stock.id == this.model.laboratory.stock.id);
      return productStock ? productStock.quantity : 0;
    }
  }

  addFile(): void {
    let pet = new PetModel();
    pet.id = this.model.probeItem.probeSampling.pet.id;
    const dialog = this.dialog.open(ModalFileAddFormComponent, {
      data: {
        subject: pet,
        fileTypes$: this.fileTypes$,
        appointments: this.model.probeItem && this.model.probeItem.probeSampling && this.model.probeItem.probeSampling.appointment ? this.model.probeItem.probeSampling.appointment : null,
        documentId: this.model.id,
      }
    });

    dialog.afterClosed().subscribe(answer => {
      if (answer) {
        this.loadFiles();
      }
    });
  }

  loadFiles() {
    this.fileLoading = true;
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.File, 
      params: {'filter': {
        'documentId': this.model.id}, order: {date: 'DESC'}},
      onSuccess: (res) => {
        if (res.response && res.status == true) {
          this.petFiles = res.response.items;
        }
        this.fileLoading = false;
      },
      onError: _=> {this.fileLoading = false;}
    }));
  }

  lockForm() {
    return this.model.status && (this.model.status.code === 'DONE' || this.model.status.code === 'CORRUPTED') ? true : false;
  }
}
