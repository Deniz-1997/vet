import {Component, Input} from '@angular/core';
import {CrudType} from 'src/app/common/crud-types';
import {PrintFormsComponent} from '../print-forms/print-forms.component';
import {Observable} from 'rxjs';
import {CrudDataType} from 'src/app/api/api-connector/crud/crud.config';

declare var $: any;

@Component({
  selector: 'app-print-list',
  templateUrl: './print-list.component.html',
  styleUrls: ['./print-list.component.css']
})
export class PrintListComponent extends PrintFormsComponent {
  @Input() typeList: string;
  @Input() appointmentId: number;
  @Input() leavingId: number;
  partition: string;
  type = CrudType.PrintForms;
  printForms$: Observable<CrudDataType[]>;

  public ngOnInit() {
    super.ngOnInit();
    if (this.appointmentId !== undefined) {
      this.partition = 'Appointment';
    } else {
      this.partition = 'Leaving';
    }
  }

  close(e?: Event): void {
    if (e) {
      e.preventDefault();
    }
    $('app-print-list a').removeClass('active');
    $('app-print-list .menu-column__user-menu-dr').fadeOut();
  }
}
