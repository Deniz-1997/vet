import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {RoutingModule} from './routing.module';
import {ListComponent} from './list/list.component';
import {EditComponent} from './edit/edit.component';
import {SharedModule} from '../../../../shared/shared.module';
import {ReferenceModule} from '../../../../shared/modules/reference-edit/reference.module';
import {ColModule, ContainerModule, RowModule, SubheaderModule, TextFieldModule} from '@korvet/ui-elements';
import {CKEditorModule} from '@ckeditor/ckeditor5-angular';

@NgModule({
  declarations: [ListComponent, EditComponent],
  imports: [
    CommonModule,
    RoutingModule,
    SharedModule,
    ReferenceModule,
    ColModule,
    RowModule,
    TextFieldModule,
    SubheaderModule,
    ContainerModule,
    CKEditorModule
  ]
})
export class MainModule {
}
