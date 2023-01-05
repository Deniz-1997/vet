import {Injectable} from '@angular/core';
import {Store} from '@ngrx/store';
import {BehaviorSubject} from 'rxjs';
import {CrudType} from 'src/app/common/crud-types';
import {ApiParamsInterface, ApiResponse} from '../api/api-connector/api-connector.models';
import {CrudState} from '../api/api-connector/crud/crud-store.service';
import {LoadGetAction, LoadGetListAction} from '../api/api-connector/crud/crud.actions';


@Injectable({
  providedIn: 'root'
})
export class UsersService {

  constructor(
    protected store: Store<CrudState>,
  ) {
  }

  public getFullName(user: { name?: string; surname: string; patronymic?: string; }): string {
    return (((user.surname).trim() + ' ' + (user.name + ' ' + user.patronymic).trim()).trim());
  }

  Get(id?: number | null, params?: ApiParamsInterface): BehaviorSubject<ApiResponse> {
    const type: CrudType = CrudType.User;
    return id ? this.getById(id.toString(), type) : this.getList(params, type);
  }

  private getById(id: string, type: CrudType): any {
    const then = new BehaviorSubject({} as ApiResponse);
    this.store.dispatch(new LoadGetAction({
      type: type,
      params: id,
      onSuccess(res: ApiResponse): void {
        then.next(res);
      }
    }));
    return then;
  }

  private getList(params: ApiParamsInterface, type: CrudType): BehaviorSubject<ApiResponse> {
    const then = new BehaviorSubject({} as ApiResponse);
    this.store.dispatch(new LoadGetListAction({
      type: type,
      params: params,
      onSuccess(res: ApiResponse): void {
        then.next(res);
      }
    }));
    return then;
  }
}
