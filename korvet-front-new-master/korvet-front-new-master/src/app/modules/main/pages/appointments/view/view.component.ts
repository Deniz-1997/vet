import {AfterViewInit, Component, OnDestroy, OnInit} from '@angular/core';
import {select, Store} from '@ngrx/store';
import {ActivatedRoute, Params, Router} from '@angular/router';
import {AppointmentModel} from '../../../../../models/appointment/appointment.models';
import {BehaviorSubject, Observable, Subject} from 'rxjs';
import {FilesService} from '../../../../../services/files.service';
import {FileModel} from '../../../../../models/file/file.models';
import {MatDialog} from '@angular/material/dialog';
import {FileMonitoredObjectModel} from '../../../../../models/file/file.monitored.object.models';
import {AppointmentsPermissionService} from '../../../../../services/appointments-permission.service';
import {CrudType} from 'src/app/common/crud-types';
import {BreadcrumbsService, IBreadcrumb} from '../../../../../services/breadcrumbs.service';
import {AppointmentLogsModel} from '../../../../../models/appointment/appointment-logs.models';
import {ReferenceAppointmentStatusModel} from '../../../../../models/reference/reference.appointment.status.models';
import {UserModels} from '../../../../../models/user/user.models';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetAction, LoadGetListAction, LoadMatchesAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelGetListLoading, getCrudModelGetLoading} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({selector: 'app-appointments-view', templateUrl: './view.component.html'})

export class ViewComponent implements OnInit, OnDestroy, AfterViewInit {
  type = CrudType.Appointment;
  appointment: AppointmentModel = new AppointmentModel();
  appointmentLogs: AppointmentLogsModel[] = [];
  loading$: Observable<boolean>;
  id: number;
  files = new BehaviorSubject<FileModel[]>([]);
  fileTypes$: Observable<FileMonitoredObjectModel[]>;
  fileLoading = false;
  private destroy$ = new Subject<any>();
  private params: Params = {};
  private isChangeBr = false;
  count = 0;
  appointments: AppointmentModel[] = [];
  getLoading = true;
  researchList = [];
  getResearchLoading = false;

  constructor(
    private store: Store<CrudState>,
    private route: ActivatedRoute,
    protected router: Router,
    private apiFilesService: FilesService,
    private dialog: MatDialog,
    public appointmentsPermission: AppointmentsPermissionService,
    protected brdSrv: BreadcrumbsService
  ) {
  }

  ngOnInit() {
    this.id = +this.route.snapshot.paramMap.get('id');
    this.loading$ = this.store.pipe(select(getCrudModelGetLoading, {type: this.type}));

    if (this.id) {
      this.params.id = this.id;
      this.params.fields = {
        0: 'id',
        1: 'date',
        2: 'name',
        3: 'survey',
        4: 'diagnosis',
        5: 'prescription',
        6: 'paymentState',
        7: 'paymentType',
        8: 'productItems',
        9: 'state',
        10: 'dateEnd',
        11: 'isExtraCharge',
        12: 'probeSamplings',
        13: 'layout',
        14: 'actions',
        15: 'listActions',
        16: 'data',
        17: 'appointmentFormTemplate',
        'cashReceipt': ['id', 'createdAt', 'fiscal'],
        'owner': ['id', 'name', 'contractDateTo'],
        'profession': ['id', 'name'],
        'user': ['id', 'surname', 'name', 'patronymic'],
        'type': ['title'],
        'previous': ['id', 'date', 'name'],
        'pet': {
          0: 'id', 1: 'type', 2: 'name', 3: 'breed', 4: 'gender', 5: 'petToOwnerId', 6: 'isDead',
          7: 'birthday', 8: 'dateOfDeath', 9: 'chipNumber', 10: 'isSterilized', 11: 'aggressive',
          12: 'aggressiveType', 13: 'vaccinationDate', 14: 'vaccinationExpired', 15: 'dateOfRetiring',
          'actualWeight': ['value']
        }
      };

      this.store.dispatch(new LoadGetAction({
        type: this.type,
        params: <any>this.params,
        onSuccess: appointment => {

          if (appointment.response) {
            this.appointment = appointment.response;
            this.changeBr();
          }

          if (appointment.response.layout) {
            this.appointmentsPermission.setAppointments(appointment.response.layout);
          }
        }
      }));
    }
  }

  ngAfterViewInit(): void {
    if (this.appointment) {
      this.store.dispatch(new LoadGetListAction({
        type: CrudType.AppointmentLogs, params: <any>this.id, onSuccess: res => {
          if (res && res.response) {
            const logs = [];
            res.response.map(v => {
              if (typeof logs[v.user.id] === 'undefined') {
                logs[v.user.id] = [];
                logs[v.user.id].push(v);
                this.appointmentLogs.push(new AppointmentLogsModel({
                  id: v.id.toString(),
                  date: v.date,
                  status: new ReferenceAppointmentStatusModel(v.status),
                  user: new UserModels(v.user)
                }));
              } else {
                const l = logs[v.user.id].filter(a => v.status.code === a.status.code);
                if (l.length === 0) {
                  logs[v.user.id].push(v);
                  this.appointmentLogs.push(new AppointmentLogsModel({
                    id: v.id.toString(),
                    date: v.date,
                    status: new ReferenceAppointmentStatusModel(v.status),
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
    if (!this.isChangeBr && this.appointment) {
      this.brdSrv.replaceLabelByIndex(
        (this.appointment.type && this.appointment.type.code === 'SECONDARY' ? 'Повторный' : 'Первичный') + ' прием от '
        + this.appointment.date + ' "'
        + this.appointment.pet.name + '"', 1);

      const brParam: IBreadcrumb = {
        current: true,
        label: this.appointment.pet.name,
        params: {id: '1866844'},
        url: '/pets/' + this.appointment.pet.id + '/profile',
      };

      this.brdSrv.addByIndex(brParam, 1);
      this.isChangeBr = true;
    }
  }

  ngOnDestroy(): void {
    this.destroy$.next();
  }
  getListRepeatAppointment(petId, callback?) {
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
        'appointmentWeight',
        'appointmentTemperature',
        'weightNotMeasured',
        'temperatureNotMeasured'
      ],
      filter: {
        pet: {id: petId},
      },
      offset: 0,
      limit: 50
    };
    if (this.appointment.id) {
      params.filter['!id'] = this.appointment.id;
    }
    this.store.dispatch(new LoadMatchesAction({
      type: CrudType.Appointment,
      params: params,
      onSuccess: result => {
        this.getLoading = false;
        if (result.response.totalCount > 0) {
          this.appointments = result.response.items;
        }
      }
    }));
  }

  selectedIndexChange(event) {
    if (event === 1) {
      if (this.count === 0) {
        if (this.appointment.pet) {
          this.count += 1;
          this.getListRepeatAppointment(this.appointment.pet.id);

        }
      }
    } else if (event === 2 && !this.researchList.length) {
      this.getResearchLoading = true;
      this.store.dispatch(new LoadGetListAction({
        type: CrudType.ResearchDocument,
        params: {
          filter:
          {
            status: {code: 'DONE'},
            probeItem: {probeSampling: {pet: {id: this.appointment.pet.id}}}
          },
          order: {date: 'DESC'},
        },
        onSuccess: (res) => {
          if (res.response && res.status == true) {
            this.researchList = res.response.items;
          }
          this.getResearchLoading = false;
        },
        onError: _ => this.getResearchLoading = false
      }));
    }
  }
}
