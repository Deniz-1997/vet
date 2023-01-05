import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {RolesGuard} from 'src/app/api/api-menu/roles.guard';
import {MainComponent} from './main.component';

const routes: Routes = [
  {
    path: '',
    canActivate: [RolesGuard],
    data: {title: 'Справочники', 'breadcrumb': 'Справочники'},
    component: MainComponent
  },
  {
    path: 'owner-activity',
    canActivate: [RolesGuard],
    data: {title: 'Виды деятельности владельцев', 'breadcrumb': 'Виды деятельности владельцев'},
    loadChildren: () => import('./owner-activity/main.module').then(m => m.MainModule)
  },
  {
    path: 'breed',
    canActivate: [RolesGuard],
    data: {title: 'Породы животных', 'breadcrumb': 'Породы животных'},
    loadChildren: () => import('./breed/main.module').then(m => m.MainModule)
  },
  {
    path: 'pets-type',
    canActivate: [RolesGuard],
    data: {title: 'Виды животных', 'breadcrumb': 'Виды животных'},
    loadChildren: () => import('./pets-type/main.module').then(m => m.MainModule)
  },
  {
    path: 'pet-lear',
    canActivate: [RolesGuard],
    data: {title: 'Масть животных', 'breadcrumb': 'Масть животных'},
    loadChildren: () => import('./pet-lear/main.module').then(m => m.MainModule)
  },
  {
    path: 'owner-legal-form',
    canActivate: [RolesGuard],
    data: {title: 'Правовые формы юр. лиц', 'breadcrumb': 'Правовые формы юр. лиц'},
    loadChildren: () => import('./owner-legal-form/main.module').then(m => m.MainModule)
  },
  {
    path: 'event-status',
    canActivate: [RolesGuard],
    data: {title: 'Статусы мероприятий', 'breadcrumb': 'Статусы мероприятий'},
    loadChildren: () => import('./event-status/main.module').then(m => m.MainModule)
  },
  {
    path: 'pet-identifier-type',
    canActivate: [RolesGuard],
    data: {title: 'Типы идентификаторов животных', 'breadcrumb': 'Типы идентификаторов животных'},
    loadChildren: () => import('./pet-identifier-type/main.module').then(m => m.MainModule)
  },
  {
    path: 'pet-aggressive-type',
    canActivate: [RolesGuard],
    data: {title: 'Степень агрессивности животного', 'breadcrumb': 'Степень агрессивности животного'},
    loadChildren: () => import('./pet-aggressive-type/main.module').then(m => m.MainModule)
  },
  {
    path: 'event-type',
    canActivate: [RolesGuard],
    data: {title: 'Типы мероприятий', 'breadcrumb': 'Типы мероприятий'},
    loadChildren: () => import('./event-type/main.module').then(m => m.EventTypeModule)
  },
  {
    path: 'file-type',
    canActivate: [RolesGuard],
    data: {title: 'Виды файлов', 'breadcrumb': 'Виды файлов'},
    loadChildren: () => import('./file-type/main.module').then(m => m.MainModule)
  },
  {
    path: 'animal-death',
    canActivate: [RolesGuard],
    data: {title: 'Причины смерти', 'breadcrumb': 'Причины смерти'},
    loadChildren: () => import('./animal-death/main.module').then(m => m.MainModule)
  },
  {
    path: 'profession',
    canActivate: [RolesGuard],
    data: {title: 'Специальности', 'breadcrumb': 'Специальности'},
    loadChildren: () => import('./profession/main.module').then(m => m.MainModule)
  },
  {
    path: 'appointment-status',
    canActivate: [RolesGuard],
    data: {title: 'Статусы приемов', 'breadcrumb': 'Статусы приемов'},
    loadChildren: () => import('./appointment-status/main.module').then(m => m.MainModule)
  },
  {
    path: 'print-forms',
    canActivate: [RolesGuard],
    data: {title: 'Печатные формы', 'breadcrumb': 'Печатные формы'},
    loadChildren: () => import('./print-forms/main.module').then(m => m.MainModule)
  },
  {
    path: 'action',
    canActivate: [RolesGuard],
    data: {title: 'Действия', 'breadcrumb': 'Действия'},
    loadChildren: () => import('./action/main.module').then(m => m.MainModule)
  },
  {
    path: 'action-group',
    data: {title: 'Группы действий', 'breadcrumb': 'Группы действий'},
    loadChildren: () => import('./action-group/main.module').then(m => m.MainModule)
  },
  {
    path: 'icon',
    canActivate: [RolesGuard],
    data: {title: 'Иконки', 'breadcrumb': 'Иконки'},
    loadChildren: () => import('./icon/main.module').then(m => m.MainModule)
  },
  {
    path: 'contractor',
    canActivate: [RolesGuard],
    data: {title: 'Контрагенты', 'breadcrumb': 'Контрагенты'},
    loadChildren: () => import('./contractor/main.module').then(m => m.MainModule)
  },
  {
    path: 'vaccination-type',
    canActivate: [RolesGuard],
    data: {title: 'Вакцины', 'breadcrumb': 'Вакцины'},
    loadChildren: () => import('./vaccination-type/main.module').then(m => m.MainModule)
  },
  {
    path: 'sterilization-type',
    canActivate: [RolesGuard],
    data: {title: 'Виды стерилизации', 'breadcrumb': 'Виды стерилизации'},
    loadChildren: () => import('./sterilization-type/main.module').then(m => m.MainModule)
  },
  {
    path: 'shelter',
    canActivate: [RolesGuard],
    data: {title: 'Приют', 'breadcrumb': 'Приют'},
    loadChildren: () => import('./shelter/main.module').then(m => m.MainModule)
  },
  {
    path: 'tag-form',
    canActivate: [RolesGuard],
    data: {title: 'Формы бирки', 'breadcrumb': 'Формы бирки'},
    loadChildren: () => import('./tag-form/main.module').then(m => m.MainModule)
  },
  {
    path: 'tag-color',
    canActivate: [RolesGuard],
    data: {title: 'Цвета бирки', 'breadcrumb': 'Цвета бирки'},
    loadChildren: () => import('./tag-color/main.module').then(m => m.MainModule)
  },
  {
    path: 'stock',
    canActivate: [RolesGuard],
    data: {title: 'Склады', 'breadcrumb': 'Склады'},
    loadChildren: () => import('./stock/main.module').then(m => m.MainModule)
  },
  {
    path: 'type-vet-passport',
    canActivate: [RolesGuard],
    data: {title: 'Типы ветеринарных паспортов', 'breadcrumb': 'Типы ветеринарных паспортов'},
    loadChildren: () => import('./type-vet-passport/main.module').then(m => m.MainModule)
  },
  // not use
  {
    path: 'owner-status',
    data: {title: 'Статусы владельцев', 'breadcrumb': 'Статусы владельцев'},
    loadChildren: () => import('./owner-status/main.module').then(m => m.MainModule)
  },
  {
    path: 'appointment-type',
    data: {title: 'Типы обращений', 'breadcrumb': 'Типы обращений'},
    loadChildren: () => import('./appointment-type/main.module').then(m => m.MainModule)
  },
  {
    path: 'reference-manufacturer',
    data: {title: 'Производитель', 'breadcrumb': 'Производитель'},
    loadChildren: () => import('./manufacturer/main.module').then(m => m.MainModule)
  },
  {
    path: 'reference-measurement-units',
    data: {title: 'Единицы измерения', 'breadcrumb': 'Единицы измерения'},
    loadChildren: () => import('./measurement-units/measurement-units.module').then(m => m.MeasurementUnitsModule)
  },
  {
    path: 'reference-countries',
    data: {title: 'Страны', 'breadcrumb': 'Страны'},
    loadChildren: () => import('./countries/countries.module').then(m => m.CountriesModule)
  },
  {
    path: 'reference-disease',
    data: {title: 'Заболевания', 'breadcrumb': 'Заболевания'},
    loadChildren: () => import('./disease/disease.module').then(m => m.DiseaseModule)
  },
  {
    path: 'reference-release-form',
    data: {title: 'Форма выпуска', 'breadcrumb': 'Форма выпуска'},
    loadChildren: () => import('./release-form/main.module').then(m => m.MainModule)
  },
  {
    path: 'reference-category-nomenclature',
    data: {title: 'Категории номенклатуры', 'breadcrumb': 'Категории номенклатуры'},
    loadChildren: () => import('./category-nomenclature/main.module').then(m => m.MainModule)
  },
  {
    path: 'form-template',
    canActivate: [RolesGuard],
    data: {title: 'Конструктор приемов', 'breadcrumb': 'Конструктор приемов'},
    loadChildren: () => import('./form-template/main.module').then(m => m.MainModule)
  },
  {
    path: 'mobile-devices',
    data: {title: 'Мобильные кассы', 'breadcrumb': 'Мобильные кассы'},
    loadChildren: () => import('./form-template/main.module').then(m => m.MainModule)
  },
  {
    path: 'form-template-field',
    canActivate: [RolesGuard],
    data: {title: 'Поля формы', 'breadcrumb': 'Поля формы'},
    loadChildren: () => import('./form-template-field/main.module').then(m => m.MainModule)
  },
  {
    path: 'form-template-field-property',
    canActivate: [RolesGuard],
    data: {title: 'Свойства полей шаблонов', 'breadcrumb': 'Свойства полей шаблонов'},
    loadChildren: () => import('./form-template-field-property/main.module').then(m => m.MainModule)
  },
  {
    path: 'reason-retiring',
    canActivate: [RolesGuard],
    data: {title: 'Причины выбытия животных', 'breadcrumb': 'Причины выбытия животных'},
    loadChildren: () => import('./pet-reason-retiring/pet-reason-retiring.module').then(m => m.PetReasonRetiringModule)
  },

  {
    path: 'template-reference',
    canActivate: [RolesGuard],
    data: {title: 'Справочники конструктора', 'breadcrumb': 'Справочники конструктора'},
    loadChildren: () => import('./template-reference/main.module').then(m => m.MainModule)
  },
  {
    path: 'template-reference-values',
    canActivate: [RolesGuard],
    data: {title: 'Значения справочников конструктора', 'breadcrumb': 'Значения справочников конструктора'},
    loadChildren: () => import('./template-reference-values/main.module').then(m => m.MainModule)
  },
  {
    path: 'notifications-channel',
    canActivate: [RolesGuard],
    data: {title: 'Каналы оповещения', 'breadcrumb': 'Каналы оповещения'},
    loadChildren: () => import('./notifications/channel/main.module').then(m => m.MainModule)
  },
  {
    path: 'notifications-type',
    canActivate: [RolesGuard],
    data: {title: 'Типы оповещения', 'breadcrumb': 'Типы оповещения'},
    loadChildren: () => import('./notifications/type/main.module').then(m => m.MainModule)
  },
  {
    path: 'laboratory/laboratory',
    canActivate: [RolesGuard],
    data: {title: 'Лаборатории', 'breadcrumb': 'Лаборатории'},
    loadChildren: () => import('../laboratory/laboratory/laboratory-reference.module').then(m => m.LaboratoryReferenceModule)
  },
  {
    path: 'laboratory/material-type',
    canActivate: [RolesGuard],
    data: {title: 'Тип материала', 'breadcrumb': 'Тип материала'},
    loadChildren: () => import('../laboratory/material-type/material-type.module').then(m => m.MaterialTypeModule)
  },
  {
    path: 'laboratory/packing',
    canActivate: [RolesGuard],
    data: {title: 'Упаковка', 'breadcrumb': 'Упаковка'},
    loadChildren: () => import('../laboratory/packing/packing.module').then(m => m.PackingModule)
  },
  {
    path: 'laboratory/research-priority',
    canActivate: [RolesGuard],
    data: {title: 'Приоритет исследования', 'breadcrumb': 'Приоритет исследования'},
    loadChildren: () => import('../laboratory/research-priority/research-priority.module').then(m => m.ResearchPriorityModule)
  },
  {
    path: 'laboratory/research-reason',
    canActivate: [RolesGuard],
    data: {title: 'Причина исследования', 'breadcrumb': 'Причина исследования'},
    loadChildren: () => import('../laboratory/research-reason/research-reason.module').then(m => m.ResearchReasonModule)
  },
  {
    path: 'laboratory/probe',
    canActivate: [RolesGuard],
    data: {title: 'Проба', 'breadcrumb': 'Проба'},
    loadChildren: () => import('../laboratory/probe/probe.module').then(m => m.ProbeModule)
  },
  {
    path: 'laboratory/research',
    canActivate: [RolesGuard],
    data: {title: 'Исследования', 'breadcrumb': 'Исследования'},
    loadChildren: () => import('../laboratory/research/research.module').then(m => m.ResearchModule)
  },
  {
    path: 'laboratory/probe-corrupt-reason',
    canActivate: [RolesGuard],
    data: {title: 'Причина брака пробы', 'breadcrumb': 'Причина брака пробы'},
    loadChildren: () => import('../laboratory/probe-corrupt-reason/probe-corrupt-reason.module').then(m => m.ProbeCorruptReasonModule)
  },
  {
    path: 'laboratory/research-equipment',
    canActivate: [RolesGuard],
    data: {title: 'Оборудование для исследования', 'breadcrumb': 'Оборудование для исследования'},
    loadChildren: () => import('../laboratory/research-equipment/research-equipment.module').then(m => m.ResearchEquipmentModule)
  },
  {
    path: 'leaving-status',
    canActivate: [RolesGuard],
    data: {title: 'Статусы выезда', 'breadcrumb': 'Статусы выезда'},
    loadChildren: () => import('./leaving-status/leaving-status.module').then(m => m.LeavingStatusModule)
  },
  {
    path: 'reason-for-leaving',
    canActivate: [RolesGuard],
    data: {title: 'Причина выезда', 'breadcrumb': 'Причина выезда'},
    loadChildren: () => import('./reason-for-leaving/reason-for-leaving.module').then(m => m.ReasonForLeavingModule)
  },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class RoutingModule {
}
