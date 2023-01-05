import {NgModule} from '@angular/core';
import {CommonModule, DatePipe} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ViewContainerDirective} from './directives/view-container.directive';
import {NgxSpinnerModule} from 'ngx-spinner';
import {ngxLoadingAnimationTypes, NgxLoadingModule} from 'ngx-loading';
import {UiSelectDirective} from './directives/ui-select.directive';
import {MenuColumnSliderComponent} from './components/menu-column-slider/menu-column-slider.component';
import {RouterModule} from '@angular/router';
import {ListFilterComponent} from './components/list/list-filter/list-filter.component';
import {SafeHtmlPipe} from './pipes/safe-html.pipe';
import {UiDatepickerDirective} from './directives/ui-datepicker.directive';
import {UiNotificationToggleDirective} from './directives/ui-notification-toggle.directive';
import {UiUserMenuDirective} from './directives/ui-user-menu.directive';
import {UiPrintMenuDirective} from './directives/ui-print-menu.directive';
import {UiMatMultiSelectComponent} from './components/ui-mat-multi-select/ui-mat-multi-select.component';
import {MenuColumnSliderDirective} from './directives/menu-column-slider.directive';
import {UiMaskPhoneDirective} from './directives/ui-mask-phone.directive';
import {UiMaskNumberDirective} from './directives/ui-mask-number.directive';
import {WeightPipe} from './pipes/weight.pipe';
import {UiMaskTimeDirective} from './directives/ui-mask-time.directive';
import {UiSelectFieldDirective} from './directives/ui-select-field.directive';
import {SameFieldDirective} from './directives/same-field.directive';
import {FileSizePipe} from './pipes/file-size.pipe';
import {FullTextSearchPipe} from './pipes/full-text-search.pipe';
import {DefaultValuePipe} from './pipes/default-value.pipe';
import {ReferenceButtonFormComponent} from './components/reference-button-form/reference-button-form.component';
import {ListViewComponent} from './components/list/list-view/list-view.component';
import {ListFilterViewComponent} from './components/list/list-filter-view/list-filter-view.component';
import {TitleViewComponent} from './components/title-view/title-view.component';
import {GridColComponent} from './components/grid/col/component';
import {GridRowComponent} from './components/grid/row/component';
import {EmptyViewComponent} from './components/empty-view/empty-view.component';
import {DateParsePipe} from './pipes/date-parse.pipe';
import {NumberOnlyDirective} from './directives/number-only.directive';
import {ShowMoreButtonComponent} from './components/show-more-button/show-more-button.component';
import {UiAutocompleteComponent} from './components/ui-autocomplete/ui-autocomplete.component';
import {MaterialModule} from './material.module';
import {NoAccessComponent} from './components/no-access/no-access.component';
import {ImplodePipe} from './pipes/implode.pipe';
import {PricePipe} from './pipes/price.pipe';
import {IconComponent} from './components/icon/icon.component';
import {FirstLetterPipe} from './pipes/first-letter.pipe';
import {TextMaskModule} from 'angular2-text-mask';
import {DatepickerOverviewComponent} from './components/datepicker-overview/datepicker-overview.component';
import {DateAdapter, MAT_DATE_FORMATS} from '@angular/material/core';
import {APP_DATE_FORMATS, AppDateAdapter} from './date-adapter';
import {TitleCaseNamePipe} from './pipes/title-case-name';
import {FormFocusDirective} from './directives/form-focus.directive';
import {NumericDirective} from './directives/numeric.directive';
import {ModalSupportFormComponent} from './components/modal-support-form/modal.component';
import {MaxNumberPipe} from './pipes/max-number.pipe';
import {ButtonCloseComponent} from './components/buttons/close/button-close.component';
import {DadataComponent} from './components/dadata/dadata.component';
import {PositiveNumberDirective} from './directives/positive-number.directive';
import {ModalFileUploadComponent} from './components/modal-file-upload/modal.component';
import {ButtonComponent} from './components/buttons/button.component';
import {BadgeComponent} from './components/badge/badge.component';
import {KorvetModule} from './korvet.module';
import {FlexModule} from '@angular/flex-layout';
import {NgArrayPipesModule} from 'ngx-pipes';
import {ButtonModule, ColModule, ContainerModule, HeaderModule, IconModule, RowModule, SubheaderModule, TextFieldModule} from '@korvet/ui-elements';
import {CloseDialogComponent} from './components/close-dialog/close-dialog.component';
import {TitleComponent} from './components/title/title.component';
import {RemoveComponentTagDirective} from './directives/remove-component-tag.directive';
import {ResizedDirective} from './directives/resized.directive';
import {FullNameFormComponent} from './components/full-name-form/full-name-form.component';
import {DialogElementsComponent} from './components/modal-support-form/dialog-elements/dialog-elements';
import {IconFileTypeComponent} from './components/icon-file-type/icon-file-type.component';
import {UiMultiSelectFieldComponent} from './components/ui-multi-select-field/ui-multi-select-field.component';
import {ModalStationListComponent} from './components/modal-station-list/modal-station-list.component';
import {ModalAlertViewComponent} from './components/modal-alert-view/modal.component';
import {TextFieldComponent} from './components/text-field/text-field.component';

