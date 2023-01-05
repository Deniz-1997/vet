import {Injectable} from '@angular/core';
import {BehaviorSubject, Observable} from 'rxjs';
import {Store} from '@ngrx/store';
import {HttpClient, HttpEvent, HttpRequest} from '@angular/common/http';
import {CrudType} from '../common/crud-types';
import {CrudState, CrudStoreService} from '../api/api-connector/crud/crud-store.service';
import {ApiParamsInterface, ApiResponse} from '../api/api-connector/api-connector.models';
import {LoadDeleteAction, LoadGetAction, LoadGetListAction} from '../api/api-connector/crud/crud.actions';

@Injectable({
  providedIn: 'root'
})
export class FilesService {
  constructor(
    protected store: Store<CrudState>,
    private http: HttpClient,
    private crudStore: CrudStoreService,
  ) {
  }

  /*CRUD*/
  /**
   * @param id
   * @param params
   * @constructor
   * @return BehaviorSubject
   */
  Get(id?: number | null, params?: ApiParamsInterface): BehaviorSubject<ApiResponse> {
    const type: CrudType = CrudType.File;
    return id ? this.getById(id.toString(), type) : this.getList(params, type);
  }

  getTypes(): BehaviorSubject<ApiResponse> {
    const then = new BehaviorSubject(<ApiResponse>{});
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceFileType,
      params: {order: {name: 'ASC'}},
      onSuccess(res: ApiResponse) {
        then.next(res);
      }
    }));
    return then;
  }

  upload<R>(formData: any): Observable<HttpEvent<R>> {
    const req = new HttpRequest('POST', this.crudStore.config[CrudType.UploadedFile].url, formData, {
      reportProgress: false
    });
    return this.http.request(req);
  }

  /*CRUD*/

  removeFile(id: number) {
    const then = new BehaviorSubject(<ApiResponse>{});
    this.store.dispatch(new LoadDeleteAction({
      type: CrudType.File,
      params: {id: id},
      onSuccess(res: ApiResponse) {
        then.next(res);
      }
    }));
    return then;
  }

  private getById(id: string, type: CrudType) {
    const then = new BehaviorSubject(<ApiResponse>{});
    this.store.dispatch(new LoadGetAction({
      type: type,
      params: id,
      onSuccess(res: ApiResponse) {
        then.next(res);
      }
    }));
    return then;
  }

  private getList(params: ApiParamsInterface, type: CrudType) {
    const then = new BehaviorSubject(<ApiResponse>{});
    this.store.dispatch(new LoadGetListAction({
      type: type,
      params: params,
      onSuccess(res: ApiResponse) {
        then.next(res);
      }
    }));
    return then;
  }

}
