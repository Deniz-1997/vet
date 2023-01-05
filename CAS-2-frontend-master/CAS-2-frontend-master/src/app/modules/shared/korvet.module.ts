import {NgModule} from '@angular/core';

import {
  ReportTableModule,
} from '@korvet/ui-elements';

@NgModule({
  imports: [
    ReportTableModule
  ],
  exports: [
    ReportTableModule
  ]
})
export class KorvetModule {
}