@NgModule({
  declarations: [
    BadgeComponent,
    ButtonComponent,
    ViewContainerDirective,
    UiSelectDirective,
    MenuColumnSliderComponent,
    ListFilterComponent,
    SafeHtmlPipe,
    UiDatepickerDirective,
    UiNotificationToggleDirective,
    UiUserMenuDirective,
    UiPrintMenuDirective,
    UiMatMultiSelectComponent,
    UiMaskPhoneDirective,
    UiMaskNumberDirective,
    MenuColumnSliderDirective,
    MaxNumberPipe,
    WeightPipe,
    UiMaskTimeDirective,
    UiSelectFieldDirective,
    SameFieldDirective,
    FileSizePipe,
    FullNameFormComponent,
    FirstLetterPipe,
    FullTextSearchPipe,
    DefaultValuePipe,
    ReferenceButtonFormComponent,
    ListViewComponent,
    ListFilterViewComponent,
    TitleViewComponent,
    EmptyViewComponent,
    DateParsePipe,
    NumberOnlyDirective,
    ShowMoreButtonComponent,
    IconComponent,
    UiAutocompleteComponent,
    NoAccessComponent,
    ImplodePipe,
    PricePipe,
    DatepickerOverviewComponent,
    TitleCaseNamePipe,
    FormFocusDirective,
    NumericDirective,
    GridColComponent,
    GridRowComponent,
    ModalSupportFormComponent,
    ButtonCloseComponent,
    DadataComponent,
    PositiveNumberDirective,
    ModalFileUploadComponent,
    ModalStationListComponent,
    CloseDialogComponent,
    TitleComponent,
    RemoveComponentTagDirective,
    ResizedDirective,
    DialogElementsComponent,
    IconFileTypeComponent,
    UiMultiSelectFieldComponent,
    ModalAlertViewComponent,
    TextFieldComponent

  ],
    imports: [
        CommonModule,
        RouterModule,
        FormsModule,
        ReactiveFormsModule,
        NgxSpinnerModule,
        NgxLoadingModule.forRoot({
            animationType: ngxLoadingAnimationTypes.wanderingCubes,
            backdropBackgroundColour: 'rgba(0,0,0,0.1)',
            backdropBorderRadius: '4px',
            primaryColour: '#e1464b',
            secondaryColour: '#e1464b',
            tertiaryColour: '#ffffff'
        }),
        MaterialModule,
        TextMaskModule,
        KorvetModule,
        FlexModule,
        NgArrayPipesModule,
        IconModule,
        RowModule,
        ColModule,
        HeaderModule,
        ButtonModule,
        ContainerModule,
        TextFieldModule,
        SubheaderModule
    ],
  exports: [
    FormsModule,
    ReactiveFormsModule,
    NgxSpinnerModule,
    ViewContainerDirective,
    UiSelectDirective,
    NgxLoadingModule,
    ListFilterComponent,
    MenuColumnSliderComponent,
    SafeHtmlPipe,
    UiDatepickerDirective,
    UiNotificationToggleDirective,
    UiUserMenuDirective,
    UiPrintMenuDirective,
    UiMatMultiSelectComponent,
    UiMaskPhoneDirective,
    UiMaskNumberDirective,
    UiMaskTimeDirective,
    MaxNumberPipe,
    WeightPipe,
    UiSelectFieldDirective,
    FileSizePipe,
    FirstLetterPipe,
    FullTextSearchPipe,
    SameFieldDirective,
    FullNameFormComponent,
    DefaultValuePipe,
    ReferenceButtonFormComponent,
    ListViewComponent,
    ListFilterViewComponent,
    TitleViewComponent,
    EmptyViewComponent,
    DateParsePipe,
    NumberOnlyDirective,
    ShowMoreButtonComponent,
    IconComponent,
    MaterialModule,
    UiAutocompleteComponent,
    NoAccessComponent,
    ImplodePipe,
    PricePipe,
    TextMaskModule,
    DatepickerOverviewComponent,
    TitleCaseNamePipe,
    FormFocusDirective,
    NumericDirective,
    GridColComponent,
    GridRowComponent,
    ModalSupportFormComponent,
    ButtonCloseComponent,
    DadataComponent,
    PositiveNumberDirective,
    ModalFileUploadComponent,
    ModalStationListComponent,
    ButtonComponent,
    BadgeComponent,
    KorvetModule,
    CloseDialogComponent,
    TitleComponent,
    RemoveComponentTagDirective,
    ResizedDirective,
    DialogElementsComponent,
    IconFileTypeComponent,
    UiMultiSelectFieldComponent,
    ModalAlertViewComponent,
    TextFieldComponent
  ],
  providers: [
    DatePipe,
    DateParsePipe,
    {provide: DateAdapter, useClass: AppDateAdapter},
    {provide: MAT_DATE_FORMATS, useValue: APP_DATE_FORMATS},
    PricePipe
  ],
  entryComponents: [ModalSupportFormComponent]
})
export class SharedModule {
}
