import {BrowserModule, Title} from '@angular/platform-browser';
import {Injector, LOCALE_ID, NgModule} from '@angular/core';
import {AppComponent} from './app.component';
import {AppRoutingModule} from './app-routing.module';

import {EffectsModule} from '@ngrx/effects';
import {AppEffects} from './store/app.effects';
import {StoreDevtoolsModule} from '@ngrx/store-devtools';
import {environment} from '../environments/environment';
import {StoreModule} from '@ngrx/store';
import {reducers, metaReducers, effects} from './store';
import {HTTP_INTERCEPTORS, HttpClientModule} from '@angular/common/http';
import {NotifierModule} from 'angular-notifier';
import {SharedModule} from './modules/shared/shared.module';
import {BrowserAnimationsModule} from '@angular/platform-browser/animations';
import {registerLocaleData} from '@angular/common';
import localeRu from '@angular/common/locales/ru';
import {CrudTypes} from './common/crud-types';
import {ApiInterceptor} from './interceptors/api.interceptor';
import {FlexLayoutModule} from '@angular/flex-layout';
import {TableVirtualScrollModule} from 'ng-table-virtual-scroll';
import {UiScrollModule} from 'ngx-ui-scroll';
import {AuthInterceptor} from './api/auth/auth.interceptor';
import {ApiConnectorModule} from './api/api-connector/api-connector.module';
import {AuthModule} from './api/auth/auth.module';

registerLocaleData(localeRu);

@NgModule({
  declarations: [
    AppComponent,
  ],
  imports: [
    FlexLayoutModule,
    BrowserModule,
    BrowserAnimationsModule,
    AppRoutingModule,
    HttpClientModule,
    StoreModule.forRoot(reducers, {
      metaReducers,
      runtimeChecks: {
        strictStateImmutability: false,
        strictActionImmutability: false,
      },
    }),
    EffectsModule.forRoot([AppEffects, ...effects]),
    ApiConnectorModule.forRoot({types: CrudTypes}),
    AuthModule.forRoot({
      credentials: environment.credentials,
      tokenName: 'token',
      authUrl: '/#/auth/login',
      urls: {
        api: ['/api/', '/api-java/'],
        apiOAuthToken: '/api/oauth/v2/token/',
        apiAccountUser: '/api/account/user/',
        apiAccountLogout: '/api/account/logout/',
        apiAccountPassword: '/api/account/password/',
        apiAccountPasswordRecovery: '/api/account/password-recovery/',
        apiRestoreCheckCode: '/api/account/check-confirmation-code/',
      },
      userKey: 'kor-vetUser',
    }),
    StoreDevtoolsModule.instrument({maxAge: 25, logOnly: environment.production}),
    NotifierModule.withConfig({
      position: {
        vertical: {position: 'top'},
        horizontal: {position: 'right'}
      }
    }),
    TableVirtualScrollModule,
    UiScrollModule,
    SharedModule
  ],
  providers: [
    Title,
    {provide: LOCALE_ID, useValue: 'ru'},
    {provide: HTTP_INTERCEPTORS, useClass: ApiInterceptor, multi: true},
    {provide: HTTP_INTERCEPTORS, useClass: AuthInterceptor, multi: true},
  ],
  bootstrap: [AppComponent]
})
export class AppModule {
  static injector: Injector;

  constructor(private injector: Injector) {
    AppModule.injector = injector;
  }
}
