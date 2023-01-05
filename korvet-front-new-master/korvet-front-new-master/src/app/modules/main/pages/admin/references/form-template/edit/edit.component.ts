import {Component, OnChanges, OnInit, SimpleChanges, ViewEncapsulation} from '@angular/core';
import {ActivatedRoute, Router} from '@angular/router';
import {MatDialog} from '@angular/material/dialog';
import {MatSelectChange} from '@angular/material/select';
import {AbstractControl, FormArray, FormBuilder, FormControl, FormGroup, ValidatorFn, Validators} from '@angular/forms';
import {select, Store} from '@ngrx/store';
import {ReferenceItemModels} from '../../../../../../../models/reference/reference.item.models';
import {CrudType} from '../../../../../../../common/crud-types';
import {
  AppointmentFormTemplateModel,
  FormTemplateModel
} from '../../../../../../../models/form-template/form-template.models';
import {NotifyService} from '../../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../../services/breadcrumbs.service';
import {ModalConfirmComponent} from '../../../../../../shared/components/modal-confirm/modal-confirm.component';
import {HighlightTag} from 'angular-text-input-highlight';
import {FormTemplateFieldModel} from '../../../../../../../models/form-template/form-template-field.models';
import {FormTemplateService} from '../../../../../../../services/form-template.service';
import {References} from '../../../../../../../common/references';
import {TemplateReferenceModel} from '../../../../../../../models/template/template-reference.models';
import {CdkDragDrop, moveItemInArray} from '@angular/cdk/drag-drop';
import {ScrollDispatcher} from '@angular/cdk/scrolling';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction, LoadCreateAction, LoadGetAction, LoadPatchAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelCreateLoading, getCrudModelGetLoading, getCrudModelCreatePatchLoading} from 'src/app/api/api-connector/crud/crud.selectors';

declare var $: any;

@Component({
  selector: 'app-edit',
  templateUrl: './edit.component.html',
  styleUrls: ['./edit.component.css'],
  encapsulation: ViewEncapsulation.None
})
export class EditComponent extends ReferenceItemModels implements OnInit, OnChanges {

