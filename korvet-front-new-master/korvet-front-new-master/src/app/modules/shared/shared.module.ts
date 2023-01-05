import {NgModule} from '@angular/core';
import {CommonModule, DatePipe} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ListComponent} from './components/list/list.component';
import {ViewContainerDirective} from './directives/view-container.directive';
import {NgxSpinnerModule} from 'ngx-spinner';
import {ngxLoadingAnimationTypes, NgxLoadingModule} from 'ngx-loading';
import {UiSelectDirective} from './directives/ui-select.directive';
import {YaMapsFieldComponent} from './components/ya-maps-field/ya-maps-field.component';
import {MenuColumnSliderComponent} from './components/menu-column-slider/menu-column-slider.component';
import {RouterModule} from '@angular/router';
import {ListFilterComponent} from './components/list-filter/list-filter.component';
import {ListActionComponent} from './components/list-action/list-action.component';
import {SafeHtmlPipe} from './pipes/safe-html.pipe';
import {YaMapComponent} from './components/ya-map/ya-map.component';
import {UiSelectComponent} from './components/ui-select/ui-select.component';
import {UiDatepickerDirective} from './directives/ui-datepicker.directive';
import {UiNotificationToggleDirective} from './directives/ui-notification-toggle.directive';
import {UiUserMenuDirective} from './directives/ui-user-menu.directive';
import {UiPrintMenuDirective} from './directives/ui-print-menu.directive';
import {UiMultiSelectFieldComponent} from './components/ui-multi-select-field/ui-multi-select-field.component';
import {MenuColumnSliderDirective} from './directives/menu-column-slider.directive';
import {OwnerSearchComponent} from './components/owner-search/owner-search.component';
import {MainOwnerPipe} from './pipes/main-owner.pipe';
import {UiMaskPhoneDirective} from './directives/ui-mask-phone.directive';
import {UiMaskNumberDirective} from './directives/ui-mask-number.directive';
import {GenderPipe} from './pipes/gender.pipe';
import {AgePipe} from './pipes/age.pipe';
import {WeightPipe} from './pipes/weight.pipe';
import {ModalConfirmComponent} from './components/modal-confirm/modal-confirm.component';
import {UiMaskTimeDirective} from './directives/ui-mask-time.directive';
import {UiSelectFieldDirective} from './directives/ui-select-field.directive';
import {SameFieldDirective} from './directives/same-field.directive';
import {ModalFileAddFormComponent} from './components/modal-file-add-form/modal-file-add-form.component';
import {FileSizePipe} from './pipes/file-size.pipe';
import {FullTextSearchPipe} from './pipes/full-text-search.pipe';
import {FilesViewComponent} from './components/files-view/files-view.component';
import {IconFileTypeComponent} from './components/icons/icon-file-type/icon-file-type.component';
import {DefaultValuePipe} from './pipes/default-value.pipe';
import {DropdownSelectComponent} from './components/dropdown-select/dropdown-select.component';
import {ReferenceButtonFormComponent} from './components/reference-button-form/reference-button-form.component';
import {ListViewComponent} from './components/list-view/list-view.component';
import {ListFilterViewComponent} from './components/list-filter-view/list-filter-view.component';
import {TitleViewComponent} from './components/title-view/title-view.component';
import {GridColComponent} from './components/grid/col/component';
import {GridRowComponent} from './components/grid/row/component';
import {InputComponent} from './components/ui/input/component';
import {FormComponent} from './components/ui/form/component';
import {EmptyViewComponent} from './components/empty-view/empty-view.component';
import {DateParsePipe} from './pipes/date-parse.pipe';
import {PetMainOwnerPipe} from './pipes/pet-main-owner.pipe';
import {UiSelectFieldComponent} from './components/ui-select-field/ui-select-field.component';
import {MatchesListViewComponent} from './components/matches-list-view/matches-list-view.component';
import {NumberOnlyDirective} from './directives/number-only.directive';
import {YaMapsSuggestionDirective} from './directives/ya-maps-suggestion.directive';
import {ShowMoreButtonComponent} from './components/show-more-button/show-more-button.component';
import {IconEditComponent} from './components/icons/icon-edit/icon-edit.component';
import {IconPrintComponent} from './components/icons/icon-print/icon-print.component';
import {IconDownloadComponent} from './components/icons/icon-donwload/icon-download.component';
import {UiAutocompleteComponent} from './components/ui-autocomplete/ui-autocomplete.component';
import {MaterialModule} from './material.module';
import {ModalSelectPetComponent} from './components/modal-select-pet/modal-select-pet.component';
import {PrintFormsComponent} from './components/print-forms/print-forms.component';
import {PrintListComponent} from './components/print-list/print-list.component';
import {NoAccessComponent} from './components/no-access/no-access.component';
import {ImplodePipe} from './pipes/implode.pipe';
import {PricePipe} from './pipes/price.pipe';
import {InfoDialogComponent} from './components/info-dialog/info-dialog.component';
import {IconMenuComponent} from './components/icons/icon-menu/icon-menu.component';
import {IconProfileComponent} from './components/icons/icon-profile/icon.component';
import {IconSettingsComponent} from './components/icons/icon-settings/icon-settings.component';
import {IconComponent} from './components/icons/icon/icon.component';
import {IconStoreComponent} from './components/icons/icon-store/icon.component';
import {IconCloseComponent} from './components/icons/icon-close/icon.component';
import {IconReceptionComponent} from './components/icons/icon-reception/icon-reception.component';
import {IconCullingComponent} from './components/icons/icon-culling/icon-culling.component';
import {IconSearchComponent} from './components/icons/icon-search/icon-search.component';
import {ModalGallseryComponent} from './components/modal-gallery/modal-gallsery.component';
import {CarouselModule} from 'ngx-owl-carousel-o';
import {ListImgComponent} from './components/list-img/list-img.component';
import {UiAutocompleteFieldComponent} from './components/ui-autocomplete-field/ui-autocomplete-field.component';
import {UiMatSelectEnumComponent} from './components/ui-mat-select-enum/ui-mat-select-enum.component';
import {ModalSimpleFormComponent} from './components/modal-simple-form/modal-simple-form.component';
import {ModalFormTemplateViewComponent} from './components/modal-form-template-view/modal-form-template-view.component';
import {FirstLetterPipe} from './pipes/first-letter.pipe';
import {TextMaskModule} from 'angular2-text-mask';
import {DatepickerOverviewComponent} from './components/datepicker-overview/datepicker-overview.component';
import {DateAdapter, MAT_DATE_FORMATS} from '@angular/material/core';
import {APP_DATE_FORMATS, AppDateAdapter} from './date-adapter';
import {TitleCaseNamePipe} from './pipes/title-case-name';
import {FormFocusDirective} from './directives/form-focus.directive';
import {ContactDocumentComponent} from './components/contact-document/contact-document.component';
import {ButtonActiveComponent} from './components/button-active/button-active.component';
import {StoreButtonViewComponent} from './components/store-button-view/store-button-view.component';
import {NumericDirective} from './directives/numeric.directive';
import {UiSelectReferenceComponent} from './components/ui-select-reference/ui-select-reference.component';
import {ViewComponent} from '../main/pages/admin/references/form-template/appointment-view/view.component';
import {ModalConfirmSumComponent} from './components/modal-confirm-sum/modal-confirm-sum.component';
import {AggressiveCountPipe} from './pipes/aggressive-count.pipe';
import {IconNotificationComponent} from './components/icons/icon-notification/icon.component';
import {IconPlusComponent} from './components/icons/icon-plus/icon.component';
import {IconDataComponent} from './components/icons/icon-data/icon.component';
import {IconDragComponent} from './components/icons/icon-drag/icon.component';
import {IconCalendarComponent} from './components/icons/icon-calendar/icon.component';
import {ModalAlertViewComponent} from './components/modal-alert-view/modal.component';
import {ModalSupportFormComponent } from './components/modal-support-form/modal.component';
import {MaxNumberPipe} from './pipes/max-number.pipe';
import {CkeditorComponent} from './components/ckeditor/component';
import { IconShopComponent } from './components/icons/icon-shop/icon-shop.component';
import { IconLaboratoryComponent } from './components/icons/icon-laboratory/icon-laboratory.component';
import {ButtonCloseComponent} from './components/button-close/button-close.component';
import {IconLeavingComponent} from './components/icons/icon-leaving/icon-leaving.component';
import {IconDropDownComponent} from './components/icons/icon-drop/icon-drop-down/icon-drop-down.component';
import {IconDragAndDropComponent} from './components/icons/icon-drop/drag-and-drop/icon-drag-and-drop.component';
import {IconDropUpComponent} from './components/icons/icon-drop/icon-drop-up/icon-drop-up.component';
import { PetSearchComponent } from './components/pet-search/pet-search.component';
import {DadataComponent} from './components/dadata/dadata.component';
import {IconCashboxComponent} from './components/icons/icon-cashbox/icon-cashbox.component';
import {IconDescriptionComponent} from './components/icons/icon-description/icon-description.component';
import {PositiveNumberDirective} from './directives/positive-number.directive';
import {IconAnimalComponent} from './components/icons/icon-animal/icon-animal.component';
import {UiMatMultiSelectComponent} from './components/ui-mat-multi-select/ui-mat-multi-select.component';
import {CashReceiptCountComponent} from './modules/cash-receipt-count/cash-receipt-count..component';
import {ResizedDirective} from './directives/resized.directive';

