import {Injectable} from '@angular/core';
import {Actions, Effect, ofType} from '@ngrx/effects';
import {
  CompleteAppendListAction,
  CompleteCreateAction, CompleteDeleteAction,
  CompleteGetAction,
  CompleteGetListAction, CompleteMatchesAction, CompletePatchAction,
  CrudActionTypes, LoadCreateAction, LoadDeleteAction,
  LoadGetAction,
  LoadGetListAction, LoadMatchesAction,
  LoadPatchAction
} from './crud.actions';
import {catchError, map, mergeMap, tap} from 'rxjs/operators';
import {of} from 'rxjs';
import {ApiConnectorService} from '../api-connector.service';
import {CrudStoreService} from './crud-store.service';


@Injectable()
export class CrudEffects {

  constructor(
    private actions$: Actions,
    private service: ApiConnectorService,
    private crud: CrudStoreService,
  ) {
  }

  @Effect()
  getMatches$ = this.actions$.pipe(
    ofType(CrudActionTypes.LoadMatchesAction),
    mergeMap((data: LoadMatchesAction) => {
      const model: any = this.crud.config[data.payload.type].model;
      return this.service.getList(
        data.payload.url || this.crud.config[data.payload.type].setData(data.payload.params).url,
        data.payload.params
      ).pipe(
        tap(
          res => data.payload.onSuccess && data.payload.onSuccess(res),
          err => data.payload.onError && data.payload.onError(err),
          () => data.payload.onComplete && data.payload.onComplete()
        ),
        map(res => new CompleteMatchesAction({
          type: data.payload.type,
          params: {
            items: res.response.items.map(item => new model(item)),
            totalCount: res.response.totalCount,
            columns: res.response.columns,
          },
        })),
        catchError(err => of(new CompleteMatchesAction({
          type: data.payload.type,
          params: null,
        })))
      );
    })
  );

  @Effect()
  getList$ = this.actions$.pipe(
    ofType(CrudActionTypes.LoadGetListAction),
    mergeMap((data: LoadGetListAction) => {
      const model: any = this.crud.config[data.payload.type].model;
      return this.service.getList(
        data.payload.url || this.crud.config[data.payload.type].setData(data.payload.params).url,
        data.payload.params
      ).pipe(
        tap(
          res => data.payload.onSuccess && data.payload.onSuccess(res),
          err => data.payload.onError && data.payload.onError(err),
          () => data.payload.onComplete && data.payload.onComplete()
        ),
        map(res => new CompleteGetListAction({
          type: data.payload.type,
          params: {
            items: res.response.items.map(item => new model(item)),
            totalCount: res.response.totalCount,
            columns: res.response.columns,
          },
        })),
        catchError(err => of(new CompleteGetListAction({
          type: data.payload.type,
          params: null,
        })))
      );
    })
  );

  @Effect()
  appendList$ = this.actions$.pipe(
    ofType(CrudActionTypes.LoadAppendListAction),
    mergeMap((data: LoadGetListAction) => {
      const model: any = this.crud.config[data.payload.type].model;
      return this.service.getList(
        data.payload.url || this.crud.config[data.payload.type].setData(data.payload.params).url,
        data.payload.params
      ).pipe(
        tap(
          res => data.payload.onSuccess && data.payload.onSuccess(res),
          err => data.payload.onError && data.payload.onError(err),
          () => data.payload.onComplete && data.payload.onComplete()
        ),
        map(res => new CompleteAppendListAction({
          type: data.payload.type,
          params: {
            items: res.response.items.map(item => new model(item)),
            totalCount: res.response.totalCount,
            columns: res.response.columns,
          },
        })),
        catchError(err => of(new CompleteAppendListAction({
          type: data.payload.type,
          params: null,
        })))
      );
    })
  );

  @Effect()
  get$ = this.actions$.pipe(
    ofType(CrudActionTypes.LoadGetAction),
    mergeMap((data: LoadGetAction) => {
      const model: any = this.crud.config[data.payload.type].model;
      return this.service.get(data.payload.url || this.crud.config[data.payload.type].setData(data.payload.params).url, data.payload.params)
        .pipe(
          tap(
            res => data.payload.onSuccess && data.payload.onSuccess(res),
            err => data.payload.onError && data.payload.onError(err),
            () => data.payload.onComplete && data.payload.onComplete()
          ),
          map(res => new CompleteGetAction({
            type: data.payload.type,
            params: new model(res.response),
          })),
          catchError(err => of(new CompleteGetAction({
            type: data.payload.type,
            params: null,
          })))
        );
    })
  );

  @Effect()
  patch$ = this.actions$.pipe(
    ofType(CrudActionTypes.LoadPatchAction),
    mergeMap((data: LoadPatchAction) => {
      const model: any = this.crud.config[data.payload.type].model;
      return this.service.patch(
        data.payload.url || this.crud.config[data.payload.type].setData(data.payload.params).url,
        data.payload.params as any,
        data.payload.fields as any,
        data.payload.dataType
      ).pipe(
        tap(
          res => data.payload.onSuccess && data.payload.onSuccess(res),
          err => data.payload.onError && data.payload.onError(err),
          () => data.payload.onComplete && data.payload.onComplete()
        ),
        map(res => {
          return new CompletePatchAction({
            type: data.payload.type,
            params: new model(res.response)
          });
        }),
        catchError(err => of(new CompletePatchAction({
          type: data.payload.type,
          params: null,
        })))
      );
    })
  );

  @Effect()
  create$ = this.actions$.pipe(
    ofType(CrudActionTypes.LoadCreateAction),
    mergeMap((data: LoadCreateAction) => {
      const model: any = this.crud.config[data.payload.type].model;
      return this.service.post(
        data.payload.url || this.crud.config[data.payload.type].setData(data.payload.params).url,
        data.payload.params as any,
        data.payload.fields as any,
        data.payload.dataType
      ).pipe(
        tap(
          res => data.payload.onSuccess && data.payload.onSuccess(res),
          err => data.payload.onError && data.payload.onError(err),
          () => data.payload.onComplete && data.payload.onComplete()
        ),
        map(res => {
          return new CompleteCreateAction({
            type: data.payload.type,
            params: new model(res.response)
          });
        }),
        catchError(err => of(new CompleteCreateAction({
          type: data.payload.type,
          params: null,
        })))
      );
    })
  );

  @Effect()
  delete$ = this.actions$.pipe(
    ofType(CrudActionTypes.LoadDeleteAction),
    mergeMap((data: LoadDeleteAction) => {
      return this.service
        .delete(data.payload.url || this.crud.config[data.payload.type].setData(data.payload.params).url)
        .pipe(
          tap(
            res => data.payload.onSuccess && data.payload.onSuccess(res),
            err => data.payload.onError && data.payload.onError(err),
            () => data.payload.onComplete && data.payload.onComplete()
          ),
          map(res => new CompleteDeleteAction(data.payload)),
          catchError(err => of(new CompleteDeleteAction({
            type: data.payload.type,
            params: null
          })))
        );
    })
  );
}