  type = CrudType.FormTemplate;
  formGroup: FormGroup;
  properties: FormArray;
  formFields: FormArray;
  fields: FormArray;
  crudType = CrudType;
  fieldTypeList;
  actionItems;
  tags: HighlightTag[] = [];
  item: FormTemplateModel;
  References = References;
  templateReferences: TemplateReferenceModel[];
  referenceViewTypes = [
    {value: 'select', title: 'Выпадающий список'},
    {value: 'radio', title: 'Переключатель'},
    {value: 'checkbox', title: 'Чекбокс'},
  ];
  _showFloatInput = [];
  appointmentFormTemplates: AppointmentFormTemplateModel[];
  limitTypes = [
    {value: 'any', title: 'Любая'},
    {value: 'current', title: 'Только текущая'},
    {value: 'gth', title: 'Текущая или больше'},
    {value: 'lth', title: 'Текущая или меньше'},
  ];
  limitMaxTypes = [
    {value: 'gth', title: 'Больше минимальной'},
    {value: 'gth_eq', title: 'Больше или равна минимальной'},
    // {value: 'any', title: 'Любая'},
  ];
  limitRanges = [
    {value: '', title: 'Без срока'},
    {value: 'day', title: 'День'},
    {value: 'week', title: 'Неделя'},
    {value: 'month', title: 'Месяц'},
    {value: 'quarter', title: 'Квартал'},
    {value: 'year_half', title: 'Полгода'},
    {value: 'year', title: 'Год'},
  ];
  public needSave = false;
  public blockCloneBtn = false;
  public showFixedButton = false;
  protected titleName = 'Шаблон';
  protected listNavigate = ['admin', 'references', 'form-template'];
  private template: any;

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService,
    protected formTemplateService: FormTemplateService,
    private dialog: MatDialog,
    private fb: FormBuilder,
    private scrollDispatcher: ScrollDispatcher
  ) {
    super(CrudType.FormTemplate, FormTemplateModel);

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.FormFieldType,
      params: {order: {surname: 'ASC'}},
      onSuccess: response => {
        this.fieldTypeList = response.response.items;
      }
    }));

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.Action,
      params: {filter: {entityClass: {'!name': null}}},
      onSuccess: response => {
        this.actionItems = response.response.items;
      }
    }));

    this.store.dispatch(new LoadGetListAction({
      type: CrudType.TemplateReference,
      params: {order: {name: 'ASC'}},
      onSuccess: response => {
        this.templateReferences = response.response.items;
      }
    }));
  }

  ngOnInit() {
    super.ngOnInit();

    this.setBreadcrumbs();

    if (this.id !== 'create') {
      this.store.dispatch(new LoadGetListAction({
        type: CrudType.AppointmentFormTemplate,
        params: {filter: {formTemplate: {id: this.id}}},
        onSuccess: response => {
          if (response.response) {
            this.appointmentFormTemplates = response.response.items;
          }
        }
      }));
    }

    this.scrollDispatcher.scrolled().subscribe(x => {
      const offset = $('#fixed-field').offset();
      this.showFixedButton = $(window).scrollTop() > (offset.top + 150);
    });
  }

  createFormField(formField?): FormGroup {
    if (!formField) {
      formField = {};
    }

    return this.fb.group({
      id: [formField.id],
      name: [formField.name],
      innerHTML: this.template.shift(),
      type: this.fb.group({
        code: [formField.formTemplateField.type.code],
        name: [formField.formTemplateField.type.name],
      }),
      required: [formField.isRequired]
    });
  }

  createTemplateField(formField?, index = null): FormGroup {
    if (!formField) {
      formField = {};
    }
    const type = formField.type ? formField.type : formField;
    const propertiesList = type.properties;
    const properties = formField.properties ? formField.properties : [];
    let sort = formField.sort >= 0 ? formField.sort : this.formFields.length + 1;

    if (index !== null) {
      sort = index + 1;
    }

    const multiSelect = this.formTemplateService.findProperty(properties, 'multi_select');
    let crudType = this.formTemplateService.findProperty(properties, 'entity');
    const viewType = this.formTemplateService.findProperty(properties, 'entity_view_type');
    const defaultReference = crudType ? References[crudType.value] : null;

    let dateLimit = this.formTemplateService.findProperty(properties, 'date_limit');
    if (dateLimit) {
      dateLimit = dateLimit.value ? dateLimit.value : dateLimit.defaultValue;
    }

    let dateMinLimit = this.formTemplateService.findProperty(properties, 'date_min_limit');
    if (dateMinLimit) {
      dateMinLimit = dateMinLimit.value ? dateMinLimit.value : dateMinLimit.defaultValue;
    }

    let dateRange = this.formTemplateService.findProperty(properties, 'date_range');
    if (dateRange) {
      dateRange = dateRange.value !== '' ? dateRange.value : dateRange.defaultValue;
    }

    let dateMinRange = this.formTemplateService.findProperty(properties, 'date_min_range');
    if (dateMinRange) {
      dateMinRange = dateMinRange.value !== '' ? dateMinRange.value : dateMinRange.defaultValue;
    }

    let fieldTitle = this.formTemplateService.findProperty(properties, 'title');
    if (fieldTitle) {
      fieldTitle = fieldTitle.value !== '' ? fieldTitle.value : fieldTitle.defaultValue;
    }

    const indent = this.formTemplateService.findProperty(properties, 'indent');
    if (indent) {
    }


    if (defaultReference && defaultReference.parent && !formField.parentReference) {
      const parentReference = References[defaultReference.parent];
      const parentData = {
        crudType: parentReference.crudType,
        parentCode: parentReference.parentCode,
        title: parentReference.name
      };
      formField.parentReference = parentData;
      if (parentReference.parent) {
        const subParentReference = References[parentReference.parent];
        formField.parentReference = {
          crudType: subParentReference.crudType,
          parentCode: subParentReference.parentCode,
          title: subParentReference.name
        };
        formField.subParentReference = parentData;
      }
    }

    if (type.code === 'template_reference') {
      crudType = CrudType.TemplateReferenceValues;
    } else if (crudType) {
      crudType = crudType.value;
    }

    return this.fb.group({
      type: this.fb.group({
        id: [type.id, [Validators.required]],
        code: [type.code],
        name: [type.name],
        properties: type.properties ? this.fb.array(type.properties.map(item => {
          return this.fb.group({
            id: item.id,
            name: item.name,
            code: item.code,
            description: item.description,
          });
        })) : this.fb.array([]),
      }),
      properties: this.fb.array(this.createFieldProperty(properties, propertiesList)),
      sort: [sort],
      description: [formField.description ? formField.description : ''],
      id: [formField.id ? formField.id : ''],
      name: [formField.name ? formField.name : null],
      multiSelect: multiSelect ? multiSelect.value : '0',
      parentRefName: defaultReference ? defaultReference.parent : '',
      crudType: crudType ? crudType : '',
      parentReference: !formField.parentReference ? null : this.fb.group({
        crudType: formField.parentReference.crudType,
        parentCode: formField.parentReference.parentCode,
        filter: formField.parentReference.filter ? formField.parentReference.filter : null,
        value: formField.parentReference.value ? formField.parentReference.value : null,
        title: References[formField.parentReference.crudType].name,
      }),
      subParentReference: !formField.subParentReference ? null : this.fb.group({
        crudType: formField.subParentReference.crudType,
        parentCode: formField.subParentReference.parentCode,
        filter: formField.subParentReference.filter ? formField.subParentReference.filter : null,
        value: formField.subParentReference.value ? formField.subParentReference.value : null,
        title: References[formField.subParentReference.crudType].name,
      }),
      filter: formField ? formField.filter : null,
      viewType: viewType ? viewType.value : 'select',
      title: (crudType && References[crudType.value]) ? References[crudType.value].name : null,

      showTitle: fieldTitle,
      showTitleValue: dateRange && dateRange !== '',

      showDateRange: dateLimit && !['any', 'current'].includes(dateLimit),
      showDateRangeValue: dateRange && dateRange !== '',

      showDateMinRange: dateMinLimit && !['any', 'current'].includes(dateMinLimit),
      showDateMinRangeValue: dateMinRange && dateMinRange !== '',
    });
  }

  createFieldProperty(properties, propertiesList): any {
    const result = [];
    propertiesList = propertiesList ? propertiesList : [];
    for (const property of propertiesList) {
      const propertyCurrent = properties.filter(prop => prop.formFieldProperty && (prop.formFieldProperty.id === property.id))[0];
      let value: any;

      if (propertyCurrent && !['entity_default_value'].includes(propertyCurrent.formFieldProperty.code)) {
        value = propertyCurrent.value;
      } else if (propertyCurrent && propertyCurrent.value) {
        value = Array.isArray(propertyCurrent.value) ? propertyCurrent.value : JSON.parse(propertyCurrent.value);
      } else if (!propertyCurrent) {
        value = property.value ? property.value.toString() : '';
      }

      if (propertyCurrent
        && propertyCurrent.formFieldProperty.code === 'float'
        && +propertyCurrent.value > 0) {
        this._showFloatInput[propertyCurrent.formField.id] = true;
      }

      if (propertyCurrent
        && propertyCurrent.formFieldProperty.code === 'float'
        && +propertyCurrent.value > 0) {
        this._showFloatInput[propertyCurrent.formField.id] = true;
      }

      if (property.code === 'multi_select') {
        value = +value;
      }

      if (!propertyCurrent && ['date_limit', 'date_min_limit', 'date_max_limit'].includes(property.code)) {
        value = property.defaultValue;
      }

      if (['date_range_value', 'date_min_range_value', 'date_max_range_value'].includes(property.code)) {
        value = value ? value : 1;
      }

      const formGroup = this.fb.group({
        formFieldProperty: this.fb.group({
          id: [property.id],
          code: [property.code],
          name: [property.name],
          description: [property.description],
        }),
        value: [value]
      });

      if (propertyCurrent && propertyCurrent.id) {
        formGroup.addControl('id', new FormControl(propertyCurrent.id));
      }

      result.push(formGroup);
    }
    return result;
  }

  previewForm(scrollTo?: HTMLElement): void {
    this.loading$ = this.store.pipe(select(getCrudModelCreateLoading, {type: CrudType.FormTemplatePreview}));
    this.store.dispatch(new LoadCreateAction({
      type: CrudType.FormTemplatePreview,
      params: {
        id: this.formGroup.get('id').value,
        template: this.prepareTemplate(this.formGroup.get('template').value),
        name: this.formGroup.get('name').value,
        appointmentCount: this.item.appointmentCount,
        formFields: this.formGroup.get('formFields').value.map(item => {
          return item;
        }),
      },
      onSuccess: response => {
        if (scrollTo) {
          scrollTo.scrollIntoView();
        }
        this.tags = [];
        // this.item = response.response;
        this.item.templateArr = this.formTemplateService.createTemplateArr(response.response.template);
        if (response.response.fields && response.response.fields.length) {
          this.item.fields = response.response.fields.map(field => {
            const entityViewType = this.formTemplateService.findProperty(field.formTemplateField.properties, 'entity_view_type');
            if (entityViewType && entityViewType.value) {
              field.entityViewType = entityViewType.value;
            }

            const multiSelect = this.formTemplateService.findProperty(field.formTemplateField.properties, 'multi_select');
            if (entityViewType && multiSelect.value) {
              field.multiSelect = +multiSelect.value > 0;
            }

            const defaultValue = this.formTemplateService.findProperty(field.formTemplateField.properties, 'entity_default_value');
            if (defaultValue && defaultValue.value) {
              field.defaultValue = defaultValue.value;
            }

            if (['date', 'multi_date'].includes(field.formTemplateField.type.code)) {
              field.formTemplateField.extraData = this.formTemplateService.getDateExtraData(
                field.formTemplateField.properties,
                field.formTemplateField.type.code
              );
            }

            if (['template_reference'].includes(field.formTemplateField.type.code)) {
              field.crudType = CrudType.TemplateReferenceValues;
            }

            return field;
          });
        } else {
          delete this.item.fields;
        }
      },
      onError: error => {
        this.handleError(error);
        window.scrollTo(0, 0);
      }
    }));

  }

  previewFormErr() {
    this.notify.handleMessage('Вы не добавили поле', 'warning');
    const addFieldButton = $('#addFieldButton');
    let xCount = 0;
    const btnRedInterval = window.setInterval(() => {
      addFieldButton.toggleClass('color-red');
      if (++xCount === 10) {
        addFieldButton.removeClass('color-red');
        window.clearInterval(btnRedInterval);
      }
    }, 500);
  }

  cloneForm() {
    this.blockCloneBtn = true;
    this.loading$ = this.store.pipe(select(getCrudModelGetLoading, {type: CrudType.FormTemplateClone}));
    this.store.dispatch(new LoadGetAction({
      type: CrudType.FormTemplateClone,
      params: {id: this.id},
      onSuccess: response => {
        if (response.response.id) {
          this.router.navigateByUrl('/', {skipLocationChange: true}).then(() => {
            this.router.navigate(['admin', 'references', 'form-template', response.response.id]).then();
          });
        }
      },
      onError: error => {
        this.blockCloneBtn = false;
        this.notify.handleMessage('Ошибка при клонировании шаблона', 'danger');
      }
    }));
  }

  addField() {
    this.needSave = true;
    this.formFields.push(this.createTemplateField());
    this.item.formFields = [...this.formFields.value] as FormTemplateFieldModel[];
  }

  addFieldProperty(formField, fieldTypeId, index) {
    if (!formField.value.properties.length) {
      const formType = this.fieldTypeList.filter(fieldType => fieldType.id === fieldTypeId)[0];
      formType.sort = formField.value.sort;
      const field = this.createTemplateField(formType);
      console.log(field, formType);
      this.formFields.setControl(index, field);
    }
  }

  removeField(formField, index) {
    this.needSave = true;
    // Если значения в характеристиках уже есть, выбрасываем попап с предупреждением
    const properties = formField.get('properties').value;
    const notEmptyProperties = properties.filter(property => (property.value !== null && property.value !== ''));
    if (notEmptyProperties.length) {

      const dialogRef = this.dialog.open(ModalConfirmComponent, {
        data: {
          head: 'Вы уверены что хотите удалить "' + formField.get('type').get('name').value + '"?',
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
          const fb = this.formGroup.get('formFields') as FormArray;
          delete this.item.formFields[index];
          fb.removeAt(index);
          // Пересчет индексов
          this.refreshIndexFormFields();
        }
        this.needSave = false;
      });
    } else {
      this.formFields.removeAt(index);
      const fb = this.formGroup.get('formFields') as FormArray;
      delete this.item.formFields[index];
      fb.removeAt(index);
      this.needSave = false;
    }

    // Пересчет индексов
    this.refreshIndexFormFields();
  }

  toggleOnChange(show, element) {
    (show !== 'true') ? $(element).show() : $(element).hide();
  }

  onChangeMultiSelect(value, index, formField) {
    formField.value.multiSelect = value !== 'false' ? '0' : '1';

    formField.value.properties.map(property => {
      if (property.formFieldProperty.code === 'multi_select') {
        property.value = formField.value.multiSelect;
      } else if (property.formFieldProperty.code === 'entity_default_value') {
        property.value = null;
      }
      return property;
    });

    this.updateFormField(formField.value, index);
  }

  submit($event?, value?: any): void {
    if ($event) {
      $event.preventDefault();
    }

    if (this.formGroup.valid) {
      const model = value ? value : {...this.formGroup.value} as FormTemplateModel;

      // Пересчет индексов
      this.refreshIndexFormFields(model);

      if (this.item.id) {
        model.template = this.prepareTemplate(model.template);
        model.active = !model.active;
        model.formFields.map(formField => {
          formField.extraData = {
            parentReference: formField.parentReference ? formField.parentReference : null,
            subParentReference: formField.subParentReference ? formField.subParentReference : null,
            crudType: formField.crudType ? formField.crudType : null,
            viewType: formField.viewType ? formField.viewType : null,
            parentRefName: formField.parentRefName ? formField.parentRefName : null,
            filter: formField.filter ? formField.filter : null,
          };
          return formField;
        });
      }
      const action = this.item.id ? LoadPatchAction : LoadCreateAction;

      model.template = this.convertTemplate(model);
      this.loading$ = this.store.pipe(select(this.item.id ? getCrudModelCreatePatchLoading : getCrudModelCreateLoading, {type: this.type}));
      this.store.dispatch(new action({
        type: this.type,
        params: model,
        onSuccess: (res) => {
          this.needSave = false;
          if (res.status === true && res.response && res.response.id) {
            const n = JSON.parse(JSON.stringify(this.listNavigate));
            if (res.response.id && action === LoadCreateAction) {
              n.push(res.response.id);
            }

            if (this.id === 'create') {
              this.router.navigateByUrl('/', {skipLocationChange: true}).then(() => {
                this.notify.handleMessage('Шаблон успешно создан', 'success');
                this.router.navigate(n).then();
              });
            } else {
              this.router.navigateByUrl('/', {skipLocationChange: true}).then(() => {
                this.notify.handleMessage('Шаблон успешно сохранен', 'success');
                this.router.navigate(['/admin/references/form-template/', res.response.id]).then();
              });
            }
          }
        },
        onError: error => {
          this.handleError(error);
        }
      }));
    } else {
      this.notify.handleMessage('Заполните обязательные поля', 'warning');
      this.showError = true;
    }
  }

  errorsHighlight(items) {
    // https://github.com/mattlewis92/angular-text-input-highlight/blob/master/demo/demo.component.ts
    if (items && items.length) {
      this.tags = [];
      let templateValue = this.formGroup.get('template').value;
      items.forEach(item => {
        const regExp = new RegExp(item, 'g');
        const mention = regExp.exec(templateValue);
        this.tags.push({
          indices: {
            start: mention.index,
            end: mention.index + mention[0].length,
          },
          cssClass: 'bg-pink-dark',
          data: mention[1]
        });
        templateValue = templateValue.slice(0, mention.index) + new Array(mention[0].length).fill(' ').join('') + templateValue.slice(mention.index + mention[0].length);
      });
    }
  }

  setBreadcrumbs() {
    if (this.brdSrv) {
      this.brdSrv.deleteIndex(2);
    }
  }

  handleError(error) {
    if (error.errors && error.errors.length) {
      const errors = JSON.parse(error.errors[0].relatedField);
      if (Array.isArray(errors) && errors.length) {
        const items = [];
        errors.forEach(el => {
          items.push(el.item);
          this.notify.handleMessage(el.item + ': ' + el.message, 'danger');
        });
        this.errorsHighlight(items);
      }
    }
  }

  prepareTemplate(template: string) {
    // Удаляет все html теги, кроме <strong></strong>
    return template
      .replace(
        new RegExp(/<(?!strong\/?)(?!\/strong\/?)[^>]+>/g),
        ''
      );
  }

  ngOnChanges(changes: SimpleChanges): void {
    if (changes) {

    }
  }

  getNumberMin(property: any, s) {
    const matrix = this.getNumberMatrix(s);
    if (property.value.formFieldProperty.code === 'max_number_value') {
      return '';
    } else if (property.value.formFieldProperty.code === 'min_number_value') {
      return matrix.getMin();
    }
    return '';
  }

  getNumberStep(property: any, s) {
    const matrix = this.getNumberMatrix(s);
    return matrix.getStep();
  }

  getNumberMatrix(s) {
    const m = {
      minValue: s[0].value.value,
      maxValue: s[1].value.value,
      isFloat: s[2].value.value === true,
      countSymbols: s[3].value.value,
      isNegative: +s[4].value.value > 0,
      name: '',
      getMin: function () {
        if (this.isNegative) {
          return NaN;
        }
        return 0;
      },
      getStep: function () {
        if (this.isFloat) {
          this.countSymbols = this.countSymbols || 0;
          if (this.countSymbols > 0) {
            return parseFloat('0.' + '0'.repeat(this.countSymbols - 1) + '1');
          }
          return 1;
        }
        return 1;
      }
    };
    m.name = 'min';
    s[0].controls.value.matrix = {...m};
    m.name = 'max';
    s[1].controls.value.matrix = {...m};
    s[0].controls.value.setValidators(this.number({min: m.isNegative ? NaN : m.getMin(), step: m.getStep(), max: NaN}));
    if (s[0].controls.value.value) {
      s[0].controls.value.markAsTouched();
      s[0].controls.value.updateValueAndValidity();
    }
    if (s[1].controls.value.value) {
      s[1].controls.value.setValidators(this.number({min: m.getMin(), step: m.getStep(), max: NaN}));
      s[1].controls.value.markAsTouched();
    } else {
      s[1].controls.value.clearValidators();
    }
    s[1].controls.value.updateValueAndValidity();
    return m;
  }

  number(prms = {min: null, step: null, max: NaN}): ValidatorFn {
    return (control: any): { [key: string]: boolean } => {

      /*if (isPresent(Validators.required(control))) {
        return null;
      }*/

      const val: number = control.value;
      const m = control.matrix;
      if (m.name === 'min' && m.maxValue > 0 && m.minValue > m.maxValue) {
        return {'number': true};
      }
      if (m.name === 'max' && m.minValue > 0 && m.minValue > m.maxValue) {
        return {'number': true};
      }

      if (!isNaN(prms.step) && !isNaN(val)) {
        if (prms.step % 1 !== 0 && (val || '').toString().indexOf('.') < 0) {
          // return {'number': true};
        } else if (prms.step % 1 === 0 && (val || '').toString().indexOf('.') > -1) {
          return {'number': true};
        }
      }

      if (isNaN(val) || /\D\,/.test((val || '').toString())) {
        return {'number': true};
      } else if (!isNaN(prms.min) && !isNaN(prms.max)) {

        return val < prms.min || val > prms.max ? {'number': true} : null;
      } else if (!isNaN(prms.min)) {
        return val < prms.min ? {'number': true} : null;
      } else if (!isNaN(prms.max)) {
        return val > prms.max ? {'number': true} : null;
      } else {
        return null;
      }
    };
  }

  onChangeTemplateReference($event, field, index) {
    const formField = {...field.value} as FormTemplateFieldModel;
    formField.crudType = CrudType.TemplateReferenceValues;
    formField.filter = {
      templateReference: {id: $event.value}
    };
    this.updateFormField(formField, index);
  }

  onChangeReference($event, field, index) {
    const formField = {...field.value} as FormTemplateFieldModel;

    formField.crudType = '';
    formField.parentReference = null;
    formField.subParentReference = null;

    let parent = null;
    const current = References[$event.value];
    const parentReference = {crudType: '', parentCode: '', filter: null, value: null};
    const subParentReference = {crudType: '', parentCode: '', filter: null, value: null};

    if (current.parent) {
      parent = References[current.parent];
      parentReference.crudType = current.parent;
      parentReference.parentCode = current.parentCode;
      formField.parentReference = parentReference;
    } else {
      formField.crudType = current.crudType;
      formField.filter = null;
    }

    if (parent && parent.parent) {
      subParentReference.crudType = parent.parent;
      subParentReference.parentCode = parent.parentCode;
      // Поднятие родительского справочника по иерархии.
      // Родитель -> СубРодитель -> Ребенок (Целевой)
      formField.subParentReference = parentReference;
      formField.parentReference = subParentReference;
    }

    formField.properties = formField.properties.map(item => {
      if (item.formFieldProperty.code === 'entity_default_value') {
        item.value = '';
      } else if (item.formFieldProperty.code === 'entity_view_type' && !!parent) {
        item.value = 'select';
      } else if (item.formFieldProperty.code === 'multi_select' && !!parent) {
        item.value = '';
      }
      return item;
    });
    this.updateFormField(formField, index);
  }

  onChangeParentReference($event, formField, index) {
    const filter = {};
    const field = {...formField.value} as FormTemplateFieldModel;
    const parentCode = References[field.parentReference.crudType].code;
    field.parentReference.value = $event.value;
    field.properties.map(property => {
      if (property.formFieldProperty.code === 'entity_default_value') {
        property.value = null;
      }
    });
    filter[parentCode] = {id: $event.value};
    if (field.subParentReference) {
      field.subParentReference.filter = filter;
      field.subParentReference.value = null;
    } else {
      field.filter = filter;
    }
    this.updateFormField(field, index);
  }

  onChangeSubParentReference($event, formField, index) {
    const filter = {};
    const field = {...formField.value} as FormTemplateFieldModel;
    field.subParentReference.value = $event.value;
    const parentCode = References[field.subParentReference.crudType].code;
    filter[parentCode] = {id: $event.value};
    field.filter = filter;
    field.properties.map(property => {
      if (property.formFieldProperty.code === 'entity_default_value') {
        property.value = null;
      }
    });
    this.updateFormField(field, index);
  }

  onChangeReferenceViewType($event, formField, index) {
    formField.value.properties.map(property => {
      if (property.formFieldProperty.code === 'entity_default_value') {
        property.value = '';
      }
      if (property.formFieldProperty.code === 'entity_view_type') {
        property.value = $event.value;
      }
      if (property.formFieldProperty.code === 'multi_select') {
        property.value = $event.value === 'checkbox' ? '1' : '0';
      }
      return property;
    });

    this.updateFormField(formField.value, index);
  }

  updateFormField(formField: FormTemplateFieldModel, index: number) {
    const fb = this.formGroup.get('formFields') as FormArray;
    this.item.formFields[index] = formField;
    fb.removeAt(index);
    fb.insert(index, this.createTemplateField(formField));
  }

  onChangeDateLimit($event: MatSelectChange, formField, index: number, code = '') {
    formField.value.properties.map(property => {
      if (property.formFieldProperty.code === 'date_min_limit' && code === 'date_min_limit') {
        property.value = $event.value;
      }
      if (property.formFieldProperty.code === 'date_max_limit' && code === 'date_max_limit') {
        property.value = $event.value;
      }
      if (property.formFieldProperty.code === 'date_min_range' && code === 'date_min_limit') {
        property.value = '';
      }
      if (property.formFieldProperty.code === 'date_min_range_value' && code === 'date_min_limit') {
        property.value = '';
      }
      if (property.formFieldProperty.code === 'date_limit') {
        property.value = $event.value;
      }
      if (property.formFieldProperty.code === 'date_range') {
        property.value = '';
      }
      if (property.formFieldProperty.code === 'date_range_value') {
        property.value = '';
      }
      return property;
    });
    this.updateFormField(formField.value, index);
  }

  onChangeDateRange($event: MatSelectChange, formField: AbstractControl, index: number) {
    formField.value.properties.map(property => {
      if (property.formFieldProperty.code === 'date_range') {
        property.value = $event.value;
      }
      return property;
    });
    this.updateFormField(formField.value, index);
  }


  GetPrecision(floatCheck, inputPresition) {
    return (document.getElementById(floatCheck) as HTMLInputElement).checked ? (document.getElementById(inputPresition) as HTMLInputElement).value : 0;
  }

  GetSigned(signedCheck) {
    return (document.getElementById(signedCheck) as HTMLInputElement).checked;
  }

  dropped(event: CdkDragDrop<string[]>) {
    moveItemInArray(
      this.formFields.controls,
      event.previousIndex,
      event.currentIndex
    );

    this.refreshIndexFormFields();

    this.needSave = true;
  }

  // Пересчет индексов
  // tslint:disable-next-line:no-unnecessary-initializer
  refreshIndexFormFields(model = undefined) {
    if (typeof model === 'undefined') {
      this.formFields.controls.map((item, i) => {
        item.get('sort').setValue(i + 1);
        this.formGroup.value.formFields.map((val, k) => {
          if (item.get('id').value === val.id) {
            val.sort = item.get('sort').value;
          }
          return val;
        });
        return item;
      });
    } else {
      if (model.formFields !== undefined) {
        model.formFields.map((v, i) => {
          v.sort = i + 1;

          return v;
        });
      }
    }
  }

  addClass(event): void {
    if ($(event.target).hasClass('form-wr')) {
      $(event.target).find('.drag-icon').addClass('active');
    }
  }

  removeClass(event): void {
    if ($(event.target).hasClass('form-wr')) {
      $(event.target).find('.drag-icon').removeClass('active');
    }
  }

  convertTemplate(model) {
    let template = '';
    if (model.formFields !== undefined) {
      model.formFields.map((f, i) => {
        i++;
        if (f.type.code === 'title') {
          template += '\n<strong>' + f.name + ':</strong><br><br>';
        } else {
          template += f.name + ': {{{' + i + '-' + f.sort + '}}}\n';
        }
      });
    }
    return template;
  }

  protected setModel() {
    if (this.item.template) {
      this.template = this.formTemplateService.createTemplateArr(this.item.template);
    }

    let formFields = [];
    if (this.item.formFields) {
      this.item.formFields.map((formField, index) => {
        const extraData = formField.extraData ? formField.extraData : null;
        if (extraData) {
          formField.parentReference = extraData.parentReference ? extraData.parentReference : null;
          formField.subParentReference = extraData.subParentReference ? extraData.subParentReference : null;
          formField.crudType = extraData.crudType ? extraData.crudType : null;
          formField.viewType = extraData.viewType ? extraData.viewType : null;
          formField.parentRefName = extraData.parentRefName ? extraData.parentRefName : null;
          formField.filter = extraData.filter ? extraData.filter : null;
        }
        return formField;
      });
      formFields = this.item.formFields.map(formField => {
        return this.createTemplateField(formField);
      });
    }
    this.formFields = this.fb.array(formFields);

    let fields = [];
    if (this.item.fields) {
      fields = this.item.fields.map((field, index) => {

        const extraData = field.formTemplateField.extraData ? field.formTemplateField.extraData : null;
        if (extraData) {

          if (this.item.fields[index]) {
            this.item.fields[index].parentReference = extraData.parentReference ? extraData.parentReference : null;
            this.item.fields[index].subParentReference = extraData.subParentReference ? extraData.subParentReference : null;
            this.item.fields[index].crudType = extraData.crudType ? extraData.crudType : null;
            this.item.fields[index].viewType = extraData.viewType ? extraData.viewType : null;
            this.item.fields[index].parentRefName = extraData.parentRefName ? extraData.parentRefName : null;
            this.item.fields[index].filter = extraData.filter ? extraData.filter : null;
          }
        }

        return this.createFormField(field);
      });
    }
    this.fields = this.fb.array(fields);

    if (this.item.id) {
      this.formGroup = this.fb.group({
        id: [this.item.id],
        name: [this.item.name, [Validators.required]],
        template: [this.item.template ? this.item.template : ''],
        // active = false - шаблон в архиве
        active: [!this.item.active],
        formFields: this.formFields,
        fields: this.fields,
      });
    } else {
      this.formGroup = this.fb.group({
        name: [this.item.name, [Validators.required]]
      });
    }
  }
}