@NgModule({
    declarations: [
        ViewContainerDirective,
        ListComponent,
        YaMapsFieldComponent,
        UiSelectDirective,
        MenuColumnSliderComponent,
        ListFilterComponent,
        ListActionComponent,
        SafeHtmlPipe,
        YaMapComponent,
        UiSelectComponent,
        UiDatepickerDirective,
        UiNotificationToggleDirective,
        UiUserMenuDirective,
        UiPrintMenuDirective,
        UiMultiSelectFieldComponent,
        UiMaskPhoneDirective,
        UiMaskNumberDirective,
        MenuColumnSliderDirective,
        OwnerSearchComponent,
        MainOwnerPipe,
        GenderPipe,
        AggressiveCountPipe,
        MaxNumberPipe,
        AgePipe,
        WeightPipe,
        ModalConfirmComponent,
        UiMaskTimeDirective,
        UiSelectFieldDirective,
        SameFieldDirective,
        ModalFileAddFormComponent,
        ModalSelectPetComponent,
        InfoDialogComponent,
        FileSizePipe,
        FirstLetterPipe,
        FullTextSearchPipe,
        FilesViewComponent,
        IconFileTypeComponent,
        DefaultValuePipe,
        DropdownSelectComponent,
        ReferenceButtonFormComponent,
        ListViewComponent,
        ListFilterViewComponent,
        TitleViewComponent,
        EmptyViewComponent,
        DateParsePipe,
        PetMainOwnerPipe,
        UiSelectFieldComponent,
        MatchesListViewComponent,
        NumberOnlyDirective,
        YaMapsSuggestionDirective,
        ShowMoreButtonComponent,
        IconComponent,
        IconEditComponent,
        IconPrintComponent,
        IconDownloadComponent,
        IconMenuComponent,
        IconSettingsComponent,
        IconProfileComponent,
        IconStoreComponent,
        IconCloseComponent,
        IconReceptionComponent,
        IconCullingComponent,
        IconShopComponent,
        IconLeavingComponent,
        IconSearchComponent,
        UiAutocompleteComponent,
        PrintFormsComponent,
        PrintListComponent,
        NoAccessComponent,
        ImplodePipe,
        PricePipe,
        ModalGallseryComponent,
        ModalFormTemplateViewComponent,
        ListImgComponent,
        UiAutocompleteFieldComponent,
        UiMatSelectEnumComponent,
        ModalSimpleFormComponent,
        DatepickerOverviewComponent,
        TitleCaseNamePipe,
        FormFocusDirective,
        ContactDocumentComponent,
        ButtonActiveComponent,
        StoreButtonViewComponent,
        NumericDirective,
        UiSelectReferenceComponent,
        ViewComponent,
        GridColComponent,
        GridRowComponent,
        InputComponent,
        FormComponent,
        ModalConfirmSumComponent,
        ModalAlertViewComponent,
        ModalSupportFormComponent,
        CkeditorComponent,
        IconNotificationComponent,
        IconPlusComponent,
        IconDataComponent,
        IconDragComponent,
        IconCalendarComponent,
        ButtonCloseComponent,
        IconDropDownComponent,
        IconDragAndDropComponent,
        IconDropUpComponent,
        IconLaboratoryComponent,
        PetSearchComponent,
        DadataComponent,
        IconCashboxComponent,
        IconDescriptionComponent,
        PositiveNumberDirective,
        IconAnimalComponent,
        UiMatMultiSelectComponent,
        CashReceiptCountComponent,
        ResizedDirective,
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
    CarouselModule,
    TextMaskModule,
  ],
    exports: [
        FormsModule,
        ReactiveFormsModule,
        NgxSpinnerModule,
        ViewContainerDirective,
        UiSelectDirective,
        NgxLoadingModule,
        ListComponent,
        ListFilterComponent,
        ListActionComponent,
        YaMapsFieldComponent,
        MenuColumnSliderComponent,
        SafeHtmlPipe,
        YaMapComponent,
        UiSelectComponent,
        UiDatepickerDirective,
        UiNotificationToggleDirective,
        UiUserMenuDirective,
        UiPrintMenuDirective,
        UiMultiSelectFieldComponent,
        OwnerSearchComponent,
        MainOwnerPipe,
        UiMaskPhoneDirective,
        UiMaskNumberDirective,
        UiMaskTimeDirective,
        GenderPipe,
        AggressiveCountPipe,
        MaxNumberPipe,
        AgePipe,
        WeightPipe,
        ModalConfirmComponent,
        UiSelectFieldDirective,
        FileSizePipe,
        FirstLetterPipe,
        FullTextSearchPipe,
        SameFieldDirective,
        ModalFileAddFormComponent,
        ModalSelectPetComponent,
        InfoDialogComponent,
        FilesViewComponent,
        DefaultValuePipe,
        DropdownSelectComponent,
        ReferenceButtonFormComponent,
        ListViewComponent,
        ListFilterViewComponent,
        TitleViewComponent,
        IconFileTypeComponent,
        EmptyViewComponent,
        DateParsePipe,
        PetMainOwnerPipe,
        UiSelectFieldComponent,
        MatchesListViewComponent,
        NumberOnlyDirective,
        YaMapsSuggestionDirective,
        ShowMoreButtonComponent,
        IconComponent,
        IconEditComponent,
        IconPrintComponent,
        IconDownloadComponent,
        IconMenuComponent,
        IconSettingsComponent,
        IconProfileComponent,
        IconStoreComponent,
        IconCloseComponent,
        IconReceptionComponent,
        IconCullingComponent,
        IconShopComponent,
        IconLaboratoryComponent,
        IconLeavingComponent,
        IconSearchComponent,
        MaterialModule,
        UiAutocompleteComponent,
        PrintFormsComponent,
        PrintListComponent,
        NoAccessComponent,
        ImplodePipe,
        PricePipe,
        CarouselModule,
        ListImgComponent,
        UiAutocompleteFieldComponent,
        UiMatSelectEnumComponent,
        ModalSimpleFormComponent,
        TextMaskModule,
        DatepickerOverviewComponent,
        TitleCaseNamePipe,
        FormFocusDirective,
        ContactDocumentComponent,
        ButtonActiveComponent,
        StoreButtonViewComponent,
        ContactDocumentComponent,
        ModalFormTemplateViewComponent,
        NumericDirective,
        UiSelectReferenceComponent,
        ViewComponent,
        GridColComponent,
        GridRowComponent,
        InputComponent,
        FormComponent,
        ModalConfirmSumComponent,
        ModalAlertViewComponent,
        ModalSupportFormComponent,
        CkeditorComponent,
        IconNotificationComponent,
        IconPlusComponent,
        IconDataComponent,
        IconDragComponent,
        IconCalendarComponent,
        ButtonCloseComponent,
        IconDropDownComponent,
        IconDragAndDropComponent,
        IconDropUpComponent,
        PetSearchComponent,
        DadataComponent,
        PositiveNumberDirective,
        IconAnimalComponent,
        UiMatMultiSelectComponent,
        CashReceiptCountComponent,
      ResizedDirective
    ],
  providers: [
    DatePipe,
    DateParsePipe,
    {provide: DateAdapter, useClass: AppDateAdapter},
    {provide: MAT_DATE_FORMATS, useValue: APP_DATE_FORMATS},
    PricePipe
  ],
  entryComponents: [
    ModalConfirmComponent,
    ModalConfirmSumComponent,
    ModalAlertViewComponent,
    ModalSupportFormComponent,
    ModalFileAddFormComponent,
    ModalSelectPetComponent,
    InfoDialogComponent,
    ModalGallseryComponent,
    ModalSimpleFormComponent,
    ModalFormTemplateViewComponent
  ]
})
export class SharedModule {
}
