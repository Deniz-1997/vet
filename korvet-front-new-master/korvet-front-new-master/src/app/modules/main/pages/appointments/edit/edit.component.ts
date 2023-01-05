import {Component, OnDestroy, OnInit} from '@angular/core';
import {FormArray, FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {AppointmentModel, AppointmentProductItem} from '../../../../../models/appointment/appointment.models';
import {select, Store} from '@ngrx/store';
import { ActivatedRoute, Params, Router } from '@angular/router';
import { debounceTime, distinctUntilChanged, filter, map, switchMap, takeUntil } from 'rxjs/operators';
import { PetModel } from '../../../../../models/pet/pet.models';
import {combineLatest, Observable, of, Subject} from 'rxjs';
import { PetsService } from '../../../../../services/pets.service';
import { NotifyService } from '../../../../../services/notify.service';
import { ReferenceProductModel } from '../../../../../models/reference/reference.product.models';
import { ModalConfirmComponent } from '../../../../shared/components/modal-confirm/modal-confirm.component';
import { MatDialog } from '@angular/material/dialog';
import { FilesService } from '../../../../../services/files.service';
import { AppointmentsPermissionService } from '../../../../../services/appointments-permission.service';
import { ReferenceStockModel } from '../../../../../models/reference/stock';
import { CrudType, CrudTypes } from 'src/app/common/crud-types';
import { EnumModel } from '../../../../../models/enum .models';
import { ReferenceProfessionModel } from '../../../../../models/reference/reference.profession.models';
import { BreadcrumbsService, IBreadcrumb } from '../../../../../services/breadcrumbs.service';
import { AuthService } from '../../../../../services/auth.service';
import { FormFieldValuesModel } from '../../../../../models/form-template/form-field-values.models';
import { AppointmentFormTemplateModel, FormTemplateModel } from '../../../../../models/form-template/form-template.models';
import { FormFieldModel } from '../../../../../models/form-template/form-field.models';
import { FormTemplateService } from '../../../../../services/form-template.service';
import {ChangeAppointmentStatusService} from '../../../../../services/appointment-change-status.service';
import {FilterUnitForByUserService} from '../../../../../services/filter-unit-for-by-user.service';
import { ModalProbeSamplingFormComponent } from '../../laboratory/modal-probe-sampling-form/modal-probe-sampling-form.component';
import { ProbeSamplingComponent } from '../../laboratory/probe-sampling/edit/edit.component';
import {ApiParamsModel} from 'src/app/api/api-connector/api-connector.models';
import {ApiConnectorService} from 'src/app/api/api-connector/api-connector.service';
import {CrudState, CrudStoreService} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction, LoadGetAction, LoadMatchesAction, LoadPatchAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelGetLoading, getCrudModelData, getCrudModelMatches, getCrudModelStoreId, getCrudModelCreatePatchLoading} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({
  templateUrl: './edit.component.html',
  styleUrls: ['./edit.component.css']
})
export class EditComponent implements OnInit, OnDestroy {
  crudType = CrudType;
  type = CrudType.Appointment;
  pet = new PetModel();
  idPets: number;
  petName: string;
  petType: string;
  owners$: Observable<{ id: number, name: string }[]>;
  users$: Observable<{ id: number, fullName: string }[]>;
  public formGroup: FormGroup;
  loading$: Observable<boolean>;
  public model: AppointmentModel = new AppointmentModel();
  previousAppointments$: Observable<AppointmentModel[]>;
  professions$: Observable<ReferenceProfessionModel[]>;
  showError = false;
  PaymentStateTypeEnum = [
    {id: 'NOT_PAID', name: 'Не оплачено'},
    {id: 'PAID', name: 'Оплачено'}
  ];
  PaymentTypeEnum: EnumModel;
  referenceProductItems$: Observable<ReferenceProductModel[]>;
  productFields = {0: 'id', 1: 'name', 2: 'price', 3: 'measurementUnits', 4: 'quantity'};
  productStockFields = {0: 'id', 1: 'name', 2: 'price', 3: 'measurementUnits', 4: 'quantity', 5: 'productStock'};
  referenceStocks$: Observable<ReferenceStockModel[]>;
  referenceStocks: ReferenceStockModel[];
  Object: [];
  template: any;
  private destroy$ = new Subject<any>();
  private params: Params = {};
  private isChangeBr = false;
  unitId: any;

