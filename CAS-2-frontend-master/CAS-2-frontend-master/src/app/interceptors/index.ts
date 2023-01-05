import { HTTP_INTERCEPTORS } from '@angular/common/http';
import { ApiInterceptor } from './api.interceptor';


export const interceptors = [
  {provide: HTTP_INTERCEPTORS, useClass: ApiInterceptor, multi: true},
];
