import {Component} from '@angular/core';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {ReferenceItemModels} from '../../../../../../../models/reference/reference.item.models';
import {NotifyService} from '../../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../../services/breadcrumbs.service';
import {CrudType} from '../../../../../../../common/crud-types';
import {ResearchPriorityModel} from 'src/app/models/laboratory/research-priority.model';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';

@Component({templateUrl: './edit.component.html'})

export class EditComponent extends ReferenceItemModels {
  protected listNavigate = ['admin', 'references', 'laboratory', 'research-priority'];
  protected titleName = 'Приоритет исследования';

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService
  ) {
    super(CrudType.ResearchPriority, ResearchPriorityModel);
  }

  protected setModel() {
    this.formGroup = new FormGroup({
      name: new FormControl(this.item.name, [Validators.required]),
      level: new FormControl(this.item.level, [Validators.required]),
    });
  }

}
