import {Component, OnDestroy, OnInit, ViewChild, ViewEncapsulation} from '@angular/core';
import {ReferenceItemModels} from '../../../../../../models/reference/reference.item.models';
import {select, Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {NotifyService} from '../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../services/breadcrumbs.service';
import {ReferenceUnitModel} from '../../../../../../models/reference/reference.unit.models';
import {FormArray, FormControl, FormGroup, Validators} from '@angular/forms';
import {EnumModel} from '../../../../../../models/enum .models';
import {Observable, Subject} from 'rxjs';
import {debounceTime, distinctUntilChanged, takeUntil} from 'rxjs/operators';
import {ReferenceStockModel} from '../../../../../../models/reference/stock';
import {ReferenceProductModel} from '../../../../../../models/reference/reference.product.models';
import {CrudType} from 'src/app/common/crud-types';
import {MatDialog} from '@angular/material/dialog';
import {ModalConfirmComponent} from '../../../../../shared/components/modal-confirm/modal-confirm.component';
import {ReferenceMeasurementUnitsModel} from '../../../../../../models/reference/reference.measurement.units.models';
import {ReferenceDiseaseModel} from '../../../../../../models/reference/reference.disease.models';
import {ApiConnectorService} from 'src/app/api/api-connector/api-connector.service';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction, LoadDeleteAction, LoadAppendListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({
  selector: 'app-product-edit',
  templateUrl: './edit.component.html',
  encapsulation: ViewEncapsulation.None,
})
export class EditComponent extends ReferenceItemModels implements OnInit, OnDestroy {
  defaultVatRateCode = 'VAT_20';
  PaymentObjectEnum: EnumModel[];
  VatRateEnum: EnumModel[];
  ProductCodeTypeEnum: EnumModel[];
  referenceUnits$: Observable<ReferenceUnitModel[]>;
  referenceUnits: ReferenceUnitModel[];
  referenceMeasurementUnits$: Observable<ReferenceMeasurementUnitsModel[]>;
  referenceMeasurementUnits: ReferenceMeasurementUnitsModel[];
  referenceStocks$: Observable<ReferenceStockModel[]>;
  referenceStocks: ReferenceStockModel[];
  protected listNavigate = ['store', 'product'];
  protected titleName = '';
  private destroy$ = new Subject<any>();
  categoryFields = {0: 'id', 1: 'name', 2: 'parent', 3: 'sort'};
  crudType = CrudType;
  manufacturerFields = {0: 'id', 1: 'name', 2: 'countries'};
  countriesFields = {0: 'id', 1: 'name', 2: 'sort'};
  isEnabled = false;
  diseaseItem: Observable<ReferenceDiseaseModel[]>;
  count: number;

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService,
    protected crud: ApiConnectorService,
    private dialog: MatDialog
  ) {
    super(CrudType.ReferenceProduct, ReferenceProductModel);

    this.referenceStocks$ = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceStock}));
    this.referenceStocks$.subscribe(item => {
      this.referenceStocks = item;
      this.setReferenceStocks();
    });

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceStock,
      params: {
        order: {name: 'ASC'},
        offset: 0,
        limit: 1000
      }
    }));
    this.referenceUnits$ = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceUnit}));
    this.referenceUnits$.subscribe(item => this.referenceUnits = item);
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceUnit,
      params: {
        order: {name: 'ASC'},
        offset: 0,
        limit: 1000
      }
    }));
    this.referenceMeasurementUnits$ = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceMeasurementUnits}));
    this.referenceMeasurementUnits$.subscribe(item => this.referenceMeasurementUnits = item);
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceMeasurementUnits,
      params: {
        order: {name: 'ASC'},
        offset: 0,
        limit: 1000
      }
    }));
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.Enum,
      params: {
        filter: {
          id: [
            'PaymentObjectEnum',
            'VatRateEnum',
            'ProductCodeTypeEnum'
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

  ngOnInit() {
    super.ngOnInit();
  }

  setReferenceStocks() {
    this.referenceStocks.map(
      stock => {
        if (this.item && this.item.productStock) {
          stock.visible = !this.item.productStock.some(i => i.stock.id === stock.id);
        }
      }
    );
  }

  getLabel() {
    const productCode = this.formGroup.get('productCode.type.code').value;

    if (productCode === 'MEDICINES') {
      return 'Контрольный (идентификационный) знак (КиЗ)';
    } else if (productCode === 'TOBACCO') {
      return 'Код идентификации';
    } else {
      return 'Серийный номер';
    }
  }

  isProductCode(): boolean {
    if (this.formGroup) {
      return !!this.formGroup.get('productCode.type.code').value && this.formGroup.get('productCode.type.code').value !== 'NULL';
    } else {
      return true;
    }
  }

  getProductStocksBase(id?: number, typeId?: number, quantity?: string) {
    typeId = typeId || null;
    quantity = quantity || '';
    const itemFormGroup = new FormGroup({
      id: new FormControl(id),
      stock: new FormGroup({
        id: new FormControl(typeId, [Validators.required]),
      }),
      quantity: new FormControl(quantity),
    });

    itemFormGroup.get('stock.id')
      .valueChanges
      .pipe(
        takeUntil(this.destroy$)
      )
      .pipe(
        debounceTime(500),
        distinctUntilChanged()
      )
      .subscribe(value => {
        this.setStockOption();
      });

    return itemFormGroup;
  }

  setStockOption(): void {
    this.referenceStocks.map(
      stock => {
        stock.visible = !this.formGroup.controls.productStock.value.some(i => i.stock.id === stock.id);
      }
    );
  }

  ngOnDestroy(): void {
    this.destroy$.next();
  }

  initProductStocks() {
    if (this.item && this.item.productStock && this.item.productStock.length > 0) {
      const control = new FormArray([]);
      const products = this.item.productStock.sort((product1, product2) => product1.stock.name > product2.stock.name ? 1 : -1);
      for (const i in products) {
        if (products[i]) {
          control.push(this.getProductStocksBase(
            products[i].id,
            products[i].stock.id,
            products[i].quantity));
        }
      }
      return control;
    }
    return new FormArray([]);
  }

  addProductStock() {
    const control = <FormArray>this.formGroup.controls['productStock'];
    control.push(this.getProductStocksBase());
  }

  setRemoveProductStocks(index, id) {
    const control = <FormArray>this.formGroup.controls['productStock'];
    if (control.controls[index].get('quantity').value) {
      this.onDelete(index, id);
    } else {
      control.removeAt(index);
    }
    this.setStockOption();
  }

  canAddStock(): number {
    return this.referenceStocks.length - this.formGroup.controls.productStock.value.length;
  }

  onDelete(index: number, id) {
    const control = <FormArray>this.formGroup.controls['productStock'];
    const dialogRef = this.dialog.open(ModalConfirmComponent, {
      data: {
        head: `Вы точно хотите удалить
        ${control.controls[index].get('quantity').value ? control.controls[index].get('quantity').value : 0} товаров на складе?`,
        headComment: 'Действие необратимо',
        actions: [
          {
            class: 'btn-st btn-st--left btn-st--gray',
            action: false,
            title: 'Отмена'
          },
          {
            class: 'btn-st btn-st--right btn-st--red',
            action: true,
            title: 'Удалить'
          },
        ],
      }
    });

    dialogRef.afterClosed().subscribe((result: boolean) => {
      if (result) {
        control.removeAt(index);
        this.store.dispatch(new LoadDeleteAction({
          type: CrudType.ProductStock,
          params: {id: id},
        }));
      }
    });
  }

  submit($event?, value?: any): void {
    value = value ? value : {...this.formGroup.value};
    if (value && !this.formGroup.controls['budgetDrug'].value) {
      value.budgetDrug = false;
    }
    if (value && value.budgetDrug === false) {
      if (value.price === 0) {
        return this.notify.handleMessage('Цена не может быть 0 , если это не бюджетный товар', 'warning');
      }
    }
    if (value && value.unit && value.unit.id === null) {
      value.unit = null;
    }
    if (value && value.stock && value.stock.id === null) {
      value.stock = null;
    }
    if (value && !value.disease) {
      delete value.disease;
    }
    if (value && value.productStock && value.productStock.length > 0) {
      for (let i = 0; i < value.productStock.length; i++) {
        if (!value.productStock[i].id) {
          delete value.productStock[i].id;
        }
      }
    }

    return super.submit($event, value);
  }

  protected setModel() {
    this.formGroup = new FormGroup({
      name: new FormControl(this.item.name, [Validators.required]),
      active: new FormControl(this.item.active !== undefined ? this.item.active : true, []),
      price: new FormControl(this.item.price !== undefined ? this.item.price : null, [Validators.required]),
      measure: new FormControl(this.item.measure),
      paymentObject: new FormGroup({
        code: new FormControl(this.item.paymentObject && this.item.paymentObject.code, [Validators.required]),
      }),
      productCode: new FormGroup({
        type: new FormGroup({
          code: new FormControl((this.item.productCode && this.item.productCode.type) ? this.item.productCode.type.code : null)
        }),
        gtin: new FormControl(this.item.productCode ? this.item.productCode.gtin : null),
        serial: new FormControl(
          this.item.productCode ? this.item.productCode.serial : null),
      }),
      vatRate: new FormGroup({
        code: new FormControl(this.item.vatRate && this.item.vatRate.code ? this.item.vatRate.code : this.defaultVatRateCode, [Validators.required]),
      }),
      country: new FormControl(this.item.country !== undefined ? this.item.country : '', []),
      itemType: new FormControl(this.item.itemType !== undefined ? this.item.itemType : '', []),
      fullName: new FormControl(this.item.fullName !== undefined ? this.item.fullName : '', []),
      imported: new FormControl(this.item.imported !== undefined ? this.item.imported : false, []),
      unit: new FormGroup({
        id: new FormControl(this.item.unit && this.item.unit.id, [])
      }),
      measurementUnits: new FormGroup({
        id: new FormControl(this.item.measurementUnits && this.item.measurementUnits.id, [Validators.required]),
      }),
      countries: new FormControl(this.item.countries ? this.item.countries : '', []),
      categoryNomenclature: new FormControl(this.item.categoryNomenclature ? this.item.categoryNomenclature : '', []),
      manufacturer: new FormControl(this.item.manufacturer ? this.item.manufacturer : '', []),
      releaseForm: new FormControl(this.item.releaseForm ? this.item.releaseForm : '', []),
      productStock: this.initProductStocks(),
      budgetDrug: new FormControl(this.item.budgetDrug),
      disease: new FormControl(this.item.disease),
    });

    if (this.item.productCode && this.item.productCode.type && this.item.productCode.type.code) {
      this.formGroup.get('productCode.gtin').setValidators([Validators.required, Validators.minLength(14)]);
      this.formGroup.get('productCode.serial').setValidators([Validators.required, Validators.minLength(13)]);
    }
    this.formGroup.get('budgetDrug').value ? this.isEnabled = true : this.isEnabled = false;

    this.setReferenceStocks();

    this.formGroup.get('productCode.type.code').valueChanges
      .pipe(
        takeUntil(this.destroy$)
      )
      .subscribe(item => {
        if (item) {
          this.formGroup.get('productCode.gtin').setValidators([Validators.required, Validators.minLength(14)]);
          this.formGroup.get('productCode.serial').setValidators([Validators.required, Validators.minLength(13)]);
          this.formGroup.get('productCode.gtin').enable();
          this.formGroup.get('productCode.serial').enable();
        } else {
          this.formGroup.get('productCode.gtin').clearValidators();
          this.formGroup.get('productCode.serial').clearValidators();
          this.formGroup.get('productCode.gtin').disable();
          this.formGroup.get('productCode.serial').disable();

        }
      });
    this.formGroup.controls.manufacturer.valueChanges
      .pipe(
        takeUntil(this.destroy$)
      )
      .subscribe(value => {
        if (value) {
          this.formGroup.get('countries').setValue(typeof value === 'object' ? value.countries : undefined);
        }
      });
    this.formGroup.get('paymentObject').valueChanges
      .pipe(
        takeUntil(this.destroy$)
      )
      .subscribe(item => {
        if (item.code === 'SERVICE') {
          this.formGroup.get('budgetDrug').setValue(false);
        }
      });

    this.formGroup.get('budgetDrug').valueChanges
      .pipe(
        takeUntil(this.destroy$)
      )
      .subscribe(item => {
        if (item) {
          this.formGroup.get('vatRate.code').setValue('NONE');
          this.formGroup.get('price').setValue(0.00);
          this.isEnabled = true;
        } else {
          this.formGroup.get('vatRate.code').setValue(this.defaultVatRateCode);
          this.formGroup.get('price').setValue(null);
          this.isEnabled = false;
        }
      });
  }

  filter(item) {
    const countries = this.formGroup.get('countries').value !== undefined ? this.formGroup.get('countries').value?.id : undefined;

    if (item === 'countries') {
      return {'id': countries};
    }
    return {'countries.name': countries};
  }

  apendList(offset) {
    this.store.dispatch(new LoadAppendListAction({
      type: CrudType.ReferenceDisease,
      params: {
        order: {name: 'ASC'},
        fields: {0: 'id', 1: 'name'},
        offset: offset,
        limit: 100
      },
      onSuccess: res => {

        if (res.response.items.length !== 0) {

          this.count += res.response.countItems;
          if (this.count <= res.response.totalCount) {
            this.apendList(this.count);
          }
        }

      }
    }));
  }
}


