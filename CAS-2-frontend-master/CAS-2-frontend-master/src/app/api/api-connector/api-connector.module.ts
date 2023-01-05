import { InjectionToken, ModuleWithProviders, NgModule } from '@angular/core';
import { CrudStoreService, CrudStoreServiceConfig } from './crud/crud-store.service';
import { ActionReducer, StoreModule } from '@ngrx/store';
import { EffectsModule } from '@ngrx/effects';
import { CrudEffects } from './crud/crud.effects';
import { ApiConnectorService } from './api-connector.service';

export const CRUD_REDUCER_TOKEN = new InjectionToken<ActionReducer<any>>('Account state reducer');
export function crudReducerFactory(accountReducer: CrudStoreService): ActionReducer<any> {
  return accountReducer.createReducer();
}

@NgModule({
  imports: [
    StoreModule.forFeature('crud', CRUD_REDUCER_TOKEN),
    EffectsModule.forFeature([CrudEffects])
  ],
  providers: [ApiConnectorService, CrudStoreService]
})
export class ApiConnectorModule {

  static forRoot(config: CrudStoreServiceConfig): ModuleWithProviders<any> {
    return {
      ngModule: ApiConnectorModule,
      providers: [
        {provide: CrudStoreServiceConfig, useValue: config},
        {provide: CRUD_REDUCER_TOKEN, deps: [CrudStoreService], useFactory: crudReducerFactory}
      ],
    };
  }
}
