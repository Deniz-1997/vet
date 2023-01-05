import {NgModule} from '@angular/core';
import {FormFocusDirective} from './directives/form-focus.directive';
import {MenuColumnSliderDirective} from './directives/menu-column-slider.directive';
import {NumberOnlyDirective} from './directives/number-only.directive';
import {PositiveNumberDirective} from './directives/positive-number.directive';
import {SameFieldDirective} from './directives/same-field.directive';
import {UiDatepickerDirective} from './directives/ui-datepicker.directive';
import {UiMaskNumberDirective} from './directives/ui-mask-number.directive';
import {UiMaskPhoneDirective} from './directives/ui-mask-phone.directive';
import {UiMaskTimeDirective} from './directives/ui-mask-time.directive';
import {UiNotificationToggleDirective} from './directives/ui-notification-toggle.directive';
import {UiPrintMenuDirective} from './directives/ui-print-menu.directive';
import {UiSelectFieldDirective} from './directives/ui-select-field.directive';
import {UiSelectDirective} from './directives/ui-select.directive';
import {UiUserMenuDirective} from './directives/ui-user-menu.directive';
import {ViewContainerDirective} from './directives/view-container.directive';

@NgModule({
  declarations: [
    FormFocusDirective,
    MenuColumnSliderDirective,
    NumberOnlyDirective,
    PositiveNumberDirective,
    SameFieldDirective,
    UiDatepickerDirective,
    UiMaskNumberDirective,
    UiMaskPhoneDirective,
    UiMaskTimeDirective,
    UiNotificationToggleDirective,
    UiPrintMenuDirective,
    UiSelectFieldDirective,
    UiSelectDirective,
    UiUserMenuDirective,
    ViewContainerDirective,
  ],
  imports: [
  ],
  exports: [
    FormFocusDirective,
    MenuColumnSliderDirective, NumberOnlyDirective,
    PositiveNumberDirective,
    SameFieldDirective,
    UiDatepickerDirective,
    UiMaskNumberDirective,
    UiMaskPhoneDirective,
    UiMaskTimeDirective,
    UiNotificationToggleDirective,
    UiPrintMenuDirective,
    UiSelectFieldDirective,
    UiSelectDirective,
    UiUserMenuDirective,
    ViewContainerDirective,
  ]
})
export class KorvetFrontDirectivesModule { }
