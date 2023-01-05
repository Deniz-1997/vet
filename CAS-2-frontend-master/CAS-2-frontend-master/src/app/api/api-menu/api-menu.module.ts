import {ModuleWithProviders, NgModule} from '@angular/core';
import {ApiMenuComponent} from './api-menu.component';
import {ApiMenuConfig} from './api-menu.config';
import {CommonModule} from '@angular/common';
import {MatCardModule} from '@angular/material/card';
import {MatDividerModule} from '@angular/material/divider';
import {MatIconModule} from '@angular/material/icon';
import {MatListModule} from '@angular/material/list';
import {RouterModule} from '@angular/router';
import {AuthModule} from 'src/app/modules/auth/auth.module';


@NgModule({
  declarations: [ApiMenuComponent],
  imports: [CommonModule,
    MatCardModule,
    MatDividerModule,
    MatIconModule,
    MatListModule,
    AuthModule,
    RouterModule],
  exports: [ApiMenuComponent]
})
export class ApiMenuModule {

  static forRoot(config: ApiMenuConfig): ModuleWithProviders<any> {
    return {
      ngModule: ApiMenuModule,
      providers: [
        {provide: ApiMenuConfig, useValue: config}
      ]
    };
  }
}
