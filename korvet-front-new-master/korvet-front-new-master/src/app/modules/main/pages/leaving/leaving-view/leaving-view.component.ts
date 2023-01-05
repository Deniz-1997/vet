import {AfterViewInit, Component, OnDestroy, OnInit} from '@angular/core';
import {select, Store} from '@ngrx/store';
import {ActivatedRoute, Params, Router} from '@angular/router';
import {BehaviorSubject, Observable, Subject} from 'rxjs';
import {FilesService} from '../../../../../services/files.service';
import {FileModel} from '../../../../../models/file/file.models';
import {MatDialog} from '@angular/material/dialog';
import {FileMonitoredObjectModel} from '../../../../../models/file/file.monitored.object.models';
import {CrudType} from 'src/app/common/crud-types';
import {BreadcrumbsService, IBreadcrumb} from '../../../../../services/breadcrumbs.service';
import {UserModels} from '../../../../../models/user/user.models';
import {LeavingModel} from '../../../../../models/leaving/leaving.models';
import {LeavingLogsModel} from '../../../../../models/leaving/leaving-logs.models';
import {LeavingPermissionService} from '../../../../../services/leaving-premission.service';
import {ReferenceLeavingStatusModel} from '../../../../../models/reference/reference.leaving.status.models';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction, LoadMatchesAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelGetListLoading} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({selector: 'app-leaving-view', templateUrl: './leaving-view.component.html'})

export class LeavingViewComponent implements OnInit, OnDestroy, AfterViewInit {
  type = CrudType.Leaving;
  leaving: LeavingModel = new LeavingModel();
  leavingLogs: LeavingLogsModel[] = [];
  loading$: Observable<boolean>;
  id: number;
  files = new BehaviorSubject<FileModel[]>([]);
  fileTypes$: Observable<FileMonitoredObjectModel[]>;
  fileLoading = false;
  private destroy$ = new Subject<any>();
  private params: Params = {};
  private isChangeBr = false;
  count = 0;
  leavings: LeavingModel[] = [];
  getLoading = true;
  petType: string;

  constructor(
    private store: Store<CrudState>,
    private route: ActivatedRoute,
    protected router: Router,
    private apiFilesService: FilesService,
    private dialog: MatDialog,
    public leavingPermission: LeavingPermissionService,
    protected brdSrv: BreadcrumbsService
  ) {
  }

  ngOnInit() {
    this.id = +this.route.snapshot.paramMap.get('id');
    this.loading$ = this.store.pipe(select(getCrudModelGetListLoading, {type: this.type}));

    if (this.id) {
      this.params.id = this.id;
      this.params.mode = ['data', 'actions', 'listActions', 'layout'];

      this.store.dispatch(new LoadGetListAction({
        type: this.type,
        params: <any>this.params,
        onSuccess: leaving => {

          if (leaving.response) {
            this.leaving = leaving.response;
            this.petType = this.leaving.pet.type.icon.code;
            this.changeBr();
          }

          if (leaving.response.layout) {
            this.leavingPermission.setLeaving(leaving.response.layout);
          }
        }
      }));
    }
  }

  ngAfterViewInit(): void {
    if (this.leaving) {
      this.store.dispatch(new LoadGetListAction({
        type: CrudType.LeavingLogs, params: <any>this.id, onSuccess: res => {
          if (res && res.response) {
            const logs = [];
            res.response.map(v => {
              if (typeof logs[v.user.id] === 'undefined') {
                logs[v.user.id] = [];
                logs[v.user.id].push(v);
                this.leavingLogs.push(new LeavingLogsModel({
                  id: v.id.toString(),
                  date: v.date,
                  leavingStatus: new ReferenceLeavingStatusModel(v.leavingStatus),
                  user: new UserModels(v.user)
                }));
              } else {
                const l = logs[v.user.id].filter(a => v.leavingStatus.code === a.leavingStatus.code);
                if (l.length === 0) {
                  logs[v.user.id].push(v);
                  this.leavingLogs.push(new LeavingLogsModel({
                    id: v.id.toString(),
                    date: v.date,
                    leavingStatus: new ReferenceLeavingStatusModel(v.leavingStatus),
                    user: new UserModels(v.user)
                  }));
                }
              }
            });
          }
        }
      }));
    }
  }

  changeBr() {
    if (!this.isChangeBr && this.leaving) {
      this.brdSrv.replaceLabelByIndex(
        (this.leaving.type && this.leaving.type.code === 'SECONDARY' ? 'Повторный' : 'Первичный') + ' выезд  от '
        + this.leaving.date + ' "'
        + this.leaving.pet?.name + '"', 1);

      const brParam: IBreadcrumb = {
        current: true,
        label: this.leaving.pet?.name,
        params: {id: '1866844'},
        url: '/pets/' + this.leaving.pet?.id + '/profile',
      };

      this.brdSrv.addByIndex(brParam, 1);
      this.isChangeBr = true;
    }
  }

  ngOnDestroy(): void {
    this.destroy$.next();
  }
  getListRepeatLeaving(petId, callback?) {
    const params = {
      fields: [
        'id',
        'type',
        'date',
        'pet',
        'owner',
        'profession',
        'user',
        'name',
        'survey',
        'diagnosis',
        'prescription',
        'appointmentFormTemplate',
        'leavingWeight',
        'leavingTemperature',
        'weightNotMeasured',
        'temperatureNotMeasured'
      ],
      filter: {
        pet: { id: petId },
      },
      offset: 0,
      limit: 50
    };
    if (this.leaving.id) {
      params.filter['!id'] = this.leaving.id;
    }
    this.store.dispatch(new LoadMatchesAction({
      type: CrudType.Leaving,
      params: params,
      onSuccess: result => {
        console.log(result.response);
        this.getLoading = false;
        if (result.response.totalCount > 0) {
          this.leavings = result.response.items;
        }
      }
    }));
  }

  selectedIndexChange(event) {
    if (event === 1) {
      if (this.count === 0) {
        if (this.leaving.pet) {
          this.count += 1;
          this.getListRepeatLeaving(this.leaving.pet.id);

        }
        this.getLoading = false;
      }
    }
  }
}