  constructor(
    private router: Router,
    protected route: ActivatedRoute,
    private petsService: PetsService,
    private notify: NotifyService,
    private store: Store<CrudState>,
    private dialog: MatDialog,
    private apiFilesService: FilesService,
    public appointmentsPermission: AppointmentsPermissionService,
    private crud: ApiConnectorService,
    private crudConfig: CrudStoreService,
    protected brdSrv: BreadcrumbsService,
    protected authService: AuthService,
    private fb: FormBuilder,
    private changeAppointmentStatus: ChangeAppointmentStatusService,
    private formTemplateService: FormTemplateService,
    private getUnitIdService: FilterUnitForByUserService,
  ) {

    this.loading$ = combineLatest([
      this.store.pipe(select(getCrudModelGetLoading, { type: CrudType.Pet })),
      this.store.pipe(select(getCrudModelGetLoading, { type: this.type })),
      this.store.pipe(select(getCrudModelCreatePatchLoading, {type: this.type})),
    ]).pipe(map(loading => loading.some(l => l)));
    this.owners$ = this.store.pipe(select(getCrudModelData, {type: CrudType.Owner}));
    this.unitId = getUnitIdService;

    this.users$ = this.store.pipe(select(getCrudModelData, {type: CrudType.User})).pipe(
      map(item => {
        return item.map(user => {
            return {id: user['id'], fullName: user.getFullName()};
          }
        );
      })
    );

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.User,
      params: {
        fields: {0: 'id', 1: 'surname', 2: 'name', 3: 'patronymic'},
        order: {surname: 'ASC'},
        offset: 0,
        limit: 10
      }
    }));

    this.previousAppointments$ = this.store.pipe(select(getCrudModelMatches, {type: CrudType.Appointment}));

    this.professions$ = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceProfession}));

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceProfession,
      params: {
        order: {surname: 'ASC'},
        offset: 0,
        limit: 30
      }
    }));


    this.store.dispatch(new LoadGetListAction({
      type: CrudType.Enum,
      params: {
        filter: {
          id: [
            'PaymentStateTypeEnum',
            'PaymentTypeEnum',
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

    this.referenceProductItems$ = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceProduct}));
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceProduct,
      params: {
        order: {surname: 'ASC'},
        offset: 0,
        limit: 10
      }
    }));

    this.referenceStocks$ = this.store.pipe(select(getCrudModelData, {
      type: CrudType.ReferenceStock,
    }));

    this.referenceStocks$.subscribe(item => {
      const unit_id = (this.authService.user$.value !== null) ? this.authService.user$.value.user['unit']['id'] : null;
      this.referenceStocks = item.filter((v) => v.unit === null || v.unit.id === unit_id);
    });

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceStock,
      params: {
        filter: {
          showInAppointment: true
        },
        order: {id: 'ASC'},
        offset: 0,
        limit: 1000
      }
    }));
  }

  initProductItems(model, type?) {
    if (model && model.productItemsWithChildren && model.productItemsWithChildren.length > 0) {
      // const children = this.model.productItems.reduce((acc, pItem) => {
      //   pItem.children.forEach(child => acc.set(child.id, child));
      //   return acc;
      // }, new Map());
      // this.model.productItems = this.model.productItems.filter(pItem => !children.has(pItem.id));
      let control;

      if (!type) {
        control = new FormArray([]);
      } else {
        control = <FormArray>this.formGroup.controls['productItems'];
      }
      if (model.productItems) {
        model.productItems.forEach(item => {
          if (item.stock) {
            if (item.product.productStock && typeof item.product.productStock === 'object') {
              item.product.productStock = Object.values(item.product.productStock);
            }
            item.product.balance = item.product.productStock.reduce((acc, productStock) => {
              if (item.stock.id === productStock.stock.id) {
                acc += productStock.quantity;
              }
              return acc;
            }, 0);
          }
        });
      }

      for (const i in model.productItemsWithChildren) {
        if (model.productItemsWithChildren.hasOwnProperty(i) && model.productItemsWithChildren[i]) {
          const product = model.productItemsWithChildren[i];
          let paymentObject = null;
          if (product.product.paymentObject && typeof product.product.paymentObject === 'object') {
            paymentObject = (product.product.paymentObject ? product.product.paymentObject.code : null);
          } else {
            paymentObject = (product.product.paymentObject ? product.product.paymentObject : null);
          }

          const stockId = (paymentObject === 'COMMODITY' ? product.stock.id : null);
          control.push(this.getProductItemBase(
            product.quantity,
            product.measure,
            product.price,
            product.amount,
            product.priceWithCharge,
            product.product,
            product.id,
            paymentObject,
            stockId,
            product.children,
          ));
        }
      }
      return control;
    }
    return new FormArray([]);
  }

  initFormTemplate(appointmentFormTemplate?: AppointmentFormTemplateModel, formTemplate?: FormTemplateModel) {
    let formFieldValues: any[];
    let showDeleted = false;
    if (appointmentFormTemplate) {
      this.template = appointmentFormTemplate.formTemplate.template.split(new RegExp(/{{{.*?}}}/g));
      formFieldValues = appointmentFormTemplate.formFieldValues.map(formFieldValue => {
        return this.initFormFieldValues(formFieldValue, null);
      });
      showDeleted = true;
    } else {
      this.template = formTemplate.template.split(new RegExp(/{{{.*?}}}/g));
      formFieldValues = formTemplate.fields.map(field => {
        return this.initFormFieldValues(null, field);
      });
    }

    const result = this.fb.group({
      formTemplate: this.fb.group({
        id: [formTemplate ? formTemplate.id : appointmentFormTemplate.formTemplate.id],
        name: [formTemplate ? formTemplate.name : appointmentFormTemplate.formTemplate.name],
        template: [formTemplate ? formTemplate.template : appointmentFormTemplate.formTemplate.template],
      }),
      formFieldValues: this.fb.array(formFieldValues),
      showDeleted: showDeleted,
    });

    if (formTemplate) {
      const control = <FormArray>this.formGroup.controls['appointmentFormTemplate'];
      control.push(result);
    }
    return result;
  }

  initFormFieldValues(formFieldValue?: FormFieldValuesModel, formField?: FormFieldModel) {

    const formTemplateField = formFieldValue ? formFieldValue.formField.formTemplateField : formField.formTemplateField;
    const validators = [];
    let required = formFieldValue ? formFieldValue.formField.isRequired : formField.isRequired;

    let float = false;
    let signed = false;
    let precision = 0;
    let date = null;
    let time = null;
    let dateMin = null;
    let dateMax = null;
    let dateMinTime = null;
    let dateMaxTime = null;
    let showDateTime = false;
    let showDateMinTime = false;
    let showDateMaxTime = false;
    let dateMaxLimit = null;
    let innerHTML = null;
    let defaultInputValue = '';
    if (formTemplateField.properties) {
      for (const property of formTemplateField.properties) {
        if (['text_length'].includes(property.formFieldProperty.code)) {
          validators.push(Validators.maxLength(+property.value));
        } else if (['float'].includes(property.formFieldProperty.code)) {
          float = +property.value > 0;
        } else if (['signed'].includes(property.formFieldProperty.code)) {
          signed = +property.value > 0;
        } else if (['precision'].includes(property.formFieldProperty.code)) {
          precision = +property.value;
        } else if (['min_number_value'].includes(property.formFieldProperty.code) && property.value) {
          validators.push(Validators.min(+property.value));
        } else if (['max_number_value'].includes(property.formFieldProperty.code) && property.value) {
          validators.push(Validators.max(+property.value));
        } else if (['date_time'].includes(property.formFieldProperty.code)) {
          // time = property.value ? property.value : '12:00';
        } else if (['date_min_time'].includes(property.formFieldProperty.code)) {
          // dateMinTime = property.value ? property.value : '12:00';
        } else if (['date_max_time'].includes(property.formFieldProperty.code)) {
          // dateMaxTime = property.value ? property.value : '12:00';
        } else if (['date_format'].includes(property.formFieldProperty.code)) {
          showDateTime = +property.value > 0;
        } else if (['date_min_format'].includes(property.formFieldProperty.code)) {
          showDateMinTime = +property.value > 0;
        } else if (['date_max_format'].includes(property.formFieldProperty.code)) {
          showDateMaxTime = +property.value > 0;
        } else if (['date_max_limit'].includes(property.formFieldProperty.code)) {
          dateMaxLimit = property.value;
        } else if (['text_default_value'].includes(property.formFieldProperty.code)
          || ['text_area_default_value'].includes(property.formFieldProperty.code)
          || ['number_default_value'].includes(property.formFieldProperty.code)) {
          defaultInputValue = property.value;
        }
      }
    } else if (formFieldValue && formFieldValue.type && formFieldValue.type === 'multi_date') {
      dateMin = formFieldValue.dateMin;
      dateMax = formFieldValue.dateMax;
      dateMinTime = formFieldValue.dateMinTime;
      dateMaxTime = formFieldValue.dateMaxTime;
      showDateTime = formFieldValue.showDateTime;
      showDateMinTime = formFieldValue.showDateMinTime;
      showDateMaxTime = formFieldValue.showDateMaxTime;
      dateMaxLimit = formFieldValue.dateMaxLimit;
      required = formFieldValue.required;
      innerHTML = formFieldValue.innerHTML;
    }

    if (required) {
      validators.push(Validators.required);
    }

    let crudType = null;
    let extraData = null;
    if (formFieldValue && formFieldValue.extraData) {
      extraData = formFieldValue.extraData;
    } else if (formTemplateField.extraData) {
      extraData = formTemplateField.extraData;
    }

    if (extraData) {
      crudType = extraData.crudType;
    }

    if (formTemplateField.type && ['date', 'multi_date'].includes(formTemplateField.type.code) && formTemplateField.properties) {
      extraData = this.formTemplateService.getDateExtraData(formTemplateField.properties, formTemplateField.type.code);
      if (formTemplateField.type.code === 'date' && formFieldValue && formFieldValue.value) {
        if (this.formTemplateService.isJSON(formFieldValue.value)) {
          const data = JSON.parse(formFieldValue.value);
          date = data.date ? data.date : null;
          time = data.time ? data.time : null;
        }
      }
      if (formTemplateField.type.code === 'multi_date' && formFieldValue && formFieldValue.value) {
        if (this.formTemplateService.isJSON(formFieldValue.value)) {
          const data = JSON.parse(formFieldValue.value);
          dateMin = data.dateMin ? data.dateMin : null;
          dateMax = data.dateMax ? data.dateMax : null;
          dateMinTime = data.dateMinTime ? data.dateMinTime : null;
          dateMaxTime = data.dateMaxTime ? data.dateMaxTime : null;
          extraData.dateMax.min = this.formTemplateService.createDate(
            data.dateMin,
            dateMaxLimit && dateMaxLimit === 'gth' ? 1 : null
          );
        }
      }
    }

    // Reference default value
    let value = formFieldValue ? formFieldValue.value : '';
    let defaultValue = '';
    let multiSelect = (formFieldValue && formFieldValue.multiSelect) ? formFieldValue.multiSelect : false;
    if (formTemplateField.properties && ['reference', 'template_reference'].includes(formTemplateField.type.code)) {
      formTemplateField.properties.map(property => {
        if (property.formFieldProperty.code === 'entity_default_value' && property.value !== '') {
          defaultValue = JSON.parse(property.value);
        }
        if (property.formFieldProperty.code === 'multi_select') {
          multiSelect = +property.value > 0;
        }
      });
      value = (formFieldValue && formFieldValue.value !== '') ? JSON.parse(formFieldValue.value) : defaultValue;
    }

    const type = formTemplateField.type ? formTemplateField.type.code : formFieldValue.type;

    if ((type === 'text' || type === 'textarea' || type === 'number') && !value) {
      value = defaultInputValue;
    }
    return this.fb.group({
      value: [value, !['date', 'multi_date'].includes(type) ? validators : []],
      type: type,
      required: required,
      name: formFieldValue ? formFieldValue.formField.name : formField.name,
      float: float,
      signed: signed,
      precision: float ? (precision ? precision : 20) : 0,
      innerHTML: innerHTML ? innerHTML : this.template.shift(),
      formField: this.fb.group({
        id: [formFieldValue ? formFieldValue.formField.id : formField.id],
        formTemplateField: this.fb.group({
          id: [formTemplateField.id],
        }),
      }),
      crudType: crudType,
      extraData: extraData,
      multiSelect: multiSelect,
      date: [date, type === 'date' ? validators : []],
      time: [time, showDateTime ? validators : []],
      dateMinTime: [dateMinTime, showDateMinTime ? validators : []],
      dateMaxTime: [dateMaxTime, showDateMaxTime ? validators : []],
      dateMin: [dateMin, type === 'multi_date' ? validators : []],
      dateMax: [dateMax, type === 'multi_date' ? validators : []],
      showDateTime: showDateTime,
      showDateMinTime: showDateMinTime,
      showDateMaxTime: showDateMaxTime,
      dateMaxLimit: dateMaxLimit,
    });
  }

  setModel() {
    let data = '';
    let time = '';
    let timeEnd = '';
    let dateEnd = null;
    let appointmentFormTemplate = [];

    if (this.model.date) {
      const datetime = this.model.date.split(' ');
      data = datetime[0];
      time = datetime[1];
    }
    if (this.model.timeEnd) {
      const datetime = this.model.timeEnd.split(' ');
      timeEnd = datetime[1];
    } else if (this.model.dateEnd && this.model.dateEnd.length > 10) {
      const datetimeEnd = this.model.dateEnd.split(' ');
      dateEnd = datetimeEnd[0];
      timeEnd = datetimeEnd[1];
    } else if (this.model.dateEnd && this.model.timeEnd) {
      dateEnd = this.model.dateEnd;
      timeEnd = this.model.timeEnd;
    }

    if (this.model.pet) {
      this.idPets = this.model.pet.id;
      this.petName = this.model.pet.name;
      this.petType = this.model.pet.type.icon?.code;
      this.getRepeatAppointment(this.model.pet.id);
    }

    if (this.model.appointmentFormTemplate && this.model.appointmentFormTemplate instanceof Array) {
      appointmentFormTemplate = this.model.appointmentFormTemplate.map(formTemplate => {
        return this.initFormTemplate(formTemplate);
      });
    }

    try {
      this.formGroup = new FormGroup({
        id: new FormControl(this.model.id || ''),
        data: new FormControl(data || '', [Validators.required]),
        time: new FormControl(time || '', [Validators.required]),
        dateEnd: new FormControl(dateEnd || null),
        timeEnd: new FormControl(timeEnd || ''),
        name: new FormControl(this.model.name || ''),
        survey: new FormControl(this.model.survey || ''),
        temperature: new FormControl({
          value: (this.model.appointmentTemperature && !this.model.temperatureNotMeasured) ? this.model.appointmentTemperature.temperature.value : null,
          disabled: this.model.temperatureNotMeasured
        }),
        temperature_check: new FormControl(this.model.temperatureNotMeasured),
        weight: new FormControl({
          value: (this.model.appointmentWeight && !this.model.weightNotMeasured) ? this.model.appointmentWeight.weight.value / 1000 : null,
          disabled: this.model.weightNotMeasured
        }),
        weight_check: new FormControl(this.model.weightNotMeasured),
        pet: new FormControl(this.model.pet ? {
          id: this.model.pet.id,
          name: this.model.pet.name
        } : null, [Validators.required]),
        owner: new FormControl(this.model.owner ? {
          id: this.model.owner.id,
          name: this.model.owner.name
        } : null, [Validators.required]),
        user: new FormControl(this.model.user ? {
          id: this.model.user.id,
          fullName: this.model.user.getFullName()
        } : null),
        type: new FormGroup({
          code: new FormControl(this.model.type && this.model.type.code || null, [Validators.required]),
        }),
        diagnosis: new FormControl(this.model.diagnosis || ''),
        prescription: new FormControl(this.model.prescription || ''),
        profession: new FormControl(this.model.profession ? {
          id: this.model.profession.id,
          name: this.model.profession.name
        } : null, [Validators.required]),
        previous: new FormGroup({
          id: new FormControl(this.model.previous ? this.model.previous.id : null, [])
        }),
        paymentState: new FormGroup({
          code: new FormControl(this.model.paymentState && this.model.paymentState.code || null),
        }),
        paymentType: new FormGroup({
          code: new FormControl(this.model.paymentType ? this.model.paymentType.code : 'CASH')
        }),
        isExtraCharge: new FormControl(this.model.isExtraCharge ? this.model.isExtraCharge : false),
        extraCharge: new FormControl({
          value: this.model.extraCharge ? this.model.extraCharge : 50,
          disabled: !this.model.isExtraCharge
        }),
        productItems: this.initProductItems(this.model),
        state: new FormGroup({
          code: new FormControl(this.model.state ? this.model.state.code : 'DRAFT', [Validators.required]),
          title: new FormControl(this.model.state ? this.model.state.title : null)
        }, [Validators.required]),
        appointmentFormTemplate: new FormArray(appointmentFormTemplate)
      }, [this.temperatureValidator, this.weightValidator]);
    } catch (e) {
      console.error(e);
    }
    this.formGroup.controls.profession.valueChanges
      .pipe(
        takeUntil(this.destroy$)
      )
      .subscribe((res) => {
        if (res) {
          this.formGroup.get('user').setValue(null);
        }
      });

    this.formGroup.get('type.code').valueChanges
      .pipe(
        takeUntil(this.destroy$)
      )
      .subscribe((res: string) => {
        if (res === 'SECONDARY') {
          const petId = this.formGroup.get('pet').value.id;
          if (petId) {
            this.getRepeatAppointment(petId, (item) => {
              if (item.response.totalCount === 0) {
                this.formGroup.controls.type.get('code').setValue('PRIMARY');
                this.notify.handleMessage('У данного животного нет предыдущих приёмов', 'warning');
              }
            });
          }
        } else {
          this.formGroup.get('previous.id').setValue(null);
        }
      });


    this.formGroup.get('extraCharge')
      .valueChanges
      .pipe(
        takeUntil(this.destroy$),
        debounceTime(500),
        distinctUntilChanged()
      )
      .subscribe(value => {
        if (value) {
          this.setAllAmount();
        }
      });

    this.formGroup.get('data').valueChanges
      .pipe(
        takeUntil(this.destroy$)
      )
      .subscribe(() => {
        this.brdSrv.replaceLabelByIndex(this.getBrAppointment(), 2);
      });

    this.formGroup.get('time').valueChanges
      .pipe(
        takeUntil(this.destroy$)
      )
      .subscribe(() => {
        this.brdSrv.replaceLabelByIndex(this.getBrAppointment(), 2);
      });

    this.formGroup.get('temperature_check').valueChanges
      .pipe(
        takeUntil(this.destroy$)
      )
      .subscribe(() => {
        if (this.formGroup.get('temperature_check').value) {
          this.formGroup.get('temperature').setValue(null);
          this.formGroup.get('temperature').disable();
        } else {
          this.formGroup.get('temperature').enable();
        }
      });
    this.formGroup.get('weight_check').valueChanges
      .pipe(
        takeUntil(this.destroy$)
      )
      .subscribe(() => {
        if (this.formGroup.get('weight_check').value) {
          this.formGroup.get('weight').setValue(null);
          this.formGroup.get('weight').disable();
        } else {
          this.formGroup.get('weight').enable();
        }
      });

    this.changeBr();
  }

  ngOnInit() {
    // this.setModel();
    const id = this.route.snapshot.paramMap.get('id');
    const code = this.route.snapshot.queryParamMap.get('code');

    if (id) {
      this.params.id = id;
      this.params.fields = {
        0: 'id',
        1: 'date',
        2: 'name',
        3: 'survey',
        4: 'diagnosis',
        5: 'prescription',
        6: 'paymentState',
        7: 'paymentType',
        8: 'productItems',
        9: 'state',
        10: 'dateEnd',
        11: 'isExtraCharge',
        12: 'probeSamplings',
        13: 'layout',
        14: 'actions',
        15: 'listActions',
        16: 'data',
        17: 'appointmentFormTemplate',
        18: 'weightNotMeasured',
        19: 'temperatureNotMeasured',
        'cashReceipt': ['id', 'createdAt', 'fiscal'],
        'owner': ['id', 'name', 'contractDateTo'],
        'profession': ['id', 'name'],
        'user': ['id', 'surname', 'name', 'patronymic'],
        'type': ['title'],
        'previous': ['id', 'date', 'name'],
        'pet': {
          0: 'id', 1: 'type', 2: 'name', 3: 'birthday',
          'actualWeight': ['date', 'value'],
          'actualTemperature': ['date', 'value']
        },
        'appointmentTemperature': {0: 'id',
          'temperature': ['id', 'date', 'value']
        },
        'appointmentWeight': {0: 'id',
          'weight': ['id', 'date', 'value']
        }
      };
      if (!this.appointmentsPermission.getAppointments()) {
        
        this.store.dispatch(new LoadGetListAction({
          type: this.type,
          params: <any>this.params,
          onSuccess: appointment => {
            this.model = new AppointmentModel(appointment.response);
            
            if (this.model.productItemsWithChildren && this.model.productItemsWithChildren
              && this.model.productItemsWithChildren.length > 0) {

              const idArray = [];
              this.model.productItemsWithChildren.map(item => idArray.push(item.product.id));
              this.store.dispatch(new LoadGetListAction({
                type: CrudType.ReferenceProduct,
                params: {
                  filter: {
                    id: idArray
                  },
                  fields: this.productStockFields
                },
                onSuccess: response => {
                  this.model.productItemsWithChildren.map(
                    productItem => {
                      response.response.items.map(data => {
                        if (data.quantity !== 0) {
                          data.productStock.map(
                            stock => {
                              if (productItem.stock && stock.stock.id === productItem.stock.id) {
                                productItem.product.balance = stock.quantity;
                              }
                            }
                          );
                        } else {
                          productItem.product.balance = data.quantity;
                        }
                      });
                    }
                  );
                  this.setModel();
                  if (appointment.response.layout) {
                    this.appointmentsPermission.setAppointments(appointment.response.layout);
                  }
                }
              }));
            } else {
              this.setModel();
              this.disableExtraCharge();
              if (appointment.response.layout) {
                this.appointmentsPermission.setAppointments(appointment.response.layout);
              }
            }
          }
        }));
      } else {

        this.store.pipe(
          select(getCrudModelStoreId, {type: CrudType.Appointment, params: id}),
          filter(appointment => !!appointment),
          takeUntil(this.destroy$),
        ).subscribe(appointment => {
          if (appointment && appointment.id) {
            this.model = appointment;

            if (this.model.productItemsWithChildren && this.model.productItemsWithChildren
              && this.model.productItemsWithChildren.length > 0) {

              const idArray = [];
              this.model.productItemsWithChildren.map(item => idArray.push(item.product.id));
              this.store.dispatch(new LoadGetListAction({
                type: CrudType.ReferenceProduct,
                params: {
                  filter: {
                    id: idArray
                  },
                  fields: this.productStockFields
                },
                onSuccess: response => {
                  this.model.productItemsWithChildren.map(
                    productItem => {
                      response.response.items.map(data => {
                        if (productItem.product.id === data.id) {
                          if (data.quantity !== 0) {
                            data.productStock.map(
                              stock => {
                                if (stock.stock.id === productItem.stock.id) {
                                  productItem.product.balance = stock.quantity;
                                }
                              }
                            );
                          } else {
                            productItem.product.balance = data.quantity;
                          }
                        }
                      });
                    }
                  );
                  this.setModel();
                }
              }));
            } else {
              this.setModel();
            }
          }
        });
        this.store.dispatch(new LoadGetAction({type: CrudType.Appointment,  params: <any>this.params}));
      }
    } else {
      this.setModel();
    }

    if (code && id) {
      this.changeAppointmentStatus.changeAppointmentStatusCode(code, Number(id));
    }

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceFileType,
      params: {order: {'sort': 'ASC', 'name': 'ASC'}, limit: 10}
    }));
  }

  getRepeatAppointment(petId, callback?) {
    const params = {
      fields: {0: 'id', 1: 'name', 2: 'date'},
      filter: {
        pet: {id: petId},
        type: 'PRIMARY'
      },
      offset: 0,
      limit: 5
    };
    if (this.model.id) {
      params.filter['!id'] = this.model.id;
    }
    this.store.dispatch(new LoadMatchesAction({
      type: CrudType.Appointment,
      params: params,
      onSuccess: result => {
        if (result.response.totalCount > 0) {
          this.formGroup.get('previous.id').setValue(result.response.items[0].id);
        }
        if (callback) {
          callback(result);
        }
      }
    }));
  }

  hasProfession() {
    return !!(this.formGroup.controls.profession.value && this.formGroup.controls.profession.value.id);
  }

  isProfession() {
    return {professions: {id: this.formGroup.get('profession').value.id}};
  }

  submit(): void {
    this.showError = true;

    if (this.formGroup.valid) {

      const model = {...this.formGroup.value};

      if (this.model.lastUpdate) {
        model.lastUpdate = this.model.lastUpdate;
      }

      if (this.model.cashReceipt && this.model.cashReceipt.id) {
        delete model.productItems;
      }

      if (typeof model.productItems !== 'undefined' && model.productItems.length) {
        const products = {};

        for (const i in model.productItems) {
          if (model.productItems.hasOwnProperty(i)) {
            if (model.productItems[i]['product']['price'] == 0 ) {
              if (model.productItems[i]['product']['budgetDrug'] === false) {
                return this.notify.handleMessage('Не бюджетный товар не может иметь цену 0, пожалуйста проверьте номенклатуру товара '
                  + model.productItems[i]['product']['name'], 'warning');
              }
            }
            const products_ = model.productItems[i].paymentObject === 'SERVICE' ? model.productItems[i].items : [model.productItems[i]];
            products_.map(v => {
              if (typeof products[v.product.id] !== 'undefined') {
                products[v.product.id].quantity += parseFloat(v.quantity);
              } else {
                const stock_quantity = typeof v.product.productStock !== 'undefined' ? v.product.productStock.find(a => a.stock.id === v.stock.id).quantity : v.product.quantity;

                products[v.product.id] = {
                  name: v.product.name,
                  stock_quantity: parseFloat(stock_quantity),
                  quantity: parseFloat(v.quantity)
                };
              }
            });
          }
          if (model.productItems[i].items.length) {
            model.productItems[i].items.forEach(element => {
              element.quantity = parseFloat(element.quantity);
            });
          }
        }


        const notify = this.notify;
        if (model['state']['code'] !== 'REGISTERED') {
          Object.keys(products).map(function (key) {
            if (products[key].quantity > products[key].stock_quantity) {
              notify.handleMessage(`Количество товара <b>"${products[key].name}"</b> превышает остатки`, 'danger');
              throw new Error('Quantity error');
            }
          });
        }

        for (const i in model.productItems) {
          if (model.productItems.hasOwnProperty(i)) {

            if (typeof model.productItems[i].quantity === 'string') {
              model.productItems[i].quantity = parseFloat(model.productItems[i].quantity);
            }

            if (model.productItems[i].id === null) {
              delete model.productItems[i].id;
            }

            if (typeof model.productItems[i].productStock !== 'undefined') {
              delete model.productItems[i].productStock;
            }

            if (typeof model.productItems[i].product !== 'undefined') {
              if (typeof model.productItems[i].product.productStock !== 'undefined') {
                delete model.productItems[i].product.productStock;
              }
            }

            if (model.productItems[i].stock !== null && model.productItems[i].stock.id === null) {
              delete model.productItems[i].stock;
              model.productItems[i].stock = null;
            }

            model.productItems[i].items.forEach(item => {
              if (item.id === null) {
                delete item.id;
              }
            });
          }
        }
      }

      /*удаляем id:null, в АПИ ошибка будет*/
      if (model.user && (model.user.id === null || model.user.id === undefined)) {
        model.user = null;
      }

      if (model.owner && model.user && model.user.owner) {
        delete model.user.owner;
      }

      if (model.user && model.user.fullName) {
        delete model.user.fullName;
      }

      if (model.previous && model.previous.id === null) {
        delete model.previous;
        model.previous = null;
      }

      if (model.type && model.previous && model.type.code === 'PRIMARY') {
        delete model.previous;
        model.previous = null;
      }
      model.weightNotMeasured = this.formGroup.controls['weight_check'].value ? this.formGroup.controls['weight_check'].value : false;
      model.temperatureNotMeasured = this.formGroup.controls['temperature_check'].value ? this.formGroup.controls['temperature_check'].value : false;
      if (!this.formGroup.controls['temperature_check'].value || this.model.appointmentTemperature) {
        model.appointmentTemperature = {
          'id': this.model.appointmentTemperature ? this.model.appointmentTemperature.id : 0,
          'appointment': {'id': model.id},
          'deleted': model.temperatureNotMeasured,
          'temperature': {
            'id': (this.model.appointmentTemperature && this.model.appointmentTemperature.temperature) ? this.model.appointmentTemperature.temperature.id : 0,
            'date': this.model.date,
            'value': this.formGroup.controls['temperature'].value ? parseFloat(parseFloat(this.formGroup.controls['temperature'].value).toFixed(3)) : 0,
            'pet': {'id': this.model.pet.id},
            'deleted': model.temperatureNotMeasured,
          }
        };
      }
      if (!this.formGroup.controls['weight_check'].value || this.model.appointmentWeight) {
        model.appointmentWeight = {
          'id': this.model.appointmentWeight ? this.model.appointmentWeight.id : 0,
          'appointment': {'id': model.id},
          'deleted': model.weightNotMeasured,
          'weight': {
            'id': (this.model.appointmentWeight && this.model.appointmentWeight.weight) ? this.model.appointmentWeight.weight.id : 0,
            'date': this.model.date,
            'value': this.formGroup.controls['weight'].value ? parseInt((parseFloat(this.formGroup.controls['weight'].value) * 1000).toString(), 10) : 0,
            'pet': {'id': this.model.pet.id},
            'deleted': model.weightNotMeasured,
          }
        };
      }
      model.date = model.data + ' ' + model.time.substr(0, 5) + ':00';
      delete model['data'];
      delete model['time'];
      if (model.dateEnd && model.timeEnd) {
        model.dateEnd = model.dateEnd.substr(0, 10) + ' ' + model.timeEnd.substr(0, 5) + ':00';
      }
      delete model.timeEnd;

      if (model.appointmentFormTemplate && model.appointmentFormTemplate.length) {
        model.appointmentFormTemplate = model.appointmentFormTemplate.map(formTemplate => {
          if (formTemplate.formFieldValues && formTemplate.formFieldValues.length) {
            formTemplate.formFieldValues = formTemplate.formFieldValues.map(field => {
              if (field.type === 'date') {
                field.value = {
                  date: field.date,
                  time: field.time,
                };
              }
              if (field.type === 'multi_date') {
                field.value = {
                  dateMin: field.dateMin,
                  dateMax: field.dateMax,
                  dateMinTime: field.dateMinTime,
                  dateMaxTime: field.dateMaxTime,
                };
              }
              if (typeof field.value === 'object') {
                field.value = JSON.stringify(field.value);
              }
              return field;
            });
          }
          return formTemplate;
        });
      }
     
      this.store.dispatch(new LoadPatchAction({
        type: this.type,
        params: model,
        onSuccess: (res) => {
          if (res.status === true && res.response && res.response.id) {
            this.router.navigate(['/appointments/', this.model.id]).then();
          }
        },
        onError: (err) => {
          if (err.errors.length && err.errors[0].stringCode == 'APPOINTMENT_CHANGED') {
            const dialogRef = this.dialog.open(ModalConfirmComponent, {
              width: window.innerWidth > 960 ? '20%' : '90%',
              height: '100% - 50px',
              data: {
                head: err.errors[0].message,
                headComment: 'Внимание! Введенные данные могут быть утеряны.',
                actions: [
                  {
                    class: 'btn-st btn-st--left btn-st--gray',
                    action: false,
                    title: 'Отмена'
                  },
                  {
                    class: 'btn-st btn-st--right btn-st--blue',
                    action: true,
                    title: 'Обновить'
                  },
                ],
              }
            });
            dialogRef.afterClosed().subscribe((result: boolean) => {
              if (result) {
                window.location.reload();
              }
            });
          }
        }
      }));
    } else {
      if (this.formGroup.controls.productItems &&
        this.formGroup.controls.productItems['controls'].length > 0 &&
        this.formGroup.controls.productItems['controls'].some(item => item.controls.quantity.invalid)) {
        const invalidFormGroup = this.formGroup.controls.productItems['controls'].find(item => item.controls.quantity.invalid);
        if (invalidFormGroup && invalidFormGroup.get('quantity').value === 0) {
          this.notify.handleMessage('Дозировка/количество не может быть 0', 'warning');
        } else {
          this.notify.handleMessage('Количество превышает остатки', 'warning');
        }
      } else {
        this.notify.handleMessage('Заполните обязательные поля', 'warning');
      }
    }
  }

  getBrAppointment(): string {
    return (this.formGroup.value.type && this.formGroup.value.type.code === 'SECONDARY' ? 'Повторный' : 'Первичный') + ' прием от '
      + this.formGroup.value.data + ' "'
      + this.formGroup.value.time + ' "'
      + this.formGroup.value.pet.name + '"';
  }

  changeBr() {
    if (!this.isChangeBr) {
      this.brdSrv.replaceLabelByIndex(
        this.getBrAppointment(), 1);

      const brParam: IBreadcrumb = {
        current: true,
        label: this.formGroup.value.pet.name,
        params: {id: '1866844'},
        url: '/pets/' + this.formGroup.value.pet.id + '/profile',
      };

      this.brdSrv.addByIndex(brParam, 1);
      this.isChangeBr = true;
    }
  }

  ngOnDestroy(): void {
    this.destroy$.next();
  }

  getProductItemBase(
    quantity: number = 1,
    measure: string = 'шт.',
    price: number = 0,
    amount?: number,
    priceWithCharge?: number,
    product?: ReferenceProductModel,
    id?: number,
    paymentObject?: string | null,
    stockId?: number | null,
    items?: AppointmentProductItem[],
  ) {
    const balance = product && product.balance ? product.balance : 0;
    if (quantity > balance && balance) {
      quantity = balance;
    }
    const itemFormGroup = new FormGroup({
      quantity: new FormControl(quantity ? quantity : (paymentObject !== 'SERVICE' && !balance ? 0 : 1), [
        Validators.required,
        Validators.min(0),
        ...paymentObject !== 'SERVICE' && this.model.state.code !== 'REGISTERED' ? [Validators.max(balance)] : [],
      ]),
      product: new FormControl(product ? product : null, [Validators.required]),
      price: new FormControl(price ? price : 0, []),
      amount: new FormControl(amount ? amount : 0, []),
      priceWithCharge: new FormControl(priceWithCharge ? priceWithCharge : (price ? price : 0), []),
      measure: new FormControl(measure ? measure : 'шт.'),
      balance: new FormControl({
        value: balance,
        disabled: true,
      }),
      id: new FormControl(id ? id : null),
      paymentObject: new FormControl(paymentObject ? paymentObject : null),
      stock: new FormGroup({
        id: new FormControl(stockId ? stockId : null)
      })
    });
    itemFormGroup.addControl('items', new FormArray((items && items.length) ? items.map(item => {
      const pObject = (item.product.paymentObject ? item.product.paymentObject.code : null);
      const sId = item.stock ? item.stock.id : null;
      return this.getProductItemBase(
        item.quantity,
        item.product.measurementUnits ? item.product.measurementUnits.name : null,
        item.price,
        item.amount,
        item.priceWithCharge,
        item.product,
        item.id,
        pObject,
        sId,
      );
    }) : []));

    itemFormGroup.get('product')
      .valueChanges
      .pipe(
        takeUntil(this.destroy$),
        debounceTime(500),
        distinctUntilChanged(),
        switchMap(value => {
          if (value && value instanceof Object && itemFormGroup.get('paymentObject').value === 'COMMODITY') {
            return this.crud.getList(this.crudConfig.config[CrudType.ProductStock].setData(CrudTypes[CrudType.ProductStock].params).url,
              new ApiParamsModel({
                filter: {
                  product: {id: value.id},
                  stock: {id: itemFormGroup.get('stock.id').value}
                }
              })).pipe(
              map(res => {
                try {
                  value.quantity = res.response.items.reduce((acc, productStock) => acc + productStock.quantity, 0);
                } catch (e) {
                  value.quantity = 0;
                }
                return value;
              })
            );
          } else {
            return of(value);
          }
        })
      ).subscribe((value: any) => {
      if (value && value instanceof Object) {
        itemFormGroup.get('measure').setValue(value.measure);
        itemFormGroup.get('price').setValue(value.price);
        itemFormGroup.get('balance').setValue(value.quantity);
        itemFormGroup.get('amount').setValue(this.setAmount(
          itemFormGroup.get('price').value,
          itemFormGroup.get('quantity').value
        ));
        itemFormGroup.get('priceWithCharge').setValue(this.setPriceWithCharge(
          itemFormGroup.get('price').value,
          itemFormGroup.get('quantity').value,
          itemFormGroup.get('paymentObject').value,
        ));
        itemFormGroup.get('quantity').setValidators([
          Validators.required,
          Validators.min(0.001),
          ...itemFormGroup.get('paymentObject').value !== 'SERVICE' && !itemFormGroup.get('id').value ?
            [Validators.max(value.quantity)] : [],
        ]);
        itemFormGroup.get('quantity').setValue(itemFormGroup.get('paymentObject').value !== 'SERVICE' && !value.quantity ? 0 : 1);
      }
    });

    itemFormGroup.get('quantity')
      .valueChanges
      .pipe(
        takeUntil(this.destroy$)
      )
      .pipe(
        debounceTime(500),
        distinctUntilChanged()
      )
      .subscribe((value: number) => {
        itemFormGroup.get('amount').setValue(this.setAmount(
          itemFormGroup.get('price').value,
          value
        ));
        itemFormGroup.get('priceWithCharge').setValue(this.setPriceWithCharge(
          itemFormGroup.get('price').value,
          value,
          itemFormGroup.get('paymentObject').value,
        ));
      });

    return itemFormGroup;
  }

  setAmount(price, quantity): number {
    return (quantity > 0 && price > 0) ? price * quantity : 0;
  }

  setPriceWithCharge(price, quantity, paymentObject): number {
    if (price > 0) {
      if (paymentObject === 'SERVICE' && this.formGroup.get('isExtraCharge').value && this.formGroup.get('extraCharge').value > 0) {
        return parseFloat((price * (1 + this.formGroup.get('extraCharge').value / 100)).toFixed(2));
      }
      return price;
    }
    return 0;
  }

  onSetAmount() {
    this.disableExtraCharge();
    this.setAllAmount();
  }

  disableExtraCharge() {
    if (this.formGroup.get('isExtraCharge').value) {
      this.formGroup.get('extraCharge').enable();
    } else {
      this.formGroup.get('extraCharge').disable();
    }
  }

  setAllAmount() {
    const control = <FormArray>this.formGroup.controls['productItems'];
    if (control.value.length > 0) {
      for (const i in control.value) {
        if (control.value.hasOwnProperty(i)) {
          // control.controls[i].controls['amount'].setValue(
          //   this.setAmount(
          //     control.controls[i].get('price').value,
          //     control.controls[i].get('quantity').value)
          // );
          control.controls[i].controls['priceWithCharge'].setValue(
            this.setPriceWithCharge(
              control.controls[i].get('price').value,
              control.controls[i].get('quantity').value,
              control.controls[i].get('paymentObject').value)
          );
        }
      }
    }
  }

  addProductItem($event?, paymentObject?, stockId?, item?) {
    if ($event) {
      $event.preventDefault();
    }
    const control = item ? item.get('items') as FormArray : <FormArray>this.formGroup.controls['productItems'];
    control.push(this.getProductItemBase(
      1,
      'шт.',
      0,
      0,
      0,
      null,
      null,
      paymentObject,
      stockId));

  }

  removeProductItem(id, $event, item?) {
    if ($event) {
      $event.preventDefault();
    }
    const control = item ? item.get('items') as FormArray : <FormArray>this.formGroup.controls['productItems'];
    if (!control.controls[id].get('product').value) {
      control.removeAt(id);
    } else {
      this.onDelete(id, item);
    }
  }

  onDelete(id: number, item?: FormControl) {
    const control = item ? item.get('items') as FormArray : <FormArray>this.formGroup.controls['productItems'];
    const dialogRef = this.dialog.open(ModalConfirmComponent, {
      width: window.innerWidth > 960 ? '20%' : '90%',
      height: '100% - 50px',
      data: {
        head: 'Вы точно хотите удалить позицию?',
        headComment: 'Действие необратимо <br> (' + control.controls[id].get('product').value.name + ')',
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
        control.removeAt(id);
      }
    });
  }

  getProductItemSumAndCount() {
    const control = <FormArray>this.formGroup.controls['productItems'];
    return control.controls.reduce((acc, c) => {
      const items = c.get('items') as FormArray;
      if (items && items.controls.length) {
        acc.count += items.controls.length + 1;
        // acc.sum += items.controls.reduce((itemsAcc, i) => itemsAcc + (i.get('amount').value || 0), c.get('amount').value || 0);
        acc.sum += items.controls.reduce((itemsAcc, i) => itemsAcc +
          ((i.get('priceWithCharge').value * i.get('quantity').value) || 0),
          ((c.get('priceWithCharge').value * c.get('quantity').value)) || c.get('amount').value || 0);
      } else {
        acc.count++;
        if (c.get('priceWithCharge').value) {
          acc.sum += Math.round((c.get('priceWithCharge').value || 0) * c.get('quantity').value * 100) / 100;
        } else {
          acc.sum += Math.round((c.get('amount').value || 0) * 100) / 100;
        }
      }
      for (const i in this.model.probeSamplings) {
        acc.count++;
        acc.sum += ProbeSamplingComponent.getProbeSamplingAmount(this.model.probeSamplings[i]);
      }
      return acc;
    }, {count: 0, sum: 0});
  }

  /**
   *
   * @param stockId
   * @return ReferenceStockModel
   */
  getStockById(stockId: number) {
    for (const i in this.referenceStocks) {
      if (this.referenceStocks.hasOwnProperty(i) && this.referenceStocks[i].id === stockId) {
        return new ReferenceStockModel(this.referenceStocks[i]);
      }
    }
    return new ReferenceStockModel();
  }

  getFilterProduct(control: FormControl) {
    const value = control.value;
    const filterProduct = {
      active: 1,
      existPrice: 1
    };
    if (value.paymentObject === 'SERVICE') {
      filterProduct['paymentObject'] = value.paymentObject;
    } else if (value.paymentObject === 'COMMODITY') {
      filterProduct['paymentObject'] = value.paymentObject;
      filterProduct['productStock'] = {stock: {id: value.stock.id}, '!=quantity': 0};
      filterProduct['existQuantity'] = 1;
    }
    return filterProduct;
  }

  public changeItemQuantity(item, value) {
    if (!value) {
      return;
    }
    const quantity = this.getMaxQuantity(item);
    const parset_value: number = parseFloat(value);
    if (item.get('paymentObject').value !== 'SERVICE') {
      // tslint:disable-next-line:triple-equals
      item.get('quantity').setValue((quantity == parset_value) ? value : quantity);
    }
  }

  removeFormTemplate(appointmentFormTemplate, index) {

    const formFieldValues = appointmentFormTemplate.get('formFieldValues').value;
    const appointmentFormTemplateArray = this.formGroup.get('appointmentFormTemplate') as FormArray;
    const notEmptyValues = formFieldValues.filter(formField => (formField.value !== null && formField.value !== ''));
    if (notEmptyValues.length) {
      const dialogRef = this.dialog.open(ModalConfirmComponent, {
        width: window.innerWidth > 960 ? '20%' : '90%',
        height: '100% - 50px',
        data: {
          head: 'Вы уверены что хотите удалить "' + 'Шаблон' + ' ' + (index + 1) + ' ' + '-' + ' ' + appointmentFormTemplate.get('formTemplate').get('name').value + '"?',
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
          appointmentFormTemplateArray.removeAt(index);
        }
      });
    } else {
      appointmentFormTemplateArray.removeAt(index);
    }
  }

  // get appointmentFormTemplate() {
  //   return this.formGroup.get('appointmentFormTemplate') as FormArray;
  // }

  formFieldErrorHandler(error) {
    let message = '';
    if (error.maxlength) {
      // { "maxlength": { "requiredLength": .., "actualLength":  ..} }
      message = 'Максимальная длина поля - ' + error.maxlength.requiredLength;
    } else if (error.max) {
      //  { "max": { "max": .., "actual": .. } }
      message = 'Верхний предел числа - ' + error.max.max;
    } else if (error.min) {
      //  { "min": { "min": .., "actual": .. } }
      message = 'Нижний предел числа - ' + error.min.min;
    }
    // else if (error.required) {
    //   //  { "required": true|false }
    //   message = 'Это поле обязательное';
    // }
    return message;
  }

  onChangeParentReference($event: any, formField: any, index: number, templateIndex: number) {
    const fb = this.formGroup.get('appointmentFormTemplate') as FormArray;
    const formFieldValues = fb.controls[templateIndex].get('formFieldValues') as FormArray;
    const formFieldValue = formFieldValues.value[index];
    const field = formFieldValues.at(index) as FormGroup;
    const filterValue = {};
    filterValue[field.get('extraData').value.parentReference.parentCode] = {id: $event.value};

    this.template = [];
    this.template.push(field.value.innerHTML);

    if (!formFieldValue.extraData.subParentReference) {
      formFieldValue.extraData.filter = filterValue;
    } else {
      formFieldValue.extraData.subParentReference.filter = filterValue;
      formFieldValue.extraData.subParentReference.value = '';
      formFieldValue.extraData.filter = null;
    }

    formFieldValue.extraData.parentReference.value = $event.value;
    formFieldValue.value = '';

    formFieldValues.removeAt(index);
    formFieldValues.insert(index, this.initFormFieldValues(formFieldValue));
  }

  onChangeSubParentReference($event: any, formField: any, index: number, templateIndex: number) {
    const fb = this.formGroup.get('appointmentFormTemplate') as FormArray;
    const formFieldValues = fb.controls[templateIndex].get('formFieldValues') as FormArray;
    const formFieldValue = formFieldValues.value[index];
    const field = formFieldValues.at(index) as FormGroup;
    const filterValue = {};
    filterValue[field.get('extraData').value.subParentReference.parentCode] = {id: $event.value};

    this.template = [];
    this.template.push(field.value.innerHTML);

    formFieldValue.extraData.filter = filterValue;
    formFieldValue.extraData.subParentReference.value = $event.value;
    formFieldValue.value = '';
    formFieldValues.removeAt(index);
    formFieldValues.insert(index, this.initFormFieldValues(formFieldValue));
  }

  onChangeMinDate($event: any, formField: any, index: number, templateIndex: number) {
    const fb = this.formGroup.get('appointmentFormTemplate') as FormArray;
    const formFieldValues = fb.controls[templateIndex].get('formFieldValues') as FormArray;
    const formFieldValue = formFieldValues.value[index];
    const date = new Date($event.value);

    if (formFieldValue.dateMaxLimit === 'gth') {
      date.setDate(date.getDate() + 1);
    }
    formFieldValue.extraData.dateMax.min = date;
    formFieldValue.dateMax = null;
    formFieldValue.dateMinTime = null;
    formFieldValue.dateMaxTime = null;

    formFieldValues.removeAt(index);
    formFieldValues.insert(index, this.initFormFieldValues(formFieldValue));
  }

  onChangeMaxDate($event: any, formField: any, index: number, templateIndex: number) {
    const fb = this.formGroup.get('appointmentFormTemplate') as FormArray;
    const formFieldValues = fb.controls[templateIndex].get('formFieldValues') as FormArray;
    const formFieldValue = formFieldValues.value[index];
    const dateMax = $event.value;
    if (!formFieldValue.dateMin) {
      return;
    }

    const dateMin = this.formTemplateService.createDate(formFieldValue.dateMin);

    if (!$event.value || dateMin.getDate() > dateMax.getDate()) {
      formFieldValue.dateMax = this.formTemplateService.createDate(
        formFieldValue.dateMin,
        formFieldValue.dateMaxLimit === 'gth' ? 1 : 0
      );

    }

    formFieldValue.dateMaxTime = null;
    formFieldValue.dateMax = typeof formFieldValue.dateMax === 'string'
      ? formFieldValue.dateMax
      : this.formTemplateService.dateToString(formFieldValue.dateMax);
    formFieldValues.removeAt(index);
    formFieldValues.insert(index, this.initFormFieldValues(formFieldValue));
  }

  onChangeDateMinTime($event: any, formField: any, index: number, templateIndex: number) {
    const fb = this.formGroup.get('appointmentFormTemplate') as FormArray;
    const formFieldValues = fb.controls[templateIndex].get('formFieldValues') as FormArray;
    const formFieldValue = formFieldValues.value[index];
    formFieldValue.dateMinTime = $event;
    formFieldValue.dateMaxTime = null;
    formFieldValues.removeAt(index);
    formFieldValues.insert(index, this.initFormFieldValues(formFieldValue));
  }

  onChangeDateMaxTime($event: any, formField: any, index: number, templateIndex: number) {
    const fb = this.formGroup.get('appointmentFormTemplate') as FormArray;
    const formFieldValues = fb.controls[templateIndex].get('formFieldValues') as FormArray;
    const formFieldValue = formFieldValues.value[index];
    if (!formFieldValue.dateMin) {
      return;
    }

    let timeMin = formFieldValue.dateMinTime ? formFieldValue.dateMinTime : '00:00';
    timeMin = timeMin.split(':');
    let dateMin = formFieldValue.dateMin.split('.');
    dateMin = new Date(+dateMin[2], +dateMin[1] - 1, +dateMin[0], timeMin[0], timeMin[1]);

    const timeMax = $event.split(':');
    let dateMax = formFieldValue.dateMax.split('.');
    dateMax = new Date(+dateMax[2], +dateMax[1] - 1, +dateMax[0], timeMax[0], timeMax[1]);

    if (dateMin > dateMax) {
      formFieldValue.dateMaxTime = formFieldValue.dateMinTime;
      formFieldValues.removeAt(index);
      formFieldValues.insert(index, this.initFormFieldValues(formFieldValue));
    }
  }

  showFormTemplates() {
    return this.authService.permissions('ROLE_DOCTOR');
  }

  temperatureValidator(group: FormGroup): { [s: string]: boolean } {
    if (group.controls['temperature_check'] && !group.controls['temperature_check'].value
      && group.controls['temperature'] && !group.controls['temperature'].value) {
      group.controls['temperature'].setErrors({required: true});
      return {'temperature': true};
    } else {
      group.controls['temperature'].setErrors(null);
    }
    return null;
  }

  weightValidator(group: FormGroup): { [s: string]: boolean } {
    if (group.controls['weight_check'] && !group.controls['weight_check'].value
      && group.controls['weight'] && !group.controls['weight'].value) {
      group.controls['weight'].setErrors({required: true});
      return {'weight': true};
    } else {
      group.controls['weight'].setErrors(null);
    }
    return null;
  }

  private getMaxQuantity(item) {
    const balance = item.get('balance').value;
    const quantity = item.get('quantity').value;
    if (quantity > balance) {
      return balance;
    }
    return quantity;
  }

  probeSamplingModal(probeSampling = null) {
    if (probeSampling) {
      probeSampling.appointment = this.model;
    }
    const dialogRef = this.dialog.open(ModalProbeSamplingFormComponent, {
      width: window.innerWidth > 960 ? '60%' : '90%',
      height: '100% - 50px',
      data: {
        appointment: this.model,
        probeSampling: probeSampling
      }
    });

    dialogRef.afterClosed().subscribe((result) => {
      if (result) {
        this.store.dispatch(new LoadGetAction({
          type: CrudType.Appointment,
          params: this.model.id,
          onSuccess: (res) => {
            if (res.response && res.status == true) {
              this.model.probeSamplings = res.response.probeSamplings;
            }
          },
        }));
      }
    });
  }

  getProbeSamplingPrice(probeSampling) {
    return ProbeSamplingComponent.getProbeSamplingAmount(probeSampling);
  }
}
